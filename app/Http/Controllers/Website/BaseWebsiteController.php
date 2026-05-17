<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Package;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class BaseWebsiteController extends Controller
{
    protected function active($query)
    {
        return $query->where('is_active', true);
    }

    protected function translated(mixed $value, ?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        if ($value instanceof Model) {
            return '';
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            }
        }

        if (is_array($value)) {
            return (string) (
                $value[$locale]
                ?? $value['en']
                ?? $value['ar']
                ?? $value['it']
                ?? $value['It']
                ?? reset($value)
                ?? ''
            );
        }

        return trim((string) ($value ?? ''));
    }

    protected function shortText(mixed $value, int $limit = 150): string
    {
        return Str::limit(strip_tags($this->translated($value)), $limit);
    }


    protected function packagePrice(Package $package): string
    {
        $amount = $package->start_from_price ?? $package->base_price ?? null;

        if ($amount === null) {
            $firstPrice = $package->relationLoaded('prices') ? $package->prices->first() : null;
            $amount = $firstPrice?->amount;
        }

        if ($amount === null) {
            return __('Ask for price');
        }

        $currency = $package->relationLoaded('currency') ? $package->currency : null;
        $symbol = $currency?->symbol ?: Currency::query()->where('is_default', true)->value('symbol') ?: '$';

        return __('From') . ' ' . $symbol . number_format((float) $amount, 0);
    }

    protected function packageDuration(Package $package): string
    {
        if (!empty($package->duration_text)) {
            return (string) $package->duration_text;
        }

        if (!empty($package->duration_days)) {
            return $package->duration_days . ' ' . __('Days');
        }

        if (!empty($package->duration_hours)) {
            return $package->duration_hours . ' ' . __('Hours');
        }

        return __('Flexible');
    }

    protected function packageRoute(Package $package): string
    {
        return match ($package->package_type) {
            'day_tour', 'shore_excursion' => route('website.tours.show', $package->slug),
            default => route('website.trips.show', $package->slug),
        };
    }

    protected function packageCard(Package $package): array
    {
        $highlights = $package->relationLoaded('highlights')
            ? $package->highlights->take(4)->map(fn($item) => $this->translated($item->getRawOriginal('title') ?? $item->title))->filter()->values()->all()
            : [];

        if (empty($highlights) && $package->relationLoaded('tags')) {
            $highlights = $package->tags->take(4)->pluck('name')->filter()->values()->all();
        }

        return [
            'id' => $package->id,
            'slug' => $package->slug,
            'title' => $this->translated($package->getRawOriginal('title') ?? $package->title),
            'subtitle' => $this->translated($package->getRawOriginal('subtitle') ?? $package->subtitle),
            'description' => $this->shortText($package->getRawOriginal('short_description') ?: $package->getRawOriginal('description'), 190),
            'image' => $this->imageUrl($package->featured_image, 'website/photos/home2.webp'),
            'price' => $this->packagePrice($package),
            'duration' => $this->packageDuration($package),
            'tour_type' => ucfirst((string) ($package->tour_type ?: 'Private')),
            'route_text' => $package->route_text ?: $this->translated($package->getRawOriginal('destinations_text') ?? null),
            'is_ultra_luxury' => (bool) $package->is_ultra_luxury,
            'is_best_seller' => (bool) $package->is_best_seller,
            'url' => $this->packageRoute($package),
            'tags' => $highlights,
        ];
    }
    protected function lang(): string
    {
        return app()->getLocale() ?: 'en';
    }

    protected function transValue($value, ?string $fallback = ''): string
    {
        if ($value === null) {
            return $fallback ?? '';
        }

        if (is_array($value)) {
            return (string) ($value[$this->lang()] ?? $value['en'] ?? $value['ar'] ?? reset($value) ?? $fallback ?? '');
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return (string) ($decoded[$this->lang()] ?? $decoded['en'] ?? $decoded['ar'] ?? reset($decoded) ?? $fallback ?? '');
            }
            return $value;
        }

        return (string) $value;
    }

    protected function cleanHtml(?string $html): string
    {
        return trim((string) $html);
    }

    protected function plainText(?string $html, int $limit = 180): string
    {
        return Str::limit(trim(strip_tags((string) $html)), $limit);
    }

    protected function imageUrl(?string $path, ?string $fallback = null): string
    {
        $fallback = $fallback ?: asset('website/photos/home2.webp');

        if (!$path) {
            return $fallback;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (Str::startsWith($path, ['storage/', 'website/', 'images/'])) {
            return asset($path);
        }

        if (Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        return asset($path);
    }

    protected function money($amount, ?string $symbol = '$'): ?string
    {
        if ($amount === null || $amount === '') {
            return null;
        }

        return ($symbol ?: '$') . rtrim(rtrim(number_format((float) $amount, 2), '0'), '.');
    }
}
