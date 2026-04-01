<?php

namespace App\Http\Controllers\Admin;

use App\Models\Currency;
use App\Models\Destination;
use App\Models\Package;
use App\Models\PackageCategory;
use App\Services\PackageAiService;
use App\Traits\HandlesTranslatedFields;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PackageController extends Controller
{
    use HandlesTranslatedFields;

    public function index(Request $request): View
    {
        $packages = Package::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $this->applyTranslatedSearch($query, ['title', 'subtitle', 'short_description', 'description'], $request->string('q'));
            })
            ->latest()
            ->paginate($this->perPage($request));

        return $this->view('admin.packages.index', ['packages' => $packages]);
    }

    public function create(): View
    {
        $categories = PackageCategory::all();
        $destinations = Destination::all();
        $currencies = Currency::all();
        return $this->view('admin.packages.create', ['categories' => $categories, 'destinations' => $destinations, 'currencies' => $currencies]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'integer'],
            'primary_country_id' => ['nullable', 'integer'],
            'package_type' => ['nullable', 'string'],
            'slug' => ['nullable', 'string'],
            'title' => ['nullable', 'string'],
            'subtitle' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'duration_days' => ['nullable', 'integer'],
            'duration_nights' => ['nullable', 'integer'],
            'start_from_price' => ['nullable', 'numeric'],
            'compare_price' => ['nullable', 'numeric'],
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
            'is_featured' => ['nullable', 'boolean'],
            'is_best_seller' => ['nullable', 'boolean'],
            'is_ultra_luxury' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'min_participants' => ['nullable', 'integer'],
            'max_participants' => ['nullable', 'integer'],
            'booking_lead_days' => ['nullable', 'integer'],
            'cancellation_policy' => ['nullable', 'string'],
            'terms_conditions' => ['nullable', 'string'],
            'video_url' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer'],
            'seo_title' => ['nullable', 'string'],
            'seo_description' => ['nullable', 'string'],
            'breadcrumb_title' => ['nullable', 'string'],
            'canonical_url' => ['nullable', 'string'],
        ]);

        $data = $this->translateModelFields($data, [
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
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']['en'] ?? 'package-' . time());
        Package::create($data);

        return $this->success('admin.packages.index', 'Package created.');
    }

    public function show(Package $package): View
    {
        return $this->view('admin.packages.show', compact('package'));
    }

    public function edit(Package $package): View
    {
        return $this->view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'integer'],
            'primary_country_id' => ['nullable', 'integer'],
            'package_type' => ['nullable', 'string'],
            'slug' => ['nullable', 'string'],
            'title' => ['nullable', 'string'],
            'subtitle' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'duration_days' => ['nullable', 'integer'],
            'duration_nights' => ['nullable', 'integer'],
            'start_from_price' => ['nullable', 'numeric'],
            'compare_price' => ['nullable', 'numeric'],
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
            'is_featured' => ['nullable', 'boolean'],
            'is_best_seller' => ['nullable', 'boolean'],
            'is_ultra_luxury' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'min_participants' => ['nullable', 'integer'],
            'max_participants' => ['nullable', 'integer'],
            'booking_lead_days' => ['nullable', 'integer'],
            'cancellation_policy' => ['nullable', 'string'],
            'terms_conditions' => ['nullable', 'string'],
            'video_url' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer'],
            'seo_title' => ['nullable', 'string'],
            'seo_description' => ['nullable', 'string'],
            'breadcrumb_title' => ['nullable', 'string'],
            'canonical_url' => ['nullable', 'string'],
        ]);

        $data = $this->translateModelFields($data, [
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
        ]);

        $package->update($data);

        return $this->success('admin.packages.index', 'Package updated.');
    }

    public function destroy(Package $package): RedirectResponse
    {
        $package->delete();

        return $this->success('admin.packages.index', 'Package deleted.');
    }

    public function statistics()
    {
        return response()->json([
            'total' => Package::count(),
            'active' => Package::where('is_active', true)->count(),
            'featured' => Package::where('is_featured', true)->count(),
        ]);
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $ids = (array) $request->input('ids', []);
        $action = (string) $request->input('action');

        if ($action === 'delete') {
            Package::whereIn('id', $ids)->delete();
        } elseif ($action === 'activate') {
            Package::whereIn('id', $ids)->update(['is_active' => true]);
        } elseif ($action === 'deactivate') {
            Package::whereIn('id', $ids)->update(['is_active' => false]);
        }

        return back()->with('success', 'Bulk action applied.');
    }

    public function toggleStatus(Package $package): RedirectResponse
    {
        $package->update(['is_active' => !(bool) $package->is_active]);

        return back()->with('success', 'Package status updated.');
    }

    public function toggleFeatured(Package $package): RedirectResponse
    {
        $package->update(['is_featured' => !(bool) $package->is_featured]);

        return back()->with('success', 'Package featured updated.');
    }

    public function duplicate(Package $package): RedirectResponse
    {
        $copy = $package->replicate();
        $copy->slug = $package->slug . '-' . now()->timestamp;
        $copy->title = [
            'en' => $package->display_title . ' (Copy)',
            'ar' => $package->display_title . ' (Copy)',
        ];
        $copy->save();

        return redirect()->route('admin.packages.edit', $copy)->with('success', 'Package duplicated.');
    }

    public function createWithAI()
    {
        $destinations = Destination::all();
        $categories = PackageCategory::all();
        return $this->view('admin.packages.create-with-ai', compact('destinations', 'categories'));
    }

    public function storeWithAI(
        Request $request,
        PackageAiService $packageAiService
    ): RedirectResponse {
        $data = $request->validate([
            'prompt' => ['required', 'string'],
            'duration_days' => ['nullable', 'integer'],
            'destination_id' => ['nullable', 'integer', 'exists:destinations,id'],
            'category_id' => ['nullable', 'integer', 'exists:package_categories,id'],
        ]);

        $destination = !empty($data['destination_id'])
            ? Destination::find($data['destination_id'])
            : null;

        $category = !empty($data['category_id'])
            ? PackageCategory::find($data['category_id'])
            : null;

        $aiData = $packageAiService->generate([
            'prompt' => $data['prompt'],
            'duration_days' => $data['duration_days'] ?? null,
            'destination_name' => $this->adminTrans($destination?->name),
            'category_name' => $this->adminTrans($category?->name),
        ]);

        if (!$aiData || !is_array($aiData)) {
            return back()
                ->withInput()
                ->with('error', 'فشل توليد البيانات بالذكاء الاصطناعي');
        }

        $finalData = array_merge($data, $aiData);

        unset(
            $finalData['prompt'],
            $finalData['destination_id']
        );

        $finalData['category_id'] = $data['category_id'] ?? null;

        if (!empty($destination?->country_id)) {
            $finalData['primary_country_id'] = $destination->country_id;
        }

        $translatableFields = [
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

        foreach ($translatableFields as $field) {
            if (!array_key_exists($field, $finalData)) {
                continue;
            }

            if (is_array($finalData[$field])) {
                $finalData[$field] = array_filter(
                    $finalData[$field],
                    fn($value) => !is_null($value) && $value !== ''
                );

                if (empty($finalData[$field])) {
                    $finalData[$field] = [
                        'en' => '',
                        'ar' => '',
                    ];
                }
            } elseif (is_string($finalData[$field]) && trim($finalData[$field]) !== '') {
                $finalData[$field] = $this->translateModelFields(
                    [$field => $finalData[$field]],
                    [$field]
                )[$field];
            } else {
                $finalData[$field] = [
                    'en' => '',
                    'ar' => '',
                ];
            }
        }

        $finalData['duration_days'] = $finalData['duration_days'] ?? ($data['duration_days'] ?? null);
        $finalData['duration_nights'] = $finalData['duration_nights'] ?? (
            !empty($finalData['duration_days']) && (int) $finalData['duration_days'] > 0
            ? (int) $finalData['duration_days'] - 1
            : null
        );

        $allowedTourTypes = ['private', 'group', 'shared', 'custom'];
        $allowedDifficultyLevels = ['easy', 'moderate', 'hard'];
        $allowedBookingModes = ['request', 'instant'];
        $allowedPackageTypes = ['travel_package', 'nile_cruise', 'day_tour', 'shore_excursion', 'tailor_made'];

        $finalData['tour_type'] = in_array(($finalData['tour_type'] ?? null), $allowedTourTypes, true)
            ? $finalData['tour_type']
            : 'private';

        $finalData['difficulty_level'] = in_array(($finalData['difficulty_level'] ?? null), $allowedDifficultyLevels, true)
            ? $finalData['difficulty_level']
            : 'easy';

        $finalData['booking_mode'] = in_array(($finalData['booking_mode'] ?? null), $allowedBookingModes, true)
            ? $finalData['booking_mode']
            : 'request';

        $finalData['package_type'] = in_array(($finalData['package_type'] ?? null), $allowedPackageTypes, true)
            ? $finalData['package_type']
            : 'travel_package';

        $finalData['is_active'] = true;
        $finalData['is_featured'] = false;
        $finalData['is_best_seller'] = false;
        $finalData['is_ultra_luxury'] = false;
        $finalData['rating_avg'] = $finalData['rating_avg'] ?? 0;
        $finalData['reviews_count'] = $finalData['reviews_count'] ?? 0;
        $finalData['sort_order'] = $finalData['sort_order'] ?? 0;

        $finalData['slug'] = !empty($finalData['slug'])
            ? $finalData['slug']
            : Str::slug(
                $finalData['title']['en']
                    ?? $finalData['title']['ar']
                    ?? 'package-' . time()
            );

        Package::create($finalData);

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'تم إنشاء الباقة بالذكاء الاصطناعي');
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
    function adminTrans($value, array $preferred = ['ar', 'en'])
    {
        if (! is_array($value)) {
            return (string) ($value ?? '');
        }

        foreach ($preferred as $lang) {
            if (! empty($value[$lang])) {
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
