<?php

namespace App\Http\Controllers\Website;

use App\Models\City;
use App\Models\Package;
use App\Models\PackageCategory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PackageController extends BaseWebsiteController
{
    public function index(Request $request): View
    {
        $selectedType = $request->input('type');
        $duration = $request->input('duration') ?: $request->input('days');
        $destinationSlug = trim((string) ($request->input('destination') ?: $request->input('city', '')));
        $search = trim((string) $request->input('q', ''));
        $category = trim((string) $request->input('category', ''));

        if ($selectedType === 'travel_package' && !$duration && $destinationSlug === '' && $search === '' && $category === '') {
            return app(TravelPackageController::class)->index($request);
        }

        $durationTitle = $duration ? __(':days Days Egypt Travel Packages', ['days' => $duration]) : __('Egypt Travel Packages');
        $durationSubtitle = $duration
            ? __('Browse our handpicked :days-day private Egypt vacation packages and itineraries.', ['days' => $duration])
            : __('Browse a curated selection of private Egypt travel packages, vacations, and tailor-made journeys.');

        return $this->renderListingPage(
            $request,
            ['travel_package'],
            [
                'badge' => __('Travel Packages'),
                'title' => $durationTitle,
                'subtitle' => $durationSubtitle,
                'overview_title' => __('Find your ideal Egypt travel package'),
                'overview_text' => __('Explore flexible multi-day travel packages designed around comfort, discovery, and memorable pharaonic experiences across Egypt.'),
                'empty_title' => __('No travel packages found'),
                'empty_text' => __('Try changing the search filters or browse our other travel packages.'),
                'button_text' => __('View Package'),
            ]
        );
    }

    public function tours(Request $request): View|RedirectResponse
    {
        $selectedType = $request->input('type');
        $destinationSlug = trim((string) ($request->input('destination') ?: $request->input('city', '')));
        $search = trim((string) $request->input('q', ''));
        $category = trim((string) $request->input('category', ''));

        if ($selectedType === 'shore_excursion') {
            return redirect()->route('website.shore_excursions.index', $request->except('type'));
        }

        if ($selectedType === 'day_tour' && $destinationSlug === '' && $search === '' && $category === '') {
            return app(DayTourController::class)->index($request);
        }

        $destinationCity = $destinationSlug !== '' ? City::where('slug', $destinationSlug)->first() : null;

        $cityName = $destinationCity
            ? $this->translated($destinationCity->getRawOriginal('name') ?? $destinationCity->name)
            : null;

        $pageTitle = $destinationCity
            ? __(':city Day Tours & Excursions', ['city' => $cityName])
            : __('Egypt Day Tours & Excursions');

        $pageSubtitle = $destinationCity
            ? __('Discover private day tours, sightseeing landmarks, and authentic excursions in :city.', ['city' => $cityName])
            : __('Discover expertly planned day tours and shore excursions with seamless logistics and unforgettable highlights.');

        return $this->renderListingPage(
            $request,
            ['day_tour'],
            [
                'badge' => $destinationCity ? __(':city Day Tours', ['city' => $cityName]) : __('Browse Tours'),
                'title' => $pageTitle,
                'subtitle' => $pageSubtitle,
                'overview_title' => $destinationCity ? __('Day Tours & Excursions in :city', ['city' => $cityName]) : __('Choose a tour that fits your schedule'),
                'overview_text' => $destinationCity
                    ? __('Explore handpicked private day tours and guided excursions in :city with licensed English-speaking Egyptologists and private transfers.', ['city' => $cityName])
                    : __('From quick cultural discoveries to full-day private adventures, explore flexible experiences built around your pace.'),
                'empty_title' => $destinationCity ? __('No tours found in :city', ['city' => $cityName]) : __('No tours found'),
                'empty_text' => __('Try changing the search filters or browse our other tours.'),
                'button_text' => __('View Tour'),
            ]
        );
    }

    public function shoreExcursions(Request $request): View|RedirectResponse
    {
        $requestedSection = $request->input('section') ?: $request->input('destination') ?: $request->input('city');
        if ($requestedSection) {
            $normalizedKey = $this->normalizeShoreSectionKey((string) $requestedSection);
            if ($normalizedKey) {
                return redirect()->route('website.shore_excursions.section', [
                    'section' => $normalizedKey,
                ] + $request->except(['section', 'destination', 'city']));
            }
        }

        $shoreExcursionSections = $this->shoreExcursionSections($request);
        $isSearch = $request->filled('q') || $request->filled('duration') || $request->filled('days') || $request->boolean('luxury') || $request->filled('category');

        return $this->renderListingPage(
            $request,
            ['shore_excursion'],
            [
                'is_landing_hub' => !$isSearch,
                'hero_image' => asset('website/images/shore-excursions/safaga.jpg'),
                'badge' => __('Egypt Cruise Port Tours'),
                'title' => __('Egypt Shore Excursions'),
                'subtitle' => __('Private and small-group shore excursions planned around your cruise schedule, with port pickup and a timely return to your ship.'),
                'overview_title' => __('Explore Egypt from Your Cruise Port'),
                'overview_text' => __('Discover Cairo, Luxor, Alexandria, and the Red Sea from Safaga, Alexandria, Port Said, and Ain Sokhna. Every excursion is designed around ship arrival and departure times, with professional guides, comfortable transfers, and clear group pricing.'),
                'empty_title' => __('No shore excursions found'),
                'empty_text' => __('Try changing the search filters or contact us for a custom cruise-port excursion.'),
                'button_text' => __('View Shore Excursion'),
                'shore_sections' => $shoreExcursionSections,
            ]
        );
    }

    public function shoreExcursionSection(Request $request, string $section): View
    {
        $normalizedKey = $this->normalizeShoreSectionKey($section);
        if (!$normalizedKey) {
            abort(404);
        }

        $sectionDefinitions = $this->rawShoreSectionDefinitions();
        $sectionDef = $sectionDefinitions[$normalizedKey] ?? null;
        if (!$sectionDef) {
            abort(404);
        }

        $shoreExcursionSections = $this->shoreExcursionSections($request, $normalizedKey);

        return $this->renderListingPage(
            $request,
            ['shore_excursion'],
            [
                'is_landing_hub' => false,
                'badge' => $sectionDef['title'],
                'title' => $sectionDef['title'],
                'subtitle' => $sectionDef['subtitle'],
                'hero_image' => $sectionDef['image'],
                'overview_title' => $sectionDef['title'],
                'overview_text' => __('All excursions departing from :port are scheduled around your ship timetable, guaranteeing a timely return to your cruise.', ['port' => $sectionDef['port_name'] ?? $sectionDef['title']]),
                'empty_title' => __('No shore excursions found for :port', ['port' => $sectionDef['port_name'] ?? $sectionDef['title']]),
                'empty_text' => __('Try changing the search filters or contact us for a custom excursion from this port.', ['port' => $sectionDef['title']]),
                'button_text' => __('View Shore Excursion'),
                'shore_sections' => $shoreExcursionSections,
                'current_section' => $sectionDef,
                'section_slug' => $normalizedKey,
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
        $selectedDestinationSlug = trim((string) ($request->input('destination') ?: $request->input('city', '')));
        $duration = $request->input('duration') ?: $request->input('days');
        $luxury = $request->boolean('luxury');

        $categories = PackageCategory::query()
            ->where('is_active', true)
            ->whereIn('category_type', $allowedTypes)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $selectedCategory = $selectedCategorySlug !== ''
            ? $categories->firstWhere('slug', $selectedCategorySlug)
            : null;

        $destinations = City::query()
            ->where('is_active', true)
            ->where(function ($q) use ($allowedTypes) {
                $q->whereHas('packages', fn($sub) => $sub->where('is_active', true)->whereIn('package_type', $allowedTypes))
                    ->orWhereHas('attractions.packageAttractions.package', fn($sub) => $sub->where('is_active', true)->whereIn('package_type', $allowedTypes));
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($destinations->isEmpty()) {
            $destinations = City::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();
        }

        $selectedDestination = $selectedDestinationSlug !== ''
            ? ($destinations->firstWhere('slug', $selectedDestinationSlug) ?? City::query()->where('slug', $selectedDestinationSlug)->first())
            : null;

        $packagesQuery = Package::query()
            ->with(['currency', 'primaryCountry', 'highlights', 'tags', 'cruise', 'category'])
            ->where('is_active', true)
            ->whereIn('package_type', $allowedTypes);

        if (!empty($pageContent['current_section'])) {
            $this->applyShoreSectionScope($packagesQuery, $pageContent['current_section'], $destinations->keyBy('slug'));
        }

        $isLandingHub = !empty($pageContent['is_landing_hub']);

        if ($isLandingHub) {
            $packages = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);
        } else {
            $packages = $packagesQuery
                ->when($selectedType, fn($query) => $query->where('package_type', $selectedType))
                ->when($selectedCategory, fn($query) => $query->where('category_id', $selectedCategory->id))
                ->when($selectedDestination, function ($query) use ($selectedDestination) {
                    $query->where('destination_id', $selectedDestination->id);
                })
                ->when($duration, function ($query) use ($duration) {
                    $durationInt = (int) $duration;
                    $query->where(function ($q) use ($durationInt) {
                        $q->where('duration_days', $durationInt)
                            ->orWhere('title', 'like', "{$durationInt} Day%")
                            ->orWhere('title', 'like', "% {$durationInt} Day%")
                            ->orWhere('slug', 'like', "{$durationInt}-day%")
                            ->orWhere('slug', 'like', "%-{$durationInt}-day%");
                    });
                })
                ->when($luxury, function ($query) {
                    $query->where(function ($q) {
                        $q->where('is_ultra_luxury', true)
                            ->orWhere('title', 'like', '%luxury%')
                            ->orWhere('slug', 'like', '%luxury%');
                    });
                })
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                            ->orWhere('subtitle', 'like', "%{$search}%")
                            ->orWhere('short_description', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhere('schedule_text', 'like', "%{$search}%")
                            ->orWhere('destinations_text', 'like', "%{$search}%")
                            ->orWhere('pickup_location', 'like', "%{$search}%")
                            ->orWhere('dropoff_location', 'like', "%{$search}%")
                            ->orWhere('route_text', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%");
                    });
                })
                ->orderByDesc('is_featured')
                ->orderByRaw('sort_order IS NULL, sort_order ASC')
                ->latest('id')
                ->paginate(12)
                ->withQueryString();

            $packages->getCollection()->transform(
                fn(Package $package) => $this->packageListingCard($package, $pageContent['button_text'])
            );
        }

        $typeOptions = collect($allowedTypes)->map(fn(string $type) => [
            'value' => $type,
            'label' => $this->typeLabel($type),
        ])->values()->all();

        $statsCount = $isLandingHub
            ? Package::query()->where('is_active', true)->whereIn('package_type', $allowedTypes)->count()
            : $packages->total();

        return view('website.pages.packages.index', [
            'packages' => $packages,
            'pageContent' => $pageContent,
            'selectedType' => $selectedType,
            'selectedCategorySlug' => $selectedCategorySlug,
            'selectedDestinationSlug' => $selectedDestinationSlug,
            'selectedDestinationName' => $selectedDestination
                ? $this->translated($selectedDestination->getRawOriginal('name') ?? $selectedDestination->name)
                : null,
            'destinations' => $destinations->map(fn(City $city) => [
                'slug' => $city->slug,
                'name' => $this->translated($city->getRawOriginal('name') ?? $city->name),
            ])->values(),
            'search' => $search,
            'categories' => $categories->map(fn(PackageCategory $category) => [
                'slug' => $category->slug,
                'name' => $this->translated($category->getRawOriginal('name') ?? $category->name),
            ])->values(),
            'selectedCategoryName' => $selectedCategory
                ? $this->translated($selectedCategory->getRawOriginal('name') ?? $selectedCategory->name)
                : null,
            'typeOptions' => $typeOptions,
            'stats' => [
                'count' => $statsCount,
                'categories' => $categories->count(),
                'featured' => Package::query()
                    ->where('is_active', true)
                    ->whereIn('package_type', $allowedTypes)
                    ->where('is_featured', true)
                    ->count(),
            ],
        ]);
    }

    private function normalizeShoreSectionKey(?string $key): ?string
    {
        if (!$key) {
            return null;
        }

        $key = strtolower(trim($key));
        $map = [
            'safaga' => 'safaga',
            'alexandria' => 'alexandria',
            'port-said' => 'port-said',
            'port-saeed' => 'port-said',
            'sharm-el-sheikh' => 'sharm-el-sheikh',
            'sharm' => 'sharm-el-sheikh',
            'ain-el-sokhna' => 'ain-el-sokhna',
            'ain-sokhna' => 'ain-el-sokhna',
            'sokhna' => 'ain-el-sokhna',
            'accessible' => 'accessible',
        ];

        return $map[$key] ?? (isset($this->rawShoreSectionDefinitions()[$key]) ? $key : null);
    }

    private function rawShoreSectionDefinitions(): array
    {
        return [
            'safaga' => [
                'key' => 'safaga',
                'title' => __('Shore Excursions from Safaga port'),
                'port_name' => __('Safaga port'),
                'subtitle' => __('Luxor temples, royal tombs, and Red Sea cruise-port day trips.'),
                'city' => 'safaga',
                'search' => 'Safaga',
                'image' => asset('website/images/shore-excursions/safaga.jpg'),
            ],
            'alexandria' => [
                'key' => 'alexandria',
                'title' => __('Shore Excursions from Alexandria port'),
                'port_name' => __('Alexandria port'),
                'subtitle' => __('Mediterranean arrivals with Cairo, pyramids, and Alexandria highlights.'),
                'city' => 'alexandria',
                'search' => 'Alexandria',
                'image' => asset('website/images/shore-excursions/alexandria.jpg'),
            ],
            'port-said' => [
                'key' => 'port-said',
                'title' => __('Shore Excursions from Port Said port'),
                'port_name' => __('Port Said port'),
                'subtitle' => __('Suez Canal cruise calls with Cairo, Giza, and classic Egypt routes.'),
                'city' => 'port-said',
                'search' => 'Port Said',
                'image' => asset('website/images/shore-excursions/port-said.jpg'),
            ],
            'sharm-el-sheikh' => [
                'key' => 'sharm-el-sheikh',
                'title' => __('Shore Excursions from Sharm el sheikh port'),
                'port_name' => __('Sharm el sheikh port'),
                'subtitle' => __('Sinai, Red Sea landscapes, and easy-paced cruise excursions.'),
                'city' => 'sharm-el-sheikh',
                'search' => 'Sharm El Sheikh',
                'image' => asset('website/images/shore-excursions/sharm-el-sheikh.jpg'),
            ],
            'ain-el-sokhna' => [
                'key' => 'ain-el-sokhna',
                'title' => __('Shore Excursions from Ain El Sokhna port'),
                'port_name' => __('Ain El Sokhna port'),
                'subtitle' => __('Fast access to Cairo, the pyramids, and museum treasures.'),
                'city' => 'ain-sokhna',
                'search' => 'Ain Sokhna',
                'image' => asset('website/images/shore-excursions/ain-el-sokhna.jpg'),
            ],
            'accessible' => [
                'key' => 'accessible',
                'title' => __('Accessible Shore Excursions'),
                'subtitle' => __('Smoother private touring options planned around mobility needs.'),
                'city' => null,
                'search' => 'Accessible',
                'search_terms' => ['Accessible', 'Wheelchair', 'Mobility'],
                'image' => asset('website/images/shore-excursions/accessible.jpg'),
            ],
        ];
    }

    private function applyShoreSectionScope($query, array $section, $cities): void
    {
        $citySlug = $section['city'] ?? null;
        $search = $section['search'] ?? '';
        $searchTerms = $section['search_terms'] ?? [$search];

        if ($citySlug && $cities instanceof \Illuminate\Support\Collection && $cities->has($citySlug)) {
            $city = $cities->get($citySlug);
            $query->where(function ($q) use ($city, $citySlug, $search) {
                $q->whereHas('cities', fn($sub) => $sub->where('cities.id', $city->id))
                    ->orWhere('destinations_text', 'like', "%{$citySlug}%")
                    ->orWhere('pickup_location', 'like', "%{$citySlug}%")
                    ->orWhere('pickup_location', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('route_text', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', '%' . str_replace(' ', '-', strtolower($search)) . '%');
            });
        } else {
            $query->where(function ($q) use ($searchTerms, $citySlug) {
                foreach ($searchTerms as $term) {
                    $slugTerm = str_replace(' ', '-', strtolower($term));
                    $q->orWhere(function ($termQuery) use ($term, $slugTerm) {
                        $termQuery->where('title', 'like', "%{$term}%")
                            ->orWhere('subtitle', 'like', "%{$term}%")
                            ->orWhere('short_description', 'like', "%{$term}%")
                            ->orWhere('description', 'like', "%{$term}%")
                            ->orWhere('destinations_text', 'like', "%{$term}%")
                            ->orWhere('pickup_location', 'like', "%{$term}%")
                            ->orWhere('dropoff_location', 'like', "%{$term}%")
                            ->orWhere('route_text', 'like', "%{$term}%")
                            ->orWhere('slug', 'like', "%{$slugTerm}%");
                    });
                }

                if ($citySlug) {
                    $q->orWhere('destinations_text', 'like', "%{$citySlug}%")
                        ->orWhere('pickup_location', 'like', "%{$citySlug}%")
                        ->orWhere('slug', 'like', "%{$citySlug}%");
                }
            });
        }
    }

    private function shoreExcursionSections(Request $request, ?string $activeSectionKey = null): array
    {
        $sectionDefinitions = $this->rawShoreSectionDefinitions();
        $counts = $this->cachedShoreExcursionCounts($sectionDefinitions);

        return collect($sectionDefinitions)->map(function (array $section) use ($counts, $activeSectionKey) {
            return $section + [
                'url' => route('website.shore_excursions.section', ['section' => $section['key']]),
                'count' => $counts[$section['key']] ?? 0,
                'active' => ($activeSectionKey === $section['key']),
            ];
        })->values()->all();
    }

    private function cachedShoreExcursionCounts(array $sectionDefinitions): array
    {
        $version = (int) Cache::get('website.home.version', 1);

        return Cache::remember(
            'website.shore.counts.v1.' . $version . '.' . app()->getLocale(),
            now()->addMinutes(30),
            function () use ($sectionDefinitions) {
                $cities = City::query()
                    ->where('is_active', true)
                    ->get()
                    ->keyBy('slug');

                $counts = [];
                foreach ($sectionDefinitions as $section) {
                    $countQuery = Package::query()
                        ->where('is_active', true)
                        ->where('package_type', 'shore_excursion');

                    $this->applyShoreSectionScope($countQuery, $section, $cities);

                    $counts[$section['key']] = $countQuery->count();
                }

                return $counts;
            }
        );
    }
}
