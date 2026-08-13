<?php

namespace App\Services;

use App\Models\Attraction;
use App\Models\City;
use App\Models\Currency;
use App\Models\PackageCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ReadyTourTaxonomyMapper
{
    /**
     * Map remote tour_type to local package_type.
     * Rule #15: excursion -> day_tour, package -> travel_package, nile_cruise -> nile_cruise
     */
    public function mapPackageType(?string $remoteTourType, ?string $remoteCategory = null, ?string $title = null): string
    {
        $normalized = $this->normalizeSlug($remoteTourType);
        $combinedText = strtolower(($remoteTourType ?? '') . ' ' . ($remoteCategory ?? '') . ' ' . ($title ?? ''));

        if (str_contains($combinedText, 'nile') || str_contains($combinedText, 'cruise') || str_contains($combinedText, 'dahabiya') || str_contains($combinedText, 'lake nasser')) {
            return 'nile_cruise';
        }

        return match ($normalized) {
            'excursion', 'day-tour', 'day_tour' => 'day_tour',
            'nile-cruise', 'nile_cruise', 'cruise' => 'nile_cruise',
            'package', 'travel-package', 'travel_package' => 'travel_package',
            'shore-excursion', 'shore_excursion' => 'shore_excursion',
            'deal' => 'deal',
            'multi-country', 'multi_country' => 'multi_country',
            'custom' => 'custom',
            default => 'travel_package',
        };
    }

    /**
     * Resolve Nile Cruise Type and Category from template metadata.
     * Return ['nile_cruise_type_id' => int|null, 'nile_cruise_category_id' => int|null]
     */
    public function resolveNileCruiseTaxonomy(?string $remoteCategory, ?string $title = '', ?string $tourType = ''): array
    {
        $haystack = strtolower(($remoteCategory ?? '') . ' ' . ($title ?? '') . ' ' . ($tourType ?? ''));

        // Determine Nile Cruise Type
        $typeSlug = null;
        if (str_contains($haystack, 'dahabiya') || str_contains($haystack, 'dahabeya')) {
            $typeSlug = 'dahabiya-nile-cruise';
        } elseif (str_contains($haystack, 'lake nasser') || str_contains($haystack, 'lake-nasser') || str_contains($haystack, 'nasser')) {
            $typeSlug = 'lake-nasser-cruise';
        } else {
            $typeSlug = 'luxor-aswan-nile-cruises';
        }

        $type = \App\Models\NileCruiseType::where('slug', $typeSlug)->first()
            ?? \App\Models\NileCruiseType::first();

        if (!$type) {
            return [
                'nile_cruise_type_id' => null,
                'nile_cruise_category_id' => null,
            ];
        }

        $catId = null;
        if ($type->slug === 'luxor-aswan-nile-cruises') {
            $catSlug = 'standard-nile-cruises'; // default fallback for luxor & aswan

            if (str_contains($haystack, 'ultra deluxe') || str_contains($haystack, 'ultra-deluxe')) {
                $catSlug = 'ultra-deluxe-nile-cruises';
            } elseif (str_contains($haystack, 'deluxe')) {
                $catSlug = 'deluxe-nile-cruises';
            } elseif (str_contains($haystack, 'luxury') || str_contains($haystack, 'ultra luxury')) {
                $catSlug = 'luxury-nile-cruises';
            } elseif (str_contains($haystack, 'standard')) {
                $catSlug = 'standard-nile-cruises';
            }

            $cat = \App\Models\NileCruiseCategory::where('nile_cruise_type_id', $type->id)
                ->where('slug', $catSlug)
                ->first();

            $catId = $cat?->id;
        }

        return [
            'nile_cruise_type_id' => $type->id,
            'nile_cruise_category_id' => $catId,
        ];
    }

    /**
     * Map local tour_type default (Rule #15: default = private)
     */
    public function mapLocalTourType(?string $remoteTourType): string
    {
        return 'private';
    }

    /**
     * Canonical string slug normalization.
     * Rules: lowercase, trim, spaces -> -, underscores -> -, multiple hyphens -> one
     */
    public function normalizeSlug(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        $str = strtolower(trim((string) $value));
        $str = str_replace(['_', ' '], '-', $str);
        $str = preg_replace('/-+/', '-', $str);

        return trim($str, '-');
    }

    /**
     * Resolve category from remote category string.
     * Rule #16: Try category slug, then name, then package_type fallback. Return warnings if unmapped.
     */
    public function resolveCategory(?string $remoteCategory, string $packageType, array &$warnings = []): ?PackageCategory
    {
        $normalizedRemote = $this->normalizeSlug($remoteCategory);

        if ($normalizedRemote !== '') {
            // 1. Try slug match
            $category = PackageCategory::where('slug', $normalizedRemote)->first();
            if ($category) {
                return $category;
            }

            // 2. Try English name match
            $allCategories = PackageCategory::all();
            foreach ($allCategories as $cat) {
                $catSlug = $this->normalizeSlug($cat->slug);
                $nameEn = $this->normalizeSlug($cat->name['en'] ?? $cat->name['ar'] ?? '');

                if ($catSlug === $normalizedRemote || $nameEn === $normalizedRemote) {
                    return $cat;
                }
            }
        }

        // 3. Fallback to category_type matching package_type
        $fallback = PackageCategory::where('category_type', $packageType)->first()
            ?? PackageCategory::first();

        if ($remoteCategory) {
            $warnings[] = "Original category ('{$remoteCategory}') did not match local category directly. Directed to '{$fallback?->display_name}'.";
        }

        return $fallback;
    }

    /**
     * Map remote cities to local City records.
     * Rule #17: Sync cities with pivot stop_order, is_primary, nights. First is primary.
     */
    public function resolveCities(array $remoteCities, array &$warnings = []): Collection
    {
        $resolved = collect();

        foreach ($remoteCities as $index => $remoteCityName) {
            $normalized = $this->normalizeSlug($remoteCityName);
            if ($normalized === '') {
                continue;
            }

            $city = City::where('slug', $normalized)->first();

            if (!$city) {
                // Try fuzzy name match
                $city = City::all()->first(function ($c) use ($normalized) {
                    return $this->normalizeSlug($c->slug) === $normalized
                        || $this->normalizeSlug($c->name['en'] ?? '') === $normalized
                        || $this->normalizeSlug($c->name['ar'] ?? '') === $normalized;
                });
            }

            if ($city) {
                $resolved->push([
                    'city' => $city,
                    'is_primary' => ($resolved->isEmpty()),
                    'stop_order' => $resolved->count(),
                    'nights' => null,
                ]);
            } else {
                $warnings[] = "City ('{$remoteCityName}') not found locally and will be skipped from direct assignment.";
            }
        }

        return $resolved;
    }

    /**
     * Map primary attraction for destination_id.
     * Rule #18: Featured first, sort_order, id.
     */
    public function resolvePrimaryDestination(?City $primaryCity): ?Attraction
    {
        if (!$primaryCity) {
            return Attraction::query()
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->first();
        }

        return Attraction::query()
            ->where('city_id', $primaryCity->id)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->first();
    }

    /**
     * Duration mapping.
     * Rule #20: hours vs days.
     */
    public function mapDuration(?int $value, ?string $unit): array
    {
        $val = $value ?: 1;
        $unitNorm = strtolower(trim((string) $unit));

        if ($unitNorm === 'hours' || $unitNorm === 'hour') {
            return [
                'duration_type' => 'hours',
                'duration_hours' => $val,
                'duration_days' => null,
                'duration_nights' => null,
                'duration_text' => "{$val} " . ($val == 1 ? 'Hour' : 'Hours'),
            ];
        }

        return [
            'duration_type' => 'days',
            'duration_days' => $val,
            'duration_hours' => null,
            'duration_nights' => null,
            'duration_text' => "{$val} " . ($val == 1 ? 'Day' : 'Days'),
        ];
    }

    /**
     * Map currency code to local Currency model.
     * Rule #21: suggested_min_price, suggested_max_price, price_currency.
     */
    public function resolveCurrency(?string $currencyCode): ?Currency
    {
        $code = strtoupper(trim((string) $currencyCode)) ?: 'USD';

        $currency = Currency::where('code', $code)->first();
        if ($currency) {
            return $currency;
        }

        return Currency::where('is_default', true)->first() ?? Currency::first();
    }

    /**
     * Difficulty mapping.
     * Rule #23: easy, moderate, hard.
     */
    public function mapDifficulty(?string $difficulty): ?string
    {
        $norm = strtolower(trim((string) $difficulty));

        if (in_array($norm, ['easy', 'moderate', 'hard'], true)) {
            return $norm;
        }

        return null;
    }
}
