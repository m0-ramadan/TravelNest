@extends('Admin.layout.master')

@section('title', 'تعديل صفحة ثابتة بالذكاء الاصطناعي')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #696cff;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --dark-bg: #1e1e2d;
            --dark-card: #2b3b4c;
        }

        body {
            font-family: "Cairo", sans-serif !important;
            background: var(--dark-bg);
            color: #fff;
        }

        .card {
            background: var(--dark-card);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .card-header {
            background: var(--primary-gradient);
            color: white;
            padding: 25px 30px;
            border-radius: 15px 15px 0 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-control,
        .form-select {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary-color);
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.25);
        }

        .form-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            margin-bottom: 8px;
        }

        /* أزرار الذكاء الاصطناعي */
        .ai-button {
            background: rgba(105, 108, 255, 0.1);
            border: 1px solid rgba(105, 108, 255, 0.3);
            color: var(--primary-color);
            padding: 8px 15px;
            border-radius: 8px;
            transition: all 0.3s;
            font-size: 14px;
        }

        .ai-button:hover {
            background: rgba(105, 108, 255, 0.2);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .ai-button-primary {
            background: var(--primary-gradient);
            color: white;
            border: none;
            font-weight: 600;
        }

        .ai-button-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4a9a 100%);
        }

        /* تبادل اللغات */
        .language-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .language-tab {
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            transition: all 0.3s;
        }

        .language-tab:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .language-tab.active {
            background: var(--primary-gradient);
            color: white;
            border-color: var(--primary-color);
        }

        .language-content {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .language-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* أزرار الإجراءات السريعة */
        .quick-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .quick-action-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
            padding: 10px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .quick-action-btn:hover {
            background: rgba(105, 108, 255, 0.1);
            border-color: var(--primary-color);
            color: #fff;
            transform: translateY(-2px);
        }

        /* محرر النصوص */
        .note-editor {
            background: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            border-radius: 8px !important;
        }

        .note-toolbar {
            background: rgba(255, 255, 255, 0.08) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            border-radius: 8px 8px 0 0 !important;
        }

        .note-editing-area {
            background: rgba(255, 255, 255, 0.05) !important;
        }

        .ai-content-buttons {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 100;
            display: flex;
            gap: 5px;
        }

        .ai-content-buttons .btn {
            background: rgba(105, 108, 255, 0.1);
            border: 1px solid rgba(105, 108, 255, 0.3);
            color: var(--primary-color);
            font-size: 12px;
            padding: 5px 10px;
        }

        .ai-content-buttons .btn:hover {
            background: rgba(105, 108, 255, 0.2);
        }

        /* التحميل والمؤشرات */
        .ai-loading {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .ai-loading-content {
            background: var(--dark-card);
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            max-width: 500px;
            width: 90%;
        }

        .ai-progress-bar {
            width: 100%;
            height: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            overflow: hidden;
            margin: 20px 0;
        }

        .ai-progress-fill {
            height: 100%;
            background: var(--primary-gradient);
            width: 0%;
            transition: width 0.3s ease;
        }

        /* معاينة المحتوى */
        .preview-content {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 20px;
            min-height: 200px;
            max-height: 400px;
            overflow-y: auto;
            margin-top: 20px;
        }

        .preview-content h1,
        .preview-content h2,
        .preview-content h3 {
            color: #fff;
            margin-bottom: 15px;
        }

        .preview-content p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.8;
            margin-bottom: 15px;
        }

        /* مؤشرات الأحرف */
        .char-counter {
            text-align: left;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 5px;
        }

        .char-counter.warning {
            color: #ffc107;
        }

        .char-counter.danger {
            color: #dc3545;
        }

        /* تحسين SEO */
        .seo-score {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .seo-score-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .seo-score-value {
            font-size: 24px;
            font-weight: bold;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .seo-progress {
            height: 5px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
            overflow: hidden;
        }

        .seo-progress-fill {
            height: 100%;
            background: var(--primary-gradient);
            width: 0%;
            transition: width 0.5s ease;
        }

        /* رسائل التنبيه */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast {
            background: var(--dark-card);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 15px;
            min-width: 300px;
            max-width: 400px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast-success {
            border-left: 4px solid #2ecc71;
        }

        .toast-error {
            border-left: 4px solid #dc3545;
        }

        .toast-info {
            border-left: 4px solid var(--primary-color);
        }

        /* تبويبات المحتوى */
        .nav-tabs {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-tabs .nav-link {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
            border-radius: 8px 8px 0 0;
            margin-right: 5px;
            padding: 10px 20px;
        }

        .nav-tabs .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .nav-tabs .nav-link.active {
            background: var(--primary-gradient);
            color: white;
            border-color: var(--primary-color);
        }

        .tab-content {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0 0 8px 8px;
            padding: 20px;
            margin-top: -1px;
        }

        /* أزرار الحفظ */
        .save-buttons {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4a9a 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(105, 108, 255, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}">الرئيسية</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.static-pages.index') }}">الصفحات الثابتة</a>
                </li>
                <li class="breadcrumb-item active">تعديل: {{ $page->title }}</li>
            </ol>
        </nav>

        <!-- مؤشر التحميل -->
        <div class="ai-loading" id="aiLoading">
            <div class="ai-loading-content">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">جاري التحميل...</span>
                </div>
                <h4 id="aiLoadingTitle" class="mb-2">جاري المعالجة</h4>
                <p id="aiLoadingMessage" class="text-muted mb-3">يرجى الانتظار...</p>
                <div class="ai-progress-bar">
                    <div class="ai-progress-fill" id="aiProgressFill"></div>
                </div>
                <button class="btn btn-outline-secondary mt-3" onclick="cancelAIProcess()">
                    <i class="fas fa-times me-1"></i> إلغاء
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">
                                    <i class="fas fa-robot me-2"></i>
                                    تعديل صفحة ثابتة بالذكاء الاصطناعي
                                </h5>
                                <small class="opacity-75">تحسين المحتوى باستخدام الذكاء الاصطناعي</small>
                            </div>
                            <div>
                                <a href="{{ route('admin.static-pages.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-right me-2"></i>رجوع للقائمة
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- رسائل التنبيه -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- إعدادات الذكاء الاصطناعي -->
                        <div class="seo-score mb-4">
                            <div class="seo-score-title">
                                <h6 class="mb-0">إعدادات الذكاء الاصطناعي</h6>
                                <span class="seo-score-value" id="seoScore">75%</span>
                            </div>
                            <div class="seo-progress">
                                <div class="seo-progress-fill" id="seoProgressFill" style="width: 75%"></div>
                            </div>
                        </div>

                        <!-- الإجراءات السريعة -->
                        <div class="quick-actions mb-4">
                            <div class="quick-action-btn" onclick="enhanceWithAI('all')">
                                <i class="fas fa-magic"></i>
                                <span>تحسين كامل</span>
                            </div>
                            <div class="quick-action-btn" onclick="generateSEO()">
                                <i class="fas fa-search"></i>
                                <span>إنشاء SEO</span>
                            </div>
                            <div class="quick-action-btn" onclick="translatePage()">
                                <i class="fas fa-language"></i>
                                <span>ترجمة تلقائية</span>
                            </div>
                            <div class="quick-action-btn" onclick="optimizeContent()">
                                <i class="fas fa-bolt"></i>
                                <span>تحسين المحتوى</span>
                            </div>
                        </div>

                        <form action="{{ route('admin.static-pages.update', $page->id) }}" method="POST" id="editPageForm">
                            @csrf
                            @method('PUT')

                            <!-- تبادل اللغات -->
                            <div class="language-tabs mb-4">
                                <button type="button" class="language-tab active" data-lang="ar">
                                    <i class="fas fa-language me-2"></i>العربية
                                </button>
                                <button type="button" class="language-tab" data-lang="en">
                                    <i class="fas fa-globe me-2"></i>English
                                </button>
                            </div>

                            <!-- المحتوى العربي -->
                            <div class="language-content active" id="content-ar">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="title_ar" class="form-label">
                                            <i class="fas fa-heading me-2"></i>عنوان الصفحة
                                        </label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                id="title_ar" name="title" value="{{ old('title', $page->title) }}"
                                                placeholder="أدخل عنوان الصفحة" required>
                                            <div class="ai-content-buttons">
                                                <button type="button" class="btn btn-sm" onclick="enhanceTitle()">
                                                    <i class="fas fa-wand-magic-sparkles"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="char-counter" id="titleCounter">
                                            عدد الأحرف: <span id="titleChars">{{ strlen($page->title) }}</span>
                                        </div>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="content_ar" class="form-label">
                                            <i class="fas fa-edit me-2"></i>محتوى الصفحة
                                        </label>
                                        <div class="position-relative">
                                            <textarea class="form-control @error('content') is-invalid @enderror summernote" id="content_ar" name="content"
                                                rows="12">{{ old('content', $page->content) }}</textarea>
                                            <div class="ai-content-buttons">
                                                <button type="button" class="btn btn-sm" onclick="enhanceContent()">
                                                    <i class="fas fa-wand-magic-sparkles"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm" onclick="expandContent()">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm" onclick="simplifyContent()">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="char-counter" id="contentCounter">
                                            عدد الأحرف: <span
                                                id="contentChars">{{ strlen(strip_tags($page->content)) }}</span>
                                        </div>
                                        @error('content')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- المحتوى الإنجليزي -->
                            <div class="language-content" id="content-en">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="title_en" class="form-label">
                                            <i class="fas fa-heading me-2"></i>Page Title
                                        </label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control" id="title_en" value=""
                                                placeholder="Enter page title">
                                            <div class="ai-content-buttons">
                                                <button type="button" class="btn btn-sm" onclick="translateTitle()">
                                                    <i class="fas fa-language"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="content_en" class="form-label">
                                            <i class="fas fa-edit me-2"></i>Page Content
                                        </label>
                                        <div class="position-relative">
                                            <textarea class="form-control summernote" id="content_en" rows="12" placeholder="Enter page content"></textarea>
                                            <div class="ai-content-buttons">
                                                <button type="button" class="btn btn-sm" onclick="translateContent()">
                                                    <i class="fas fa-language"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm" onclick="enhanceContent('en')">
                                                    <i class="fas fa-wand-magic-sparkles"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SEO -->
                            <div class="mt-4">
                                <h5 class="mb-3">
                                    <i class="fas fa-search me-2"></i>
                                    تحسين محركات البحث (SEO)
                                </h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="meta_title" class="form-label">
                                            <i class="fas fa-search me-2"></i>Meta Title
                                        </label>
                                        <div class="position-relative">
                                            <input type="text"
                                                class="form-control @error('meta_title') is-invalid @enderror"
                                                id="meta_title" name="meta_title"
                                                value="{{ old('meta_title', $page->meta_title) }}"
                                                placeholder="عنوان الصفحة لمحركات البحث">
                                            <div class="ai-content-buttons">
                                                <button type="button" class="btn btn-sm" onclick="generateMetaTitle()">
                                                    <i class="fas fa-robot"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="char-counter" id="metaTitleCounter">
                                            عدد الأحرف: <span id="metaTitleChars">{{ strlen($page->meta_title) }}</span>
                                            (مثالي: 50-60 حرف)
                                        </div>
                                        @error('meta_title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="slug" class="form-label">
                                            <i class="fas fa-link me-2"></i>الرابط (Slug)
                                        </label>
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                            id="slug" name="slug" value="{{ old('slug', $page->slug) }}"
                                            placeholder="الرابط التلقائي">
                                        @error('slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">
                                        <i class="fas fa-align-left me-2"></i>Meta Description
                                    </label>
                                    <div class="position-relative">
                                        <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                            name="meta_description" rows="4" placeholder="وصف الصفحة لمحركات البحث">{{ old('meta_description', $page->meta_description) }}</textarea>
                                        <div class="ai-content-buttons">
                                            <button type="button" class="btn btn-sm"
                                                onclick="generateMetaDescription()">
                                                <i class="fas fa-robot"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="char-counter" id="metaDescCounter">
                                        عدد الأحرف: <span id="metaDescChars">{{ strlen($page->meta_description) }}</span>
                                        (مثالي: 150-160 حرف)
                                    </div>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="meta_keywords" class="form-label">
                                        <i class="fas fa-tags me-2"></i>Meta Keywords
                                    </label>
                                    <input type="text"
                                        class="form-control @error('meta_keywords') is-invalid @enderror"
                                        id="meta_keywords" name="meta_keywords"
                                        value="{{ old('meta_keywords', $page->meta_keywords) }}"
                                        placeholder="كلمات مفتاحية مفصولة بفواصل">
                                    @error('meta_keywords')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- الحالة -->
                            <div class="row mt-4">
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-toggle-on me-2"></i>حالة الصفحة
                                    </label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="active" {{ $page->status == 'active' ? 'selected' : '' }}>
                                            <i class="fas fa-check-circle me-2"></i>نشط
                                        </option>
                                        <option value="inactive" {{ $page->status == 'inactive' ? 'selected' : '' }}>
                                            <i class="fas fa-times-circle me-2"></i>غير نشط
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- معاينة المحتوى -->
                            <div class="mt-4">
                                <h5 class="mb-3">
                                    <i class="fas fa-eye me-2"></i>
                                    معاينة المحتوى
                                </h5>
                                <div class="preview-content" id="pagePreview">
                                    @if ($page->content)
                                        {!! $page->content !!}
                                    @else
                                        <p class="text-muted">سيظهر محتوى الصفحة هنا بعد التعديل...</p>
                                    @endif
                                </div>
                            </div>

                            <!-- أزرار الحفظ -->
                            <div class="save-buttons">
                                <a href="{{ route('admin.static-pages.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>إلغاء
                                </a>
                                <button type="button" class="btn btn-primary ai-button-primary" onclick="saveWithAI()">
                                    <i class="fas fa-robot me-2"></i>حفظ مع الذكاء الاصطناعي
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>حفظ التعديلات
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            // تهيئة Summernote
            $('.summernote').summernote({
                height: 300,
                lang: 'ar-AR',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                placeholder: 'اكتب محتوى الصفحة هنا...',
                callbacks: {
                    onChange: function(contents) {
                        updateCharCount('content', contents);
                        updatePreview();
                    }
                }
            });

            // تحديث تعداد الأحرف
            $('#title_ar, #meta_title, #meta_description').on('keyup', function() {
                const field = $(this).attr('id');
                const text = $(this).val();
                updateCharCount(field, text);
                updateSEOscore();
            });

            // تحديث المعاينة
            $('#title_ar, #content_ar').on('keyup change', function() {
                updatePreview();
            });

            // تبادل اللغات
            $('.language-tab').on('click', function() {
                const lang = $(this).data('lang');

                // تحديث الألسنة النشطة
                $('.language-tab').removeClass('active');
                $(this).addClass('active');

                // إظهار المحتوى المناسب
                $('.language-content').removeClass('active');
                $(`#content-${lang}`).addClass('active');
            });

            // حساب وتحسين SEO
            updateSEOscore();
        });

        // دالة تحديث تعداد الأحرف
        function updateCharCount(field, text) {
            const chars = text ? text.length : 0;

            switch (field) {
                case 'title_ar':
                    $('#titleChars').text(chars);
                    $('#titleCounter').toggleClass('warning', chars > 60);
                    $('#titleCounter').toggleClass('danger', chars > 70);
                    break;

                case 'content_ar':
                    const cleanText = text ? text.replace(/<[^>]*>/g, '') : '';
                    $('#contentChars').text(cleanText.length);
                    break;

                case 'meta_title':
                    $('#metaTitleChars').text(chars);
                    $('#metaTitleCounter').toggleClass('warning', chars > 60);
                    $('#metaTitleCounter').toggleClass('danger', chars > 70);
                    break;

                case 'meta_description':
                    $('#metaDescChars').text(chars);
                    $('#metaDescCounter').toggleClass('warning', chars > 160);
                    $('#metaDescCounter').toggleClass('danger', chars > 180);
                    break;
            }
        }

        // دالة تحديث المعاينة
        function updatePreview() {
            const title = $('#title_ar').val() || 'عنوان الصفحة';
            const content = $('#content_ar').val() || 'محتوى الصفحة...';

            const previewHtml = `
                <h1>${title}</h1>
                ${content}
            `;

            $('#pagePreview').html(previewHtml);
        }

        // دالة تحديث درجة SEO
        function updateSEOscore() {
            let score = 100;

            // فحص عنوان الصفحة
            const titleLength = $('#title_ar').val().length;
            if (titleLength < 30) score -= 20;
            else if (titleLength > 70) score -= 10;

            // فحص عنوان SEO
            const metaTitleLength = $('#meta_title').val().length;
            if (metaTitleLength < 50) score -= 15;
            else if (metaTitleLength > 60) score -= 10;

            // فحص وصف SEO
            const metaDescLength = $('#meta_description').val().length;
            if (metaDescLength < 140) score -= 15;
            else if (metaDescLength > 160) score -= 10;

            // فحص المحتوى
            const contentLength = $('#content_ar').val().replace(/<[^>]*>/g, '').length;
            if (contentLength < 300) score -= 20;
            else if (contentLength < 500) score -= 10;

            // تحديث العرض
            score = Math.max(0, Math.min(100, score));
            $('#seoScore').text(score + '%');
            $('#seoProgressFill').css('width', score + '%');
        }

        // ============================================
        // دوال الذكاء الاصطناعي
        // ============================================

        let aiProcessActive = false;

        // عرض نافذة التحميل
        function showLoading(title, message) {
            $('#aiLoadingTitle').text(title);
            $('#aiLoadingMessage').text(message);
            $('#aiProgressFill').css('width', '0%');
            $('#aiLoading').show();
            aiProcessActive = true;
        }

        // إخفاء نافذة التحميل
        function hideLoading() {
            $('#aiLoading').hide();
            aiProcessActive = false;
        }

        // تحديث شريط التقدم
        function updateProgress(percentage) {
            $('#aiProgressFill').css('width', percentage + '%');
        }

        // إلغاء العملية
        function cancelAIProcess() {
            if (aiProcessActive) {
                hideLoading();
                showToast('تم الإلغاء', 'تم إلغاء العملية', 'info');
            }
        }

        // عرض رسائل التنبيه
        function showToast(title, message, type = 'info') {
            const toastId = 'toast-' + Date.now();
            const toastHtml = `
                <div id="${toastId}" class="toast-container">
                    <div class="toast toast-${type}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>${title}</strong>
                                <p class="mb-0">${message}</p>
                            </div>
                            <button type="button" class="btn-close btn-close-white" onclick="$(this).closest('.toast-container').remove()"></button>
                        </div>
                    </div>
                </div>
            `;

            $('body').append(toastHtml);

            // إزالة الرسالة بعد 5 ثوانٍ
            setTimeout(() => {
                $(`#${toastId}`).remove();
            }, 5000);
        }

        // تحسين العنوان
        function enhanceTitle() {
            const currentTitle = $('#title_ar').val();

            if (!currentTitle) {
                showToast('تحذير', 'الرجاء إدخال عنوان أولاً', 'warning');
                return;
            }

            showLoading('تحسين العنوان', 'جاري تحسين العنوان باستخدام الذكاء الاصطناعي...');

            $.ajax({
                url: '{{ route('admin.static-pages.ai.enhance-title') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    text: currentTitle,
                    type: 'page_title',
                    action: 'enhance'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $('#title_ar').val(response.enhanced_text);
                            updateCharCount('title_ar', response.enhanced_text);
                            updateSEOscore();
                            showToast('تم التحسين', 'تم تحسين العنوان بنجاح', 'success');
                        }, 500);
                    } else {
                        hideLoading();
                        showToast('خطأ', response.message || 'حدث خطأ أثناء التحسين', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    console.error('خطأ AJAX:', error);
                    showToast('خطأ', 'حدث خطأ في الاتصال بالخادم', 'error');
                }
            });
        }

        // تحسين المحتوى
        function enhanceContent() {
            const currentContent = $('#content_ar').val();

            if (!currentContent) {
                showToast('تحذير', 'الرجاء إدخال محتوى أولاً', 'warning');
                return;
            }

            showLoading('تحسين المحتوى', 'جاري تحسين المحتوى باستخدام الذكاء الاصطناعي...');
            updateProgress(30);

            $.ajax({
                url: '{{ route('admin.static-pages.ai.enhance-content') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    text: currentContent,
                    type: 'page_content',
                    action: 'enhance'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $('#content_ar').summernote('code', response.enhanced_text);
                            updateCharCount('content_ar', response.enhanced_text);
                            updatePreview();
                            updateSEOscore();
                            showToast('تم التحسين', 'تم تحسين المحتوى بنجاح', 'success');
                        }, 500);
                    } else {
                        hideLoading();
                        showToast('خطأ', response.message || 'حدث خطأ أثناء التحسين', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    console.error('خطأ AJAX:', error);
                    showToast('خطأ', 'حدث خطأ في الاتصال بالخادم', 'error');
                }
            });
        }

        // توسيع المحتوى
        function expandContent() {
            const currentContent = $('#content_ar').val();

            if (!currentContent) {
                showToast('تحذير', 'الرجاء إدخال محتوى أولاً', 'warning');
                return;
            }

            showLoading('توسيع المحتوى', 'جاري توسيع المحتوى باستخدام الذكاء الاصطناعي...');

            $.ajax({
                url: '{{ route('admin.static-pages.ai.expand-content') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    text: currentContent,
                    action: 'expand'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $('#content_ar').summernote('code', response.expanded_text);
                            updateCharCount('content_ar', response.expanded_text);
                            updatePreview();
                            updateSEOscore();
                            showToast('تم التوسيع', 'تم توسيع المحتوى بنجاح', 'success');
                        }, 500);
                    } else {
                        hideLoading();
                        showToast('خطأ', response.message || 'حدث خطأ أثناء التوسيع', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    console.error('خطأ AJAX:', error);
                    showToast('خطأ', 'حدث خطأ في الاتصال بالخادم', 'error');
                }
            });
        }

        // تبسيط المحتوى
        function simplifyContent() {
            const currentContent = $('#content_ar').val();

            if (!currentContent) {
                showToast('تحذير', 'الرجاء إدخال محتوى أولاً', 'warning');
                return;
            }

            showLoading('تبسيط المحتوى', 'جاري تبسيط المحتوى باستخدام الذكاء الاصطناعي...');

            $.ajax({
                url: '{{ route('admin.static-pages.ai.simplify-content') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    text: currentContent,
                    action: 'simplify'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $('#content_ar').summernote('code', response.simplified_text);
                            updateCharCount('content_ar', response.simplified_text);
                            updatePreview();
                            showToast('تم التبسيط', 'تم تبسيط المحتوى بنجاح', 'success');
                        }, 500);
                    } else {
                        hideLoading();
                        showToast('خطأ', response.message || 'حدث خطأ أثناء التبسيط', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    console.error('خطأ AJAX:', error);
                    showToast('خطأ', 'حدث خطأ في الاتصال بالخادم', 'error');
                }
            });
        }

        // ترجمة العنوان
        function translateTitle() {
            const arabicTitle = $('#title_ar').val();

            if (!arabicTitle) {
                showToast('تحذير', 'الرجاء إدخال العنوان العربي أولاً', 'warning');
                return;
            }

            showLoading('ترجمة العنوان', 'جاري ترجمة العنوان إلى الإنجليزية...');

            $.ajax({
                url: '{{ route('admin.static-pages.ai.translate') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    text: arabicTitle,
                    target_lang: 'en'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $('#title_en').val(response.translated_text);
                            showToast('تم الترجمة', 'تمت ترجمة العنوان بنجاح', 'success');
                        }, 500);
                    } else {
                        hideLoading();
                        showToast('خطأ', response.message || 'حدث خطأ أثناء الترجمة', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    console.error('خطأ AJAX:', error);
                    showToast('خطأ', 'حدث خطأ في الاتصال بالخادم', 'error');
                }
            });
        }

        // ترجمة المحتوى
        function translateContent() {
            const arabicContent = $('#content_ar').val();

            if (!arabicContent) {
                showToast('تحذير', 'الرجاء إدخال المحتوى العربي أولاً', 'warning');
                return;
            }

            showLoading('ترجمة المحتوى', 'جاري ترجمة المحتوى إلى الإنجليزية...');
            updateProgress(30);

            $.ajax({
                url: '{{ route('admin.static-pages.ai.translate-content') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    text: arabicContent,
                    target_lang: 'en'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $('#content_en').summernote('code', response.translated_text);
                            showToast('تم الترجمة', 'تمت ترجمة المحتوى بنجاح', 'success');
                        }, 500);
                    } else {
                        hideLoading();
                        showToast('خطأ', response.message || 'حدث خطأ أثناء الترجمة', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    console.error('خطأ AJAX:', error);
                    showToast('خطأ', 'حدث خطأ في الاتصال بالخادم', 'error');
                }
            });
        }

        // إنشاء عنوان SEO
        function generateMetaTitle() {
            const pageTitle = $('#title_ar').val();

            if (!pageTitle) {
                showToast('تحذير', 'الرجاء إدخال عنوان الصفحة أولاً', 'warning');
                return;
            }

            showLoading('إنشاء عنوان SEO', 'جاري إنشاء عنوان SEO محسن...');

            $.ajax({
                url: '{{ route('admin.static-pages.ai.generate-meta-title') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    title: pageTitle
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $('#meta_title').val(response.meta_title);
                            updateCharCount('meta_title', response.meta_title);
                            updateSEOscore();
                            showToast('تم الإنشاء', 'تم إنشاء عنوان SEO بنجاح', 'success');
                        }, 500);
                    } else {
                        hideLoading();
                        showToast('خطأ', response.message || 'حدث خطأ أثناء الإنشاء', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    console.error('خطأ AJAX:', error);
                    showToast('خطأ', 'حدث خطأ في الاتصال بالخادم', 'error');
                }
            });
        }

        // إنشاء وصف SEO
        function generateMetaDescription() {
            const pageContent = $('#content_ar').val();

            if (!pageContent) {
                showToast('تحذير', 'الرجاء إدخال محتوى الصفحة أولاً', 'warning');
                return;
            }

            showLoading('إنشاء وصف SEO', 'جاري إنشاء وصف SEO محسن...');

            $.ajax({
                url: '{{ route('admin.static-pages.ai.generate-meta-description') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    content: pageContent,
                    max_length: 160
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $('#meta_description').val(response.meta_description);
                            updateCharCount('meta_description', response.meta_description);
                            updateSEOscore();
                            showToast('تم الإنشاء', 'تم إنشاء وصف SEO بنجاح', 'success');
                        }, 500);
                    } else {
                        hideLoading();
                        showToast('خطأ', response.message || 'حدث خطأ أثناء الإنشاء', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    console.error('خطأ AJAX:', error);
                    showToast('خطأ', 'حدث خطأ في الاتصال بالخادم', 'error');
                }
            });
        }

        // تحسين كامل
        function enhanceWithAI(type) {
            showLoading('تحسين كامل', 'جاري تحسين الصفحة باستخدام الذكاء الاصطناعي...');

            // تسلسل عمليات التحسين
            setTimeout(() => {
                updateProgress(20);
                enhanceTitle();
            }, 1000);

            setTimeout(() => {
                updateProgress(40);
                enhanceContent();
            }, 3000);

            setTimeout(() => {
                updateProgress(60);
                generateMetaTitle();
            }, 5000);

            setTimeout(() => {
                updateProgress(80);
                generateMetaDescription();
            }, 7000);

            setTimeout(() => {
                updateProgress(100);
                setTimeout(() => {
                    hideLoading();
                    showToast('تم التحسين الكامل', 'تم تحسين الصفحة بالكامل بنجاح', 'success');
                }, 1000);
            }, 9000);
        }

        // إنشاء SEO
        function generateSEO() {
            showLoading('إنشاء SEO', 'جاري إنشاء محتوى SEO محسن...');
            updateProgress(30);

            // إنشاء العنوان والوصف معاً
            setTimeout(() => {
                updateProgress(50);
                generateMetaTitle();
            }, 1000);

            setTimeout(() => {
                updateProgress(80);
                generateMetaDescription();
            }, 3000);

            setTimeout(() => {
                updateProgress(100);
                setTimeout(() => {
                    hideLoading();
                    showToast('تم إنشاء SEO', 'تم إنشاء محتوى SEO بنجاح', 'success');
                }, 500);
            }, 5000);
        }

        // ترجمة الصفحة
        function translatePage() {
            showLoading('ترجمة الصفحة', 'جاري ترجمة الصفحة كاملة...');

            setTimeout(() => {
                updateProgress(30);
                translateTitle();
            }, 1000);

            setTimeout(() => {
                updateProgress(70);
                translateContent();
            }, 3000);

            setTimeout(() => {
                updateProgress(100);
                setTimeout(() => {
                    hideLoading();
                    showToast('تم الترجمة', 'تمت ترجمة الصفحة بنجاح', 'success');
                }, 500);
            }, 5000);
        }

        // تحسين المحتوى
        function optimizeContent() {
            showLoading('تحسين المحتوى', 'جاري تحسين محتوى الصفحة...');

            setTimeout(() => {
                updateProgress(40);
                enhanceContent();
            }, 1000);

            setTimeout(() => {
                updateProgress(70);
                expandContent();
            }, 3000);

            setTimeout(() => {
                updateProgress(100);
                setTimeout(() => {
                    hideLoading();
                    showToast('تم التحسين', 'تم تحسين المحتوى بنجاح', 'success');
                }, 500);
            }, 5000);
        }

        // حفظ مع الذكاء الاصطناعي
        function saveWithAI() {
            Swal.fire({
                title: 'حفظ مع الذكاء الاصطناعي',
                text: 'سيتم تحسين الصفحة ثم حفظها. هل تريد المتابعة؟',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'نعم، احفظ',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('حفظ الصفحة', 'جاري تحسين وحفظ الصفحة...');

                    // تسلسل عمليات التحسين ثم الحفظ
                    setTimeout(() => {
                        updateProgress(25);
                        enhanceTitle();
                    }, 1000);

                    setTimeout(() => {
                        updateProgress(50);
                        generateSEO();
                    }, 3000);

                    setTimeout(() => {
                        updateProgress(75);
                        updateSEOscore();
                    }, 6000);

                    setTimeout(() => {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $('#editPageForm').submit();
                        }, 1000);
                    }, 8000);
                }
            });
        }
    </script>
@endsection
