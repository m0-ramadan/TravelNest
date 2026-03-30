<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Models\PackageCategory;
use App\Traits\HandlesTranslatedFields;
use App\Traits\UploadFileTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackageCategoryController extends Controller
{
    use UploadFileTrait, HandlesTranslatedFields;

    public function index(Request $request): View
    {
        $packageCategories = PackageCategory::with(['country', 'parent'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $this->applyTranslatedSearch($query, ['name', 'description', 'seo_title', 'seo_description'], $request->string('q'));
            })
            ->latest()
            ->paginate($this->perPage($request))
            ->withQueryString();

        return $this->view('admin.package-categories.index', [
            'categories' => $packageCategories,
        ]);
    }

    public function create(): View
    {
        $countries = Country::where('is_active', true)->get();
        $parents = PackageCategory::where('is_active', true)->get();

        return $this->view('admin.package-categories.create', compact('countries', 'parents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'parent_id' => ['nullable', 'integer', 'exists:package_categories,id'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'slug' => ['required', 'string', 'max:255', 'unique:package_categories,slug'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_type' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'min_days' => ['nullable', 'integer', 'min:0'],
            'max_days' => ['nullable', 'integer', 'min:0'],
            'price_from' => ['nullable', 'numeric'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
        ]);

        $data = $this->translateModelFields($data, [
            'name',
            'description',
            'category_type',
            'seo_title',
            'seo_description',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage('package-categories', $request->file('image'));
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        PackageCategory::create($data);

        return $this->success('admin.package-categories.index', 'PackageCategory created.');
    }

    public function show($category): View
    {
        $category = PackageCategory::with(['country', 'parent'])->findOrFail($category);
        return $this->view('admin.package-categories.show', compact('category'));
    }

    public function edit($packageCategory): View
    {
        $category = PackageCategory::findOrFail($packageCategory);
        $countries = Country::where('is_active', true)->get();
        $parents = PackageCategory::where('id', '!=', $category->id)
            ->where('is_active', true)
            ->get();

        return $this->view('admin.package-categories.edit', compact('category', 'countries', 'parents'));
    }

    public function update(Request $request, PackageCategory $packageCategory): RedirectResponse
    {
        $data = $request->validate([
            'parent_id' => ['nullable', 'integer', 'exists:package_categories,id'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'slug' => ['required', 'string', 'max:255', 'unique:package_categories,slug,' . $packageCategory->id],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_type' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'min_days' => ['nullable', 'integer', 'min:0'],
            'max_days' => ['nullable', 'integer', 'min:0'],
            'price_from' => ['nullable', 'numeric'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
        ]);

        $data = $this->translateModelFields($data, [
            'name',
            'description',
            'category_type',
            'seo_title',
            'seo_description',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage('package-categories', $request->file('image'));
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $packageCategory->update($data);

        return $this->success('admin.package-categories.index', 'PackageCategory updated.');
    }

    public function destroy(PackageCategory $packageCategory): RedirectResponse
    {
        $packageCategory->delete();

        return $this->success('admin.package-categories.index', 'PackageCategory deleted.');
    }
}
