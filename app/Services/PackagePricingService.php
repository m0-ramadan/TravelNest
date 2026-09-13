<?php

namespace App\Services;

use App\Models\Package;
use Illuminate\Support\Facades\Log;

class PackagePricingService
{
    /**
     * Recalculate package starting and range prices according to its package_type.
     */
    public function recalculate(Package $package): void
    {
        $package->refresh();

        switch ($package->package_type) {
            case 'day_tour':
                $this->recalculateDayTrip($package);
                break;
            case 'travel_package':
                $this->recalculateTourPackage($package);
                break;
            case 'nile_cruise':
                $this->recalculateNileCruise($package);
                break;
            default:
                $this->recalculateFallback($package);
                break;
        }
    }

    /**
     * Recalculate pricing for Day Trips (Group-Size Tiers).
     */
    public function recalculateDayTrip(Package $package): void
    {
        $tiers = $package->group_pricing_tiers;
        $prices = [];
        $tierPricesByPax = [];

        if (is_array($tiers)) {
            foreach ($tiers as $tier) {
                $price = (float) ($tier['price_per_person'] ?? $tier['price'] ?? 0);
                if ($price > 0) {
                    $prices[] = $price;
                    $minPax = (int) ($tier['min'] ?? $tier['persons_count'] ?? 0);
                    if ($minPax > 0) {
                        $tierPricesByPax[$minPax] = $price;
                    }
                }
            }
        }

        if (empty($prices)) {
            $fallbackFields = [
                6 => $package->price_6_plus_persons,
                5 => $package->price_5_persons,
                4 => $package->price_4_persons,
                3 => $package->price_3_persons,
                2 => $package->price_2_persons,
                1 => $package->price_1_person,
                0 => $package->adult_price,
            ];
            foreach ($fallbackFields as $pax => $f) {
                if ($f !== null && (float) $f > 0) {
                    $prices[] = (float) $f;
                    if ($pax > 0) {
                        $tierPricesByPax[$pax] = (float) $f;
                    }
                }
            }
        }

        // Older day tours and prices created from the standalone Package
        // Prices screen use package_prices instead of group tiers. They are a
        // valid day-tour source and must feed both the public starting price
        // and the pricing section.
        if (empty($prices)) {
            $package->loadMissing('prices');

            foreach ($package->prices as $price) {
                if ((float) $price->amount > 0) {
                    $prices[] = (float) $price->amount;
                }
            }
        }

        if (!empty($prices)) {
            $minPrice = min($prices);
            $maxPrice = max($prices);

            $package->start_from_price = $minPrice;
            $package->price_from = $minPrice;
            $package->price_to = $maxPrice > $minPrice ? $maxPrice : $minPrice;

            if (empty($package->adult_price) || (float) $package->adult_price <= 0) {
                $package->adult_price = $tierPricesByPax[1] ?? $minPrice;
            }
        }

        if ($package->isDirty(['start_from_price', 'price_from', 'price_to', 'adult_price'])) {
            $package->saveQuietly();
        }
    }

    /**
     * Recalculate pricing for Tour Packages (Accommodations -> Seasons -> Occupancy Items).
     */
    public function recalculateTourPackage(Package $package): void
    {
        $prices = [];

        $package->loadMissing(['tourPackageAccommodations.seasons.items']);

        foreach ($package->tourPackageAccommodations as $acc) {
            if (!$acc->is_active) {
                continue;
            }
            foreach ($acc->seasons as $season) {
                if (!$season->is_active) {
                    continue;
                }
                foreach ($season->items as $item) {
                    if ($item->is_active && (float) $item->price > 0) {
                        $prices[] = (float) $item->price;
                    }
                }
            }
        }

        // The package editor and the standalone package-price screens both
        // store valid legacy/general prices in package_prices. Include them
        // when no accommodation season pricing has been configured.
        if (empty($prices)) {
            $package->loadMissing('prices');

            foreach ($package->prices as $price) {
                if ((float) $price->amount > 0) {
                    $prices[] = (float) $price->amount;
                }
            }
        }

        if (empty($prices)) {
            $fallback = (float) ($package->adult_price ?: ($package->price_1_person ?: $package->start_from_price));
            if ($fallback > 0) {
                $prices[] = $fallback;
            }
        }

        if (!empty($prices)) {
            $minPrice = min($prices);
            $maxPrice = max($prices);

            $package->start_from_price = $minPrice;
            $package->price_from = $minPrice;
            $package->price_to = $maxPrice > $minPrice ? $maxPrice : $minPrice;
        }

        if ($package->isDirty(['start_from_price', 'price_from', 'price_to'])) {
            $package->saveQuietly();
        }
    }

    /**
     * Recalculate pricing for Nile Cruises (Durations -> Seasons -> Cabin Items).
     */
    public function recalculateNileCruise(Package $package): void
    {
        $nileService = app(NileCruisePackageService::class);
        $nileService->recalculateStartingPrice($package);
    }

    /**
     * Recalculate fallback when type is unspecified.
     */
    public function recalculateFallback(Package $package): void
    {
        $package->loadMissing('prices');

        $prices = array_filter(array_merge([
            (float) $package->start_from_price,
            (float) $package->adult_price,
            (float) $package->price_from,
        ], $package->prices->pluck('amount')->map(fn ($price) => (float) $price)->all()), fn($p) => $p > 0);

        if (!empty($prices)) {
            $minPrice = min($prices);
            $package->start_from_price = $minPrice;
            $package->price_from = $minPrice;

            if ($package->isDirty(['start_from_price', 'price_from'])) {
                $package->saveQuietly();
            }
        }
    }
}
