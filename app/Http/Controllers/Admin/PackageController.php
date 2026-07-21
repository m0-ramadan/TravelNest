<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attraction;
use App\Models\City;
use App\Models\Currency;
use App\Models\Package;
use App\Models\PackageCategory;
use App\Services\PackageAiService;
use App\Traits\ApiResponseTrait;
use App\Traits\HandlesTranslatedFields;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PackageController extends Controller
{
    use HandlesTranslatedFields;

    private const PACKAGE_TYPES = [
        'travel_package',
        'nile_cruise',
        'day_tour',
        'shore_excursion',
        'deal',
        'multi_country',
        'custom',
    ];

    protected array $translatedFields = [
        'title',
        'subtitle',
        'short_description',
        'description',
        'schedule_text',
        'pickup_location',
        'dropoff_location',
        'destinations_text',
        'location_summary',
        'cancellation_policy',
        'terms_conditions',
        'seo_title',
        'seo_description',
        'breadcrumb_title',
    ];

    public function index(Request $request): View
    {
        $packages = Package::query()
            ->with(['category', 'primaryCountry', 'currency', 'destination.city'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $this->applyTranslatedSearch(
                    $query,
                    ['title', 'subtitle', 'short_description', 'description'],
                    $request->string('q')
                );
            })
            ->latest()
            ->paginate($this->perPage($request))
            ->withQueryString();

        return view('admin.packages.index', compact('packages'));
    }
    protected function perPage(Request $request, int $default = 15): int
    {
        return max(5, min((int) $request->input('per_page', $default), 100));
    }
    public function create(): View
    {
        return view('admin.packages.create', [
            'categories' => PackageCategory::all(),
            'destinations' => $this->packageCities(),
            'currencies' => Currency::all(),
            'attractions' => $this->packageAttractionOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePackage($request);

        DB::transaction(function () use ($request, $validated) {
            $packageData = $this->preparePackageData($request, $validated);

            $package = Package::create($packageData);

            if ($request->has('facilities')) {
                $this->syncFacilities($package, $request);
            }
            $this->syncPackageAttractions($package, $request);
            $this->syncItineraries($package, $request);
            $this->syncInclusions($package, $request);
            $this->syncPrices($package, $request);
        });

        return redirect()->route('admin.packages.index')->with('success', 'تم إنشاء الرحلة بنجاح.');
    }

    public function show(Package $package): View
    {
        $package->load([
            'category',
            'destination.city',
            'currency',
            'facilities',
            'packageAttractions.attraction',
            'itineraries',
            'inclusions',
            'prices.currency',
        ]);

        return view('admin.packages.show', compact('package'));
    }

    public function edit(Package $package): View
    {
        $package->load([
            'facilities',
            'packageAttractions.attraction',
            'itineraries',
            'inclusions',
            'prices',
        ]);

        return view('admin.packages.edit', [
            'package' => $package,
            'categories' => PackageCategory::all(),
            'destinations' => $this->packageCities(),
            'currencies' => Currency::all(),
            'attractions' => $this->packageAttractionOptions(
                $package->packageAttractions->pluck('attraction_id')->all()
            ),
        ]);
    }

    public function update(Request $request, Package $package): RedirectResponse
    {
        $validated = $this->validatePackage($request);

        DB::transaction(function () use ($request, $validated, $package) {
            $packageData = $this->preparePackageData($request, $validated, $package);

            $package->update($packageData);

            if ($request->has('facilities')) {
                $this->syncFacilities($package, $request);
            }
            $this->syncPackageAttractions($package, $request);
            $this->syncItineraries($package, $request);
            $this->syncInclusions($package, $request);
            $this->syncPrices($package, $request);
        });

        return $this->success('admin.packages.index', 'تم تعديل الرحلة بنجاح.');
    }

    public function destroy(Package $package): RedirectResponse
    {
        $package->delete();

        return $this->success('admin.packages.index', 'تم حذف الرحلة بنجاح.');
    }

    public function createWithAI(): View
    {
        return view('admin.packages.create-with-ai', [
            'destinations' => $this->packageCities(),
            'categories' => PackageCategory::all(),
            'currencies' => Currency::all(),
        ]);
    }

    public function storeWithAI(Request $request, PackageAiService $packageAiService): RedirectResponse
    {
        $data = $request->validate([
            'prompt' => ['required', 'string'],
            'category_id' => ['nullable', 'integer'],
            'destination_id' => ['nullable', 'integer'],
            'primary_country_id' => ['nullable', 'integer'],
            'currency_id' => ['nullable', 'integer'],
            'package_type' => ['nullable', 'string', 'in:' . implode(',', self::PACKAGE_TYPES)],
            'tour_type' => ['nullable', 'string'],
            'difficulty_level' => ['nullable', 'string'],
            'booking_mode' => ['nullable', 'string'],
            'duration_type' => ['nullable', 'string', 'in:days,hours'],
            'duration_days' => ['nullable', 'integer'],
            'duration_nights' => ['nullable', 'integer'],
            'duration_hours' => ['nullable', 'integer'],
            'route_text' => ['nullable', 'string'],
            'schedule_text' => ['nullable', 'string'],
            'luxury_level' => ['nullable', 'string'],
            'content_language' => ['nullable', 'string'],
            'extra_instructions' => ['nullable', 'string'],
        ]);

        $selectedCity = !empty($data['destination_id'])
            ? City::find($data['destination_id'])
            : null;
        $destination = $selectedCity
            ? $this->resolveDestinationAttractionFromCityId((int) $selectedCity->id)
            : null;

        $category = !empty($data['category_id'])
            ? PackageCategory::find($data['category_id'])
            : null;

        $aiData = $packageAiService->generate([
            'prompt' => $data['prompt'],
            'duration_days' => $data['duration_days'] ?? null,
            'duration_nights' => $data['duration_nights'] ?? null,
            'duration_hours' => $data['duration_hours'] ?? null,
            'route_text' => $data['route_text'] ?? null,
            'schedule_text' => $data['schedule_text'] ?? null,
            'luxury_level' => $data['luxury_level'] ?? null,
            'content_language' => $data['content_language'] ?? 'en',
            'extra_instructions' => $data['extra_instructions'] ?? null,
            'destination_name' => $this->adminTrans($selectedCity?->name),
            'category_name' => $this->adminTrans($category?->name),
        ]);

        if (!$aiData || !is_array($aiData)) {
            return back()->withInput()->with('error', 'فشل توليد بيانات الرحلة بالذكاء الاصطناعي.');
        }

        DB::transaction(function () use ($request, $data, $aiData, $destination, $selectedCity) {
            $finalData = array_merge($aiData, $data);

            unset(
                $finalData['prompt'],
                $finalData['luxury_level'],
                $finalData['content_language'],
                $finalData['extra_instructions']
            );

            $finalData['destination_id'] = $destination?->id;

            if (!empty($selectedCity?->country_id)) {
                $finalData['primary_country_id'] = $selectedCity->country_id;
            }

            $finalData['is_active'] = true;
            $finalData['is_featured'] = false;
            $finalData['is_best_seller'] = false;
            $finalData['is_ultra_luxury'] = false;
            $finalData['rating_avg'] = $finalData['rating_avg'] ?? 0;
            $finalData['reviews_count'] = $finalData['reviews_count'] ?? 0;
            $finalData['sort_order'] = $finalData['sort_order'] ?? 0;
            $finalData['duration_type'] = $finalData['duration_type']
                ?? (!empty($finalData['duration_hours']) ? 'hours' : 'days');

            if ($finalData['duration_type'] === 'hours') {
                $finalData['duration_days'] = null;
                $finalData['duration_nights'] = null;
            } else {
                $finalData['duration_hours'] = null;
            }

            $packageData = $this->prepareAiPackageData($finalData);

            $package = Package::create($packageData);

            $this->createFacilitiesFromArray($package, $aiData['facilities'] ?? []);
            $this->createItinerariesFromArray($package, $aiData['itinerary'] ?? []);
            $this->createInclusionsFromArray($package, $aiData['included'] ?? [], 'included');
            $this->createInclusionsFromArray($package, $aiData['excluded'] ?? [], 'excluded');
            $this->createPricesFromArray($package, $aiData['prices'] ?? [], $package->currency_id);
        });

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'تم إنشاء الرحلة بالذكاء الاصطناعي.');
    }

    public function toggleStatus(Package $package): RedirectResponse
    {
        $package->update(['is_active' => !(bool) $package->is_active]);

        return back()->with('success', 'تم تحديث حالة الرحلة.');
    }

    public function toggleFeatured(Package $package): RedirectResponse
    {
        $package->update(['is_featured' => !(bool) $package->is_featured]);

        return back()->with('success', 'تم تحديث تمييز الرحلة.');
    }

    public function duplicate(Package $package): RedirectResponse
    {
        DB::transaction(function () use ($package) {
            $package->load(['facilities', 'packageAttractions', 'itineraries', 'inclusions', 'prices']);

            $copy = $package->replicate();
            $copy->slug = $package->slug . '-' . now()->timestamp;
            $copy->title = [
                'en' => ($package->display_title ?? $this->adminTrans($package->title)) . ' (Copy)',
                'ar' => ($package->display_title ?? $this->adminTrans($package->title)) . ' (Copy)',
            ];
            $copy->save();

            foreach ($package->facilities as $facility) {
                $copy->facilities()->create([
                    'title' => $facility->title,
                    'description' => $facility->description ?? '',
                    'sort_order' => $facility->sort_order,
                ]);
            }

            foreach ($package->packageAttractions as $packageAttraction) {
                $copy->packageAttractions()->create($packageAttraction->only([
                    'attraction_id',
                    'title',
                    'teaser',
                    'image',
                    'sort_order',
                ]));
            }

            foreach ($package->itineraries as $day) {
                $copy->itineraries()->create($day->only([
                    'duration',
                    'day_number',
                    'title',
                    'description',
                    'meals_breakfast',
                    'meals_lunch',
                    'meals_dinner',
                ]));
            }

            foreach ($package->inclusions as $item) {
                $copy->inclusions()->create($item->only([
                    'title',
                    'content',
                    'description',
                    'type',
                    'item_type',
                    'sort_order',
                ]));
            }

            foreach ($package->prices as $price) {
                $copy->prices()->create($price->only([
                    'label',
                    'season_name',
                    'price_type',
                    'room_type',
                    'amount',
                    'currency_id',
                    'valid_from',
                    'valid_to',
                    'notes',
                ]));
            }

            session()->flash('duplicated_package_id', $copy->id);
        });

        $copyId = session('duplicated_package_id');

        return redirect()
            ->route('admin.packages.edit', $copyId)
            ->with('success', 'تم نسخ الرحلة بنجاح.');
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $ids = (array) $request->input('ids', []);
        $action = (string) $request->input('action');

        if ($action === 'delete') {
            Package::whereIn('id', $ids)->delete();
        }

        if ($action === 'activate') {
            Package::whereIn('id', $ids)->update(['is_active' => true]);
        }

        if ($action === 'deactivate') {
            Package::whereIn('id', $ids)->update(['is_active' => false]);
        }

        return back()->with('success', 'تم تنفيذ الإجراء بنجاح.');
    }

    public function statistics()
    {
        return response()->json([
            'total' => Package::count(),
            'active' => Package::where('is_active', true)->count(),
            'featured' => Package::where('is_featured', true)->count(),
        ]);
    }

    public function enhanceWithAI(Request $request)
    {
        return response()->json(['message' => 'Connect AI service here.']);
    }

    public function generateSeoWithAI(Request $request)
    {
        return response()->json([
            'meta_title' => $request->input('title'),
            'meta_description' => $request->input('short_description'),
        ]);
    }

    public function translateWithAI(Request $request)
    {
        return response()->json(['message' => 'Connect translation service here.']);
    }

    private function validatePackage(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'category_id' => ['nullable', 'integer'],
            'destination_id' => ['nullable', 'integer'],
            'primary_country_id' => ['nullable', 'integer'],
            'package_type' => ['nullable', 'string', 'in:' . implode(',', self::PACKAGE_TYPES)],
            'slug' => ['nullable', 'string'],
            'title' => ['nullable', 'string'],
            'subtitle' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],

            'duration_type' => ['required', 'string', 'in:days,hours'],
            'duration_days' => ['nullable', 'integer'],
            'duration_nights' => ['nullable', 'integer'],
            'duration_hours' => ['nullable', 'integer'],
            'duration_text' => ['nullable', 'string'],
            'route_text' => ['nullable', 'string'],

            'start_from_price' => ['nullable', 'numeric'],
            'compare_price' => ['nullable', 'numeric'],
            'adult_price' => ['required', 'numeric', 'min:0'],
            'child_price' => ['nullable', 'numeric', 'min:0'],
            'infant_price' => ['nullable', 'numeric', 'min:0'],
            'adult_min_age' => ['required', 'integer', 'min:0'],
            'child_min_age' => ['required', 'integer', 'min:0'],
            'child_max_age' => ['required', 'integer', 'gte:child_min_age'],
            'infant_min_age' => ['required', 'integer', 'min:0'],
            'infant_max_age' => ['required', 'integer', 'gte:infant_min_age'],
            'currency_id' => ['nullable', 'integer'],

            'schedule_text' => ['nullable', 'string'],
            'pickup_location' => ['nullable', 'string'],
            'dropoff_location' => ['nullable', 'string'],
            'destinations_text' => ['nullable', 'string'],
            'location_summary' => ['nullable', 'string'],
            'tour_type' => ['nullable', 'string'],
            'difficulty_level' => ['nullable', 'string'],
            'booking_mode' => ['nullable', 'string'],

            'rating_avg' => ['nullable', 'numeric'],
            'reviews_count' => ['nullable', 'integer'],
            'min_participants' => ['nullable', 'integer'],
            'max_participants' => ['nullable', 'integer'],
            'booking_lead_days' => ['nullable', 'integer'],

            'cancellation_policy' => ['nullable', 'string'],
            'terms_conditions' => ['nullable', 'string'],
            'children_policy' => ['nullable', 'string'],
            'pickup_policy' => ['nullable', 'string'],
            'pricing_information' => ['nullable', 'string'],

            'video_url' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer'],

            'seo_title' => ['nullable', 'string'],
            'seo_description' => ['nullable', 'string'],
            'breadcrumb_title' => ['nullable', 'string'],
            'canonical_url' => ['nullable', 'string'],

            'is_featured' => ['nullable', 'boolean'],
            'is_best_seller' => ['nullable', 'boolean'],
            'is_ultra_luxury' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],

            'featured_image' => ['nullable', 'image', 'max:5120'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['nullable', 'image', 'max:5120'],

            'facilities' => ['nullable', 'array'],
            'facilities.*.title' => ['nullable', 'string'],
            'facilities.*.sort_order' => ['nullable', 'integer'],

            'attraction_ids' => ['nullable', 'array'],
            'attraction_ids.*' => ['integer', 'distinct', 'exists:attractions,id'],

            'itinerary' => ['nullable', 'array'],
            'itinerary.*.duration' => ['nullable', 'string'],
            'itinerary.*.day_number' => ['nullable', 'integer'],
            'itinerary.*.title' => ['nullable', 'string'],
            'itinerary.*.description' => ['nullable', 'string'],
            'itinerary.*.meals_breakfast' => ['nullable', 'boolean'],
            'itinerary.*.meals_lunch' => ['nullable', 'boolean'],
            'itinerary.*.meals_dinner' => ['nullable', 'boolean'],

            'included' => ['nullable', 'array'],
            'included.*.title' => ['nullable', 'string'],

            'excluded' => ['nullable', 'array'],
            'excluded.*.title' => ['nullable', 'string'],

            'prices' => ['nullable', 'array'],
            'prices.*.label' => ['nullable', 'string'],
            'prices.*.season_name' => ['nullable', 'string'],
            'prices.*.price_type' => ['nullable', 'string'],
            'prices.*.room_type' => ['nullable', 'string'],
            'prices.*.amount' => ['nullable', 'numeric'],
            'prices.*.currency_id' => ['nullable', 'integer'],
            'prices.*.valid_from' => ['nullable', 'date'],
            'prices.*.valid_to' => ['nullable', 'date'],
            'prices.*.notes' => ['nullable', 'string'],

            'faq_json' => ['nullable', 'array'],
            'faq_json.*.question' => ['nullable', 'string'],
            'faq_json.*.answer' => ['nullable', 'string'],
        ]);

        $validator->after(function ($validator) use ($request) {
            $adultMinAge = (int) $request->input('adult_min_age', 12);
            $childMinAge = (int) $request->input('child_min_age', 2);
            $childMaxAge = (int) $request->input('child_max_age', 11);
            $infantMinAge = (int) $request->input('infant_min_age', 0);
            $infantMaxAge = (int) $request->input('infant_max_age', 1);

            if ($infantMaxAge >= $childMinAge) {
                $validator->errors()->add('infant_max_age', 'يجب أن تنتهي فئة الرضع قبل بداية فئة الأطفال.');
            }

            if ($childMaxAge >= $adultMinAge) {
                $validator->errors()->add('child_max_age', 'يجب أن تنتهي فئة الأطفال قبل بداية فئة البالغين.');
            }

            if ($adultMinAge <= $infantMaxAge) {
                $validator->errors()->add('adult_min_age', 'حد البالغين يجب أن يكون أكبر من الحد الأعلى للرضع.');
            }
        });

        return $validator->validate();
    }

    private function preparePackageData(Request $request, array $validated, ?Package $package = null): array
    {
        $data = collect($validated)->except([
            'featured_image',
            'gallery_images',
            'facilities',
            'attraction_ids',
            'itinerary',
            'included',
            'excluded',
            'prices',
            'faq_json',
        ])->toArray();

        $selectedCity = !empty($data['destination_id'])
            ? City::find($data['destination_id'])
            : null;
        $selectedAttraction = $selectedCity
            ? $this->resolveDestinationAttractionFromCityId((int) $selectedCity->id)
            : null;

        $data['destination_id'] = $selectedAttraction?->id;
        $data['package_type'] = $this->normalizePackageType($data['package_type'] ?? null);
        $data['duration_type'] = $data['duration_type'] ?? 'days';

        if ($data['duration_type'] === 'hours') {
            $data['duration_days'] = null;
            $data['duration_nights'] = null;
        } else {
            $data['duration_hours'] = null;
        }

        if (empty($data['primary_country_id']) && !empty($selectedCity?->country_id)) {
            $data['primary_country_id'] = $selectedCity->country_id;
        }

        $data = $this->translateModelFields($data, $this->translatedFields);
        $data['faq_json'] = $this->normalizeFaqItems((array) $request->input('faq_json', []));

        $data['slug'] = !empty($data['slug'])
            ? Str::slug($data['slug'])
            : Str::slug($data['title']['en'] ?? $data['title']['ar'] ?? 'package-' . time());

        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_best_seller'] = $request->boolean('is_best_seller');
        $data['is_ultra_luxury'] = $request->boolean('is_ultra_luxury');

        [$priceFrom, $priceTo] = $this->resolveCategoryPriceBounds($data);
        $data['price_from'] = $priceFrom;
        $data['price_to'] = $priceTo;
        $data['start_from_price'] = $priceFrom;

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->uploadFile($request->file('featured_image'), 'packages');
        } elseif ($package) {
            unset($data['featured_image']);
        }

        if ($request->hasFile('gallery_images')) {
            $data['gallery_images'] = $this->uploadMultipleFiles($request->file('gallery_images'), 'packages/gallery');
        } elseif ($package) {
            unset($data['gallery_images']);
        }

        return $data;
    }

    private function normalizePackageType(?string $packageType): string
    {
        $packageType = strtolower(trim((string) $packageType));

        return match ($packageType) {
            'tailor_made' => 'custom',
            'travel-package' => 'travel_package',
            'nile-cruise' => 'nile_cruise',
            'day-tour' => 'day_tour',
            'shore-excursion' => 'shore_excursion',
            'multi-country' => 'multi_country',
            '' => 'travel_package',
            default => in_array($packageType, self::PACKAGE_TYPES, true) ? $packageType : 'travel_package',
        };
    }

    private function normalizeFaqItems(array $items): array
    {
        $normalized = [];

        foreach ($items as $item) {
            $question = trim((string) ($item['question'] ?? ''));
            $answer = trim((string) ($item['answer'] ?? ''));

            if ($question === '' && $answer === '') {
                continue;
            }

            $translated = $this->translateModelFields([
                'question' => $question,
                'answer' => $answer,
            ], ['question', 'answer']);

            $normalized[] = [
                'question' => $translated['question'] ?? ['en' => $question, 'ar' => $question],
                'answer' => $translated['answer'] ?? ['en' => $answer, 'ar' => $answer],
            ];
        }

        return $normalized;
    }

    private function resolveCategoryPriceBounds(array $data): array
    {
        $prices = collect([
            $data['adult_price'] ?? null,
            $data['child_price'] ?? null,
            $data['infant_price'] ?? null,
        ])->filter(fn ($price) => $price !== null && $price !== '');

        $paidPrices = $prices->filter(fn ($price) => (float) $price > 0);

        $priceFrom = (float) ($paidPrices->min() ?? 0);
        $priceTo = (float) ($prices->max() ?? 0);

        return [$priceFrom, $priceTo];
    }

    private function prepareAiPackageData(array $data): array
    {
        $packageData = collect($data)->except([
            'facilities',
            'itinerary',
            'included',
            'excluded',
            'prices',
        ])->toArray();

        $packageData = $this->normalizeTranslatedFields($packageData);

        $packageData['slug'] = !empty($packageData['slug'])
            ? Str::slug($packageData['slug'])
            : Str::slug(
                $packageData['title']['en']
                    ?? $packageData['title']['ar']
                    ?? 'package-' . time()
            );

        return $packageData;
    }

    private function packageCities()
    {
        return City::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    private function packageAttractionOptions(array $includeIds = [])
    {
        return Attraction::query()
            ->with('city')
            ->where(function ($query) use ($includeIds) {
                $query->where('is_active', true);

                if (!empty($includeIds)) {
                    $query->orWhereIn('id', $includeIds);
                }
            })
            ->orderBy('city_id')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    private function resolveDestinationAttractionFromCityId(int $cityId): ?Attraction
    {
        return Attraction::query()
            ->where('city_id', $cityId)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->first();
    }

    private function normalizeTranslatedFields(array $data): array
    {
        foreach ($this->translatedFields as $field) {
            if (!array_key_exists($field, $data)) {
                continue;
            }

            if (is_array($data[$field])) {
                $data[$field] = [
                    'en' => $data[$field]['en'] ?? $data[$field]['ar'] ?? '',
                    'ar' => $data[$field]['ar'] ?? $data[$field]['en'] ?? '',
                ];
                continue;
            }

            if (is_string($data[$field]) && trim($data[$field]) !== '') {
                $data[$field] = $this->translateModelFields([$field => $data[$field]], [$field])[$field];
                continue;
            }

            $data[$field] = ['en' => '', 'ar' => ''];
        }

        return $data;
    }

    private function syncFacilities(Package $package, Request $request): void
    {
        $package->facilities()->delete();

        foreach ((array) $request->input('facilities', []) as $index => $facility) {
            if (empty($facility['title'])) {
                continue;
            }

            $package->facilities()->create([
                'title' => $facility['title'],
                'description' => '',
                'sort_order' => $facility['sort_order'] ?? $index,
            ]);
        }
    }

    private function syncPackageAttractions(Package $package, Request $request): void
    {
        $selectedIds = collect((array) $request->input('attraction_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        $attractions = Attraction::query()
            ->whereIn('id', $selectedIds)
            ->get()
            ->keyBy('id');

        $package->packageAttractions()->delete();

        foreach ($selectedIds as $sortOrder => $attractionId) {
            $attraction = $attractions->get($attractionId);

            if (!$attraction) {
                continue;
            }

            $package->packageAttractions()->create([
                'attraction_id' => $attraction->id,
                'sort_order' => $sortOrder,
            ]);
        }
    }

    private function syncItineraries(Package $package, Request $request): void
    {
        $package->itineraries()->delete();
        $position = 0;

        foreach ((array) $request->input('itinerary', []) as $day) {
            if (empty($day['title']) && empty($day['description'])) {
                continue;
            }

            $package->itineraries()->create([
                'duration' => $day['duration'] ?? null,
                'day_number' => $position + 1,
                'title' => $day['title'] ?? null,
                'description' => $day['description'] ?? null,
                'meals_breakfast' => !empty($day['meals_breakfast']),
                'meals_lunch' => !empty($day['meals_lunch']),
                'meals_dinner' => !empty($day['meals_dinner']),
                'sort_order' => $position,
            ]);

            $position++;
        }
    }

    private function syncInclusions(Package $package, Request $request): void
    {
        $package->inclusions()->delete();

        foreach ((array) $request->input('included', []) as $index => $item) {
            $title = trim((string) ($item['title'] ?? ''));

            if ($title === '') {
                continue;
            }

            $package->inclusions()->create([
                'title' => $title,
                'content' => $title,
                'type' => 'included',
                'item_type' => 'included',
                'sort_order' => $index,
            ]);
        }

        foreach ((array) $request->input('excluded', []) as $index => $item) {
            $title = trim((string) ($item['title'] ?? ''));

            if ($title === '') {
                continue;
            }

            $package->inclusions()->create([
                'title' => $title,
                'content' => $title,
                'type' => 'excluded',
                'item_type' => 'excluded',
                'sort_order' => $index,
            ]);
        }
    }

    private function syncPrices(Package $package, Request $request): void
    {
        $package->prices()->delete();

        foreach ((array) $request->input('prices', []) as $price) {
            if (empty($price['amount'])) {
                continue;
            }

            $package->prices()->create([
                'label' => $price['label'] ?? null,
                'season_name' => $price['season_name'] ?? null,
                'price_type' => $price['price_type'] ?? 'from',
                'room_type' => $price['room_type'] ?? null,
                'amount' => $price['amount'],
                'currency_id' => $price['currency_id'] ?? $package->currency_id,
                'valid_from' => $price['valid_from'] ?? null,
                'valid_to' => $price['valid_to'] ?? null,
                'notes' => $price['notes'] ?? null,
            ]);
        }
    }

    private function createFacilitiesFromArray(Package $package, array $facilities): void
    {
        foreach ($facilities as $index => $facility) {
            $title = is_array($facility) ? ($facility['title'] ?? null) : $facility;

            if (!$title) {
                continue;
            }

            $package->facilities()->create([
                'title' => $title,
                'description' => '',
                'sort_order' => $index,
            ]);
        }
    }

    private function createItinerariesFromArray(Package $package, array $itinerary): void
    {
        $position = 0;

        foreach ($itinerary as $day) {
            if (!is_array($day)) {
                continue;
            }

            $package->itineraries()->create([
                'duration' => $day['duration'] ?? null,
                'day_number' => $position + 1,
                'title' => $day['title'] ?? null,
                'description' => $day['description'] ?? null,
                'meals_breakfast' => !empty($day['meals_breakfast']),
                'meals_lunch' => !empty($day['meals_lunch']),
                'meals_dinner' => !empty($day['meals_dinner']),
                'sort_order' => $position,
            ]);

            $position++;
        }
    }

    private function createInclusionsFromArray(Package $package, array $items, string $type): void
    {
        foreach ($items as $index => $item) {
            $title = is_array($item) ? ($item['title'] ?? null) : $item;

            if (!$title) {
                continue;
            }

            $package->inclusions()->create([
                'title' => $title,
                'content' => $title,
                'type' => $type,
                'item_type' => $type,
                'sort_order' => $index,
            ]);
        }
    }

    private function createPricesFromArray(Package $package, array $prices, ?int $currencyId = null): void
    {
        foreach ($prices as $price) {
            if (!is_array($price) || empty($price['amount'])) {
                continue;
            }

            $package->prices()->create([
                'label' => $price['label'] ?? $price['duration'] ?? null,
                'season_name' => $price['season_name'] ?? $price['season'] ?? null,
                'price_type' => $price['price_type'] ?? 'from',
                'room_type' => $price['room_type'] ?? null,
                'amount' => $price['amount'],
                'currency_id' => $price['currency_id'] ?? $currencyId,
                'valid_from' => $price['valid_from'] ?? null,
                'valid_to' => $price['valid_to'] ?? null,
                'notes' => $price['notes'] ?? null,
            ]);
        }
    }

    private function uploadFile($file, string $path): string
    {
        return 'storage/' . $file->store($path, 'public');
    }

    private function uploadMultipleFiles(array $files, string $path): array
    {
        $uploaded = [];

        foreach ($files as $file) {
            if ($file) {
                $uploaded[] = $this->uploadFile($file, $path);
            }
        }

        return $uploaded;
    }

    private function adminTrans($value, array $preferred = ['ar', 'en']): string
    {
        if (!is_array($value)) {
            return (string) ($value ?? '');
        }

        foreach ($preferred as $lang) {
            if (!empty($value[$lang])) {
                return (string) $value[$lang];
            }
        }

        foreach ($value as $translation) {
            if (is_string($translation) && trim($translation) !== '') {
                return trim($translation);
            }
        }

        return '';
    }
}
