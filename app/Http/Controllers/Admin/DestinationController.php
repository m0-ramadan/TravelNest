<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Country;
use App\Models\Destination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(Request $request): View
    {
        $destinations = Destination::query()
            ->when($request->filled('q'), fn($q) => $q->where('name', 'like', '%' . $request->string('q') . '%'))
            ->latest()
            ->paginate($this->perPage($request));

        $countries = Country::where('is_active', true)->get();
        $cities = City::all();

        return $this->view('admin.destinations.index', ['destinations' => $destinations, 'countries' => $countries, 'cities' => $cities]);
    }

    public function create(): View
    {
        $countries = Country::where('is_active', true)->get();
        $cities = City::all();

        return $this->view('admin.destinations.create', compact('countries', 'cities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'parent_id' => ['nullable', 'integer'],
            'country_id' => ['nullable', 'integer'],
            'city_id' => ['nullable', 'integer'],
            'type' => ['nullable', 'string'],
            'slug' => ['nullable', 'string'],
            'name' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'hero_image' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'seo_title' => ['nullable', 'string'],
            'seo_description' => ['nullable', 'string'],
        ]);

        Destination::create($data);

        return $this->success('admin.destinations.index', 'Destination created.');
    }

    public function show(Destination $destination): View
    {
        return $this->view('admin.destinations.show', compact('destination'));
    }

    public function edit(Destination $destination): View
    {
        $cities = City::all();
        $countries = Country::where('is_active', true)->get();
        return $this->view('admin.destinations.edit', compact('destination', 'countries', 'cities'));
    }

    public function update(Request $request, Destination $destination): RedirectResponse
    {
        $data = $request->validate([
            'parent_id' => ['nullable', 'integer'],
            'country_id' => ['nullable', 'integer'],
            'city_id' => ['nullable', 'integer'],
            'type' => ['nullable', 'string'],
            'slug' => ['nullable', 'string'],
            'name' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'hero_image' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'seo_title' => ['nullable', 'string'],
            'seo_description' => ['nullable', 'string'],
        ]);

        $destination->update($data);

        return $this->success('admin.destinations.index', 'Destination updated.');
    }

    public function destroy(Destination $destination): RedirectResponse
    {
        $destination->delete();

        return $this->success('admin.destinations.index', 'Destination deleted.');
    }

    public function statistics()
    {
        return response()->json([
            'total' => Destination::count(),
            'active' => Destination::where('is_active', true)->count(),
            'featured' => Destination::where('is_featured', true)->count(),
        ]);
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $ids = (array) $request->input('ids', []);
        $action = (string) $request->input('action');

        if ($action === 'delete') {
            Destination::whereIn('id', $ids)->delete();
        } elseif ($action === 'activate') {
            Destination::whereIn('id', $ids)->update(['is_active' => true]);
        } elseif ($action === 'deactivate') {
            Destination::whereIn('id', $ids)->update(['is_active' => false]);
        }

        return back()->with('success', 'Bulk action applied.');
    }

    public function toggleStatus(Destination $destination): RedirectResponse
    {
        $destination->update(['is_active' => ! (bool) $destination->is_active]);

        return back()->with('success', 'Destination status updated.');
    }

    public function toggleFeatured(Destination $destination): RedirectResponse
    {
        $destination->update(['is_featured' => ! (bool) $destination->is_featured]);

        return back()->with('success', 'Destination featured updated.');
    }

    public function duplicate(Destination $destination): RedirectResponse
    {
        $copy = $destination->replicate();
        $copy->slug = $destination->slug . '-' . now()->timestamp;
        $copy->name = $destination->name . ' (Copy)';
        $copy->save();

        return redirect()->route('admin.destinations.edit', $copy)->with('success', 'Destination duplicated.');
    }
}
