<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ArticleCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Article::with(['category', 'author']);

        // البحث
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // التصنيف
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // الحالة
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'featured') {
                $query->where('is_featured', true);
            } elseif ($request->status === 'published') {
                $query->where('published_at', '<=', now());
            } elseif ($request->status === 'draft') {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '>', now());
            }
        }

        // الترتيب
        $sortBy = $request->get('sort_by', 'published_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        // التاريخ من وإلى
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('published_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('published_at', '<=', $request->date_to);
        }

        // العدد من وإلى
        if ($request->has('views_from') && $request->views_from) {
            $query->where('views_count', '>=', $request->views_from);
        }
        if ($request->has('views_to') && $request->views_to) {
            $query->where('views_count', '<=', $request->views_to);
        }

        $articles = $query->paginate(15);
        $categories = ArticleCategory::active()->get();
        $authors = User::get();

        // الإحصائيات
        $stats = [
            'total' => Article::count(),
            'active' => Article::where('is_active', true)->count(),
            'inactive' => Article::where('is_active', false)->count(),
            'featured' => Article::where('is_featured', true)->count(),
            'total_views' => Article::sum('views_count'),
            'draft' => Article::whereNull('published_at')
                ->orWhere('published_at', '>', now())
                ->count(),
        ];

        return view('Admin.articles.index', compact('articles', 'categories', 'authors', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ArticleCategory::active()->get();
        $tags = Tag::all();
        $authors = User::get();
        $languages = Language::where('is_active', true)->get();

        return view('Admin.articles.create', compact('categories', 'tags', 'authors', 'languages'));
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Store article with AI and multiple languages
     */
    public function storeWithAI(Request $request)
    {
        $request->validate([
            'title_ar' => 'required|string|max:255',
            'content_ar' => 'required|string',
            'excerpt_ar' => 'nullable|string|max:500',
            'category_id' => 'required|exists:article_categories,id',
            'author_id' => 'required|exists:users,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'meta_title_ar' => 'nullable|string|max:255',
            'meta_description_ar' => 'nullable|string|max:500',
            'meta_keywords_ar' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'translation_style' => 'in:formal,simplified,seo,creative',
            'tone' => 'in:neutral,friendly,professional,enthusiastic'
        ]);

        try {
            // الحصول على جميع اللغات النشطة
            $languages = Language::where('is_active', true)->get();

            // تهيئة مصفوفات JSON
            $titleData = [];
            $contentData = [];
            $excerptData = [];
            $metaTitleData = [];
            $metaDescriptionData = [];
            $metaKeywordsData = [];

            // البيانات العربية
            $arabicData = [
                'title' => $request->title_ar,
                'content' => $request->content_ar,
                'excerpt' => $request->excerpt_ar ?? '',
                'meta_title' => $request->meta_title_ar ?? '',
                'meta_description' => $request->meta_description_ar ?? '',
                'meta_keywords' => $request->meta_keywords_ar ?? ''
            ];

            // إضافة البيانات العربية
            $titleData['ar'] = $request->title_ar;
            $contentData['ar'] = $request->content_ar;
            $excerptData['ar'] = $request->excerpt_ar ?? '';
            $metaTitleData['ar'] = $request->meta_title_ar ?? '';
            $metaDescriptionData['ar'] = $request->meta_description_ar ?? '';
            $metaKeywordsData['ar'] = $request->meta_keywords_ar ?? '';

            // ترجمة النص للغات الأخرى
            $errors = [];

            foreach ($languages as $language) {
                if ($language->code === 'ar') {
                    continue; // تخطي العربية لأنها مضافه بالفعل
                }

                try {
                    $translatedData = $this->translateWithAI($arabicData, $language->code, $request->translation_style, $request->tone);

                    if ($translatedData) {
                        $titleData[$language->code] = $translatedData['title'] ?? '';
                        $contentData[$language->code] = $translatedData['content'] ?? '';
                        $excerptData[$language->code] = $translatedData['excerpt'] ?? '';
                        $metaTitleData[$language->code] = $translatedData['meta_title'] ?? '';
                        $metaDescriptionData[$language->code] = $translatedData['meta_description'] ?? '';
                        $metaKeywordsData[$language->code] = $translatedData['meta_keywords'] ?? '';
                    } else {
                        $errors[] = "فشل في ترجمة المقال للغة: {$language->name}";
                    }
                } catch (\Exception $e) {
                    $errors[] = "خطأ في ترجمة اللغة {$language->name}: " . $e->getMessage();
                }
            }

            // إعداد بيانات المقال
            $articleData = [
                'slug' => Str::slug($request->title_ar) . '-' . Str::random(6),
                'title' => json_encode($titleData, JSON_UNESCAPED_UNICODE),
                'content' => json_encode($contentData, JSON_UNESCAPED_UNICODE),
                'excerpt' => json_encode($excerptData, JSON_UNESCAPED_UNICODE),
                'category_id' => $request->category_id,
                'author_id' => $request->author_id,
                'image_alt' => $request->image_alt,
                'meta_title' => json_encode($metaTitleData, JSON_UNESCAPED_UNICODE),
                'meta_description' => json_encode($metaDescriptionData, JSON_UNESCAPED_UNICODE),
                'meta_keywords' => json_encode($metaKeywordsData, JSON_UNESCAPED_UNICODE),
                'is_active' => $request->boolean('is_active'),
                'is_featured' => $request->boolean('is_featured'),
                'published_at' => $request->published_at,
            ];

            // حساب وقت القراءة (باللغة العربية)
            $articleData['reading_time'] = $this->calculateReadingTime($request->content_ar);

            // رفع الصورة إذا وجدت
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('articles', 'public');
                $articleData['image'] = $imagePath;
            }

            // إنشاء المقال
            $article = Article::create($articleData);

            // إضافة التاغات
            if ($request->has('tags')) {
                $article->tags()->sync($request->tags);
            }

            // إعداد رسالة النجاح
            $successMessage = 'تم إنشاء المقال بنجاح بـ ' . count($titleData) . ' لغات';

            if (!empty($errors)) {
                $successMessage .= ' (مع بعض الأخطاء: ' . implode(', ', $errors) . ')';
            }

            return redirect()->route('admin.articles.index')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء المقال: ' . $e->getMessage());
        }
    }

    /**
     * ترجمة النص باستخدام DeepSeek AI
     */
    private function translateWithAI($data, $targetLanguage, $style = 'formal', $tone = 'neutral')
    {
        $apiKey = config('services.deepseek.api_key');

        if (!$apiKey) {
            Log::error('DeepSeek API key is not configured');
            return null;
        }

        // إعداد محتوى النص للترجمة
        $translationPrompt = $this->buildTranslationPrompt($data, $targetLanguage, $style, $tone);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت مترجم محترف متخصص في ترجمة المقالات والمنشورات. قم بترجمة النص إلى اللغة المطلوبة مع الحفاظ على السياق والمعنى والدقة.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $translationPrompt
                    ]
                ],
                'max_tokens' => 4000,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $translatedText = $result['choices'][0]['message']['content'] ?? null;

                if ($translatedText) {
                    return $this->parseTranslatedResponse($translatedText);
                }
            }

            Log::error('DeepSeek API error', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('DeepSeek API exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * تحسين النص باستخدام الذكاء الاصطناعي
     */
    public function enhanceWithAI(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'type' => 'required|in:title,content,excerpt,meta_title,meta_description',
            'action' => 'required|in:enhance,complete,seo',
            'tone' => 'nullable|in:neutral,friendly,professional,enthusiastic',
            'style' => 'nullable|in:formal,simplified,seo,creative'
        ]);

        $apiKey = config('services.deepseek.api_key');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'مفتاح API غير متوفر'
            ], 500);
        }

        try {
            $prompt = $this->buildEnhancementPrompt(
                $request->text,
                $request->type,
                $request->action,
                $request->tone,
                $request->style
            );

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت مساعد ذكي متخصص في تحسين النصوص العربية. قم بتحسين النص مع الحفاظ على معناه الأصلي.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 2000,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $enhancedText = $result['choices'][0]['message']['content'] ?? null;

                if ($enhancedText) {
                    return response()->json([
                        'success' => true,
                        'enhanced_text' => trim($enhancedText)
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'فشل في تحسين النص'
            ], 500);
        } catch (\Exception $e) {
            Log::error('AI enhancement error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الاتصال بالذكاء الاصطناعي'
            ], 500);
        }
    }

    /**
     * إنشاء مقال كامل بالذكاء الاصطناعي مع الترجمات
     */
    /**
     * إنشاء مقال كامل بالذكاء الاصطناعي مع الترجمات
     */
    public function generateWithAI(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:article_categories,id',
            'author_id' => 'nullable|exists:users,id',
            'tone' => 'nullable|in:neutral,friendly,professional,enthusiastic',
            'style' => 'nullable|in:formal,simplified,seo,creative',
            'generate_all' => 'nullable|boolean'
        ]);

        $apiKey = config('services.deepseek.api_key');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'مفتاح API غير متوفر'
            ], 500);
        }

        try {
            $category = ArticleCategory::find($request->category_id);
            $prompt = $this->buildArticleGenerationPrompt(
                $request->title,
                $category->name,
                $request->tone,
                $request->style
            );

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(120)->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت كاتب محترف متخصص في كتابة المقالات العربية. قم بكتابة مقال متكامل بناءً على العنوان والتصنيف المحدد.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 4000,
                'temperature' => 0.8,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $generatedContent = $result['choices'][0]['message']['content'] ?? null;

                if ($generatedContent) {
                    $parsedArticle = $this->parseGeneratedArticle($generatedContent);

                    // إذا طلب توليد الترجمات
                    $translations = [
                        'title' => ['ar' => $parsedArticle['title']],
                        'content' => ['ar' => $parsedArticle['content']],
                        'excerpt' => ['ar' => $parsedArticle['excerpt']],
                        'meta_title' => ['ar' => $parsedArticle['meta_title']],
                        'meta_description' => ['ar' => $parsedArticle['meta_description']],
                        'meta_keywords' => ['ar' => $parsedArticle['meta_keywords']]
                    ];

                    $translationProgress = [];

                    if ($request->boolean('generate_all')) {
                        $languages = Language::where('is_active', true)->where('code', '!=', 'ar')->get();
                        $totalLanguages = count($languages);
                        $completedLanguages = 0;

                        foreach ($languages as $index => $language) {
                            try {
                                // حساب النسبة المئوية للتقدم
                                $progressPercentage = round(($index / $totalLanguages) * 100);

                                // إرسال تقدم الترجمة (اختياري - يمكن استخدامه لـ WebSocket أو SSE)
                                $translatedData = $this->translateWithAI($parsedArticle, $language->code, $request->style, $request->tone);

                                if ($translatedData) {
                                    $translations['title'][$language->code] = $translatedData['title'] ?? '';
                                    $translations['content'][$language->code] = $translatedData['content'] ?? '';
                                    $translations['excerpt'][$language->code] = $translatedData['excerpt'] ?? '';
                                    $translations['meta_title'][$language->code] = $translatedData['meta_title'] ?? '';
                                    $translations['meta_description'][$language->code] = $translatedData['meta_description'] ?? '';
                                    $translations['meta_keywords'][$language->code] = $translatedData['meta_keywords'] ?? '';

                                    $completedLanguages++;

                                    // تسجيل تقدم الترجمة لهذه اللغة
                                    $translationProgress[$language->code] = [
                                        'status' => 'completed',
                                        'message' => 'تمت الترجمة بنجاح',
                                        'progress' => round(($completedLanguages / $totalLanguages) * 100)
                                    ];
                                } else {
                                    $translationProgress[$language->code] = [
                                        'status' => 'failed',
                                        'message' => 'فشل في الترجمة',
                                        'progress' => round(($completedLanguages / $totalLanguages) * 100)
                                    ];
                                }
                            } catch (\Exception $e) {
                                $translationProgress[$language->code] = [
                                    'status' => 'error',
                                    'message' => 'حدث خطأ: ' . $e->getMessage(),
                                    'progress' => round(($completedLanguages / $totalLanguages) * 100)
                                ];
                            }
                        }
                    }

                    return response()->json([
                        'success' => true,
                        'data' => $parsedArticle,
                        'translations' => $translations,
                        'progress' => [
                            'total' => $totalLanguages ?? 0,
                            'completed' => $completedLanguages ?? 0,
                            'percentage' => $totalLanguages ? round(($completedLanguages / $totalLanguages) * 100) : 0,
                            'details' => $translationProgress
                        ]
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'فشل في إنشاء المقال'
            ], 500);
        } catch (\Exception $e) {
            Log::error('AI generation error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء المقال'
            ], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'content_ar' => 'required|string',
            'excerpt_ar' => 'nullable|string|max:500',
            'category_id' => 'required|exists:article_categories,id',
            'author_id' => 'required|exists:users,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'meta_title_ar' => 'nullable|string|max:255',
            'meta_description_ar' => 'nullable|string|max:500',
            'meta_keywords_ar' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // إنشاء بيانات JSON للعربية فقط
        $articleData = [
            'slug' => Str::slug($validated['title_ar']) . '-' . Str::random(6),
            'title' => json_encode(['ar' => $validated['title_ar']], JSON_UNESCAPED_UNICODE),
            'content' => json_encode(['ar' => $validated['content_ar']], JSON_UNESCAPED_UNICODE),
            'excerpt' => json_encode(['ar' => $validated['excerpt_ar'] ?? ''], JSON_UNESCAPED_UNICODE),
            'category_id' => $validated['category_id'],
            'author_id' => $validated['author_id'],
            'image_alt' => $validated['image_alt'],
            'meta_title' => json_encode(['ar' => $validated['meta_title_ar'] ?? ''], JSON_UNESCAPED_UNICODE),
            'meta_description' => json_encode(['ar' => $validated['meta_description_ar'] ?? ''], JSON_UNESCAPED_UNICODE),
            'meta_keywords' => json_encode(['ar' => $validated['meta_keywords_ar'] ?? ''], JSON_UNESCAPED_UNICODE),
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
            'published_at' => $validated['published_at'],
            'reading_time' => $this->calculateReadingTime($validated['content_ar']),
        ];

        // رفع الصورة إذا وجدت
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
            $articleData['image'] = $imagePath;
        }

        // إنشاء المقال
        $article = Article::create($articleData);

        // إضافة التاغات
        if ($request->has('tags')) {
            $article->tags()->sync($request->tags);
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'تم إنشاء المقال بنجاح');
    }


    /**
     * بناء الـ Prompt للترجمة
     */
    private function buildTranslationPrompt($data, $targetLanguage, $style, $tone)
    {
        $styleMap = [
            'formal' => 'رسمية ومهنية',
            'simplified' => 'مبسطة وسهلة الفهم',
            'seo' => 'محسنة لمحركات البحث',
            'creative' => 'إبداعية وجذابة'
        ];

        $toneMap = [
            'neutral' => 'محايدة',
            'friendly' => 'ودودة',
            'professional' => 'مهنية',
            'enthusiastic' => 'متحمسة'
        ];

        $prompt = "قم بترجمة المقال التالي إلى اللغة {$targetLanguage}:\n\n";
        $prompt .= "أسلوب الترجمة: {$styleMap[$style]}\n";
        $prompt .= "نبرة النص: {$toneMap[$tone]}\n\n";

        $prompt .= "عنوان المقال:\n{$data['title']}\n\n";

        if (!empty($data['excerpt'])) {
            $prompt .= "ملخص المقال:\n{$data['excerpt']}\n\n";
        }

        $prompt .= "محتوى المقال:\n{$data['content']}\n\n";

        if (!empty($data['meta_title'])) {
            $prompt .= "عنوان SEO:\n{$data['meta_title']}\n\n";
        }

        if (!empty($data['meta_description'])) {
            $prompt .= "وصف SEO:\n{$data['meta_description']}\n\n";
        }

        if (!empty($data['meta_keywords'])) {
            $prompt .= "الكلمات المفتاحية:\n{$data['meta_keywords']}\n\n";
        }

        $prompt .= "يرجى إرجاع النتيجة بالتنسيق التالي:\n";
        $prompt .= "TITLE: [العنوان المترجم]\n";
        $prompt .= "CONTENT: [المحتوى المترجم]\n";
        $prompt .= "EXCERPT: [الملخص المترجم]\n";
        $prompt .= "META_TITLE: [عنوان SEO المترجم]\n";
        $prompt .= "META_DESCRIPTION: [وصف SEO المترجم]\n";
        $prompt .= "META_KEYWORDS: [الكلمات المفتاحية المترجمة]\n";

        return $prompt;
    }

    /**
     * تحليل الاستجابة المترجمة
     */
    private function parseTranslatedResponse($response)
    {
        $result = [
            'title' => '',
            'content' => '',
            'excerpt' => '',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => ''
        ];

        $lines = explode("\n", $response);

        foreach ($lines as $line) {
            $line = trim($line);
            if (str_starts_with($line, 'TITLE:')) {
                $result['title'] = trim(str_replace('TITLE:', '', $line));
            } elseif (str_starts_with($line, 'CONTENT:')) {
                $result['content'] = trim(str_replace('CONTENT:', '', $line));
            } elseif (str_starts_with($line, 'EXCERPT:')) {
                $result['excerpt'] = trim(str_replace('EXCERPT:', '', $line));
            } elseif (str_starts_with($line, 'META_TITLE:')) {
                $result['meta_title'] = trim(str_replace('META_TITLE:', '', $line));
            } elseif (str_starts_with($line, 'META_DESCRIPTION:')) {
                $result['meta_description'] = trim(str_replace('META_DESCRIPTION:', '', $line));
            } elseif (str_starts_with($line, 'META_KEYWORDS:')) {
                $result['meta_keywords'] = trim(str_replace('META_KEYWORDS:', '', $line));
            }
        }

        return $result;
    }

    /**
     * بناء Prompt لتحسين النص
     */
    private function buildEnhancementPrompt($text, $type, $action, $tone, $style)
    {
        $typeMap = [
            'title' => 'العنوان',
            'content' => 'المحتوى',
            'excerpt' => 'الملخص',
            'meta_title' => 'عنوان SEO',
            'meta_description' => 'وصف SEO'
        ];

        $actionMap = [
            'enhance' => 'تحسين',
            'complete' => 'إكمال',
            'seo' => 'تحسين SEO'
        ];

        $toneMap = [
            'neutral' => 'محايدة',
            'friendly' => 'ودودة',
            'professional' => 'مهنية',
            'enthusiastic' => 'متحمسة'
        ];

        $prompt = "النص الحالي ({$typeMap[$type]}):\n{$text}\n\n";

        if ($action === 'enhance') {
            $prompt .= "الرجاء تحسين هذا النص لجعله أكثر احترافية وجاذبية.";
        } elseif ($action === 'complete') {
            $prompt .= "الرجاء إكمال هذا النص ليكون مقالاً متكاملاً.";
        } elseif ($action === 'seo') {
            $prompt .= "الرجاء تحسين هذا النص لمحركات البحث (SEO)";
        }

        if ($tone) {
            $prompt .= " مع نبرة {$toneMap[$tone]}.";
        }

        if ($style) {
            $prompt .= " وأسلوب {$style}.";
        }

        $prompt .= "\n\nيرجى إرجاع النص المحسن فقط دون أي إضافات.";

        return $prompt;
    }

    /**
     * إنشاء مقال كامل بالذكاء الاصطناعي
     */

    /**
     * بناء Prompt لإنشاء المقال
     */
    private function buildArticleGenerationPrompt($title, $category, $tone, $style)
    {
        $toneMap = [
            'neutral' => 'محايدة',
            'friendly' => 'ودودة',
            'professional' => 'مهنية',
            'enthusiastic' => 'متحمسة'
        ];

        $styleMap = [
            'formal' => 'رسمية',
            'simplified' => 'مبسطة',
            'seo' => 'محسنة لمحركات البحث',
            'creative' => 'إبداعية'
        ];

        $prompt = "عنوان المقال: {$title}\n";
        $prompt .= "تصنيف المقال: {$category}\n";
        $prompt .= "نبرة المقال: " . ($toneMap[$tone] ?? 'محايدة') . "\n";
        $prompt .= "أسلوب الكتابة: " . ($styleMap[$style] ?? 'رسمية') . "\n\n";

        $prompt .= "الرجاء كتابة مقال متكامل يتضمن:\n";
        $prompt .= "1. مقدمة جذابة\n";
        $prompt .= "2. محتوى رئيسي مقسم إلى فقرات\n";
        $prompt .= "3. خاتمة ملخصة\n";
        $prompt .= "4. ملخص للمقال\n";
        $prompt .= "5. عنوان SEO مناسب\n";
        $prompt .= "6. وصف SEO\n";
        $prompt .= "7. كلمات مفتاحية\n\n";

        $prompt .= "يرجى إرجاع النتيجة بالتنسيق التالي:\n";
        $prompt .= "TITLE: [العنوان]\n";
        $prompt .= "CONTENT: [المحتوى]\n";
        $prompt .= "EXCERPT: [الملخص]\n";
        $prompt .= "META_TITLE: [عنوان SEO]\n";
        $prompt .= "META_DESCRIPTION: [وصف SEO]\n";
        $prompt .= "META_KEYWORDS: [الكلمات المفتاحية]\n";

        return $prompt;
    }


    /**
     * تحليل المقال المولد
     */
    private function parseGeneratedArticle($content)
    {
        $result = [
            'title' => '',
            'content' => '',
            'excerpt' => '',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => ''
        ];

        $lines = explode("\n", $content);

        $currentSection = '';
        foreach ($lines as $line) {
            if (str_starts_with($line, 'TITLE:')) {
                $result['title'] = trim(str_replace('TITLE:', '', $line));
            } elseif (str_starts_with($line, 'CONTENT:')) {
                $currentSection = 'content';
                $result['content'] = trim(str_replace('CONTENT:', '', $line));
            } elseif (str_starts_with($line, 'EXCERPT:')) {
                $result['excerpt'] = trim(str_replace('EXCERPT:', '', $line));
            } elseif (str_starts_with($line, 'META_TITLE:')) {
                $result['meta_title'] = trim(str_replace('META_TITLE:', '', $line));
            } elseif (str_starts_with($line, 'META_DESCRIPTION:')) {
                $result['meta_description'] = trim(str_replace('META_DESCRIPTION:', '', $line));
            } elseif (str_starts_with($line, 'META_KEYWORDS:')) {
                $result['meta_keywords'] = trim(str_replace('META_KEYWORDS:', '', $line));
            } elseif (
                $currentSection === 'content' && !str_starts_with($line, 'EXCERPT:') &&
                !str_starts_with($line, 'META_')
            ) {
                $result['content'] .= "\n" . $line;
            }
        }

        // تنظيف النتائج
        foreach ($result as $key => $value) {
            $result[$key] = trim($value);
        }

        return $result;
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $article->load(['category', 'author', 'tags', 'comments.user']);
        return view('Admin.articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $categories = ArticleCategory::active()->get();
        $tags = Tag::all();
        $authors = User::get();
        $languages = Language::where('is_active', true)->get();

        $article->load('tags', 'translations');

        return view('Admin.articles.edit', compact('article', 'categories', 'tags', 'authors', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'required|exists:article_categories,id',
            'author_id' => 'required|exists:users,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // تحديث slug إذا تغير العنوان
        if ($validated['title'] !== $article->title) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(6);
        }

        // رفع صورة جديدة إذا وجدت
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }

            $imagePath = $request->file('image')->store('articles', 'public');
            $validated['image'] = $imagePath;
        }

        // حساب وقت القراءة
        $validated['reading_time'] = $this->calculateReadingTime($validated['content']);

        // تحديث المقال
        $article->update($validated);

        // تحديث التاغات
        if ($request->has('tags')) {
            $article->tags()->sync($request->tags);
        } else {
            $article->tags()->detach();
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'تم تحديث المقال بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        // حذف الصورة
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        // حذف العلاقات
        $article->tags()->detach();
        $article->comments()->delete();

        // حذف المقال
        $article->delete();

        return response()->json(['success' => 'تم حذف المقال بنجاح']);
    }

    /**
     * Toggle article status.
     */
    public function toggleStatus(Article $article)
    {
        $article->update(['is_active' => !$article->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'تم تغيير حالة المقال بنجاح',
            'is_active' => $article->is_active
        ]);
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Article $article)
    {
        $article->update(['is_featured' => !$article->is_featured]);

        return response()->json([
            'success' => true,
            'message' => 'تم تغيير حالة التمييز بنجاح',
            'is_featured' => $article->is_featured
        ]);
    }

    /**
     * Calculate reading time.
     */
    private function calculateReadingTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        $readingTime = ceil($wordCount / 200); // 200 كلمة في الدقيقة
        return $readingTime > 0 ? $readingTime : 1;
    }

    /**
     * Bulk actions.
     */
    public function bulkActions(Request $request)
    {
        $action = $request->action;
        $ids = $request->ids;

        if (!$ids) {
            return back()->with('error', 'لم يتم تحديد أي مقالات');
        }

        switch ($action) {
            case 'activate':
                Article::whereIn('id', $ids)->update(['is_active' => true]);
                $message = 'تم تفعيل المقالات المحددة';
                break;

            case 'deactivate':
                Article::whereIn('id', $ids)->update(['is_active' => false]);
                $message = 'تم تعطيل المقالات المحددة';
                break;

            case 'feature':
                Article::whereIn('id', $ids)->update(['is_featured' => true]);
                $message = 'تم تمييز المقالات المحددة';
                break;

            case 'unfeature':
                Article::whereIn('id', $ids)->update(['is_featured' => false]);
                $message = 'تم إلغاء تمييز المقالات المحددة';
                break;

            case 'delete':
                $articles = Article::whereIn('id', $ids)->get();
                foreach ($articles as $article) {
                    if ($article->image) {
                        Storage::disk('public')->delete($article->image);
                    }
                    $article->tags()->detach();
                    $article->comments()->delete();
                    $article->delete();
                }
                $message = 'تم حذف المقالات المحددة';
                break;

            default:
                return back()->with('error', 'الإجراء غير معروف');
        }

        return back()->with('success', $message);
    }
    // إنشاء مقال كامل بالذكاء الاصطناعي
    public function generateFullArticle(Request $request)
    {
        try {
            $request->validate([
                'topic' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'author_id' => 'nullable|exists:authors,id',
                'style' => 'nullable|in:informative,persuasive,narrative,descriptive',
                'length' => 'nullable|in:short,medium,long'
            ]);

            // هنا يمكنك استدعاء خدمة الذكاء الاصطناعي (مثال باستخدام OpenAI)
            $apiKey = config('services.openai.api_key');

            $prompt = "اكتب مقال {$request->style} عن موضوع: {$request->topic}";
            if ($request->length) {
                $lengths = [
                    'short' => '300 كلمة',
                    'medium' => '600 كلمة',
                    'long' => '1000 كلمة'
                ];
                $prompt .= " بطول {$lengths[$request->length]}";
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'أنت كاتب محترف باللغة العربية.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 2000,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? '';

                // استخراج العنوان من المحتوى
                $title = $this->extractTitleFromContent($content);

                // استخراج الملخص
                $excerpt = $this->extractExcerptFromContent($content);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'title' => $title,
                        'content' => $content,
                        'excerpt' => $excerpt
                    ],
                    'translations' => $this->generateTranslations($title, $content, $excerpt)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'فشل في التواصل مع خدمة الذكاء الاصطناعي'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ]);
        }
    }

    // توليد العنوان
    public function generateTitle(Request $request)
    {
        try {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'language' => 'required|string|size:2'
            ]);

            $category = Category::find($request->category_id);

            // استدعاء الذكاء الاصطناعي لإنشاء عنوان
            $titles = [
                'ar' => "عنوان مقال عن {$category->name}",
                'en' => "Article title about {$category->name}"
            ];

            return response()->json([
                'success' => true,
                'title' => $titles[$request->language] ?? $titles['ar']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ]);
        }
    }

    // توليد المحتوى
    public function generateContent(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'nullable|exists:categories,id',
                'language' => 'required|string|size:2'
            ]);

            $categoryName = $request->category_id ? Category::find($request->category_id)->name : 'عام';

            // استدعاء الذكاء الاصطناعي
            $content = $this->generateAIContent(
                "اكتب مقال عن: {$request->title} في تصنيف {$categoryName}",
                $request->language
            );

            return response()->json([
                'success' => true,
                'content' => $content
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ]);
        }
    }

    // تحسين المحتوى
    public function enhanceContent(Request $request)
    {
        try {
            $request->validate([
                'content' => 'required|string',
                'language' => 'required|string|size:2',
                'action' => 'nullable|in:enhance,correct,simplify'
            ]);

            // استدعاء الذكاء الاصطناعي لتحسين المحتوى
            $enhancedContent = $this->enhanceAIContent(
                $request->content,
                $request->language,
                $request->action
            );

            return response()->json([
                'success' => true,
                'enhanced_content' => $enhancedContent
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ]);
        }
    }

    // توليد الملخص
    public function generateExcerpt(Request $request)
    {
        try {
            $request->validate([
                'content' => 'required|string',
                'language' => 'required|string|size:2'
            ]);

            $excerpt = $this->generateAIExcerpt($request->content, $request->language);

            return response()->json([
                'success' => true,
                'excerpt' => $excerpt
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ]);
        }
    }

    // ترجمة لكل اللغات
    public function translateAll(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string'
            ]);

            $languages = Language::where('code', '!=', 'ar')->active()->get();
            $translations = [];

            foreach ($languages as $language) {
                $translations[$language->code] = [
                    'title' => $this->translateText($request->title, $language->code),
                    'content' => $this->translateText($request->content, $language->code),
                    'excerpt' => $this->generateExcerptFromContent($request->content, $language->code)
                ];
            }

            return response()->json([
                'success' => true,
                'translations' => $translations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ]);
        }
    }

    // تحسين لكل اللغات
    public function improveAll(Request $request)
    {
        try {
            $request->validate([
                'languages' => 'required|array'
            ]);

            $improved = [];
            foreach ($request->languages as $lang => $data) {
                $improved[$lang] = [
                    'title' => $this->improveAIText($data['title'] ?? '', $lang),
                    'content' => $this->improveAIText($data['content'] ?? '', $lang),
                    'excerpt' => $this->improveAIText($data['excerpt'] ?? '', $lang)
                ];
            }

            return response()->json([
                'success' => true,
                'improved' => $improved
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ]);
        }
    }

    // توليد عنوان SEO
    public function generateMetaTitle(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'language' => 'required|string|size:2'
            ]);

            $metaTitle = $this->generateAIMetaTitle($request->title, $request->language);

            return response()->json([
                'success' => true,
                'meta_title' => $metaTitle
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ]);
        }
    }

    // توليد وصف SEO
    public function generateMetaDescription(Request $request)
    {
        try {
            $request->validate([
                'content' => 'required|string',
                'language' => 'required|string|size:2'
            ]);

            $metaDescription = $this->generateAIMetaDescription($request->content, $request->language);

            return response()->json([
                'success' => true,
                'meta_description' => $metaDescription
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ]);
        }
    }

    // توليد كلمات مفتاحية
    public function generateKeywords(Request $request)
    {
        try {
            $request->validate([
                'content' => 'required|string',
                'language' => 'required|string|size:2'
            ]);

            $keywords = $this->generateAIKeywords($request->content, $request->language);

            return response()->json([
                'success' => true,
                'keywords' => $keywords
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ]);
        }
    }

    // تحسين النص العام
    public function enhanceText(Request $request)
    {
        try {
            $request->validate([
                'text' => 'required|string',
                'type' => 'required|in:grammar,style,clarity,brevity'
            ]);

            $enhancedText = $this->enhanceGeneralText($request->text, $request->type);

            return response()->json([
                'success' => true,
                'enhanced_text' => $enhancedText
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ]);
        }
    }



    // ============================================
    // الدوال المساعدة
    // ============================================

    private function extractTitleFromContent($content)
    {
        // استخراج أول سطر كعنوان
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (!empty($trimmed) && strlen($trimmed) < 100) {
                return $trimmed;
            }
        }

        return 'عنوان المقال';
    }

    private function extractExcerptFromContent($content, $length = 150)
    {
        // إزالة علامات HTML إذا وجدت
        $cleanContent = strip_tags($content);
        // أخذ أول 150 حرف
        $excerpt = mb_substr($cleanContent, 0, $length);

        // إضافة ... إذا كان النص أطول
        if (mb_strlen($cleanContent) > $length) {
            $excerpt .= '...';
        }

        return $excerpt;
    }

    private function generateTranslations($title, $content, $excerpt)
    {
        // هذا مثال بسيط - في الواقع ستقوم باستدعاء خدمة الترجمة
        $translations = [];
        $languages = Language::where('code', '!=', 'ar')->active()->get();

        foreach ($languages as $language) {
            $translations[$language->code] = [
                'title' => "[Translation] {$title}",
                'content' => "[Translation] {$content}",
                'excerpt' => "[Translation] {$excerpt}"
            ];
        }

        return $translations;
    }

    private function generateAIContent($prompt, $language)
    {
        // استدعاء API الذكاء الاصطناعي
        // هذا مثال بسيط - في الواقع ستقوم باستدعاء OpenAI أو خدمة مماثلة
        return "محتوى المقال المولد بالذكاء الاصطناعي باللغة {$language} بناءً على: {$prompt}";
    }

    private function enhanceAIContent($content, $language, $action)
    {
        // تحسين المحتوى باستخدام الذكاء الاصطناعي
        return "المحتوى المحسن بالذكاء الاصطناعي باللغة {$language}: {$content}";
    }

    private function generateAIExcerpt($content, $language)
    {
        // توليد ملخص باستخدام الذكاء الاصطناعي
        $excerpt = mb_substr(strip_tags($content), 0, 150);
        return $excerpt . (mb_strlen($content) > 150 ? '...' : '');
    }

    private function translateText($text, $targetLanguage)
    {
        // ترجمة النص للغة المطلوبة
        return "[Translated to {$targetLanguage}] {$text}";
    }

    private function generateExcerptFromContent($content, $language)
    {
        return $this->generateAIExcerpt($content, $language);
    }

    private function improveAIText($text, $language)
    {
        // تحسين النص باستخدام الذكاء الاصطناعي
        return "النص المحسن باللغة {$language}: {$text}";
    }

    private function generateAIMetaTitle($title, $language)
    {
        // توليد عنوان SEO
        return "{$title} - موقعنا | باللغة {$language}";
    }

    private function generateAIMetaDescription($content, $language)
    {
        // توليد وصف SEO
        $cleanContent = strip_tags($content);
        $description = mb_substr($cleanContent, 0, 150);
        return $description . (mb_strlen($cleanContent) > 150 ? '...' : '');
    }

    private function generateAIKeywords($content, $language)
    {
        // توليد كلمات مفتاحية
        return "مقال, {$language}, محتوى, إبداعي";
    }

    private function enhanceGeneralText($text, $type)
    {
        // تحسين النص العام
        $improvements = [
            'grammar' => 'النص المصحح لغوياً',
            'style' => 'النص المحسن أسلوبياً',
            'clarity' => 'النص الأكثر وضوحاً',
            'brevity' => 'النص الأكثر إيجازاً'
        ];

        return "{$improvements[$type]}: {$text}";
    }
}
