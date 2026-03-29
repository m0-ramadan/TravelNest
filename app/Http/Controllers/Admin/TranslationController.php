<?php

namespace App\Http\Controllers\Admin;

use App\Models\Translation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TranslationController extends Controller
{
    public function index(Request $request): View
    {
        $translations = Translation::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = '%' . $request->string('q') . '%';
                $query->where('locale', 'like', $search)
                    ->orWhere('field', 'like', $search)
                    ->orWhere('value', 'like', $search)
                    ->orWhere('translatable_type', 'like', $search);
            })
            ->latest()
            ->paginate($this->perPage($request));

        return $this->view('admin.translations.index', compact('translations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'translatable_type' => ['required', 'string', 'max:255'],
            'translatable_id' => ['required', 'integer'],
            'locale' => ['required', 'string', 'max:10'],
            'field' => ['required', 'string', 'max:100'],
            'value' => ['nullable', 'string'],
        ]);

        Translation::create($data);

        return back()->with('success', 'Translation created.');
    }

    public function update(Request $request, Translation $translation): RedirectResponse
    {
        $data = $request->validate([
            'locale' => ['required', 'string', 'max:10'],
            'field' => ['required', 'string', 'max:100'],
            'value' => ['nullable', 'string'],
        ]);

        $translation->update($data);

        return back()->with('success', 'Translation updated.');
    }

    public function destroy(Translation $translation): RedirectResponse
    {
        $translation->delete();

        return back()->with('success', 'Translation deleted.');
    }

    public function byModel(string $type, int $id): View
    {
        $translations = Translation::query()
            ->where('translatable_type', $type)
            ->where('translatable_id', $id)
            ->latest()
            ->paginate(50);

        return $this->view('admin.translations.by-model', compact('translations', 'type', 'id'));
    }

    public function bulkUpdate(Request $request): RedirectResponse
    {
        foreach ((array) $request->input('translations', []) as $row) {
            if (! empty($row['id'])) {
                Translation::where('id', $row['id'])->update([
                    'locale' => $row['locale'] ?? null,
                    'field' => $row['field'] ?? null,
                    'value' => $row['value'] ?? null,
                ]);
            }
        }

        return back()->with('success', 'Translations updated.');
    }
}
