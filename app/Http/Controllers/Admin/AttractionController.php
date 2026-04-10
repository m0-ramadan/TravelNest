<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attraction;
use App\Models\Destination;
use App\Traits\HandlesTranslatedFields;
use App\Traits\UploadFileTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AttractionController extends Controller
{
    use HandlesTranslatedFields, UploadFileTrait;

    public function index(Request $request): View
    {
        $attractions = Attraction::query()
            ->with('destination')
            ->when($request->filled('q'), function ($query) use ($request) {
                $this->applyTranslatedSearch(
                    $query,
                    ['name', 'short_description', 'description', 'seo_title', 'seo_description'],
                    $request->string('q')
                );
            })
            ->when($request->filled('destination_id'), function ($query) use ($request) {
                $query->where('destination_id', $request->destination_id);
            })
            ->latest()
            ->paginate($this->perPage($request));

        $destinations = Destination::where('is_active', true)->get();

        return $this->view('admin.attractions.index', compact('attractions', 'destinations'));
    }

    public function create(): View
    {
        $destinations = Destination::where('is_active', true)->get();

        return $this->view('admin.attractions.create', compact('destinations'));
    }

    public function show(Attraction $attraction): View
    {
        $attraction->load('destination');

        return $this->view('admin.attractions.show', compact('attraction'));
    }

    public function edit(Attraction $attraction): View
    {
        $destinations = Destination::where('is_active', true)->get();

        return $this->view('admin.attractions.edit', compact('attraction', 'destinations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'destination_id' => ['nullable', 'exists:destinations,id'],
            'slug' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'seo_title' => ['nullable', 'string'],
            'seo_description' => ['nullable', 'string'],
        ]);

        $data = $this->translateModelFields($data, [
            'name',
            'short_description',
            'description',
            'seo_title',
            'seo_description',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage('attractions', $request->file('image'));
        }

        if (empty($data['slug']) && !empty($data['name'])) {
            $slugSource = is_array($data['name'])
                ? ($data['name']['en'] ?? $data['name']['ar'] ?? reset($data['name']))
                : $data['name'];

            $data['slug'] = Str::slug($slugSource ?: 'attraction-' . time());
        }

        $data['is_active'] = $request->boolean('is_active');

        Attraction::create($data);

        return $this->success('admin.attractions.index', 'Attraction created.');
    }

    public function update(Request $request, Attraction $attraction): RedirectResponse
    {
        $data = $request->validate([
            'destination_id' => ['nullable', 'exists:destinations,id'],
            'slug' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'seo_title' => ['nullable', 'string'],
            'seo_description' => ['nullable', 'string'],
        ]);

        $data = $this->translateModelFields($data, [
            'name',
            'short_description',
            'description',
            'seo_title',
            'seo_description',
        ]);

        if ($request->hasFile('image')) {
            if ($attraction->image && Storage::disk('public')->exists($attraction->image)) {
                Storage::disk('public')->delete($attraction->image);
            }

            $data['image'] = $this->uploadImage('attractions', $request->file('image'));
        }

        if (empty($data['slug']) && !empty($data['name'])) {
            $slugSource = is_array($data['name'])
                ? ($data['name']['en'] ?? $data['name']['ar'] ?? reset($data['name']))
                : $data['name'];

            $data['slug'] = Str::slug($slugSource ?: 'attraction-' . $attraction->id);
        }

        $data['is_active'] = $request->boolean('is_active');

        $attraction->update($data);

        return $this->success('admin.attractions.index', 'Attraction updated.');
    }

    public function destroy(Attraction $attraction): RedirectResponse
    {
        if ($attraction->image && Storage::disk('public')->exists($attraction->image)) {
            Storage::disk('public')->delete($attraction->image);
        }

        $attraction->delete();

        return $this->success('admin.attractions.index', 'Attraction deleted.');
    }

    public function statistics(): JsonResponse
    {
        return response()->json([
            'total' => Attraction::count(),
            'active' => Attraction::where('is_active', true)->count(),
            'inactive' => Attraction::where('is_active', false)->count(),
        ]);
    }

    public function bulkActions(Request $request): RedirectResponse
    {
        $ids = (array) $request->input('ids', []);
        $action = $request->input('action');

        if ($action === 'delete') {
            $items = Attraction::whereIn('id', $ids)->get();

            foreach ($items as $item) {
                if ($item->image && Storage::disk('public')->exists($item->image)) {
                    Storage::disk('public')->delete($item->image);
                }
                $item->delete();
            }
        }

        return back()->with('success', 'Bulk action applied.');
    }

    public function toggleStatus(Attraction $attraction): RedirectResponse
    {
        $attraction->update([
            'is_active' => ! (bool) $attraction->is_active,
        ]);

        return back()->with('success', 'Attraction status updated.');
    }
}
