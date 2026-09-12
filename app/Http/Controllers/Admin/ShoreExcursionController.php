<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\City;
use App\Models\Currency;
use App\Models\Package;
use App\Models\PackageAddon;
use App\Models\PackageInclusion;
use App\Traits\HandlesTranslatedFields;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShoreExcursionController extends Controller
{
    use HandlesTranslatedFields;

    public const SHORE_SECTIONS = [
        'alexandria' => [
            'key' => 'alexandria',
            'title_en' => 'Alexandria Port Tours',
            'title_ar' => 'رحلات ميناء الإسكندرية',
            'city_slug' => 'alexandria',
            'default_pickup' => 'Alexandria Port Marine Passenger Terminal',
        ],
        'port-said' => [
            'key' => 'port-said',
            'title_en' => 'Port Said Port Tours',
            'title_ar' => 'رحلات ميناء بورسعيد',
            'city_slug' => 'port-said',
            'default_pickup' => 'Port Said Cruise Passenger Terminal',
        ],
        'safaga' => [
            'key' => 'safaga',
            'title_en' => 'Safaga Port Tours (Luxor & Red Sea)',
            'title_ar' => 'رحلات ميناء سفاجا (الأقصر والبحر الأحمر)',
            'city_slug' => 'safaga',
            'default_pickup' => 'Safaga Port Cruise Terminal Gate',
        ],
        'ain-el-sokhna' => [
            'key' => 'ain-el-sokhna',
            'title_en' => 'Ain El Sokhna Port Tours',
            'title_ar' => 'رحلات ميناء العين السخنة',
            'city_slug' => 'ain-sokhna',
            'default_pickup' => 'Ain Sokhna Cruise Terminal Gate',
        ],
        'sharm-el-sheikh' => [
            'key' => 'sharm-el-sheikh',
            'title_en' => 'Sharm El Sheikh Shore Excursions',
            'title_ar' => 'رحلات شرم الشيخ الشاطئية',
            'city_slug' => 'sharm-el-sheikh',
            'default_pickup' => 'Sharm El Sheikh Port Cruise Terminal',
        ],
        'accessible' => [
            'key' => 'accessible',
            'title_en' => 'Accessible Shore Excursions',
            'title_ar' => 'رحلات شاطئية ميسرة (كبار السن وذوي الاحتياجات)',
            'city_slug' => null,
            'default_pickup' => 'Port Passenger Terminal',
        ],
    ];

    public function index(Request $request): View
    {
        $query = Package::query()
            ->where('package_type', 'shore_excursion')
            ->with(['currency', 'cities'])
            ->withCount('bookings')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->string('search')->toString();
                $this->applyTranslatedSearch($q, ['title'], $search);
            })
            ->when($request->filled('port'), function ($q) use ($request) {
                $port = $request->string('port')->toString();
                $q->where(function ($sub) use ($port) {
                    $sub->where('destinations_text', 'like', "%{$port}%")
                        ->orWhere('pickup_location', 'like', "%{$port}%")
                        ->orWhere('slug', 'like', "%{$port}%");
                });
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('is_active', $request->string('status')->toString() === 'active');
            })
            ->latest();

        $totalShore = Package::where('package_type', 'shore_excursion')->count();
        $activeShore = Package::where('package_type', 'shore_excursion')->where('is_active', true)->count();
        $totalBookings = Booking::whereHas('package', fn($q) => $q->where('package_type', 'shore_excursion'))->count();

        $packages = $query->paginate($this->perPage($request))->withQueryString();

        return view('admin.shore-excursions.index', [
            'packages' => $packages,
            'sections' => self::SHORE_SECTIONS,
            'totalShore' => $totalShore,
            'activeShore' => $activeShore,
            'totalBookings' => $totalBookings,
        ]);
    }

    public function create(): View
    {
        return view('admin.shore-excursions.create', [
            'sections' => self::SHORE_SECTIONS,
            'currencies' => Currency::all(),
            'defaultCurrency' => Currency::where('code', 'USD')->first() ?? Currency::first(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title.en' => ['required', 'string', 'max:255'],
            'title.ar' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:packages,slug'],
            'port_section' => ['required', 'string'],
            'pickup_location.en' => ['nullable', 'string', 'max:255'],
            'pickup_location.ar' => ['nullable', 'string', 'max:255'],
            'duration_hours' => ['nullable', 'numeric', 'min:1', 'max:72'],
            'duration_days' => ['nullable', 'integer', 'min:1', 'max:30'],
            'short_description.en' => ['nullable', 'string'],
            'short_description.ar' => ['nullable', 'string'],
            'description.en' => ['nullable', 'string'],
            'description.ar' => ['nullable', 'string'],
            'price_1_person' => ['required', 'numeric', 'min:1'],
            'price_2_persons' => ['nullable', 'numeric', 'min:1'],
            'price_4_persons' => ['nullable', 'numeric', 'min:1'],
            'price_6_plus_persons' => ['nullable', 'numeric', 'min:1'],
            'currency_id' => ['nullable', 'exists:currencies,id'],
            'featured_image' => ['nullable', 'image', 'max:5120'],
            'gallery_images.*' => ['nullable', 'image', 'max:5120'],
            'operating_days' => ['nullable', 'array'],
            'inclusions' => ['nullable', 'array'],
            'exclusions' => ['nullable', 'array'],
            'addons' => ['nullable', 'array'],
            'seo_title.en' => ['nullable', 'string', 'max:255'],
            'seo_title.ar' => ['nullable', 'string', 'max:255'],
            'seo_description.en' => ['nullable', 'string', 'max:500'],
            'seo_description.ar' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $sectionKey = $request->string('port_section')->toString();
        $sectionMeta = self::SHORE_SECTIONS[$sectionKey] ?? null;

        $slug = filled($validated['slug'] ?? null)
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']['en']);

        // Ensure unique slug
        $baseSlug = $slug;
        $counter = 1;
        while (Package::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        // Prepare group tiers
        $tiers = $this->buildGroupPricingTiers($request);
        $priceFrom = (float) collect($tiers)->pluck('price_per_person')->min() ?: (float) $request->input('price_1_person');

        $data = [
            'package_type' => 'shore_excursion',
            'slug' => $slug,
            'title' => [
                'en' => trim((string) ($validated['title']['en'] ?? '')),
                'ar' => trim((string) ($validated['title']['ar'] ?? '')) ?: trim((string) ($validated['title']['en'] ?? '')),
            ],
            'destinations_text' => [
                'en' => $sectionMeta['title_en'] ?? $sectionKey,
                'ar' => $sectionMeta['title_ar'] ?? $sectionKey,
            ],
            'pickup_location' => [
                'en' => trim((string) ($validated['pickup_location']['en'] ?? ($sectionMeta['default_pickup'] ?? 'Port Cruise Terminal'))),
                'ar' => trim((string) ($validated['pickup_location']['ar'] ?? 'رصيف الميناء ومحطة الركاب البحرية')),
            ],
            'duration_hours' => $validated['duration_hours'] ?? 8,
            'duration_days' => $validated['duration_days'] ?? 1,
            'duration_text' => ($validated['duration_hours'] ?? 8) . ' Hours',
            'short_description' => [
                'en' => trim((string) ($validated['short_description']['en'] ?? '')),
                'ar' => trim((string) ($validated['short_description']['ar'] ?? '')),
            ],
            'description' => [
                'en' => trim((string) ($validated['description']['en'] ?? '')),
                'ar' => trim((string) ($validated['description']['ar'] ?? '')),
            ],
            'price_from' => $priceFrom,
            'start_from_price' => $priceFrom,
            'group_pricing_tiers' => $tiers,
            'currency_id' => $request->input('currency_id'),
            'operating_days' => $request->input('operating_days', ['daily']),
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
            'seo_title' => $validated['seo_title'] ?? null,
            'seo_description' => $validated['seo_description'] ?? null,
        ];

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = 'storage/' . $request->file('featured_image')->store('packages', 'public');
        }

        if ($request->hasFile('gallery_images')) {
            $gallery = [];
            foreach ($request->file('gallery_images') as $file) {
                if ($file) {
                    $gallery[] = 'storage/' . $file->store('packages/gallery', 'public');
                }
            }
            $data['gallery_images'] = $gallery;
        }

        $package = DB::transaction(function () use ($data, $request, $sectionMeta) {
            $package = Package::create($data);

            // Link port city if available
            if (!empty($sectionMeta['city_slug'])) {
                $city = City::where('slug', $sectionMeta['city_slug'])->first();
                if ($city) {
                    $package->cities()->syncWithoutDetaching([$city->id]);
                }
            }

            $this->syncInclusionsAndExclusions($package, $request);
            $this->syncAddons($package, $request);

            return $package;
        });

        return redirect()->route('admin.shore-excursions.index')
            ->with('success', admin_t('Shore excursion created successfully.'));
    }

    public function edit(Package $package): View
    {
        if ($package->package_type !== 'shore_excursion') {
            abort(404);
        }

        $package->load(['inclusions', 'addons', 'currency']);

        // Determine current port section
        $currentPortKey = 'alexandria';
        $destText = is_array($package->destinations_text) ? implode(' ', $package->destinations_text) : (string) $package->destinations_text;
        $pickup = is_array($package->pickup_location) ? implode(' ', $package->pickup_location) : (string) $package->pickup_location;
        $haystack = strtolower($package->slug . ' ' . $destText . ' ' . $pickup);

        foreach (self::SHORE_SECTIONS as $key => $sec) {
            if (Str::contains($haystack, $key) || (!empty($sec['city_slug']) && Str::contains($haystack, $sec['city_slug']))) {
                $currentPortKey = $key;
                break;
            }
        }

        return view('admin.shore-excursions.edit', [
            'package' => $package,
            'sections' => self::SHORE_SECTIONS,
            'currentPortKey' => $currentPortKey,
            'currencies' => Currency::all(),
            'bookingsCount' => $package->bookings()->count(),
        ]);
    }

    public function update(Request $request, Package $package): RedirectResponse
    {
        if ($package->package_type !== 'shore_excursion') {
            abort(404);
        }

        $validated = $request->validate([
            'title.en' => ['required', 'string', 'max:255'],
            'title.ar' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:packages,slug,' . $package->id],
            'port_section' => ['required', 'string'],
            'pickup_location.en' => ['nullable', 'string', 'max:255'],
            'pickup_location.ar' => ['nullable', 'string', 'max:255'],
            'duration_hours' => ['nullable', 'numeric', 'min:1', 'max:72'],
            'duration_days' => ['nullable', 'integer', 'min:1', 'max:30'],
            'short_description.en' => ['nullable', 'string'],
            'short_description.ar' => ['nullable', 'string'],
            'description.en' => ['nullable', 'string'],
            'description.ar' => ['nullable', 'string'],
            'price_1_person' => ['required', 'numeric', 'min:1'],
            'price_2_persons' => ['nullable', 'numeric', 'min:1'],
            'price_4_persons' => ['nullable', 'numeric', 'min:1'],
            'price_6_plus_persons' => ['nullable', 'numeric', 'min:1'],
            'currency_id' => ['nullable', 'exists:currencies,id'],
            'featured_image' => ['nullable', 'image', 'max:5120'],
            'gallery_images.*' => ['nullable', 'image', 'max:5120'],
            'operating_days' => ['nullable', 'array'],
            'inclusions' => ['nullable', 'array'],
            'exclusions' => ['nullable', 'array'],
            'addons' => ['nullable', 'array'],
            'seo_title.en' => ['nullable', 'string', 'max:255'],
            'seo_title.ar' => ['nullable', 'string', 'max:255'],
            'seo_description.en' => ['nullable', 'string', 'max:500'],
            'seo_description.ar' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $sectionKey = $request->string('port_section')->toString();
        $sectionMeta = self::SHORE_SECTIONS[$sectionKey] ?? null;

        $tiers = $this->buildGroupPricingTiers($request);
        $priceFrom = (float) collect($tiers)->pluck('price_per_person')->min() ?: (float) $request->input('price_1_person');

        $data = [
            'slug' => Str::slug($validated['slug']),
            'title' => [
                'en' => trim((string) ($validated['title']['en'] ?? '')),
                'ar' => trim((string) ($validated['title']['ar'] ?? '')) ?: trim((string) ($validated['title']['en'] ?? '')),
            ],
            'destinations_text' => [
                'en' => $sectionMeta['title_en'] ?? $sectionKey,
                'ar' => $sectionMeta['title_ar'] ?? $sectionKey,
            ],
            'pickup_location' => [
                'en' => trim((string) ($validated['pickup_location']['en'] ?? ($sectionMeta['default_pickup'] ?? 'Port Cruise Terminal'))),
                'ar' => trim((string) ($validated['pickup_location']['ar'] ?? 'رصيف الميناء ومحطة الركاب البحرية')),
            ],
            'duration_hours' => $validated['duration_hours'] ?? 8,
            'duration_days' => $validated['duration_days'] ?? 1,
            'duration_text' => ($validated['duration_hours'] ?? 8) . ' Hours',
            'short_description' => [
                'en' => trim((string) ($validated['short_description']['en'] ?? '')),
                'ar' => trim((string) ($validated['short_description']['ar'] ?? '')),
            ],
            'description' => [
                'en' => trim((string) ($validated['description']['en'] ?? '')),
                'ar' => trim((string) ($validated['description']['ar'] ?? '')),
            ],
            'price_from' => $priceFrom,
            'start_from_price' => $priceFrom,
            'group_pricing_tiers' => $tiers,
            'currency_id' => $request->input('currency_id'),
            'operating_days' => $request->input('operating_days', ['daily']),
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
            'seo_title' => $validated['seo_title'] ?? null,
            'seo_description' => $validated['seo_description'] ?? null,
        ];

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = 'storage/' . $request->file('featured_image')->store('packages', 'public');
        }

        if ($request->boolean('gallery_section_present')) {
            $gallery = (array) $request->input('existing_gallery', []);
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $file) {
                    if ($file) {
                        $gallery[] = 'storage/' . $file->store('packages/gallery', 'public');
                    }
                }
            }
            $data['gallery_images'] = array_values(array_filter($gallery));
        }

        DB::transaction(function () use ($package, $data, $request, $sectionMeta) {
            $package->update($data);

            if (!empty($sectionMeta['city_slug'])) {
                $city = City::where('slug', $sectionMeta['city_slug'])->first();
                if ($city) {
                    $package->cities()->syncWithoutDetaching([$city->id]);
                }
            }

            $this->syncInclusionsAndExclusions($package, $request);
            $this->syncAddons($package, $request);
        });

        return redirect()->route('admin.shore-excursions.index')
            ->with('success', admin_t('Shore excursion updated successfully.'));
    }

    public function destroy(Package $package): RedirectResponse
    {
        if ($package->package_type !== 'shore_excursion') {
            abort(404);
        }

        if ($package->bookings()->exists()) {
            return back()->with('error', admin_t('Cannot delete this excursion because it has active bookings. You can deactivate it instead.'));
        }

        $package->delete();

        return redirect()->route('admin.shore-excursions.index')
            ->with('success', admin_t('Shore excursion deleted successfully.'));
    }

    public function toggleStatus(Package $package): JsonResponse
    {
        $package->update(['is_active' => !$package->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $package->is_active,
            'message' => $package->is_active ? admin_t('Activated successfully') : admin_t('Deactivated successfully'),
        ]);
    }

    public function toggleFeatured(Package $package): JsonResponse
    {
        $package->update(['is_featured' => !$package->is_featured]);

        return response()->json([
            'success' => true,
            'is_featured' => $package->is_featured,
        ]);
    }

    public function duplicate(Package $package): RedirectResponse
    {
        $copy = $package->replicate([
            'slug',
            'bookings_count',
            'reviews_count',
            'rating_avg',
            'created_at',
            'updated_at'
        ]);

        $copy->slug = Str::slug($package->slug . '-copy-' . uniqid());
        $title = (array) $package->title;
        $title['en'] = ($title['en'] ?? '') . ' (Copy)';
        $title['ar'] = ($title['ar'] ?? '') . ' (نسخة)';
        $copy->title = $title;
        $copy->is_active = false;
        $copy->save();

        foreach ($package->inclusions as $inc) {
            $copy->inclusions()->create($inc->only(['type', 'item_type', 'title', 'content', 'sort_order']));
        }

        foreach ($package->addons as $addon) {
            $copy->addons()->create($addon->only(['title', 'description', 'price', 'currency_id', 'price_unit', 'is_active', 'sort_order']));
        }

        return redirect()->route('admin.shore-excursions.edit', $copy)
            ->with('success', admin_t('Shore excursion duplicated successfully. You can now edit its details.'));
    }

    public function bookings(): RedirectResponse
    {
        return redirect()->route('admin.bookings.index', ['package_type' => 'shore_excursion']);
    }

    public function packageBookings(Package $package): RedirectResponse
    {
        return redirect()->route('admin.bookings.index', ['package_id' => $package->id]);
    }

    private function buildGroupPricingTiers(Request $request): array
    {
        $p1 = (float) $request->input('price_1_person');
        $p2 = (float) ($request->input('price_2_persons') ?: round($p1 * 0.8));
        $p4 = (float) ($request->input('price_4_persons') ?: round($p2 * 0.85));
        $p6 = (float) ($request->input('price_6_plus_persons') ?: round($p4 * 0.85));

        return [
            [
                'title' => 'Solo Traveler',
                'persons_count' => 1,
                'price_per_person' => $p1,
                'persons_label' => '1 Person',
                'min' => 1,
                'max' => 1,
            ],
            [
                'title' => 'Small Group',
                'persons_count' => 2,
                'price_per_person' => $p2,
                'persons_label' => '2-3 Persons',
                'min' => 2,
                'max' => 3,
            ],
            [
                'title' => 'Medium Group',
                'persons_count' => 4,
                'price_per_person' => $p4,
                'persons_label' => '4-5 Persons',
                'min' => 4,
                'max' => 5,
            ],
            [
                'title' => 'Large Group',
                'persons_count' => 6,
                'price_per_person' => $p6,
                'persons_label' => '6+ Persons',
                'min' => 6,
                'max' => null,
            ],
        ];
    }

    private function syncInclusionsAndExclusions(Package $package, Request $request): void
    {
        $package->inclusions()->delete();

        $inclusions = (array) $request->input('inclusions', []);
        foreach ($inclusions as $index => $item) {
            $title = trim(is_array($item) ? ($item['title'] ?? '') : (string) $item);
            if ($title !== '') {
                $package->inclusions()->create([
                    'type' => 'included',
                    'item_type' => 'included',
                    'title' => $title,
                    'content' => $title,
                    'sort_order' => $index,
                ]);
            }
        }

        $exclusions = (array) $request->input('exclusions', []);
        foreach ($exclusions as $index => $item) {
            $title = trim(is_array($item) ? ($item['title'] ?? '') : (string) $item);
            if ($title !== '') {
                $package->inclusions()->create([
                    'type' => 'excluded',
                    'item_type' => 'excluded',
                    'title' => $title,
                    'content' => $title,
                    'sort_order' => $index,
                ]);
            }
        }
    }

    private function syncAddons(Package $package, Request $request): void
    {
        $package->addons()->delete();

        $addons = (array) $request->input('addons', []);
        foreach ($addons as $index => $addon) {
            $title = trim((string) ($addon['title'] ?? ''));
            $price = (float) ($addon['price'] ?? 0);
            if ($title !== '' && $price > 0) {
                $package->addons()->create([
                    'title' => $title,
                    'description' => trim((string) ($addon['description'] ?? '')),
                    'price' => $price,
                    'price_unit' => $addon['price_unit'] ?? 'per_person',
                    'is_active' => true,
                    'sort_order' => $index,
                ]);
            }
        }
    }
}
