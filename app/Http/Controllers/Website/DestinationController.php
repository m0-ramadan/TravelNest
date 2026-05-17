<?php

namespace App\Http\Controllers\Website;

use App\Models\City;
use App\Models\Country;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DestinationController extends BaseWebsiteController
{
    public function index(Request $request)
    {
        $countries = Country::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $selectedCountry = $request->filled('country')
            ? $countries->firstWhere('slug', $request->country)
            : null;

        $destinations = City::query()
            ->with('country')
            ->withCount([
                'attractions' => fn ($query) => $query->where('is_active', true),
                'packages' => fn ($query) => $query->where('packages.is_active', true),
            ])
            ->where('is_active', true)
            ->when($selectedCountry, function ($query) use ($selectedCountry) {
                $query->where('country_id', $selectedCountry->id);
            })
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->paginate(12)
            ->withQueryString();

        $highlightCity = City::query()
            ->with('country')
            ->where('is_active', true)
            ->when($selectedCountry, fn ($query) => $query->where('country_id', $selectedCountry->id))
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->first();

        $heroImage = $this->imageUrl(
            $highlightCity?->hero_image ?: $highlightCity?->featured_image,
            asset('website/photos/home2.webp')
        );

        $pageTitle = $selectedCountry
            ? $selectedCountry->display_name . ' ' . __('Destinations')
            : __('Destinations');

        $heroBadge = $selectedCountry?->display_name ?: __('Luxury Travel Experiences');
        $heroTitle = $selectedCountry
            ? $selectedCountry->display_name
            : __('Explore Extraordinary Destinations');

        $heroSubtitle = $highlightCity?->display_short_description
            ?: $highlightCity?->display_description
            ?: __('Discover extraordinary multi-country adventures across magnificent destinations');

        $overviewTitle = $selectedCountry
            ? __('Discover') . ' ' . $selectedCountry->display_name
            : __('Explore Extraordinary Destinations');

        $overviewText = $highlightCity
            ? Str::limit(trim(strip_tags($highlightCity->display_description ?: $highlightCity->display_short_description)), 520)
            : __('Discover extraordinary multi-country adventures across magnificent destinations');

        $sectionTitle = $selectedCountry
            ? $selectedCountry->display_name . ' ' . __('Destinations')
            : __('Explore Extraordinary Destinations');

        $sectionSubtitle = $selectedCountry
            ? ($highlightCity?->display_short_description ?: __('Discover extraordinary multi-country adventures across magnificent destinations'))
            : __('Discover extraordinary multi-country adventures across magnificent destinations');

        $destinations->getCollection()->transform(function (City $city) {
            return [
                'title' => $city->display_name,
                'country' => $city->country?->display_name ?: __('Destination'),
                'description' => Str::limit(trim(strip_tags($city->display_short_description ?: $city->display_description)), 150),
                'image' => $this->imageUrl($city->featured_image ?: $city->hero_image, 'website/photos/home2.webp'),
                'url' => route('website.destinations.show', $city->slug),
                'attractions_count' => (int) $city->attractions_count,
                'packages_count' => (int) $city->packages_count,
            ];
        });

        return view('website.pages.destinations.index', compact(
            'destinations',
            'countries',
            'selectedCountry',
            'heroImage',
            'pageTitle',
            'heroBadge',
            'heroTitle',
            'heroSubtitle',
            'overviewTitle',
            'overviewText',
            'sectionTitle',
            'sectionSubtitle'
        ));
    }

    public function show(string $slug)
    {
        $destination = City::query()
            ->with(['country', 'attractions' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $packageIds = DB::table('package_cities')
            ->where('city_id', $destination->id)
            ->pluck('package_id');

        $packages = Package::query()
            ->with(['currency'])
            ->where('is_active', true)
            ->whereIn('id', $packageIds)
            ->orderByDesc('is_featured')
            ->limit(9)
            ->get();

        return view('website.pages.destinations.show', compact('destination', 'packages'));
    }

    public function legacyShow(string $slug)
    {
        $slug = str_replace('.html', '', $slug);

        $destination = City::query()
            ->where('slug', $slug)
            ->orWhere('slug', Str::slug($slug))
            ->first();

        if ($destination) {
            return redirect()->route('website.destinations.show', $destination->slug, 301);
        }

        abort(404);
    }
}
