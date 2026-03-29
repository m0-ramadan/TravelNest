<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(Request $request): View
    {
        $faqs = Faq::query()
            ->when($request->filled('q'), fn ($q) => $q->where('id', 'like', '%' . $request->string('q') . '%'))
            ->latest()
            ->paginate($this->perPage($request));

        return $this->view('admin.faqs.index', ['faqs' => $faqs]);
    }

    public function create(): View
    {
        return $this->view('admin.faqs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'integer'],
            'question' => ['nullable', 'string'],
            'answer' => ['nullable', 'string'],
            'context_type' => ['nullable', 'string'],
            'context_id' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        Faq::create($data);

        return $this->success('admin.faqs.index', 'Faq created.');
    }

    public function show(Faq $faq): View
    {
        return $this->view('admin.faqs.show', compact('faq'));
    }

    public function edit(Faq $faq): View
    {
        return $this->view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'integer'],
            'question' => ['nullable', 'string'],
            'answer' => ['nullable', 'string'],
            'context_type' => ['nullable', 'string'],
            'context_id' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $faq->update($data);

        return $this->success('admin.faqs.index', 'Faq updated.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return $this->success('admin.faqs.index', 'Faq deleted.');
    }

}
