<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(Request $request): View
    {
        $packages = Package::query()
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%' . $request->string('q') . '%'))
            ->latest()
            ->paginate($this->perPage($request));

        return $this->view('admin.packages.index', ['packages' => $packages]);
    }

    public function create(): View
    {
        return $this->view('admin.packages.create');
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
        $package->update(['is_active' => ! (bool) $package->is_active]);
        return back()->with('success', 'Package status updated.');
    }

    public function toggleFeatured(Package $package): RedirectResponse
    {
        $package->update(['is_featured' => ! (bool) $package->is_featured]);
        return back()->with('success', 'Package featured updated.');
    }

    public function duplicate(Package $package): RedirectResponse
    {
        $copy = $package->replicate();
        $copy->slug = $package->slug . '-' . now()->timestamp;
        $copy->title = $package->title . ' (Copy)';
        $copy->save();

        return redirect()->route('admin.packages.edit', $copy)->with('success', 'Package duplicated.');
    }

    public function createWithAI(): View
    {
        return $this->view('admin.packages.create-with-ai');
    }

    public function storeWithAI(Request $request): RedirectResponse
    {
        return $this->store($request);
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

}
