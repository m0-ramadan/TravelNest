@extends('Admin.layout.master')

@section('title', 'إنشاء مقال جديد بالذكاء الاصطناعي')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
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

        /* أدوات الذكاء الاصطناعي */
        .ai-tools {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .ai-tool-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.8);
            cursor: pointer;
            transition: all 0.3s;
            margin: 5px;
        }

        .ai-tool-btn:hover {
            background: rgba(105, 108, 255, 0.1);
            border-color: var(--primary-color);
            color: #fff;
        }

        /* إدخال الذكاء الاصطناعي */
        .ai-prompt-input {
            position: relative;
            margin-bottom: 20px;
        }

        .ai-prompt-input textarea {
            width: 100%;
            padding: 15px 100px 15px 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: #fff;
            min-height: 100px;
            resize: vertical;
        }

        .ai-prompt-input button {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--primary-gradient);
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
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

        /* اللغات */
        .languages-container {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .language-badge {
            background: rgba(105, 108, 255, 0.1);
            border: 1px solid rgba(105, 108, 255, 0.3);
            color: var(--primary-color);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
        }

        /* معاينة المحتوى */
        .preview-content {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 20px;
            min-height: 100px;
            max-height: 300px;
            overflow-y: auto;
            margin-top: 10px;
        }

        .preview-content h1,
        .preview-content h2,
        .preview-content h3 {
            color: #fff;
            margin-bottom: 15px;
        }

        .preview-content p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            margin-bottom: 10px;
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

        /* تبويبات اللغات */
        .language-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .language-tab {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .language-tab:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .language-tab.active {
            background: var(--primary-gradient);
            color: white;
            border-color: var(--primary-color);
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

        /* مؤشر التحميل */
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

        /* معاينة الصورة */
        .image-preview {
            width: 100%;
            max-width: 300px;
            height: 200px;
            border: 2px dashed rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 10px;
            display: none;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Switch Button */
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
            background-color: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* Select2 تعديلات */
        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #fff;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: rgba(105, 108, 255, 0.2);
            border-color: rgba(105, 108, 255, 0.3);
            color: #fff;
        }

        /* نظام التبويبات */
        .article-tabs {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
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
                    <a href="{{ route('admin.articles.index') }}">المقالات</a>
                </li>
                <li class="breadcrumb-item active">إنشاء مقال جديد بالذكاء الاصطناعي</li>
            </ol>
        </nav>

        <!-- مؤشر التحميل -->
        <div class="ai-loading" id="aiLoading">
            <div class="ai-loading-content">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">جاري التحميل...</span>
                </div>
                <h4 id="aiLoadingTitle" class="mb-2">جاري المعالجة</h4>
                <p id="aiLoadingMessage" class="text-muted mb-3">جاري إنشاء المقال بجميع اللغات...</p>
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
                                    إنشاء مقال جديد بالذكاء الاصطناعي
                                </h5>
                                <small class="opacity-75">أنشئ مقالاً جديداً باستخدام الذكاء الاصطناعي</small>
                            </div>
                            <div>
                                <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">
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

                        <!-- أدوات الذكاء الاصطناعي -->
                        <div class="ai-tools mb-4">
                            <h6 class="mb-3">
                                <i class="fas fa-magic me-2"></i>
                                أدوات الذكاء الاصطناعي
                            </h6>
                            <div class="d-flex flex-wrap">
                                <div class="ai-tool-btn" onclick="generateFullArticle()">
                                    <i class="fas fa-robot"></i>
                                    <span>إنشاء مقال كامل</span>
                                </div>
                                <div class="ai-tool-btn" onclick="enhanceWithAI()">
                                    <i class="fas fa-wand-magic-sparkles"></i>
                                    <span>تحسين النص</span>
                                </div>
                                <div class="ai-tool-btn" onclick="generateSEO()">
                                    <i class="fas fa-search"></i>
                                    <span>إنشاء SEO</span>
                                </div>
                                <div class="ai-tool-btn" onclick="generateExcerpt()">
                                    <i class="fas fa-align-left"></i>
                                    <span>إنشاء ملخص</span>
                                </div>
                            </div>
                        </div>

                        <!-- اللغات المتاحة -->
                        <div class="languages-container mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">اللغات المتاحة</h6>
                                    <p class="text-muted mb-0">سيتم إنشاء المقال بجميع اللغات المتاحة</p>
                                </div>
                                <div class="d-flex gap-2">
                                    @foreach ($languages as $language)
                                        <span class="language-badge">{{ $language->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <form id="articleForm" action="{{ route('admin.articles.store-with-ai') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <ul class="nav nav-tabs mb-4" id="articleTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="content-tab" data-bs-toggle="tab"
                                        data-bs-target="#content" type="button" role="tab">
                                        <i class="fas fa-edit me-2"></i>المحتوى
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="settings-tab" data-bs-toggle="tab"
                                        data-bs-target="#settings" type="button" role="tab">
                                        <i class="fas fa-cog me-2"></i>الإعدادات
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo"
                                        type="button" role="tab">
                                        <i class="fas fa-search me-2"></i>تحسين محركات البحث
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="preview-tab" data-bs-toggle="tab"
                                        data-bs-target="#preview" type="button" role="tab">
                                        <i class="fas fa-eye me-2"></i>معاينة
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="articleTabsContent">
                                <!-- تبويب المحتوى -->
                                <div class="tab-pane fade show active" id="content" role="tabpanel">
                                    <!-- تبويبات اللغات -->
                                    <div class="language-tabs mb-4">
                                        @foreach ($languages as $language)
                                            <button type="button" class="language-tab {{ $loop->first ? 'active' : '' }}"
                                                data-lang="{{ $language->code }}">
                                                <i class="fas fa-language me-2"></i>{{ $language->name }}
                                            </button>
                                        @endforeach
                                    </div>

                                    <!-- محتوى اللغة العربية -->
                                    @foreach ($languages as $language)
                                        <div class="language-content {{ $loop->first ? 'active' : '' }}"
                                            id="content-{{ $language->code }}">
                                            <div class="row">
                                                <!-- العنوان -->
                                                <div class="col-md-12 mb-3">
                                                    <label for="title_{{ $language->code }}" class="form-label">
                                                        <i class="fas fa-heading me-2"></i>عنوان المقال
                                                        @if ($language->code == 'ar')
                                                            <span class="text-danger">*</span>
                                                        @endif
                                                    </label>
                                                    <div class="position-relative">
                                                        <input type="text"
                                                            class="form-control @error('title_' . $language->code) is-invalid @enderror"
                                                            id="title_{{ $language->code }}"
                                                            name="title[{{ $language->code }}]"
                                                            value="{{ old('title.' . $language->code) }}"
                                                            placeholder="أدخل عنوان المقال بال{{ $language->name }}"
                                                            {{ $language->code == 'ar' ? 'required' : '' }}>
                                                        @if ($language->code == 'ar')
                                                            <div class="ai-content-buttons">
                                                                <button type="button" class="btn btn-sm"
                                                                    onclick="generateTitle('{{ $language->code }}')">
                                                                    <i class="fas fa-robot"></i>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="char-counter" id="titleCounter_{{ $language->code }}">
                                                        عدد الأحرف: <span id="titleChars_{{ $language->code }}">0</span>
                                                    </div>
                                                    @error('title_' . $language->code)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- المحتوى -->
                                                <div class="col-md-12 mb-3">
                                                    <label for="content_{{ $language->code }}" class="form-label">
                                                        <i class="fas fa-edit me-2"></i>محتوى المقال
                                                        @if ($language->code == 'ar')
                                                            <span class="text-danger">*</span>
                                                        @endif
                                                    </label>
                                                    <div class="position-relative">
                                                        <textarea class="form-control summernote @error('content_' . $language->code) is-invalid @enderror"
                                                            id="content_{{ $language->code }}" name="content[{{ $language->code }}]" rows="10"
                                                            placeholder="اكتب محتوى المقال بال{{ $language->name }}" {{ $language->code == 'ar' ? 'required' : '' }}>{{ old('content.' . $language->code) }}</textarea>
                                                        @if ($language->code == 'ar')
                                                            <div class="ai-content-buttons">
                                                                <button type="button" class="btn btn-sm"
                                                                    onclick="generateContent('{{ $language->code }}')">
                                                                    <i class="fas fa-robot"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-sm"
                                                                    onclick="enhanceContent('{{ $language->code }}')">
                                                                    <i class="fas fa-wand-magic-sparkles"></i>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="char-counter" id="contentCounter_{{ $language->code }}">
                                                        عدد الأحرف: <span id="contentChars_{{ $language->code }}">0</span>
                                                    </div>
                                                    @error('content_' . $language->code)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- الملخص -->
                                                <div class="col-md-12 mb-3">
                                                    <label for="excerpt_{{ $language->code }}" class="form-label">
                                                        <i class="fas fa-align-left me-2"></i>ملخص المقال
                                                    </label>
                                                    <div class="position-relative">
                                                        <textarea class="form-control @error('excerpt_' . $language->code) is-invalid @enderror"
                                                            id="excerpt_{{ $language->code }}" name="excerpt[{{ $language->code }}]" rows="3"
                                                            placeholder="اكتب ملخص المقال بال{{ $language->name }}">{{ old('excerpt.' . $language->code) }}</textarea>
                                                        @if ($language->code == 'ar')
                                                            <div class="ai-content-buttons" style="top: -35px;">
                                                                <button type="button" class="btn btn-sm"
                                                                    onclick="generateExcerpt('{{ $language->code }}')">
                                                                    <i class="fas fa-robot"></i>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="char-counter" id="excerptCounter_{{ $language->code }}">
                                                        عدد الأحرف: <span id="excerptChars_{{ $language->code }}">0</span>
                                                    </div>
                                                    @error('excerpt_' . $language->code)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- أزرار التحكم في المحتوى -->
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <button type="button" class="ai-tool-btn" onclick="translateAllLanguages()">
                                            <i class="fas fa-language"></i>
                                            <span>ترجمة لكل اللغات</span>
                                        </button>
                                        <button type="button" class="ai-tool-btn" onclick="improveAllLanguages()">
                                            <i class="fas fa-star"></i>
                                            <span>تحسين لكل اللغات</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- تبويب الإعدادات -->
                                <div class="tab-pane fade" id="settings" role="tabpanel">
                                    <div class="row">
                                        <!-- التصنيف -->
                                        <div class="col-md-6 mb-3">
                                            <label for="category_id" class="form-label">
                                                <i class="fas fa-folder me-2"></i>التصنيف
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('category_id') is-invalid @enderror"
                                                id="category_id" name="category_id" required>
                                                <option value="">اختر التصنيف</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- المؤلف -->
                                        <div class="col-md-6 mb-3">
                                            <label for="author_id" class="form-label">
                                                <i class="fas fa-user me-2"></i>المؤلف
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('author_id') is-invalid @enderror"
                                                id="author_id" name="author_id" required>
                                                <option value="">اختر المؤلف</option>
                                                @foreach ($authors as $author)
                                                    <option value="{{ $author->id }}"
                                                        {{ old('author_id') == $author->id ? 'selected' : '' }}>
                                                        {{ $author->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('author_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- صورة المقال -->
                                        <div class="col-md-12 mb-3">
                                            <label for="image" class="form-label">
                                                <i class="fas fa-image me-2"></i>صورة المقال
                                            </label>
                                            <input type="file"
                                                class="form-control @error('image') is-invalid @enderror" id="image"
                                                name="image" accept="image/*">
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="image-preview" id="imagePreview">
                                                <img id="previewImage" src="" alt="Preview">
                                            </div>
                                        </div>

                                        <!-- وصف الصورة -->
                                        <div class="col-md-12 mb-3">
                                            <label for="image_alt" class="form-label">
                                                <i class="fas fa-tag me-2"></i>وصف الصورة (Alt Text)
                                            </label>
                                            <input type="text"
                                                class="form-control @error('image_alt') is-invalid @enderror"
                                                id="image_alt" name="image_alt" value="{{ old('image_alt') }}"
                                                placeholder="أدخل وصفاً للصورة">
                                            @error('image_alt')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- إعدادات المقال -->
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_active"
                                                    name="is_active" value="1"
                                                    {{ old('is_active', 1) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">نشط</label>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_featured"
                                                    name="is_featured" value="1"
                                                    {{ old('is_featured') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_featured">مميز</label>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="published_at" class="form-label">
                                                <i class="fas fa-calendar me-2"></i>تاريخ النشر
                                            </label>
                                            <input type="datetime-local" class="form-control" id="published_at"
                                                name="published_at" value="{{ old('published_at') }}">
                                        </div>

                                        <!-- التاغات -->
                                        <div class="col-md-12 mb-3">
                                            <label for="tags" class="form-label">
                                                <i class="fas fa-tags me-2"></i>التاغات
                                            </label>
                                            <select class="form-select select2-tags" id="tags" name="tags[]"
                                                multiple>
                                                @foreach ($tags as $tag)
                                                    <option value="{{ $tag->id }}"
                                                        {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
                                                        {{ $tag->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- تبويب SEO -->
                                <div class="tab-pane fade" id="seo" role="tabpanel">
                                    <!-- تبويبات اللغات لـ SEO -->
                                    <div class="language-tabs mb-4">
                                        @foreach ($languages as $language)
                                            <button type="button"
                                                class="language-tab {{ $loop->first ? 'active' : '' }}"
                                                data-lang-seo="{{ $language->code }}">
                                                <i class="fas fa-language me-2"></i>{{ $language->name }}
                                            </button>
                                        @endforeach
                                    </div>

                                    <!-- محتوى SEO لكل لغة -->
                                    @foreach ($languages as $language)
                                        <div class="seo-content {{ $loop->first ? 'active' : '' }}"
                                            id="seo-{{ $language->code }}">
                                            <div class="row">
                                                <!-- عنوان SEO -->
                                                <div class="col-md-12 mb-3">
                                                    <label for="meta_title_{{ $language->code }}" class="form-label">
                                                        <i class="fas fa-search me-2"></i>Meta Title
                                                    </label>
                                                    <div class="position-relative">
                                                        <input type="text"
                                                            class="form-control @error('meta_title_' . $language->code) is-invalid @enderror"
                                                            id="meta_title_{{ $language->code }}"
                                                            name="meta_title[{{ $language->code }}]"
                                                            value="{{ old('meta_title.' . $language->code) }}"
                                                            placeholder="عنوان محسن لمحركات البحث">
                                                        @if ($language->code == 'ar')
                                                            <div class="ai-content-buttons">
                                                                <button type="button" class="btn btn-sm"
                                                                    onclick="generateMetaTitle('{{ $language->code }}')">
                                                                    <i class="fas fa-robot"></i>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="char-counter"
                                                        id="metaTitleCounter_{{ $language->code }}">
                                                        عدد الأحرف: <span
                                                            id="metaTitleChars_{{ $language->code }}">0</span> (مثالي:
                                                        50-60 حرف)
                                                    </div>
                                                    @error('meta_title_' . $language->code)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- وصف SEO -->
                                                <div class="col-md-12 mb-3">
                                                    <label for="meta_description_{{ $language->code }}"
                                                        class="form-label">
                                                        <i class="fas fa-align-left me-2"></i>Meta Description
                                                    </label>
                                                    <div class="position-relative">
                                                        <textarea class="form-control @error('meta_description_' . $language->code) is-invalid @enderror"
                                                            id="meta_description_{{ $language->code }}" name="meta_description[{{ $language->code }}]" rows="3"
                                                            placeholder="وصف محسن لمحركات البحث">{{ old('meta_description.' . $language->code) }}</textarea>
                                                        @if ($language->code == 'ar')
                                                            <div class="ai-content-buttons" style="top: -35px;">
                                                                <button type="button" class="btn btn-sm"
                                                                    onclick="generateMetaDescription('{{ $language->code }}')">
                                                                    <i class="fas fa-robot"></i>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="char-counter" id="metaDescCounter_{{ $language->code }}">
                                                        عدد الأحرف: <span
                                                            id="metaDescChars_{{ $language->code }}">0</span> (مثالي:
                                                        150-160 حرف)
                                                    </div>
                                                    @error('meta_description_' . $language->code)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- كلمات دلالية -->
                                                <div class="col-md-12 mb-3">
                                                    <label for="meta_keywords_{{ $language->code }}" class="form-label">
                                                        <i class="fas fa-tags me-2"></i>Meta Keywords
                                                    </label>
                                                    <div class="position-relative">
                                                        <input type="text"
                                                            class="form-control @error('meta_keywords_' . $language->code) is-invalid @enderror"
                                                            id="meta_keywords_{{ $language->code }}"
                                                            name="meta_keywords[{{ $language->code }}]"
                                                            value="{{ old('meta_keywords.' . $language->code) }}"
                                                            placeholder="كلمات مفتاحية مفصولة بفواصل">
                                                        @if ($language->code == 'ar')
                                                            <div class="ai-content-buttons" style="top: -35px;">
                                                                <button type="button" class="btn btn-sm"
                                                                    onclick="generateKeywords('{{ $language->code }}')">
                                                                    <i class="fas fa-robot"></i>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @error('meta_keywords_' . $language->code)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- تبويب المعاينة -->
                                <div class="tab-pane fade" id="preview" role="tabpanel">
                                    <!-- تبويبات اللغات للمعاينة -->
                                    <div class="language-tabs mb-4">
                                        @foreach ($languages as $language)
                                            <button type="button"
                                                class="language-tab {{ $loop->first ? 'active' : '' }}"
                                                data-lang-preview="{{ $language->code }}">
                                                <i class="fas fa-language me-2"></i>{{ $language->name }}
                                            </button>
                                        @endforeach
                                    </div>

                                    <!-- معاينة لكل لغة -->
                                    @foreach ($languages as $language)
                                        <div class="preview-content {{ $loop->first ? 'active' : '' }}"
                                            id="preview-{{ $language->code }}">
                                            <div class="preview-content-inner" style="min-height: 200px;">
                                                <p class="text-muted">سيظهر محتوى المقال هنا...</p>
                                            </div>
                                            <div class="mt-3">
                                                <h6>معلومات إضافية:</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <strong>التصنيف:</strong>
                                                            <span id="previewCategory_{{ $language->code }}"
                                                                class="text-muted">غير محدد</span>
                                                        </div>
                                                        <div class="mb-2">
                                                            <strong>المؤلف:</strong>
                                                            <span id="previewAuthor_{{ $language->code }}"
                                                                class="text-muted">غير محدد</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <strong>Meta Title:</strong>
                                                            <span id="previewMetaTitle_{{ $language->code }}"
                                                                class="text-muted">غير محدد</span>
                                                        </div>
                                                        <div class="mb-2">
                                                            <strong>Meta Description:</strong>
                                                            <span id="previewMetaDesc_{{ $language->code }}"
                                                                class="text-muted">غير محدد</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- أزرار الحفظ -->
                            <div class="save-buttons">
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-redo me-2"></i>إعادة تعيين
                                </button>
                                <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>إلغاء
                                </a>
                                <button type="button" class="btn btn-primary ai-button-primary"
                                    onclick="generateAndSave()">
                                    <i class="fas fa-robot me-2"></i>إنشاء وحفظ بالذكاء الاصطناعي
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>حفظ المقال
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // تهيئة Summernote لكل لغة
            @foreach ($languages as $language)
                $('#content_{{ $language->code }}').summernote({
                    height: 300,
                    lang: '{{ $language->code == 'ar' ? 'ar-AR' : 'en-US' }}',
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
                    placeholder: 'اكتب محتوى المقال بال{{ $language->name }}...',
                    callbacks: {
                        onChange: function(contents) {
                            updateCharCount('content_{{ $language->code }}', contents);
                            updatePreview('{{ $language->code }}');
                        },
                        onInit: function() {
                            updateCharCount('content_{{ $language->code }}', $(
                                '#content_{{ $language->code }}').val());
                        }
                    }
                });
            @endforeach

            // تهيئة Select2 للتاغات
            $('.select2-tags').select2({
                tags: true,
                placeholder: 'اختر التاغات أو أضف جديدة',
                dir: 'rtl',
                allowClear: true,
                theme: 'default',
                width: '100%',
                language: {
                    noResults: function() {
                        return "لم يتم العثور على نتائج";
                    },
                    searching: function() {
                        return "جاري البحث...";
                    }
                }
            });

            // التحكم في تبويبات اللغات للمحتوى
            $('.language-tab[data-lang]').on('click', function() {
                const lang = $(this).data('lang');

                // تفعيل التبويب المحدد
                $('.language-tab[data-lang]').removeClass('active');
                $(this).addClass('active');

                // إظهار محتوى اللغة المحددة
                $('.language-content').removeClass('active');
                $(`#content-${lang}`).addClass('active');
            });

            // التحكم في تبويبات اللغات لـ SEO
            $('.language-tab[data-lang-seo]').on('click', function() {
                const lang = $(this).data('lang-seo');

                // تفعيل التبويب المحدد
                $('.language-tab[data-lang-seo]').removeClass('active');
                $(this).addClass('active');

                // إظهار محتوى SEO للغة المحددة
                $('.seo-content').removeClass('active');
                $(`#seo-${lang}`).addClass('active');
            });

            // التحكم في تبويبات اللغات للمعاينة
            $('.language-tab[data-lang-preview]').on('click', function() {
                const lang = $(this).data('lang-preview');

                // تفعيل التبويب المحدد
                $('.language-tab[data-lang-preview]').removeClass('active');
                $(this).addClass('active');

                // إظهار معاينة اللغة المحددة
                $('.preview-content').removeClass('active');
                $(`#preview-${lang}`).addClass('active');

                // تحديث المعاينة
                updatePreview(lang);
            });

            // معاينة الصورة
            $('#image').on('change', function(e) {
                const file = e.target.files[0];
                const preview = $('#imagePreview');
                const previewImage = $('#previewImage');

                if (file) {
                    // التحقق من نوع الملف
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                    if (!validTypes.includes(file.type)) {
                        showToast('خطأ', 'الرجاء اختيار صورة بصيغة JPG, PNG, GIF أو WebP', 'error');
                        $(this).val('');
                        preview.hide();
                        return;
                    }

                    // التحقق من حجم الملف (5MB كحد أقصى)
                    const maxSize = 5 * 1024 * 1024; // 5MB
                    if (file.size > maxSize) {
                        showToast('خطأ', 'حجم الصورة يجب أن يكون أقل من 5 ميجابايت', 'error');
                        $(this).val('');
                        preview.hide();
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.attr('src', e.target.result);
                        preview.show();
                    }
                    reader.readAsDataURL(file);
                } else {
                    preview.hide();
                }
            });

            // تحديث تعداد الأحرف
            $('input, textarea').on('keyup', function() {
                const fieldId = $(this).attr('id');
                const text = $(this).val();
                updateCharCount(fieldId, text);

                // تحديث المعاينة للغة المناسبة
                const lang = fieldId.split('_').pop();
                if (['ar', 'en'].includes(lang)) {
                    updatePreview(lang);
                }
            });

            // تحديث المعاينة عند تغيير التصنيف والمؤلف
            $('#category_id, #author_id').on('change', function() {
                @foreach ($languages as $language)
                    updatePreview('{{ $language->code }}');
                @endforeach
            });

            // التحقق من النموذج
            $('#articleForm').on('submit', function(e) {
                const titleAr = $('#title_ar').val();
                const contentAr = $('#content_ar').val();
                const categoryId = $('#category_id').val();
                const authorId = $('#author_id').val();

                if (!titleAr.trim()) {
                    e.preventDefault();
                    showToast('خطأ', 'الرجاء إدخال عنوان المقال بالعربية', 'error');
                    $('#title_ar').focus();
                    return false;
                }

                if (!contentAr.trim()) {
                    e.preventDefault();
                    showToast('خطأ', 'الرجاء إدخال محتوى المقال بالعربية', 'error');
                    return false;
                }

                if (!categoryId) {
                    e.preventDefault();
                    showToast('خطأ', 'الرجاء اختيار التصنيف', 'error');
                    return false;
                }

                if (!authorId) {
                    e.preventDefault();
                    showToast('خطأ', 'الرجاء اختيار المؤلف', 'error');
                    return false;
                }

                return true;
            });
        });

        // ============================================
        // دوال المساعدة
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
            const toastClass = {
                'success': 'toast-success',
                'error': 'toast-error',
                'warning': 'toast-warning',
                'info': 'toast-info'
            } [type] || 'toast-info';

            const toastHtml = `
                <div id="${toastId}" class="toast-container">
                    <div class="toast ${toastClass}">
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

            setTimeout(() => {
                $(`#${toastId}`).remove();
            }, 5000);
        }

        // تحديث تعداد الأحرف
        function updateCharCount(fieldId, text) {
            const chars = text ? text.length : 0;
            const fieldType = fieldId.split('_')[0];
            const lang = fieldId.split('_').pop();

            switch (fieldType) {
                case 'title':
                    $(`#titleChars_${lang}`).text(chars);
                    $(`#titleCounter_${lang}`).toggleClass('warning', chars > 60);
                    $(`#titleCounter_${lang}`).toggleClass('danger', chars > 70);
                    break;

                case 'content':
                    const cleanText = text ? text.replace(/<[^>]*>/g, '') : '';
                    $(`#contentChars_${lang}`).text(cleanText.length);
                    break;

                case 'excerpt':
                    $(`#excerptChars_${lang}`).text(chars);
                    $(`#excerptCounter_${lang}`).toggleClass('warning', chars > 200);
                    break;

                case 'metaTitle':
                    $(`#metaTitleChars_${lang}`).text(chars);
                    $(`#metaTitleCounter_${lang}`).toggleClass('warning', chars > 60);
                    $(`#metaTitleCounter_${lang}`).toggleClass('danger', chars > 70);
                    break;

                case 'metaDescription':
                    $(`#metaDescChars_${lang}`).text(chars);
                    $(`#metaDescCounter_${lang}`).toggleClass('warning', chars > 160);
                    $(`#metaDescCounter_${lang}`).toggleClass('danger', chars > 180);
                    break;
            }
        }

        // تحديث المعاينة
        function updatePreview(lang) {
            const title = $(`#title_${lang}`).val() || 'عنوان المقال';
            const content = $(`#content_${lang}`).val() || 'محتوى المقال...';
            const categoryId = $('#category_id').val();
            const authorId = $('#author_id').val();
            const metaTitle = $(`#meta_title_${lang}`).val() || title;
            const metaDesc = $(`#meta_description_${lang}`).val() || 'وصف المقال...';

            // الحصول على اسم التصنيف والمؤلف
            const categoryName = categoryId ? $(`#category_id option[value="${categoryId}"]`).text() : 'غير محدد';
            const authorName = authorId ? $(`#author_id option[value="${authorId}"]`).text() : 'غير محدد';

            // تحديث معاينة المحتوى
            const previewHtml = `
                <h3>${title}</h3>
                <div>${content.substring(0, 500)}${content.length > 500 ? '...' : ''}</div>
            `;
            $(`#preview-${lang} .preview-content-inner`).html(previewHtml);

            // تحديث المعلومات الإضافية
            $(`#previewCategory_${lang}`).text(categoryName);
            $(`#previewAuthor_${lang}`).text(authorName);
            $(`#previewMetaTitle_${lang}`).text(metaTitle);
            $(`#previewMetaDesc_${lang}`).text(metaDesc);
        }

        // ============================================
        // دوال الذكاء الاصطناعي
        // ============================================

        // إنشاء مقال كامل
        function generateFullArticle() {
            const categoryId = $('#category_id').val();
            const authorId = $('#author_id').val();

            if (!categoryId) {
                showToast('تنبيه', 'الرجاء اختيار التصنيف أولاً', 'warning');
                return;
            }

            Swal.fire({
                title: 'إنشاء مقال كامل',
                html: `
                    <div class="mb-3">
                        <label for="aiArticleTopic" class="form-label">موضوع المقال</label>
                        <input type="text" id="aiArticleTopic" class="form-control" placeholder="أدخل موضوع المقال">
                    </div>
                    <div class="mb-3">
                        <label for="aiArticleStyle" class="form-label">أسلوب المقال</label>
                        <select id="aiArticleStyle" class="form-select">
                            <option value="informative">معلوماتي</option>
                            <option value="persuasive">إقناعي</option>
                            <option value="narrative">سردي</option>
                            <option value="descriptive">وصفي</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="aiArticleLength" class="form-label">طول المقال</label>
                        <select id="aiArticleLength" class="form-select">
                            <option value="short">قصير (300 كلمة)</option>
                            <option value="medium" selected>متوسط (600 كلمة)</option>
                            <option value="long">طويل (1000 كلمة)</option>
                        </select>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'إنشاء',
                cancelButtonText: 'إلغاء',
                preConfirm: () => {
                    return {
                        topic: document.getElementById('aiArticleTopic').value,
                        style: document.getElementById('aiArticleStyle').value,
                        length: document.getElementById('aiArticleLength').value,
                        category_id: categoryId,
                        author_id: authorId
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('إنشاء المقال', 'جاري إنشاء المقال بالذكاء الاصطناعي...');
                    updateProgress(20);

                    $.ajax({
                        url: '{{ route('admin.articles.ai-generate-full') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ...result.value
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                updateProgress(100);
                                setTimeout(() => {
                                    hideLoading();

                                    // تعبئة الحقول العربية
                                    $('#title_ar').val(response.data.title || '');
                                    $('#content_ar').summernote('code', response.data.content ||
                                        '');
                                    $('#excerpt_ar').val(response.data.excerpt || '');

                                    // ترجمة تلقائية للغات الأخرى
                                    if (response.translations) {
                                        Object.keys(response.translations).forEach(lang => {
                                            if (lang !== 'ar') {
                                                $(`#title_${lang}`).val(response
                                                    .translations[lang].title || '');
                                                $(`#content_${lang}`).summernote('code',
                                                    response.translations[lang]
                                                    .content || '');
                                                $(`#excerpt_${lang}`).val(response
                                                    .translations[lang].excerpt ||
                                                    '');
                                            }
                                        });
                                    }

                                    // إنشاء SEO
                                    generateSEOForAllLanguages();

                                    updatePreview('ar');
                                    showToast('تم الإنشاء', 'تم إنشاء المقال بنجاح', 'success');
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
            });
        }

        // إنشاء عنوان
        function generateTitle(lang) {
            const categoryId = $('#category_id').val();

            if (!categoryId) {
                showToast('تنبيه', 'الرجاء اختيار التصنيف أولاً', 'warning');
                return;
            }

            showLoading('إنشاء العنوان', 'جاري إنشاء عنوان مناسب...');

            $.ajax({
                url: '{{ route('admin.articles.ai-generate-title') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    category_id: categoryId,
                    language: lang
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $(`#title_${lang}`).val(response.title);
                            updateCharCount(`title_${lang}`, response.title);
                            updatePreview(lang);
                            showToast('تم الإنشاء', 'تم إنشاء العنوان بنجاح', 'success');
                        }, 500);
                    } else {
                        hideLoading();
                        showToast('خطأ', response.message || 'حدث خطأ أثناء إنشاء العنوان', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    console.error('خطأ AJAX:', error);
                    showToast('خطأ', 'حدث خطأ في الاتصال بالخادم', 'error');
                }
            });
        }

        // إنشاء محتوى
        function generateContent(lang) {
            const title = $(`#title_${lang}`).val();
            const categoryId = $('#category_id').val();

            if (!title) {
                showToast('تنبيه', 'الرجاء إدخال عنوان المقال أولاً', 'warning');
                return;
            }

            showLoading('إنشاء المحتوى', 'جاري إنشاء محتوى المقال...');
            updateProgress(30);

            $.ajax({
                url: '{{ route('admin.articles.ai-generate-content') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    title: title,
                    category_id: categoryId,
                    language: lang
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $(`#content_${lang}`).summernote('code', response.content);
                            updateCharCount(`content_${lang}`, response.content);
                            updatePreview(lang);
                            showToast('تم الإنشاء', 'تم إنشاء المحتوى بنجاح', 'success');
                        }, 500);
                    } else {
                        hideLoading();
                        showToast('خطأ', response.message || 'حدث خطأ أثناء إنشاء المحتوى', 'error');
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
        function enhanceContent(lang) {
            const content = $(`#content_${lang}`).val();

            if (!content) {
                showToast('تنبيه', 'الرجاء إدخال محتوى أولاً', 'warning');
                return;
            }

            showLoading('تحسين المحتوى', 'جاري تحسين محتوى المقال...');

            $.ajax({
                url: '{{ route('admin.articles.ai-enhance-content') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    content: content,
                    language: lang,
                    action: 'enhance'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $(`#content_${lang}`).summernote('code', response.enhanced_content);
                            updateCharCount(`content_${lang}`, response.enhanced_content);
                            updatePreview(lang);
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

        // إنشاء ملخص
        function generateExcerpt(lang) {
            const content = $(`#content_${lang}`).val();

            if (!content) {
                showToast('تنبيه', 'الرجاء إدخال محتوى المقال أولاً', 'warning');
                return;
            }

            showLoading('إنشاء الملخص', 'جاري إنشاء ملخص للمقال...');

            $.ajax({
                url: '{{ route('admin.articles.ai-generate-excerpt') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    content: content,
                    language: lang
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $(`#excerpt_${lang}`).val(response.excerpt);
                            updateCharCount(`excerpt_${lang}`, response.excerpt);
                            showToast('تم الإنشاء', 'تم إنشاء الملخص بنجاح', 'success');
                        }, 500);
                    } else {
                        hideLoading();
                        showToast('خطأ', response.message || 'حدث خطأ أثناء إنشاء الملخص', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    console.error('خطأ AJAX:', error);
                    showToast('خطأ', 'حدث خطأ في الاتصال بالخادم', 'error');
                }
            });
        }

        // ترجمة لكل اللغات
        function translateAllLanguages() {
            const titleAr = $('#title_ar').val();
            const contentAr = $('#content_ar').val();

            if (!titleAr || !contentAr) {
                showToast('تنبيه', 'الرجاء إدخال العنوان والمحتوى بالعربية أولاً', 'warning');
                return;
            }

            showLoading('الترجمة', 'جاري ترجمة المقال لكل اللغات...');
            updateProgress(10);

            $.ajax({
                url: '{{ route('admin.articles.ai-translate-all') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    title: titleAr,
                    content: contentAr
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();

                            // تعبئة الحقول لكل لغة
                            Object.keys(response.translations).forEach(lang => {
                                if (lang !== 'ar') {
                                    $(`#title_${lang}`).val(response.translations[lang].title ||
                                        '');
                                    $(`#content_${lang}`).summernote('code', response
                                        .translations[lang].content || '');
                                    $(`#excerpt_${lang}`).val(response.translations[lang]
                                        .excerpt || '');
                                    updatePreview(lang);
                                }
                            });

                            showToast('تم الترجمة', 'تمت ترجمة المقال لكل اللغات بنجاح', 'success');
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

        // تحسين لكل اللغات
        function improveAllLanguages() {
            showLoading('التحسين', 'جاري تحسين المقال لكل اللغات...');
            updateProgress(20);

            // جمع البيانات لكل لغة
            const languagesData = {};
            @foreach ($languages as $language)
                languagesData['{{ $language->code }}'] = {
                    title: $(`#title_{{ $language->code }}`).val(),
                    content: $(`#content_{{ $language->code }}`).val(),
                    excerpt: $(`#excerpt_{{ $language->code }}`).val()
                };
            @endforeach

            $.ajax({
                url: '{{ route('admin.articles.ai-improve-all') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    languages: languagesData
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();

                            // تعبئة الحقول المحسنة
                            Object.keys(response.improved).forEach(lang => {
                                $(`#title_${lang}`).val(response.improved[lang].title || '');
                                $(`#content_${lang}`).summernote('code', response.improved[lang]
                                    .content || '');
                                $(`#excerpt_${lang}`).val(response.improved[lang].excerpt ||
                                '');
                                updatePreview(lang);
                            });

                            showToast('تم التحسين', 'تم تحسين المقال لكل اللغات بنجاح', 'success');
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

        // إنشاء SEO لكل اللغات
        function generateSEOForAllLanguages() {
            showLoading('إنشاء SEO', 'جاري إنشاء محتوى SEO لكل اللغات...');

            const languages = [];
            @foreach ($languages as $language)
                languages.push('{{ $language->code }}');
            @endforeach

            let completed = 0;
            const total = languages.length;

            languages.forEach(lang => {
                setTimeout(() => {
                    generateMetaTitle(lang);
                    generateMetaDescription(lang);
                    generateKeywords(lang);

                    completed++;
                    const progress = Math.round((completed / total) * 100);
                    updateProgress(progress);

                    if (completed === total) {
                        setTimeout(() => {
                            hideLoading();
                            showToast('تم إنشاء SEO', 'تم إنشاء محتوى SEO لكل اللغات بنجاح',
                                'success');
                        }, 1000);
                    }
                }, 500 * completed);
            });
        }

        // إنشاء عنوان SEO
        function generateMetaTitle(lang) {
            const title = $(`#title_${lang}`).val();
            const content = $(`#content_${lang}`).val();

            if (!title && !content) {
                return;
            }

            $.ajax({
                url: '{{ route('admin.articles.ai-generate-meta-title') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    title: title || content.substring(0, 100),
                    language: lang
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $(`#meta_title_${lang}`).val(response.meta_title);
                        updateCharCount(`meta_title_${lang}`, response.meta_title);
                    }
                }
            });
        }

        // إنشاء وصف SEO
        function generateMetaDescription(lang) {
            const content = $(`#content_${lang}`).val();

            if (!content) {
                return;
            }

            $.ajax({
                url: '{{ route('admin.articles.ai-generate-meta-description') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    content: content,
                    language: lang
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $(`#meta_description_${lang}`).val(response.meta_description);
                        updateCharCount(`meta_description_${lang}`, response.meta_description);
                    }
                }
            });
        }

        // إنشاء كلمات مفتاحية
        function generateKeywords(lang) {
            const content = $(`#content_${lang}`).val();

            if (!content) {
                return;
            }

            $.ajax({
                url: '{{ route('admin.articles.ai-generate-keywords') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    content: content,
                    language: lang
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $(`#meta_keywords_${lang}`).val(response.keywords);
                    }
                }
            });
        }

        // إنشاء SEO
        function generateSEO() {
            const titleAr = $('#title_ar').val();
            const contentAr = $('#content_ar').val();

            if (!titleAr && !contentAr) {
                showToast('تنبيه', 'الرجاء إدخال العنوان أو المحتوى أولاً', 'warning');
                return;
            }

            showLoading('إنشاء SEO', 'جاري إنشاء محتوى SEO كامل...');

            generateSEOForAllLanguages();
        }

        // تحسين النص
        function enhanceWithAI() {
            Swal.fire({
                title: 'تحسين النص',
                html: `
                    <div class="mb-3">
                        <label for="enhanceText" class="form-label">النص المراد تحسينه</label>
                        <textarea id="enhanceText" class="form-control" rows="4" placeholder="الصق النص هنا"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="enhanceType" class="form-label">نوع التحسين</label>
                        <select id="enhanceType" class="form-select">
                            <option value="grammar">تدقيق لغوي وإملائي</option>
                            <option value="style">تحسين الأسلوب</option>
                            <option value="clarity">زيادة الوضوح</option>
                            <option value="brevity">الإيجاز</option>
                        </select>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'تحسين',
                cancelButtonText: 'إلغاء',
                preConfirm: () => {
                    return {
                        text: document.getElementById('enhanceText').value,
                        type: document.getElementById('enhanceType').value
                    };
                }
            }).then((result) => {
                if (result.isConfirmed && result.value.text) {
                    showLoading('تحسين النص', 'جاري تحسين النص باستخدام الذكاء الاصطناعي...');

                    $.ajax({
                        url: '{{ route('admin.articles.ai-enhance-text') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            text: result.value.text,
                            type: result.value.type
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                updateProgress(100);
                                setTimeout(() => {
                                    hideLoading();

                                    Swal.fire({
                                        title: 'النص المحسن',
                                        html: `
                                            <div style="text-align: right; max-height: 400px; overflow-y: auto; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                                                ${response.enhanced_text.replace(/\n/g, '<br>')}
                                            </div>
                                        `,
                                        showCancelButton: true,
                                        confirmButtonText: 'نسخ النص',
                                        cancelButtonText: 'إغلاق',
                                        width: 800
                                    }).then((copyResult) => {
                                        if (copyResult.isConfirmed) {
                                            navigator.clipboard.writeText(response
                                                .enhanced_text);
                                            showToast('تم النسخ',
                                                'تم نسخ النص المحسن إلى الحافظة',
                                                'success');
                                        }
                                    });
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
            });
        }

        // إنشاء وحفظ بالذكاء الاصطناعي
        function generateAndSave() {
            Swal.fire({
                title: 'إنشاء وحفظ بالذكاء الاصطناعي',
                text: 'سيتم إنشاء المقال بالكامل ثم حفظه. هل تريد المتابعة؟',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'نعم، إنشاء وحفظ',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('إنشاء وحفظ', 'جاري إنشاء المقال وحفظه...');

                    // تسلسل عمليات الإنشاء
                    setTimeout(() => {
                        updateProgress(20);
                        generateFullArticle();
                    }, 1000);

                    setTimeout(() => {
                        updateProgress(40);
                        translateAllLanguages();
                    }, 4000);

                    setTimeout(() => {
                        updateProgress(60);
                        improveAllLanguages();
                    }, 8000);

                    setTimeout(() => {
                        updateProgress(80);
                        generateSEOForAllLanguages();
                    }, 12000);

                    setTimeout(() => {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            // إرسال النموذج
                            $('#articleForm').submit();
                        }, 1000);
                    }, 15000);
                }
            });
        }

        // إعادة تعيين النموذج
        function resetForm() {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم مسح جميع الحقول وإعادة تعيين النموذج',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، أعد التعيين',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#articleForm')[0].reset();

                    // إعادة تعيين محررات Summernote
                    @foreach ($languages as $language)
                        $('#content_{{ $language->code }}').summernote('code', '');
                    @endforeach

                    // إعادة تعيين Select2
                    $('.select2-tags').val(null).trigger('change');

                    // إخفاء معاينة الصورة
                    $('#imagePreview').hide();

                    // إعادة تعيين العداد
                    @foreach ($languages as $language)
                        updateCharCount('title_{{ $language->code }}', '');
                        updateCharCount('content_{{ $language->code }}', '');
                        updateCharCount('excerpt_{{ $language->code }}', '');
                        updateCharCount('meta_title_{{ $language->code }}', '');
                        updateCharCount('meta_description_{{ $language->code }}', '');
                        updatePreview('{{ $language->code }}');
                    @endforeach

                    showToast('تمت الإعادة', 'تم إعادة تعيين النموذج بنجاح', 'success');
                }
            });
        }
    </script>
@endsection
