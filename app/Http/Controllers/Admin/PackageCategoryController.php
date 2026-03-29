<?php

namespace App\Http\Controllers\Admin;

use App\Models\PackageCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackageCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $package_categories = PackageCategory::query()
            ->when($request->filled('q'), fn($q) => $q->where('name', 'like', '%' . $request->string('q') . '%'))
            ->latest()
            ->paginate($this->perPage($request));

        return $this->view('admin.package-categories.index', ['categories' => $package_categories]);
    }

    public function create(): View
    {
        return $this->view('admin.package-categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'parent_id' => ['nullable', 'integer'],
            'country_id' => ['nullable', 'integer'],
            'slug' => ['nullable', 'string'],
            'name' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'category_type' => ['nullable', 'string'],
            'icon' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'min_days' => ['nullable', 'integer'],
            'max_days' => ['nullable', 'integer'],
            'price_from' => ['nullable', 'numeric'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'seo_title' => ['nullable', 'string'],
            'seo_description' => ['nullable', 'string'],
        ]);

        PackageCategory::create($data);

        return $this->success('admin.package-categories.index', 'PackageCategory created.');
    }

    public function show(PackageCategory $packageCategory): View
    {
        return $this->view('admin.package-categories.show', compact('packageCategory'));
    }

    public function edit(PackageCategory $packageCategory): View
    {
        return $this->view('admin.package-categories.edit', compact('packageCategory'));
    }

    public function update(Request $request, PackageCategory $packageCategory): RedirectResponse
    {
        $data = $request->validate([
            'parent_id' => ['nullable', 'integer'],
            'country_id' => ['nullable', 'integer'],
            'slug' => ['nullable', 'string'],
            'name' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'category_type' => ['nullable', 'string'],
            'icon' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'min_days' => ['nullable', 'integer'],
            'max_days' => ['nullable', 'integer'],
            'price_from' => ['nullable', 'numeric'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'seo_title' => ['nullable', 'string'],
            'seo_description' => ['nullable', 'string'],
        ]);

        $packageCategory->update($data);

        return $this->success('admin.package-categories.index', 'PackageCategory updated.');
    }

    public function destroy(PackageCategory $packageCategory): RedirectResponse
    {
        $packageCategory->delete();

        return $this->success('admin.package-categories.index', 'PackageCategory deleted.');
    }
}
