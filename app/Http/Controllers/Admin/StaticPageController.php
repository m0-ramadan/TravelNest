<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaticPageController extends Controller
{
    public function index(Request $request): View
    {
        $static_pages = Page::query()
            ->when($request->filled('q'), fn($q) => $q->where('title', 'like', '%' . $request->string('q') . '%'))
            ->latest()
            ->paginate($this->perPage($request));

        return $this->view('admin.static-pages.index', ['pages' => $static_pages]);
    }

    public function create(): View
    {
        return $this->view('admin.static-pages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'slug' => ['nullable', 'string'],
            'title' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'template' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string'],
            'is_home' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'seo_title' => ['nullable', 'string'],
            'seo_description' => ['nullable', 'string'],
        ]);

        Page::create($data);

        return $this->success('admin.static-pages.index', 'Page created.');
    }

    public function show(Page $Page): View
    {
        return $this->view('admin.static-pages.show', compact('Page'));
    }

    public function edit(Page $Page): View
    {
        return $this->view('admin.static-pages.edit', compact('Page'));
    }

    public function update(Request $request, Page $Page): RedirectResponse
    {
        $data = $request->validate([
            'slug' => ['nullable', 'string'],
            'title' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'template' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string'],
            'is_home' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'seo_title' => ['nullable', 'string'],
            'seo_description' => ['nullable', 'string'],
        ]);

        $Page->update($data);

        return $this->success('admin.static-pages.index', 'Page updated.');
    }

    public function destroy(Page $Page): RedirectResponse
    {
        $Page->delete();

        return $this->success('admin.static-pages.index', 'Page deleted.');
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        Page::whereIn('id', (array) $request->input('ids', []))->delete();
        return back()->with('success', 'Bulk action applied.');
    }

    public function editWithAI(Page $page): View
    {
        return $this->view('admin.static-pages.edit-with-ai', compact('page'));
    }

    public function enhanceTitleWithAI(Request $request)
    {
        return response()->json(['title' => $request->input('title')]);
    }
    public function translateContentWithAI(Request $request)
    {
        return response()->json(['content' => $request->input('content')]);
    }
    public function translateWithAI(Request $request)
    {
        return response()->json(['message' => 'Translation endpoint ready.']);
    }
    public function enhanceContentWithAI(Request $request)
    {
        return response()->json(['content' => $request->input('content')]);
    }
    public function expandContentWithAI(Request $request)
    {
        return response()->json(['content' => $request->input('content')]);
    }
    public function simplifyContentWithAI(Request $request)
    {
        return response()->json(['content' => $request->input('content')]);
    }
    public function loadTemplateWithAI(Request $request)
    {
        return response()->json(['template' => $request->input('template')]);
    }
    public function generateFromPromptWithAI(Request $request)
    {
        return response()->json(['content' => $request->input('prompt')]);
    }
    public function generatePageWithAI(Request $request)
    {
        return response()->json(['message' => 'Generator endpoint ready.']);
    }
    public function generateTitleWithAI(Request $request)
    {
        return response()->json(['title' => $request->input('topic', 'Generated Title')]);
    }
    public function generateContentWithAI(Request $request)
    {
        return response()->json(['content' => 'Generated content']);
    }
    public function formatContentWithAI(Request $request)
    {
        return response()->json(['content' => $request->input('content')]);
    }
    public function checkGrammarWithAI(Request $request)
    {
        return response()->json(['content' => $request->input('content')]);
    }
    public function enhanceTextWithAI(Request $request)
    {
        return response()->json(['content' => $request->input('content')]);
    }
    public function addSectionWithAI(Request $request)
    {
        return response()->json(['section' => $request->input('section')]);
    }
    public function generateMetaTitleWithAI(Request $request)
    {
        return response()->json(['meta_title' => $request->input('title')]);
    }
    public function generateMetaDescriptionWithAI(Request $request)
    {
        return response()->json(['meta_description' => $request->input('content')]);
    }
    public function generateKeywordsWithAI(Request $request)
    {
        return response()->json(['keywords' => []]);
    }
}
