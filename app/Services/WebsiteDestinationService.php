<?php

namespace App\Services;

use App\Models\City;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WebsiteDestinationService
{
    protected static array $homeDestinationsCache = [];

    public function homeDestinations(int $limit = 6): Collection
    {
        $cacheKey = app()->getLocale() . ':' . $limit;

        if (array_key_exists($cacheKey, self::$homeDestinationsCache)) {
            return self::$homeDestinationsCache[$cacheKey];
        }

        return self::$homeDestinationsCache[$cacheKey] = City::query()
            ->with('country')
            ->withCount(['attractions', 'packages'])
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit($limit)
            ->get()
            ->map(function (City $city) {
                $description = $city->display_short_description ?: $city->display_description;

                return [
                    'title' => $city->display_name,
                    'description' => Str::limit(strip_tags($description), 190),
                    'image' => $this->imageUrl($city->featured_image ?: $city->hero_image, 'website/photos/Dest/Egypt.jpg'),
                    'url' => route('website.destinations.show', $city->slug),
                    'country' => $city->country?->display_name ?? '',
                    'sites_count' => $city->attractions_count,
                    'packages_count' => $city->packages_count,
                ];
            })
            ->values();
    }

    protected function imageUrl(?string $path, string $fallback): string
    {
        if (!$path) {
            return asset($fallback);
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
}
