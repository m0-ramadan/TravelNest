<?php

namespace App\Http\Controllers\Admin;

use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LanguageController extends Controller
{
    public function index(Request $request): View
    {
        $languages = Language::query()
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->string('q') . '%'))
            ->latest()
            ->paginate($this->perPage($request));

        return $this->view('admin.languages.index', ['languages' => $languages]);
    }

    public function create(): View
    {
        return $this->view('admin.languages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['nullable', 'string'],
            'name' => ['nullable', 'string'],
            'native_name' => ['nullable', 'string'],
            'is_default' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        Language::create($data);

        return $this->success('admin.languages.index', 'Language created.');
    }

    public function show(Language $language): View
    {
        return $this->view('admin.languages.show', compact('language'));
    }

    public function edit(Language $language): View
    {
        return $this->view('admin.languages.edit', compact('language'));
    }

    public function update(Request $request, Language $language): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['nullable', 'string'],
            'name' => ['nullable', 'string'],
            'native_name' => ['nullable', 'string'],
            'is_default' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $language->update($data);

        return $this->success('admin.languages.index', 'Language updated.');
    }

    public function destroy(Language $language): RedirectResponse
    {
        $language->delete();

        return $this->success('admin.languages.index', 'Language deleted.');
    }

    public function toggle(Language $language): RedirectResponse
    {
        $language->update(['is_active' => ! (bool) $language->is_active]);
        return back()->with('success', 'Language status updated.');
    }

    public function setDefault(Language $language): RedirectResponse
    {
        Language::query()->update(['is_default' => false]);
        $language->update(['is_default' => true, 'is_active' => true]);
        return back()->with('success', 'Default language changed.');
    }

    public function toggleAll(Request $request): RedirectResponse
    {
        Language::query()->update(['is_active' => (bool) $request->boolean('status', true)]);
        return back()->with('success', 'All languages updated.');
    }

}
