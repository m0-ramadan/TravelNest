<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $articles = Article::query()
            ->when($request->filled('q'), fn($q) => $q->where('title', 'like', '%' . $request->string('q') . '%'))
            ->latest()
            ->paginate($this->perPage($request));

        return $this->view('admin.articles.index', ['articles' => $articles]);
    }

    public function create(): View
    {
        return $this->view('admin.articles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'integer'],
            'slug' => ['nullable', 'string'],
            'title' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string'],
            'post_type' => ['nullable', 'string'],
            'author_id' => ['nullable', 'integer'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'seo_title' => ['nullable', 'string'],
            'seo_description' => ['nullable', 'string'],
        ]);

        Article::create($data);

        return $this->success('admin.articles.index', 'Article created.');
    }

    public function show(Article $article): View
    {
        return $this->view('admin.articles.show', compact('article'));
    }

    public function edit(Article $article): View
    {
        return $this->view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'integer'],
            'slug' => ['nullable', 'string'],
            'title' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string'],
            'post_type' => ['nullable', 'string'],
            'author_id' => ['nullable', 'integer'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'seo_title' => ['nullable', 'string'],
            'seo_description' => ['nullable', 'string'],
        ]);

        $article->update($data);

        return $this->success('admin.articles.index', 'Article updated.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return $this->success('admin.articles.index', 'Article deleted.');
    }

    public function statistics()
    {
        return response()->json([
            'total' => Article::count(),
            'published' => Article::where('is_active', true)->count(),
            'featured' => Article::where('is_featured', true)->count(),
        ]);
    }

    public function bulkActions(Request $request): RedirectResponse
    {
        if ($request->input('action') === 'delete') {
            Article::whereIn('id', (array) $request->input('ids', []))->delete();
        }

        return back()->with('success', 'Bulk action applied.');
    }

    public function toggleStatus(Article $article): RedirectResponse
    {
        $article->update(['is_active' => ! (bool) $article->is_active]);
        return back()->with('success', 'Article status updated.');
    }

    public function toggleFeatured(Article $article): RedirectResponse
    {
        $article->update(['is_featured' => ! (bool) $article->is_featured]);
        return back()->with('success', 'Article featured updated.');
    }

    public function createWithAI(): View
    {

        return $this->view('admin.articles.create-with-ai');
    }

    public function storeWithAI(Request $request): RedirectResponse
    {
        return $this->store($request);
    }
    public function enhanceWithAI(Request $request)
    {
        return response()->json(['message' => 'AI enhance endpoint ready.']);
    }
    public function generateWithAI(Request $request)
    {
        return response()->json(['message' => 'AI generate endpoint ready.']);
    }
    public function generateFullArticle(Request $request)
    {
        return response()->json(['title' => 'Generated title', 'content' => 'Generated content']);
    }
    public function generateTitle(Request $request)
    {
        return response()->json(['title' => 'Generated title']);
    }
    public function generateContent(Request $request)
    {
        return response()->json(['content' => 'Generated content']);
    }
    public function enhanceContent(Request $request)
    {
        return response()->json(['content' => $request->input('content')]);
    }
    public function generateExcerpt(Request $request)
    {
        return response()->json(['excerpt' => 'Generated excerpt']);
    }
    public function translateAll(Request $request)
    {
        return response()->json(['message' => 'Translate all endpoint ready.']);
    }
    public function improveAll(Request $request)
    {
        return response()->json(['message' => 'Improve all endpoint ready.']);
    }
    public function generateMetaTitle(Request $request)
    {
        return response()->json(['meta_title' => $request->input('title')]);
    }
    public function generateMetaDescription(Request $request)
    {
        return response()->json(['meta_description' => $request->input('content')]);
    }
    public function generateKeywords(Request $request)
    {
        return response()->json(['keywords' => []]);
    }
}
