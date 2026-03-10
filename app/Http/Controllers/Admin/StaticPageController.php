<?php

namespace App\Http\Controllers\Admin;

use App\Models\StaticPage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class StaticPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // البحث والفلترة
        $query = StaticPage::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // الترتيب
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $pages = $query->paginate(10);

        return view('Admin.static-pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.static-pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'slug' => 'nullable|string|max:255|unique:static_pages,slug',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // إنشاء slug إذا لم يتم توفيره
        $slug = $request->slug;
        if (empty($slug)) {
            $slug = Str::slug($request->title, '-', 'ar');
        }

        // التأكد من أن الـ slug فريد
        $slug = $this->makeUniqueSlug($slug);

        StaticPage::create([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.static-pages.index')
            ->with('success', 'تم إنشاء الصفحة الثابتة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show($staticPage)
    {
        $staticPage = StaticPage::findOrFail($staticPage);
        return view('Admin.static-pages.show', compact('staticPage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($staticPage)
    {

        $page = StaticPage::findOrFail($staticPage);
        return view('Admin.static-pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StaticPage $staticPage)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'slug' => 'required|string|max:255|unique:static_pages,slug,' . $staticPage->id,
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $staticPage->update([
            'title' => $request->title,
            'slug' => Str::slug($request->slug, '-', 'ar'),
            'content' => $request->content,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.static-pages.index')
            ->with('success', 'تم تحديث الصفحة الثابتة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StaticPage $staticPage)
    {
        // منع حذف الصفحات الأساسية
        $protectedSlugs = ['syas-alkhsosy', 'syas-alastrgaaa', 'aldman', 'mn-nhn', 'alshrot-oalahkam'];

        if (in_array($staticPage->slug, $protectedSlugs)) {
            return redirect()->route('admin.static-pages.index')
                ->with('error', 'لا يمكن حذف هذه الصفحة الأساسية');
        }

        $staticPage->delete();

        return redirect()->route('admin.static-pages.index')
            ->with('success', 'تم حذف الصفحة الثابتة بنجاح');
    }

    /**
     * Make unique slug
     */
    private function makeUniqueSlug($slug)
    {
        $originalSlug = $slug;
        $count = 1;

        while (StaticPage::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $ids = json_decode($request->ids);

        if (!$ids) {
            return redirect()->back()->with('error', 'لم يتم تحديد أي صفحات');
        }

        switch ($action) {
            case 'activate':
                StaticPage::whereIn('id', $ids)->update(['status' => 'active']);
                $message = 'تم تفعيل الصفحات المحددة';
                break;

            case 'deactivate':
                StaticPage::whereIn('id', $ids)->update(['status' => 'inactive']);
                $message = 'تم تعطيل الصفحات المحددة';
                break;

            case 'delete':
                // منع حذف الصفحات المحمية
                $protectedSlugs = ['syas-alkhsosy', 'syas-alastrgaaa', 'aldman', 'mn-nhn', 'alshrot-oalahkam'];
                StaticPage::whereIn('id', $ids)
                    ->whereNotIn('slug', $protectedSlugs)
                    ->delete();
                $message = 'تم حذف الصفحات المحددة';
                break;

            default:
                return redirect()->back()->with('error', 'الإجراء غير صالح');
        }

        return redirect()->route('admin.static-pages.index')->with('success', $message);
    }

    /**
     * عرض نموذج التعديل بالذكاء الاصطناعي
     */
    public function editWithAI($id)
    {
        $page = StaticPage::findOrFail($id);
        return view('Admin.static-pages.edit-with-ai', compact('page'));
    }

    /**
     * تحسين العنوان باستخدام الذكاء الاصطناعي
     */
    public function enhanceTitleWithAI(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'type' => 'required|in:page_title',
            'action' => 'required|in:enhance'
        ]);

        try {
            $apiKey = config('services.deepseek.api_key');

            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'مفتاح API غير متوفر'
                ], 500);
            }

            $prompt = "الرجاء تحسين العنوان التالي لجعله أكثر جاذبية ومناسباً للصفحات الثابتة:\n\n{$request->text}\n\nيرجى إرجاع العنوان المحسن فقط.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت مساعد ذكي متخصص في تحسين عناوين الصفحات الثابتة.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 100,
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
                'message' => 'فشل في تحسين العنوان'
            ], 500);
        } catch (\Exception $e) {
            Log::error('AI title enhancement error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحسين العنوان'
            ], 500);
        }
    }

    /**
     * تحسين المحتوى باستخدام الذكاء الاصطناعي
     */
    public function enhanceContentWithAI(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'type' => 'required|in:page_content',
            'action' => 'required|in:enhance,expand,simplify'
        ]);

        try {
            $apiKey = config('services.deepseek.api_key');

            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'مفتاح API غير متوفر'
                ], 500);
            }

            $actionText = $request->action == 'expand' ? 'توسيع' : ($request->action == 'simplify' ? 'تبسيط' : 'تحسين');
            $prompt = "الرجاء {$actionText} المحتوى التالي للصفحة الثابتة:\n\n{$request->text}\n\nيرجى إرجاع المحتوى المحسن فقط.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت مساعد ذكي متخصص في تحسين محتوى الصفحات الثابتة.'
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
                    $fieldName = $request->action == 'expand' ? 'expanded_text' : ($request->action == 'simplify' ? 'simplified_text' : 'enhanced_text');

                    return response()->json([
                        'success' => true,
                        $fieldName => trim($enhancedText)
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'فشل في تحسين المحتوى'
            ], 500);
        } catch (\Exception $e) {
            Log::error('AI content enhancement error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحسين المحتوى'
            ], 500);
        }
    }
    /**
     * ترجمة المحتوى
     */
    public function translateContentWithAI(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'target_lang' => 'required|in:en,fr,es'
        ]);

        try {
            $apiKey = config('services.deepseek.api_key');

            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'مفتاح API غير متوفر'
                ], 500);
            }

            $languages = [
                'en' => 'الإنجليزية',
                'fr' => 'الفرنسية',
                'es' => 'الإسبانية'
            ];

            $targetLanguage = $languages[$request->target_lang];

            $prompt = "الرجاء ترجمة النص التالي إلى اللغة {$targetLanguage}:\n\n{$request->text}";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت مترجم محترف للغة العربية.'
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
                $translatedText = $result['choices'][0]['message']['content'] ?? null;

                if ($translatedText) {
                    return response()->json([
                        'success' => true,
                        'translated_text' => trim($translatedText)
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'فشل في ترجمة المحتوى'
            ], 500);
        } catch (\Exception $e) {
            Log::error('AI content translation error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء ترجمة المحتوى'
            ], 500);
        }
    }


    /**
     * ترجمة النص باستخدام الذكاء الاصطناعي
     */
    public function translateWithAI(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'target_lang' => 'required|in:en,fr,es' // إضافة لغات حسب الحاجة
        ]);

        try {
            $apiKey = config('services.deepseek.api_key');

            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'مفتاح API غير متوفر'
                ], 500);
            }

            $prompt = "الرجاء ترجمة النص التالي إلى اللغة {$request->target_lang}:\n\n{$request->text}\n\nيرجى إرجاع الترجمة فقط.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت مترجم محترف.'
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
                $translatedText = $result['choices'][0]['message']['content'] ?? null;

                if ($translatedText) {
                    return response()->json([
                        'success' => true,
                        'translated_text' => trim($translatedText)
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'فشل في الترجمة'
            ], 500);
        } catch (\Exception $e) {
            Log::error('AI translation error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الترجمة'
            ], 500);
        }
    }

    /**
     * إنشاء Meta Title باستخدام الذكاء الاصطناعي
     */
    public function generateMetaTitleWithAI(Request $request)
    {
        $request->validate([
            'title' => 'required|string'
        ]);

        try {
            $apiKey = config('services.deepseek.api_key');

            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'مفتاح API غير متوفر'
                ], 500);
            }

            $prompt = "الرجاء إنشاء عنوان SEO محسن (طول مثالي 50-60 حرف) للصفحة التالية:\n\nالعنوان الأصلي: {$request->title}\n\nيرجى إرجاع العنوان المحسن فقط.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت متخصص في تحسين محركات البحث (SEO) للصفحات العربية.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 100,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $metaTitle = $result['choices'][0]['message']['content'] ?? null;

                if ($metaTitle) {
                    return response()->json([
                        'success' => true,
                        'meta_title' => trim($metaTitle)
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'فشل في إنشاء عنوان SEO'
            ], 500);
        } catch (\Exception $e) {
            Log::error('AI meta title generation error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء عنوان SEO'
            ], 500);
        }
    }

    /**
     * إنشاء Meta Description باستخدام الذكاء الاصطناعي
     */
    public function generateMetaDescriptionWithAI(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'max_length' => 'nullable|integer|min:100|max:200'
        ]);

        try {
            $apiKey = config('services.deepseek.api_key');

            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'مفتاح API غير متوفر'
                ], 500);
            }

            $maxLength = $request->max_length ?? 160;
            $prompt = "الرجاء إنشاء وصف SEO محسن (طول مثالي {$maxLength} حرف) للصفحة التالية:\n\nمحتوى الصفحة: {$request->content}\n\nيرجى إرجاع الوصف المحسن فقط.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت متخصص في كتابة أوصاف SEO للصفحات العربية.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 200,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $metaDescription = $result['choices'][0]['message']['content'] ?? null;

                if ($metaDescription) {
                    return response()->json([
                        'success' => true,
                        'meta_description' => trim($metaDescription)
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'فشل في إنشاء وصف SEO'
            ], 500);
        } catch (\Exception $e) {
            Log::error('AI meta description generation error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء وصف SEO'
            ], 500);
        }
    }


    // StaticPageController.php - إضافة هذه الطرق الجديدة

    /**
     * عرض نموذج الإنشاء بالذكاء الاصطناعي
     */
    public function createWithAI()
    {
        return view('Admin.static-pages.create-with-ai');
    }

    /**
     * تحميل قالب باستخدام الذكاء الاصطناعي
     */
    public function loadTemplateWithAI(Request $request)
    {
        $request->validate([
            'template' => 'required|in:privacy,terms,about,contact,faq,custom',
            'page_type' => 'required|in:regular,legal,info'
        ]);

        try {
            $apiKey = config('services.deepseek.api_key');

            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'مفتاح API غير متوفر'
                ], 500);
            }

            $templateNames = [
                'privacy' => 'سياسة الخصوصية',
                'terms' => 'الشروط والأحكام',
                'about' => 'من نحن',
                'contact' => 'اتصل بنا',
                'faq' => 'الأسئلة الشائعة',
                'custom' => 'صفحة مخصصة'
            ];

            $pageTypes = [
                'regular' => 'صفحة عادية',
                'legal' => 'صفحة قانونية',
                'info' => 'صفحة معلوماتية'
            ];

            $templateName = $templateNames[$request->template];
            $pageType = $pageTypes[$request->page_type];

            $prompt = "الرجاء إنشاء {$templateName} من نوع {$pageType} مع مراعاة:\n";
            $prompt .= "1. عنوان مناسب للصفحة\n";
            $prompt .= "2. محتوى شامل ومناسب\n";
            $prompt .= "3. عنوان SEO مناسب\n";
            $prompt .= "4. وصف SEO مناسب\n";
            $prompt .= "5. كلمات مفتاحية مناسبة\n\n";
            $prompt .= "يرجى إرجاع النتيجة بالتنسيق التالي:\n";
            $prompt .= "TITLE: [العنوان]\n";
            $prompt .= "CONTENT: [المحتوى]\n";
            $prompt .= "META_TITLE: [عنوان SEO]\n";
            $prompt .= "META_DESCRIPTION: [وصف SEO]\n";
            $prompt .= "META_KEYWORDS: [الكلمات المفتاحية]\n";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت مساعد ذكي متخصص في إنشاء صفحات الويب الثابتة.'
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
                $content = $result['choices'][0]['message']['content'] ?? null;

                if ($content) {
                    $parsed = $this->parseAIContent($content);

                    return response()->json([
                        'success' => true,
                        'data' => $parsed
                    ]);
                }
            }

            // إذا فشل الاتصال بالـ API، استخدم القوالب المحلية
            $localTemplates = $this->getLocalTemplate($request->template, $request->page_type);
            return response()->json([
                'success' => true,
                'data' => $localTemplates
            ]);
        } catch (\Exception $e) {
            Log::error('AI template loading error', ['error' => $e->getMessage()]);

            // استخدام القوالب المحلية في حالة الخطأ
            $localTemplates = $this->getLocalTemplate($request->template, $request->page_type);
            return response()->json([
                'success' => true,
                'data' => $localTemplates
            ]);
        }
    }

    /**
     * إنشاء صفحة من وصف
     */
    public function generateFromPromptWithAI(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|min:10|max:500',
            'page_type' => 'required|in:regular,legal,info'
        ]);

        try {
            $apiKey = config('services.deepseek.api_key');

            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'مفتاح API غير متوفر'
                ], 500);
            }

            $pageTypes = [
                'regular' => 'صفحة عادية',
                'legal' => 'صفحة قانونية',
                'info' => 'صفحة معلوماتية'
            ];

            $pageType = $pageTypes[$request->page_type];

            $prompt = "الرجاء إنشاء صفحة ويب ثابتة بناءً على الوصف التالي:\n\n";
            $prompt .= "الوصف: {$request->prompt}\n";
            $prompt .= "النوع: {$pageType}\n\n";
            $prompt .= "يرجى توفير:\n";
            $prompt .= "1. عنوان مناسب للصفحة\n";
            $prompt .= "2. محتوى شامل ومناسب\n";
            $prompt .= "3. عنوان SEO مناسب\n";
            $prompt .= "4. وصف SEO مناسب\n";
            $prompt .= "5. كلمات مفتاحية مناسبة\n\n";
            $prompt .= "يرجى إرجاع النتيجة بالتنسيق التالي:\n";
            $prompt .= "TITLE: [العنوان]\n";
            $prompt .= "CONTENT: [المحتوى]\n";
            $prompt .= "META_TITLE: [عنوان SEO]\n";
            $prompt .= "META_DESCRIPTION: [وصف SEO]\n";
            $prompt .= "META_KEYWORDS: [الكلمات المفتاحية]\n";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت مساعد ذكي متخصص في إنشاء صفحات الويب بناءً على الأوصاف.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 2500,
                'temperature' => 0.8,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $content = $result['choices'][0]['message']['content'] ?? null;

                if ($content) {
                    $parsed = $this->parseAIContent($content);

                    return response()->json([
                        'success' => true,
                        'data' => $parsed
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'فشل في إنشاء الصفحة'
            ], 500);
        } catch (\Exception $e) {
            Log::error('AI page generation error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الصفحة'
            ], 500);
        }
    }

    /**
     * إنشاء صفحة كاملة
     */
    public function generatePageWithAI(Request $request)
    {
        $request->validate([
            'page_type' => 'required|in:privacy,terms,about,contact,faq,custom',
            'tone' => 'required|in:formal,friendly,professional,simple'
        ]);

        try {
            $apiKey = config('services.deepseek.api_key');

            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'مفتاح API غير متوفر'
                ], 500);
            }

            $templateNames = [
                'privacy' => 'سياسة الخصوصية',
                'terms' => 'الشروط والأحكام',
                'about' => 'من نحن',
                'contact' => 'اتصل بنا',
                'faq' => 'الأسئلة الشائعة',
                'custom' => 'صفحة مخصصة'
            ];

            $tones = [
                'formal' => 'رسمي',
                'friendly' => 'ودود',
                'professional' => 'مهني',
                'simple' => 'بسيط'
            ];

            $templateName = $templateNames[$request->page_type];
            $tone = $tones[$request->tone];

            $prompt = "الرجاء إنشاء {$templateName} بنبرة {$tone} مع مراعاة:\n";
            $prompt .= "1. عنوان جذاب ومناسب\n";
            $prompt .= "2. محتوى شامل ومنظم\n";
            $prompt .= "3. عنوان SEO محسن\n";
            $prompt .= "4. وصف SEO محسن\n";
            $prompt .= "5. كلمات مفتاحية مناسبة\n\n";
            $prompt .= "يرجى إرجاع النتيجة بالتنسيق التالي:\n";
            $prompt .= "TITLE: [العنوان]\n";
            $prompt .= "CONTENT: [المحتوى]\n";
            $prompt .= "META_TITLE: [عنوان SEO]\n";
            $prompt .= "META_DESCRIPTION: [وصف SEO]\n";
            $prompt .= "META_KEYWORDS: [الكلمات المفتاحية]\n";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت مساعد ذكي متخصص في إنشاء صفحات الويب بأنواع ونبرات مختلفة.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 3000,
                'temperature' => 0.8,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $content = $result['choices'][0]['message']['content'] ?? null;

                if ($content) {
                    $parsed = $this->parseAIContent($content);

                    return response()->json([
                        'success' => true,
                        'data' => $parsed
                    ]);
                }
            }

            // استخدام القوالب المحلية
            $localTemplates = $this->getLocalTemplate($request->page_type, 'regular');
            return response()->json([
                'success' => true,
                'data' => $localTemplates
            ]);
        } catch (\Exception $e) {
            Log::error('AI full page generation error', ['error' => $e->getMessage()]);

            $localTemplates = $this->getLocalTemplate($request->page_type, 'regular');
            return response()->json([
                'success' => true,
                'data' => $localTemplates
            ]);
        }
    }

    /**
     * إنشاء عنوان
     */
    public function generateTitleWithAI(Request $request)
    {
        $request->validate([
            'page_type' => 'required|string',
            'page_category' => 'required|in:regular,legal,info'
        ]);

        try {
            $apiKey = config('services.deepseek.api_key');

            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'مفتاح API غير متوفر'
                ], 500);
            }

            $templateNames = [
                'privacy' => 'سياسة الخصوصية',
                'terms' => 'الشروط والأحكام',
                'about' => 'من نحن',
                'contact' => 'اتصل بنا',
                'faq' => 'الأسئلة الشائعة',
                'custom' => 'صفحة مخصصة'
            ];

            $pageCategories = [
                'regular' => 'صفحة عادية',
                'legal' => 'صفحة قانونية',
                'info' => 'صفحة معلوماتية'
            ];

            $templateName = $templateNames[$request->page_type] ?? 'صفحة';
            $pageCategory = $pageCategories[$request->page_category];

            $prompt = "الرجاء إنشاء عنوان مناسب لـ {$templateName} من نوع {$pageCategory}";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت متخصص في إنشاء عناوين صفحات الويب.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 100,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $title = $result['choices'][0]['message']['content'] ?? null;

                if ($title) {
                    return response()->json([
                        'success' => true,
                        'title' => trim($title)
                    ]);
                }
            }

            // عناوين افتراضية
            $defaultTitles = [
                'privacy' => 'سياسة الخصوصية',
                'terms' => 'الشروط والأحكام',
                'about' => 'من نحن - تعرف على شركتنا',
                'contact' => 'اتصل بنا - نحن هنا لمساعدتك',
                'faq' => 'الأسئلة الشائعة - كل ما تريد معرفته',
                'custom' => 'صفحة جديدة'
            ];

            $title = $defaultTitles[$request->page_type] ?? 'صفحة جديدة';

            return response()->json([
                'success' => true,
                'title' => $title
            ]);
        } catch (\Exception $e) {
            Log::error('AI title generation error', ['error' => $e->getMessage()]);

            $defaultTitles = [
                'privacy' => 'سياسة الخصوصية',
                'terms' => 'الشروط والأحكام',
                'about' => 'من نحن',
                'contact' => 'اتصل بنا',
                'faq' => 'الأسئلة الشائعة',
                'custom' => 'صفحة جديدة'
            ];

            $title = $defaultTitles[$request->page_type] ?? 'صفحة جديدة';

            return response()->json([
                'success' => true,
                'title' => $title
            ]);
        }
    }

    /**
     * إنشاء محتوى
     */
    public function generateContentWithAI(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'page_type' => 'required|string',
            'page_category' => 'required|in:regular,legal,info'
        ]);

        try {
            $apiKey = config('services.deepseek.api_key');

            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'مفتاح API غير متوفر'
                ], 500);
            }

            $templateNames = [
                'privacy' => 'سياسة الخصوصية',
                'terms' => 'الشروط والأحكام',
                'about' => 'من نحن',
                'contact' => 'اتصل بنا',
                'faq' => 'الأسئلة الشائعة',
                'custom' => 'صفحة مخصصة'
            ];

            $pageCategories = [
                'regular' => 'صفحة عادية',
                'legal' => 'صفحة قانونية',
                'info' => 'صفحة معلوماتية'
            ];

            $templateName = $templateNames[$request->page_type] ?? 'صفحة';
            $pageCategory = $pageCategories[$request->page_category];

            $prompt = "الرجاء إنشاء محتوى لـ {$templateName} بعنوان: {$request->title}\n";
            $prompt .= "النوع: {$pageCategory}\n\n";
            $prompt .= "يرجى إنشاء محتوى شامل ومناسب.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت متخصص في كتابة محتوى صفحات الويب.'
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
                $content = $result['choices'][0]['message']['content'] ?? null;

                if ($content) {
                    return response()->json([
                        'success' => true,
                        'content' => trim($content)
                    ]);
                }
            }

            // محتوى افتراضي
            $defaultContent = $this->getDefaultContent($request->page_type, $request->title);

            return response()->json([
                'success' => true,
                'content' => $defaultContent
            ]);
        } catch (\Exception $e) {
            Log::error('AI content generation error', ['error' => $e->getMessage()]);

            $defaultContent = $this->getDefaultContent($request->page_type, $request->title);

            return response()->json([
                'success' => true,
                'content' => $defaultContent
            ]);
        }
    }

    /**
     * تنسيق المحتوى
     */
    public function formatContentWithAI(Request $request)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        try {
            $apiKey = config('services.deepseek.api_key');

            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'مفتاح API غير متوفر'
                ], 500);
            }

            $prompt = "الرجاء تنسيق المحتوى التالي لجعله مناسباً لصفحة ويب:\n\n{$request->content}\n\n";
            $prompt .= "يرجى تنسيقه مع العناوين والفقرات المناسبة.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت متخصص في تنسيق محتوى صفحات الويب.'
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
                $formattedContent = $result['choices'][0]['message']['content'] ?? null;

                if ($formattedContent) {
                    return response()->json([
                        'success' => true,
                        'formatted_content' => trim($formattedContent)
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'فشل في تنسيق المحتوى'
            ], 500);
        } catch (\Exception $e) {
            Log::error('AI content formatting error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تنسيق المحتوى'
            ], 500);
        }
    }

    /**
     * تدقيق لغوي
     */
    public function checkGrammarWithAI(Request $request)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        try {
            $apiKey = config('services.deepseek.api_key');

            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'مفتاح API غير متوفر'
                ], 500);
            }

            $prompt = "الرجاء تدقيق المحتوى التالي لغوياً وتصحيح الأخطاء:\n\n{$request->content}\n\n";
            $prompt .= "يرجى إرجاع المحتوى المصحح مع شرح التصحيحات.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت مدقق لغوي محترف للغة العربية.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 2500,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $checkedContent = $result['choices'][0]['message']['content'] ?? null;

                if ($checkedContent) {
                    // محاولة تحليل النتيجة
                    $parsed = $this->parseGrammarCheck($checkedContent);

                    return response()->json([
                        'success' => true,
                        'corrections' => $parsed['corrections'] ?? [],
                        'corrected_content' => $parsed['corrected_content'] ?? $request->content
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'corrections' => [],
                'corrected_content' => $request->content
            ]);
        } catch (\Exception $e) {
            Log::error('AI grammar check error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => true,
                'corrections' => [],
                'corrected_content' => $request->content
            ]);
        }
    }

    /**
     * تحسين النص
     */
    public function enhanceTextWithAI(Request $request)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        try {
            $apiKey = config('services.deepseek.api_key');

            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'مفتاح API غير متوفر'
                ], 500);
            }

            $prompt = "الرجاء تحسين النص التالي لجعله أكثر احترافية ووضوحاً:\n\n{$request->content}";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت متخصص في تحسين النصوص العربية.'
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
            Log::error('AI text enhancement error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحسين النص'
            ], 500);
        }
    }

    /**
     * إضافة قسم
     */
    public function addSectionWithAI(Request $request)
    {
        $request->validate([
            'section_type' => 'required|in:introduction,conclusion,faq,contact',
            'current_content' => 'required|string',
            'page_type' => 'required|string'
        ]);

        try {
            $apiKey = config('services.deepseek.api_key');

            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'مفتاح API غير متوفر'
                ], 500);
            }

            $sectionNames = [
                'introduction' => 'مقدمة',
                'conclusion' => 'خاتمة',
                'faq' => 'أسئلة شائعة',
                'contact' => 'معلومات اتصال'
            ];

            $sectionName = $sectionNames[$request->section_type];

            $prompt = "الرجاء إنشاء قسم {$sectionName} مناسب لصفحة من نوع {$request->page_type}.\n";
            $prompt .= "يجب أن يكون القسم متناسقاً مع المحتوى الحالي:\n\n{$request->current_content}\n\n";
            $prompt .= "يرجى إرجاع القسم الجديد فقط.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت متخصص في إنشاء أقسام صفحات الويب.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 1000,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $sectionContent = $result['choices'][0]['message']['content'] ?? null;

                if ($sectionContent) {
                    return response()->json([
                        'success' => true,
                        'section_content' => trim($sectionContent)
                    ]);
                }
            }

            // أقسام افتراضية
            $defaultSections = $this->getDefaultSection($request->section_type, $request->page_type);

            return response()->json([
                'success' => true,
                'section_content' => $defaultSections
            ]);
        } catch (\Exception $e) {
            Log::error('AI section addition error', ['error' => $e->getMessage()]);

            $defaultSections = $this->getDefaultSection($request->section_type, $request->page_type);

            return response()->json([
                'success' => true,
                'section_content' => $defaultSections
            ]);
        }
    }

    /**
     * توليد كلمات مفتاحية
     */
    public function generateKeywordsWithAI(Request $request)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        try {
            $apiKey = config('services.deepseek.api_key');

            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'مفتاح API غير متوفر'
                ], 500);
            }

            $prompt = "الرجاء استخراج 5-10 كلمات مفتاحية مناسبة من المحتوى التالي:\n\n{$request->content}\n\n";
            $prompt .= "يرجى إرجاع الكلمات المفتاحية مفصولة بفواصل.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت متخصص في استخراج الكلمات المفتاحية من المحتوى العربي.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 200,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $keywords = $result['choices'][0]['message']['content'] ?? null;

                if ($keywords) {
                    return response()->json([
                        'success' => true,
                        'keywords' => trim($keywords)
                    ]);
                }
            }

            // كلمات مفتاحية افتراضية
            $defaultKeywords = 'خصوصية, بيانات, أمان, معلومات, حماية';

            return response()->json([
                'success' => true,
                'keywords' => $defaultKeywords
            ]);
        } catch (\Exception $e) {
            Log::error('AI keywords generation error', ['error' => $e->getMessage()]);

            $defaultKeywords = 'خصوصية, بيانات, أمان, معلومات, حماية';

            return response()->json([
                'success' => true,
                'keywords' => $defaultKeywords
            ]);
        }
    }

    /**
     * تحليل محتوى الذكاء الاصطناعي
     */
    private function parseAIContent($content)
    {
        $parsed = [
            'title' => '',
            'content' => '',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => ''
        ];

        $lines = explode("\n", $content);
        $currentSection = '';

        foreach ($lines as $line) {
            $line = trim($line);

            if (str_starts_with($line, 'TITLE:')) {
                $parsed['title'] = trim(str_replace('TITLE:', '', $line));
                $currentSection = 'title';
            } elseif (str_starts_with($line, 'CONTENT:')) {
                $parsed['content'] = trim(str_replace('CONTENT:', '', $line));
                $currentSection = 'content';
            } elseif (str_starts_with($line, 'META_TITLE:')) {
                $parsed['meta_title'] = trim(str_replace('META_TITLE:', '', $line));
                $currentSection = 'meta_title';
            } elseif (str_starts_with($line, 'META_DESCRIPTION:')) {
                $parsed['meta_description'] = trim(str_replace('META_DESCRIPTION:', '', $line));
                $currentSection = 'meta_description';
            } elseif (str_starts_with($line, 'META_KEYWORDS:')) {
                $parsed['meta_keywords'] = trim(str_replace('META_KEYWORDS:', '', $line));
                $currentSection = 'meta_keywords';
            } elseif ($currentSection === 'content' && !empty($line)) {
                $parsed['content'] .= "\n" . $line;
            }
        }

        // تنظيف النتائج
        foreach ($parsed as $key => $value) {
            $parsed[$key] = trim($value);
        }

        return $parsed;
    }

    /**
     * تحليل تدقيق اللغة
     */
    private function parseGrammarCheck($content)
    {
        $result = [
            'corrections' => [],
            'corrected_content' => $content
        ];

        // يمكن تحسين هذا التحليل حسب تنسيق رد الـ API
        // هذا مثال بسيط
        if (strpos($content, 'التصحيح:') !== false) {
            $parts = explode('التصحيح:', $content);
            if (count($parts) > 1) {
                $corrections = [];
                for ($i = 1; $i < count($parts); $i++) {
                    $correctionText = $parts[$i];
                    if (strpos($correctionText, 'الشرح:') !== false) {
                        list($correction, $explanation) = explode('الشرح:', $correctionText, 2);
                        $corrections[] = [
                            'correction' => trim($correction),
                            'explanation' => trim($explanation)
                        ];
                    }
                }
                $result['corrections'] = $corrections;
            }
        }

        return $result;
    }

    /**
     * الحصول على قالب محلي
     */
    private function getLocalTemplate($template, $pageType)
    {
        $templates = [
            'privacy' => [
                'title' => 'سياسة الخصوصية',
                'content' => '<h2>سياسة الخصوصية</h2>
                <p>نحن نحترم خصوصيتك ونلتزم بحماية معلوماتك الشخصية. توضح سياسة الخصوصية هذه كيفية جمع واستخدام ومشاركة معلوماتك الشخصية.</p>
                
                <h3>جمع المعلومات</h3>
                <p>نقوم بجمع المعلومات التالية:</p>
                <ul>
                    <li>الاسم والبريد الإلكتروني</li>
                    <li>معلومات الاتصال</li>
                    <li>عنوان التسليم</li>
                    <li>معلومات الدفع</li>
                </ul>
                
                <h3>استخدام المعلومات</h3>
                <p>نستخدم معلوماتك لتقديم وتحسين خدماتنا، بما في ذلك:</p>
                <ul>
                    <li>معالجة الطلبات والمشتريات</li>
                    <li>تحسين تجربة المستخدم</li>
                    <li>إرسال تحديثات وعروض ترويجية</li>
                    <li>الرد على استفساراتك</li>
                </ul>',
                'meta_title' => 'سياسة الخصوصية - حماية بياناتك',
                'meta_description' => 'اقرأ سياسة الخصوصية الشاملة الخاصة بنا لمعرفة كيفية جمع واستخدام وحماية بياناتك الشخصية.',
                'meta_keywords' => 'سياسة الخصوصية, حماية البيانات, الأمان, المعلومات الشخصية'
            ],
            'terms' => [
                'title' => 'الشروط والأحكام',
                'content' => '<h2>الشروط والأحكام</h2>
                <p>باستخدامك لهذا الموقع أو خدماتنا، فإنك توافق على الالتزام بهذه الشروط والأحكام.</p>
                
                <h3>استخدام الموقع</h3>
                <p>يجب أن يكون استخدامك للموقع قانونياً وأخلاقياً، ويحظر:</p>
                <ul>
                    <li>انتحال شخصية الآخرين</li>
                    <li>نشر محتوى غير قانوني</li>
                    <li>التعدي على حقوق الملكية الفكرية</li>
                    <li>إعاقة عمل الموقع</li>
                </ul>
                
                <h3>الطلبات والدفع</h3>
                <p>جميع الأسعار معروضة بالعملة المحلية وتشمل الضريبة المضافة، ويمكن أن تتغير الأسعار دون إشعار مسبق.</p>',
                'meta_title' => 'الشروط والأحكام - قواعد استخدام الموقع',
                'meta_description' => 'اقرأ الشروط والأحكام الكاملة لاستخدام موقعنا وخدماتنا لضمان تجربة آمنة وممتعة.',
                'meta_keywords' => 'الشروط والأحكام, قواعد الاستخدام, سياسة الموقع, الشروط القانونية'
            ],
            'about' => [
                'title' => 'من نحن',
                'content' => '<h2>من نحن</h2>
                <p>نحن شركة/منصة متخصصة في تقديم حلول مبتكرة وعالية الجودة لعملائنا.</p>
                
                <h3>رسالتنا</h3>
                <p>نسعى لتقديم أفضل الخدمات والمنتجات التي تلبي احتياجات عملائنا وتتجاوز توقعاتهم.</p>
                
                <h3>رؤيتنا</h3>
                <p>أن نكون الخيار الأول في مجالنا من خلال التميز والابتكار والجودة.</p>
                
                <h3>قيمنا</h3>
                <ul>
                    <li>الجودة والتميز</li>
                    <li>الابتكار والتطوير</li>
                    <li>النزاهة والشفافية</li>
                    <li>رضا العملاء</li>
                </ul>',
                'meta_title' => 'من نحن - تعرف على شركتنا',
                'meta_description' => 'تعرف على شركتنا، رسالتنا، رؤيتنا، وقيمنا التي نعمل بها لخدمتك بشكل أفضل.',
                'meta_keywords' => 'من نحن, عن الشركة, تاريخ الشركة, رؤية الشركة, رسالة الشركة'
            ],
            'contact' => [
                'title' => 'اتصل بنا',
                'content' => '<h2>اتصل بنا</h2>
                <p>نحن هنا لمساعدتك، لا تتردد في التواصل معنا عبر أي من الطرق التالية:</p>
                
                <h3>معلومات الاتصال</h3>
                <ul>
                    <li><strong>العنوان:</strong> [أدخل العنوان هنا]</li>
                    <li><strong>الهاتف:</strong> [أدخل رقم الهاتف]</li>
                    <li><strong>البريد الإلكتروني:</strong> [أدخل البريد الإلكتروني]</li>
                    <li><strong>ساعات العمل:</strong> [أدخل ساعات العمل]</li>
                </ul>
                
                <h3>نموذج الاتصال</h3>
                <p>يمكنك أيضاً استخدام نموذج الاتصال أدناه للتواصل معنا:</p>
                <!-- نموذج الاتصال -->',
                'meta_title' => 'اتصل بنا - نحن هنا لمساعدتك',
                'meta_description' => 'تواصل معنا عبر الهاتف، البريد الإلكتروني، أو استخدم نموذج الاتصال للحصول على المساعدة.',
                'meta_keywords' => 'اتصل بنا, معلومات الاتصال, تواصل, دعم العملاء, خدمة العملاء'
            ],
            'faq' => [
                'title' => 'الأسئلة الشائعة',
                'content' => '<h2>الأسئلة الشائعة</h2>
                <p>إجابات على الأسئلة الأكثر شيوعاً من عملائنا:</p>
                
                <h3>الطلبات والتسليم</h3>
                <div class="faq-item">
                    <h4>كيف أقدم طلباً؟</h4>
                    <p>يمكنك تقديم طلب عبر الموقع أو التطبيق بسهولة.</p>
                </div>
                
                <div class="faq-item">
                    <h4>ما هي مدة التسليم؟</h4>
                    <p>تتراوح مدة التسليم بين 2-7 أيام عمل حسب الموقع.</p>
                </div>
                
                <h3>الدفع والأسعار</h3>
                <div class="faq-item">
                    <h4>ما هي وسائل الدفع المتاحة؟</h4>
                    <p>نقبل الدفع نقداً عند الاستلام وبطاقات الائتمان.</p>
                </div>',
                'meta_title' => 'الأسئلة الشائعة - إجابات على تساؤلاتك',
                'meta_description' => 'ابحث عن إجابات للأسئلة الأكثر شيوعاً حول خدماتنا، الطلبات، التسليم، الدفع، والمزيد.',
                'meta_keywords' => 'أسئلة شائعة, أسئلة وأجوبة, استفسارات, مساعدة, دعم'
            ]
        ];

        return $templates[$template] ?? [
            'title' => 'صفحة جديدة',
            'content' => '<h2>صفحة جديدة</h2><p>ابدأ بكتابة محتوى صفحتك هنا...</p>',
            'meta_title' => 'صفحة جديدة',
            'meta_description' => 'صفحة جديدة تم إنشاؤها',
            'meta_keywords' => 'صفحة, جديد'
        ];
    }

    /**
     * الحصول على محتوى افتراضي
     */
    private function getDefaultContent($pageType, $title)
    {
        $contents = [
            'privacy' => "<h2>{$title}</h2>
            <p>نحن نحترم خصوصيتك ونلتزم بحماية بياناتك الشخصية.</p>
            <h3>جمع البيانات</h3>
            <p>نقوم بجمع البيانات اللازمة لتقديم خدماتنا بشكل أفضل.</p>",
            'terms' => "<h2>{$title}</h2>
            <p>شروط استخدام موقعنا وخدماتنا.</p>
            <h3>المسؤولية</h3>
            <p>المستخدم مسؤول عن استخدامه الصحيح للموقع.</p>",
            'about' => "<h2>{$title}</h2>
            <p>تعرف على شركتنا وقيمنا.</p>
            <h3>رؤيتنا</h3>
            <p>نسعى للتميز في تقديم خدماتنا.</p>",
            'contact' => "<h2>{$title}</h2>
            <p>تواصل معنا عبر المعلومات التالية:</p>
            <h3>معلومات الاتصال</h3>
            <p>الهاتف: ########<br>البريد: info@example.com</p>",
            'faq' => "<h2>{$title}</h2>
            <p>إجابات على أسئلتك الشائعة.</p>
            <h3>الأسئلة العامة</h3>
            <p>س: كيف أستخدم الخدمة؟<br>ج: يمكنك التسجيل وبدء الاستخدام.</p>"
        ];

        return $contents[$pageType] ?? "<h2>{$title}</h2><p>محتوى الصفحة...</p>";
    }

    /**
     * الحصول على قسم افتراضي
     */
    private function getDefaultSection($sectionType, $pageType)
    {
        $sections = [
            'introduction' => '<h3>مقدمة</h3><p>في هذه الصفحة، نقدم لكم... نأمل أن تجدوا ما تبحثون عنه.</p>',
            'conclusion' => '<h3>خاتمة</h3><p>نشكركم لزيارة صفحتنا، ونأمل أن نكون قد قدمنا لكم المعلومات الكافية.</p>',
            'faq' => '<h3>الأسئلة الشائعة</h3>
            <div class="faq-item">
                <h4>سؤال شائع 1؟</h4>
                <p>إجابة السؤال الشائع 1.</p>
            </div>
            <div class="faq-item">
                <h4>سؤال شائع 2؟</h4>
                <p>إجابة السؤال الشائع 2.</p>
            </div>',
            'contact' => '<h3>معلومات الاتصال</h3>
            <p><strong>للتواصل معنا:</strong></p>
            <ul>
                <li>البريد الإلكتروني: info@example.com</li>
                <li>الهاتف: ########</li>
                <li>العنوان: [أدخل العنوان هنا]</li>
            </ul>'
        ];

        return $sections[$sectionType] ?? '<p>قسم إضافي...</p>';
    }
}
