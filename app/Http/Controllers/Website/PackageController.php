<?php

namespace App\Http\Controllers\Website;

use App\Models\Package;
use App\Models\PackageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PackageController extends BaseWebsiteController
{
    public function index(Request $request): View
    {
        return $this->renderListingPage(
            $request,
            ['travel_package', 'nile_cruise', 'deal', 'multi_country', 'custom'],
            [
                'badge' => __('Featured Journeys'),
                'title' => __('Egypt Travel Packages & Nile Cruises'),
                'subtitle' => __('Browse a curated selection of private tours, Nile cruises, multi-country journeys, and tailor-made travel experiences.'),
                'overview_title' => __('Find the right journey for your travel style'),
                'overview_text' => __('Explore flexible travel packages designed around comfort, discovery, and memorable experiences across Egypt and beyond.'),
                'empty_title' => __('No packages found'),
                'empty_text' => __('Try changing the search filters or browse our latest tours and offers.'),
                'button_text' => __('View Journey'),
            ]
        );
    }

    public function tours(Request $request): View
    {
        return $this->renderListingPage(
            $request,
            ['day_tour', 'shore_excursion'],
            [
                'badge' => __('Browse Tours'),
                'title' => __('Egypt Day Tours & Shore Excursions'),
                'subtitle' => __('Discover expertly planned day tours and shore excursions with seamless logistics and unforgettable highlights.'),
                'overview_title' => __('Choose a tour that fits your schedule'),
                'overview_text' => __('From quick cultural discoveries to full-day private adventures, explore flexible experiences built around your pace.'),
                'empty_title' => __('No tours found'),
                'empty_text' => __('Try changing the search filters or browse our latest offers instead.'),
                'button_text' => __('View Tour'),
            ]
        );
    }

    public function show(Request $request, ?string $country = null, string $slug = null)
    {
        if ($slug === null) {
            $slug = $country;
            $country = null;
        }

        return app(\App\Http\Controllers\Website\TripController::class)->show($slug);
    }

    private function renderListingPage(Request $request, array $allowedTypes, array $pageContent): View
    {
        $selectedType = $request->filled('type') && in_array($request->string('type')->toString(), $allowedTypes, true)
            ? $request->string('type')->toString()
            : null;

        $search = trim((string) $request->input('q', ''));
        $selectedCategorySlug = trim((string) $request->input('category', ''));

        $categories = PackageCategory::query()
            ->where('is_active', true)
            ->whereIn('category_type', $allowedTypes)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $selectedCategory = $selectedCategorySlug !== ''
            ? $categories->firstWhere('slug', $selectedCategorySlug)
            : null;

        $packages = Package::query()
            ->with(['currency', 'primaryCountry', 'highlights', 'tags', 'cruise', 'category'])
            ->where('is_active', true)
            ->whereIn('package_type', $allowedTypes)
            ->when($selectedType, fn ($query) => $query->where('package_type', $selectedType))
            ->when($selectedCategory, fn ($query) => $query->where('category_id', $selectedCategory->id))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('subtitle', 'like', "%{$search}%")
                        ->orWhere('short_description', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('schedule_text', 'like', "%{$search}%")
                        ->orWhere('destinations_text', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('is_featured')
            ->orderByRaw('sort_order IS NULL, sort_order ASC')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        $packages->getCollection()->transform(fn (Package $package) => $this->mapListingCard($package, $pageContent['button_text']));

        $typeOptions = collect($allowedTypes)->map(fn (string $type) => [
            'value' => $type,
            'label' => $this->typeLabel($type),
        ])->values()->all();

        return view('website.pages.packages.index', [
            'packages' => $packages,
            'pageContent' => $pageContent,
            'selectedType' => $selectedType,
            'selectedCategorySlug' => $selectedCategorySlug,
            'search' => $search,
            'categories' => $categories->map(fn (PackageCategory $category) => [
                'slug' => $category->slug,
                'name' => $this->translated($category->getRawOriginal('name') ?? $category->name),
            ])->values(),
            'selectedCategoryName' => $selectedCategory
                ? $this->translated($selectedCategory->getRawOriginal('name') ?? $selectedCategory->name)
                : null,
            'typeOptions' => $typeOptions,
            'stats' => [
                'count' => $packages->total(),
                'categories' => $categories->count(),
                'featured' => Package::query()
                    ->where('is_active', true)
                    ->whereIn('package_type', $allowedTypes)
                    ->where('is_featured', true)
                    ->count(),
            ],
        ]);
    }

    private function mapListingCard(Package $package, string $buttonText): array
    {
        $highlights = $package->highlights
            ->take(2)
            ->map(fn ($item) => $this->translated($item->getRawOriginal('title') ?? $item->title))
            ->filter()
            ->values()
            ->all();

        if (empty($highlights)) {
            $highlights = $package->tags->take(2)->pluck('name')->filter()->values()->all();
        }

        $tourType = trim((string) ($package->tour_type ?: Str::headline(str_replace('_', ' ', (string) $package->package_type))));
        $schedule = trim((string) ($package->cruise?->sailing_days ?: $this->translated($package->getRawOriginal('schedule_text') ?? $package->schedule_text)));

        return [
            'title' => $this->translated($package->getRawOriginal('title') ?? $package->title),
            'url' => $this->packageRoute($package),
            'image' => $this->imageUrl($package->featured_image, 'website/photos/home2.webp'),
            'price' => $this->packagePrice($package),
            'badge' => $package->is_ultra_luxury
                ? __('Ultra Luxury')
                : ($package->is_best_seller ? __('Best Seller') : null),
            'duration' => $this->packageDuration($package),
            'tour_type' => __($tourType !== '' ? $tourType : $this->typeLabel($package->package_type)),
            'schedule' => $schedule,
            'country' => $this->translated($package->primaryCountry?->getRawOriginal('name') ?? $package->primaryCountry?->name),
            'description' => $this->shortText(
                $package->getRawOriginal('short_description') ?: $package->getRawOriginal('description'),
                170
            ),
            'highlights' => $highlights,
            'button_text' => $buttonText,
            'type_label' => $this->typeLabel($package->package_type),
        ];
    }

    private function typeLabel(string $type): string
    {
        return match ($type) {
            'travel_package' => __('Travel Packages'),
            'nile_cruise' => __('Nile Cruises'),
            'day_tour' => __('Day Tours'),
            'shore_excursion' => __('Shore Excursions'),
            'multi_country' => __('Multi Country Tours'),
            'deal' => __('Travel Deals'),
            'custom' => __('Tailor-made Trips'),
            default => Str::headline(str_replace('_', ' ', $type)),
        };
    }
}
