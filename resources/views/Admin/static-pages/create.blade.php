@extends('Admin.layout.master')

@section('title', 'إنشاء صفحة ثابتة بالذكاء الاصطناعي')

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

        /* قوالب جاهزة */
        .template-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .template-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }

        .template-card:hover {
            background: rgba(105, 108, 255, 0.1);
            border-color: var(--primary-color);
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(105, 108, 255, 0.2);
        }

        .template-card.active {
            background: rgba(105, 108, 255, 0.2);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.3);
        }

        .template-icon {
            font-size: 32px;
            margin-bottom: 10px;
            color: var(--primary-color);
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

        /* أنواع الصفحات */
        .page-type-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .page-type-btn {
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.8);
            cursor: pointer;
            transition: all 0.3s;
        }

        .page-type-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .page-type-btn.active {
            background: var(--primary-gradient);
            color: white;
            border-color: var(--primary-color);
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
                <li class="breadcrumb-item active">إنشاء صفحة جديدة بالذكاء الاصطناعي</li>
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
                                    إنشاء صفحة ثابتة بالذكاء الاصطناعي
                                </h5>
                                <small class="opacity-75">أنشئ صفحة جديدة باستخدام الذكاء الاصطناعي</small>
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

                        <!-- أدوات الذكاء الاصطناعي -->
                        <div class="ai-tools mb-4">
                            <h6 class="mb-3">
                                <i class="fas fa-magic me-2"></i>
                                أدوات الذكاء الاصطناعي
                            </h6>
                            <div class="d-flex flex-wrap">
                                <div class="ai-tool-btn" onclick="generateFromAI()">
                                    <i class="fas fa-robot"></i>
                                    <span>إنشاء بالذكاء الاصطناعي</span>
                                </div>
                                <div class="ai-tool-btn" onclick="enhanceWithAI()">
                                    <i class="fas fa-wand-magic-sparkles"></i>
                                    <span>تحسين النص</span>
                                </div>
                                <div class="ai-tool-btn" onclick="generateSEO()">
                                    <i class="fas fa-search"></i>
                                    <span>إنشاء SEO</span>
                                </div>
                                <div class="ai-tool-btn" onclick="checkGrammar()">
                                    <i class="fas fa-spell-check"></i>
                                    <span>تدقيق لغوي</span>
                                </div>
                            </div>
                        </div>

                        <!-- إدخال الذكاء الاصطناعي -->
                        <div class="ai-prompt-input mb-4">
                            <textarea id="aiPrompt" placeholder="أدخل وصفاً للصفحة التي تريد إنشاءها باستخدام الذكاء الاصطناعي..."></textarea>
                            <button onclick="generateFromPrompt()">
                                <i class="fas fa-bolt me-2"></i>إنشاء
                            </button>
                        </div>

                        <!-- قوالب جاهزة -->
                        <div class="mb-4">
                            <h6 class="mb-3">
                                <i class="fas fa-layer-group me-2"></i>
                                قوالب جاهزة
                            </h6>
                            <div class="template-cards">
                                <div class="template-card" data-template="privacy" onclick="selectTemplate('privacy')">
                                    <div class="template-icon">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <h6>سياسة الخصوصية</h6>
                                    <p>إنشاء صفحة سياسة خصوصية شاملة</p>
                                </div>

                                <div class="template-card" data-template="terms" onclick="selectTemplate('terms')">
                                    <div class="template-icon">
                                        <i class="fas fa-file-contract"></i>
                                    </div>
                                    <h6>الشروط والأحكام</h6>
                                    <p>إنشاء صفحة شروط وأحكام قانونية</p>
                                </div>

                                <div class="template-card" data-template="about" onclick="selectTemplate('about')">
                                    <div class="template-icon">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <h6>من نحن</h6>
                                    <p>إنشاء صفحة تعريفية عن الشركة</p>
                                </div>

                                <div class="template-card" data-template="contact" onclick="selectTemplate('contact')">
                                    <div class="template-icon">
                                        <i class="fas fa-address-book"></i>
                                    </div>
                                    <h6>اتصل بنا</h6>
                                    <p>إنشاء صفحة معلومات الاتصال</p>
                                </div>

                                <div class="template-card" data-template="faq" onclick="selectTemplate('faq')">
                                    <div class="template-icon">
                                        <i class="fas fa-question-circle"></i>
                                    </div>
                                    <h6>الأسئلة الشائعة</h6>
                                    <p>إنشاء صفحة أسئلة وأجوبة</p>
                                </div>

                                <div class="template-card" data-template="custom" onclick="selectTemplate('custom')">
                                    <div class="template-icon">
                                        <i class="fas fa-pencil-alt"></i>
                                    </div>
                                    <h6>مخصص</h6>
                                    <p>إنشاء صفحة مخصصة من البداية</p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('admin.static-pages.store') }}" method="POST" id="createPageForm">
                            @csrf

                            <!-- أنواع الصفحات -->
                            <div class="page-type-selector mb-4">
                                <div class="page-type-btn active" data-type="regular"
                                    onclick="selectPageType('regular')">
                                    <i class="fas fa-file-alt me-2"></i>صفحة عادية
                                </div>
                                <div class="page-type-btn" data-type="legal" onclick="selectPageType('legal')">
                                    <i class="fas fa-gavel me-2"></i>صفحة قانونية
                                </div>
                                <div class="page-type-btn" data-type="info" onclick="selectPageType('info')">
                                    <i class="fas fa-info-circle me-2"></i>صفحة معلوماتية
                                </div>
                            </div>

                            <ul class="nav nav-tabs mb-4" id="pageTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="basic-tab" data-bs-toggle="tab"
                                        data-bs-target="#basic" type="button" role="tab">
                                        <i class="fas fa-info-circle me-2"></i>المعلومات الأساسية
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="content-tab" data-bs-toggle="tab"
                                        data-bs-target="#content" type="button" role="tab">
                                        <i class="fas fa-edit me-2"></i>المحتوى
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

                            <div class="tab-content" id="pageTabsContent">
                                <!-- تبويب المعلومات الأساسية -->
                                <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="title" class="form-label">
                                                <i class="fas fa-heading me-2"></i>عنوان الصفحة
                                            </label>
                                            <div class="position-relative">
                                                <input type="text"
                                                    class="form-control @error('title') is-invalid @enderror"
                                                    id="title" name="title" value="{{ old('title') }}"
                                                    placeholder="أدخل عنوان الصفحة" required>
                                                <div class="ai-content-buttons">
                                                    <button type="button" class="btn btn-sm" onclick="generateTitle()">
                                                        <i class="fas fa-robot"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="char-counter" id="titleCounter">
                                                عدد الأحرف: <span id="titleChars">0</span>
                                            </div>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="slug" class="form-label">
                                                <i class="fas fa-link me-2"></i>الرابط (Slug)
                                            </label>
                                            <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                                id="slug" name="slug" value="{{ old('slug') }}"
                                                placeholder="سيتم إنشاؤه تلقائياً">
                                            <small class="text-muted">مثال: سياسة-الخصوصية</small>
                                            @error('slug')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="status" class="form-label">
                                                <i class="fas fa-toggle-on me-2"></i>حالة الصفحة
                                            </label>
                                            <select class="form-select @error('status') is-invalid @enderror"
                                                id="status" name="status" required>
                                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                                    <i class="fas fa-check-circle me-2"></i>نشط
                                                </option>
                                                <option value="inactive"
                                                    {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                                    <i class="fas fa-times-circle me-2"></i>غير نشط
                                                </option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- تبويب المحتوى -->
                                <div class="tab-pane fade" id="content" role="tabpanel">
                                    <div class="mb-3">
                                        <label for="content" class="form-label">
                                            <i class="fas fa-edit me-2"></i>محتوى الصفحة
                                        </label>
                                        <div class="position-relative">
                                            <textarea class="form-control @error('content') is-invalid @enderror summernote" id="content" name="content"
                                                rows="12" placeholder="أدخل محتوى الصفحة هنا...">{{ old('content') }}</textarea>
                                            <div class="ai-content-buttons">
                                                <button type="button" class="btn btn-sm" onclick="generateContent()">
                                                    <i class="fas fa-robot"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm" onclick="enhanceContent()">
                                                    <i class="fas fa-wand-magic-sparkles"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm" onclick="formatContent()">
                                                    <i class="fas fa-magic"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="char-counter" id="contentCounter">
                                            عدد الأحرف: <span id="contentChars">0</span>
                                        </div>
                                        @error('content')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- أدوات المحرر -->
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <button type="button" class="ai-tool-btn" onclick="addSection('introduction')">
                                            <i class="fas fa-plus"></i>
                                            <span>إضافة مقدمة</span>
                                        </button>
                                        <button type="button" class="ai-tool-btn" onclick="addSection('conclusion')">
                                            <i class="fas fa-flag"></i>
                                            <span>إضافة خاتمة</span>
                                        </button>
                                        <button type="button" class="ai-tool-btn" onclick="addSection('faq')">
                                            <i class="fas fa-question"></i>
                                            <span>إضافة أسئلة شائعة</span>
                                        </button>
                                        <button type="button" class="ai-tool-btn" onclick="addSection('contact')">
                                            <i class="fas fa-phone"></i>
                                            <span>إضافة معلومات اتصال</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- تبويب SEO -->
                                <div class="tab-pane fade" id="seo" role="tabpanel">
                                    <div class="mb-3">
                                        <label for="meta_title" class="form-label">
                                            <i class="fas fa-search me-2"></i>Meta Title
                                        </label>
                                        <div class="position-relative">
                                            <input type="text"
                                                class="form-control @error('meta_title') is-invalid @enderror"
                                                id="meta_title" name="meta_title" value="{{ old('meta_title') }}"
                                                placeholder="عنوان الصفحة لمحركات البحث">
                                            <div class="ai-content-buttons">
                                                <button type="button" class="btn btn-sm" onclick="generateMetaTitle()">
                                                    <i class="fas fa-robot"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="char-counter" id="metaTitleCounter">
                                            عدد الأحرف: <span id="metaTitleChars">0</span> (مثالي: 50-60 حرف)
                                        </div>
                                        @error('meta_title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="meta_description" class="form-label">
                                            <i class="fas fa-align-left me-2"></i>Meta Description
                                        </label>
                                        <div class="position-relative">
                                            <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                                name="meta_description" rows="4" placeholder="وصف الصفحة لمحركات البحث">{{ old('meta_description') }}</textarea>
                                            <div class="ai-content-buttons">
                                                <button type="button" class="btn btn-sm"
                                                    onclick="generateMetaDescription()">
                                                    <i class="fas fa-robot"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="char-counter" id="metaDescCounter">
                                            عدد الأحرف: <span id="metaDescChars">0</span> (مثالي: 150-160 حرف)
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
                                            id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}"
                                            placeholder="كلمات مفتاحية مفصولة بفواصل">
                                        <div class="ai-content-buttons" style="top: -35px; left: 10px;">
                                            <button type="button" class="btn btn-sm" onclick="generateKeywords()">
                                                <i class="fas fa-robot"></i>
                                            </button>
                                        </div>
                                        @error('meta_keywords')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- تبويب المعاينة -->
                                <div class="tab-pane fade" id="preview" role="tabpanel">
                                    <div class="preview-content" id="pagePreview">
                                        <p class="text-muted">سيظهر محتوى الصفحة هنا بعد ملء الحقول...</p>
                                    </div>

                                    <div class="mt-3">
                                        <h6>معلومات إضافية:</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <strong>الرابط:</strong>
                                                    <span id="previewSlug" class="text-muted">/page/</span>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>الحالة:</strong>
                                                    <span id="previewStatus" class="text-muted">غير محدد</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <strong>Meta Title:</strong>
                                                    <span id="previewMetaTitle" class="text-muted">غير محدد</span>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Meta Description:</strong>
                                                    <span id="previewMetaDesc" class="text-muted">غير محدد</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- أزرار الحفظ -->
                            <div class="save-buttons">
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-redo me-2"></i>إعادة تعيين
                                </button>
                                <a href="{{ route('admin.static-pages.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>إلغاء
                                </a>
                                <button type="button" class="btn btn-primary ai-button-primary"
                                    onclick="generateAndSave()">
                                    <i class="fas fa-robot me-2"></i>إنشاء وحفظ بالذكاء الاصطناعي
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>حفظ الصفحة
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
                    },
                    onInit: function() {
                        updateCharCount('content', $('#content').val());
                    }
                }
            });

            // توليد slug من العنوان
            $('#title').on('keyup blur', function() {
                const title = $(this).val();
                if (title && !$('#slug').val()) {
                    const slug = generateSlug(title);
                    $('#slug').val(slug);
                    updatePreview();
                }
                updateCharCount('title', title);
            });

            // تحديث تعداد الأحرف
            $('#title, #meta_title, #meta_description').on('keyup', function() {
                const field = $(this).attr('id');
                const text = $(this).val();
                updateCharCount(field, text);
                updatePreview();
            });

            // تحديث المعاينة
            $('input, textarea, select').on('change keyup', function() {
                updatePreview();
            });

            // التحقق من النموذج
            $('#createPageForm').on('submit', function(e) {
                const title = $('#title').val();
                const content = $('#content').val();

                if (!title.trim()) {
                    e.preventDefault();
                    showToast('خطأ', 'الرجاء إدخال عنوان الصفحة', 'error');
                    return false;
                }

                if (!content.trim()) {
                    e.preventDefault();
                    showToast('خطأ', 'الرجاء إدخال محتوى الصفحة', 'error');
                    return false;
                }

                return true;
            });
        });

        // ============================================
        // دوال المساعدة
        // ============================================

        let selectedTemplate = 'custom';
        let selectedPageType = 'regular';
        let aiProcessActive = false;

        // توليد slug
        function generateSlug(text) {
            return text
                .toLowerCase()
                .replace(/[^\u0600-\u06FF\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/--+/g, '-')
                .trim();
        }

        // تحديث تعداد الأحرف
        function updateCharCount(field, text) {
            const chars = text ? text.length : 0;

            switch (field) {
                case 'title':
                    $('#titleChars').text(chars);
                    $('#titleCounter').toggleClass('warning', chars > 60);
                    $('#titleCounter').toggleClass('danger', chars > 70);
                    break;

                case 'content':
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

        // تحديث المعاينة
        function updatePreview() {
            const title = $('#title').val() || 'عنوان الصفحة';
            const content = $('#content').val() || 'محتوى الصفحة...';
            const slug = $('#slug').val() || 'slug';
            const status = $('#status').val();
            const metaTitle = $('#meta_title').val() || title;
            const metaDesc = $('#meta_description').val() || 'وصف الصفحة...';

            // تحديث معاينة المحتوى
            const previewHtml = `
                <h1>${title}</h1>
                ${content}
            `;
            $('#pagePreview').html(previewHtml);

            // تحديث المعلومات الإضافية
            $('#previewSlug').text(`/page/${slug}`);
            $('#previewStatus').text(status === 'active' ? 'نشط' : 'غير نشط');
            $('#previewMetaTitle').text(metaTitle);
            $('#previewMetaDesc').text(metaDesc);
        }

        // ============================================
        // دوال الذكاء الاصطناعي
        // ============================================

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

            setTimeout(() => {
                $(`#${toastId}`).remove();
            }, 5000);
        }

        // اختيار قالب
        function selectTemplate(template) {
            selectedTemplate = template;
            $('.template-card').removeClass('active');
            $(`.template-card[data-template="${template}"]`).addClass('active');

            if (template !== 'custom') {
                loadTemplate(template);
            }
        }

        // اختيار نوع الصفحة
        function selectPageType(type) {
            selectedPageType = type;
            $('.page-type-btn').removeClass('active');
            $(`.page-type-btn[data-type="${type}"]`).addClass('active');
        }

        // تحميل قالب
        function loadTemplate(template) {
            showLoading('تحميل القالب', 'جاري تحميل القالب المحدد...');

            $.ajax({
                url: '{{ route('admin.static-pages.ai.load-template') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    template: template,
                    page_type: selectedPageType
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();

                            // تعبئة الحقول
                            if (response.data.title) {
                                $('#title').val(response.data.title);
                                updateCharCount('title', response.data.title);

                                const slug = generateSlug(response.data.title);
                                $('#slug').val(slug);
                            }

                            if (response.data.content) {
                                $('#content').summernote('code', response.data.content);
                                updateCharCount('content', response.data.content);
                            }

                            if (response.data.meta_title) {
                                $('#meta_title').val(response.data.meta_title);
                                updateCharCount('meta_title', response.data.meta_title);
                            }

                            if (response.data.meta_description) {
                                $('#meta_description').val(response.data.meta_description);
                                updateCharCount('meta_description', response.data.meta_description);
                            }

                            if (response.data.meta_keywords) {
                                $('#meta_keywords').val(response.data.meta_keywords);
                            }

                            updatePreview();
                            showToast('تم التحميل', `تم تحميل قالب ${template} بنجاح`, 'success');
                        }, 500);
                    } else {
                        hideLoading();
                        showToast('خطأ', response.message || 'حدث خطأ أثناء تحميل القالب', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    console.error('خطأ AJAX:', error);
                    showToast('خطأ', 'حدث خطأ في الاتصال بالخادم', 'error');
                }
            });
        }

        // إنشاء من الوصف
        function generateFromPrompt() {
            const prompt = $('#aiPrompt').val();

            if (!prompt.trim()) {
                showToast('تحذير', 'الرجاء إدخال وصف للصفحة', 'warning');
                return;
            }

            showLoading('إنشاء الصفحة', 'جاري إنشاء الصفحة بناءً على الوصف...');
            updateProgress(20);

            $.ajax({
                url: '{{ route('admin.static-pages.ai.generate-from-prompt') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    prompt: prompt,
                    page_type: selectedPageType
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();

                            // تعبئة الحقول
                            if (response.data.title) {
                                $('#title').val(response.data.title);
                                updateCharCount('title', response.data.title);

                                const slug = generateSlug(response.data.title);
                                $('#slug').val(slug);
                            }

                            if (response.data.content) {
                                $('#content').summernote('code', response.data.content);
                                updateCharCount('content', response.data.content);
                            }

                            if (response.data.meta_title) {
                                $('#meta_title').val(response.data.meta_title);
                                updateCharCount('meta_title', response.data.meta_title);
                            }

                            if (response.data.meta_description) {
                                $('#meta_description').val(response.data.meta_description);
                                updateCharCount('meta_description', response.data.meta_description);
                            }

                            if (response.data.meta_keywords) {
                                $('#meta_keywords').val(response.data.meta_keywords);
                            }

                            updatePreview();
                            showToast('تم الإنشاء', 'تم إنشاء الصفحة بنجاح', 'success');
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

        // إنشاء بالذكاء الاصطناعي
        function generateFromAI() {
            Swal.fire({
                title: 'إنشاء بالذكاء الاصطناعي',
                html: `
                    <div class="mb-3">
                        <label for="pageType" class="form-label">نوع الصفحة</label>
                        <select id="pageType" class="form-select">
                            <option value="privacy">سياسة الخصوصية</option>
                            <option value="terms">الشروط والأحكام</option>
                            <option value="about">من نحن</option>
                            <option value="contact">اتصل بنا</option>
                            <option value="faq">الأسئلة الشائعة</option>
                            <option value="custom">مخصص</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="pageTone" class="form-label">نبرة النص</label>
                        <select id="pageTone" class="form-select">
                            <option value="formal">رسمي</option>
                            <option value="friendly">ودود</option>
                            <option value="professional">مهني</option>
                            <option value="simple">بسيط</option>
                        </select>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'إنشاء',
                cancelButtonText: 'إلغاء',
                preConfirm: () => {
                    const pageType = document.getElementById('pageType').value;
                    const pageTone = document.getElementById('pageTone').value;

                    return {
                        pageType,
                        pageTone
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('إنشاء الصفحة', 'جاري إنشاء الصفحة باستخدام الذكاء الاصطناعي...');

                    $.ajax({
                        url: '{{ route('admin.static-pages.ai.generate-page') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            page_type: result.value.pageType,
                            tone: result.value.pageTone
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                updateProgress(100);
                                setTimeout(() => {
                                    hideLoading();

                                    // تعبئة الحقول
                                    if (response.data.title) {
                                        $('#title').val(response.data.title);
                                        updateCharCount('title', response.data.title);

                                        const slug = generateSlug(response.data.title);
                                        $('#slug').val(slug);
                                    }

                                    if (response.data.content) {
                                        $('#content').summernote('code', response.data.content);
                                        updateCharCount('content', response.data.content);
                                    }

                                    if (response.data.meta_title) {
                                        $('#meta_title').val(response.data.meta_title);
                                        updateCharCount('meta_title', response.data.meta_title);
                                    }

                                    if (response.data.meta_description) {
                                        $('#meta_description').val(response.data
                                            .meta_description);
                                        updateCharCount('meta_description', response.data
                                            .meta_description);
                                    }

                                    if (response.data.meta_keywords) {
                                        $('#meta_keywords').val(response.data.meta_keywords);
                                    }

                                    updatePreview();
                                    showToast('تم الإنشاء', 'تم إنشاء الصفحة بنجاح', 'success');
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

        // إنشاء العنوان
        function generateTitle() {
            const pageType = selectedTemplate;

            showLoading('إنشاء العنوان', 'جاري إنشاء عنوان مناسب...');

            $.ajax({
                url: '{{ route('admin.static-pages.ai.generate-title') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    page_type: pageType,
                    page_category: selectedPageType
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $('#title').val(response.title);
                            updateCharCount('title', response.title);

                            const slug = generateSlug(response.title);
                            $('#slug').val(slug);

                            updatePreview();
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

        // إنشاء المحتوى
        function generateContent() {
            const pageType = selectedTemplate;
            const title = $('#title').val();

            if (!title) {
                showToast('تحذير', 'الرجاء إدخال عنوان الصفحة أولاً', 'warning');
                return;
            }

            showLoading('إنشاء المحتوى', 'جاري إنشاء محتوى الصفحة...');
            updateProgress(30);

            $.ajax({
                url: '{{ route('admin.static-pages.ai.generate-content') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    title: title,
                    page_type: pageType,
                    page_category: selectedPageType
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $('#content').summernote('code', response.content);
                            updateCharCount('content', response.content);
                            updatePreview();
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
        function enhanceContent() {
            const content = $('#content').val();

            if (!content) {
                showToast('تحذير', 'الرجاء إدخال محتوى أولاً', 'warning');
                return;
            }

            showLoading('تحسين المحتوى', 'جاري تحسين محتوى الصفحة...');

            $.ajax({
                url: '{{ route('admin.static-pages.ai.enhance-content') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    content: content,
                    action: 'enhance'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $('#content').summernote('code', response.enhanced_content);
                            updateCharCount('content', response.enhanced_content);
                            updatePreview();
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

        // تنسيق المحتوى
        function formatContent() {
            const content = $('#content').val();

            if (!content) {
                showToast('تحذير', 'الرجاء إدخال محتوى أولاً', 'warning');
                return;
            }

            showLoading('تنسيق المحتوى', 'جاري تنسيق محتوى الصفحة...');

            $.ajax({
                url: '{{ route('admin.static-pages.ai.format-content') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    content: content
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $('#content').summernote('code', response.formatted_content);
                            updateCharCount('content', response.formatted_content);
                            updatePreview();
                            showToast('تم التنسيق', 'تم تنسيق المحتوى بنجاح', 'success');
                        }, 500);
                    } else {
                        hideLoading();
                        showToast('خطأ', response.message || 'حدث خطأ أثناء التنسيق', 'error');
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
            const title = $('#title').val();

            if (!title) {
                showToast('تحذير', 'الرجاء إدخال عنوان الصفحة أولاً', 'warning');
                return;
            }

            showLoading('إنشاء عنوان SEO', 'جاري إنشاء عنوان SEO محسن...');

            $.ajax({
                url: '{{ route('admin.static-pages.ai.generate-meta-title') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    title: title
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $('#meta_title').val(response.meta_title);
                            updateCharCount('meta_title', response.meta_title);
                            updatePreview();
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
            const content = $('#content').val();

            if (!content) {
                showToast('تحذير', 'الرجاء إدخال محتوى الصفحة أولاً', 'warning');
                return;
            }

            showLoading('إنشاء وصف SEO', 'جاري إنشاء وصف SEO محسن...');

            $.ajax({
                url: '{{ route('admin.static-pages.ai.generate-meta-description') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    content: content
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $('#meta_description').val(response.meta_description);
                            updateCharCount('meta_description', response.meta_description);
                            updatePreview();
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

        // إنشاء كلمات مفتاحية
        function generateKeywords() {
            const content = $('#content').val();

            if (!content) {
                showToast('تحذير', 'الرجاء إدخال محتوى الصفحة أولاً', 'warning');
                return;
            }

            showLoading('إنشاء كلمات مفتاحية', 'جاري إنشاء كلمات مفتاحية مناسبة...');

            $.ajax({
                url: '{{ route('admin.static-pages.ai.generate-keywords') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    content: content
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $('#meta_keywords').val(response.keywords);
                            updatePreview();
                            showToast('تم الإنشاء', 'تم إنشاء الكلمات المفتاحية بنجاح', 'success');
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

        // إنشاء SEO
        function generateSEO() {
            const title = $('#title').val();
            const content = $('#content').val();

            if (!title) {
                showToast('تحذير', 'الرجاء إدخال عنوان الصفحة أولاً', 'warning');
                return;
            }

            showLoading('إنشاء SEO', 'جاري إنشاء محتوى SEO كامل...');
            updateProgress(30);

            // إنشاء العنوان والوصف والكلمات المفتاحية معاً
            setTimeout(() => {
                updateProgress(50);
                generateMetaTitle();
            }, 1000);

            setTimeout(() => {
                updateProgress(70);
                generateMetaDescription();
            }, 3000);

            setTimeout(() => {
                updateProgress(90);
                generateKeywords();
            }, 5000);

            setTimeout(() => {
                updateProgress(100);
                setTimeout(() => {
                    hideLoading();
                    showToast('تم إنشاء SEO', 'تم إنشاء محتوى SEO كامل بنجاح', 'success');
                }, 500);
            }, 7000);
        }

        // تدقيق لغوي
        function checkGrammar() {
            const content = $('#content').val();

            if (!content) {
                showToast('تحذير', 'الرجاء إدخال محتوى أولاً', 'warning');
                return;
            }

            showLoading('تدقيق لغوي', 'جاري تدقيق المحتوى لغوياً...');

            $.ajax({
                url: '{{ route('admin.static-pages.ai.check-grammar') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    content: content
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();

                            if (response.corrections && response.corrections.length > 0) {
                                let correctionsHtml =
                                    '<div style="text-align: right; max-height: 300px; overflow-y: auto;">';
                                response.corrections.forEach(correction => {
                                    correctionsHtml += `
                                        <div class="mb-2 p-2 border rounded">
                                            <strong>التصحيح:</strong> ${correction.correction}<br>
                                            <small class="text-muted">${correction.explanation}</small>
                                        </div>
                                    `;
                                });
                                correctionsHtml += '</div>';

                                Swal.fire({
                                    title: 'نتائج التدقيق اللغوي',
                                    html: correctionsHtml,
                                    icon: 'info',
                                    showCancelButton: true,
                                    confirmButtonText: 'تطبيق التصحيحات',
                                    cancelButtonText: 'إلغاء'
                                }).then((result) => {
                                    if (result.isConfirmed && response.corrected_content) {
                                        $('#content').summernote('code', response
                                            .corrected_content);
                                        updateCharCount('content', response.corrected_content);
                                        updatePreview();
                                        showToast('تم التصحيح', 'تم تطبيق التصحيحات بنجاح',
                                            'success');
                                    }
                                });
                            } else {
                                showToast('تم التدقيق', 'لا توجد أخطاء لغوية في المحتوى', 'success');
                            }
                        }, 500);
                    } else {
                        hideLoading();
                        showToast('خطأ', response.message || 'حدث خطأ أثناء التدقيق', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    console.error('خطأ AJAX:', error);
                    showToast('خطأ', 'حدث خطأ في الاتصال بالخادم', 'error');
                }
            });
        }

        // تحسين النص
        function enhanceWithAI() {
            const content = $('#content').val();

            if (!content) {
                showToast('تحذير', 'الرجاء إدخال محتوى أولاً', 'warning');
                return;
            }

            showLoading('تحسين النص', 'جاري تحسين النص باستخدام الذكاء الاصطناعي...');

            $.ajax({
                url: '{{ route('admin.static-pages.ai.enhance-text') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    content: content
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $('#content').summernote('code', response.enhanced_text);
                            updateCharCount('content', response.enhanced_text);
                            updatePreview();
                            showToast('تم التحسين', 'تم تحسين النص بنجاح', 'success');
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

        // إضافة قسم
        function addSection(sectionType) {
            const currentContent = $('#content').val();

            showLoading('إضافة قسم', `جاري إضافة قسم ${sectionType}...`);

            $.ajax({
                url: '{{ route('admin.static-pages.ai.add-section') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    section_type: sectionType,
                    current_content: currentContent,
                    page_type: selectedTemplate
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();

                            const newContent = currentContent + '\n\n' + response.section_content;
                            $('#content').summernote('code', newContent);
                            updateCharCount('content', newContent);
                            updatePreview();
                            showToast('تم الإضافة', `تم إضافة قسم ${sectionType} بنجاح`, 'success');
                        }, 500);
                    } else {
                        hideLoading();
                        showToast('خطأ', response.message || 'حدث خطأ أثناء إضافة القسم', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    console.error('خطأ AJAX:', error);
                    showToast('خطأ', 'حدث خطأ في الاتصال بالخادم', 'error');
                }
            });
        }

        // إنشاء وحفظ بالذكاء الاصطناعي
        function generateAndSave() {
            Swal.fire({
                title: 'إنشاء وحفظ بالذكاء الاصطناعي',
                text: 'سيتم إنشاء الصفحة بالكامل ثم حفظها. هل تريد المتابعة؟',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'نعم، إنشاء وحفظ',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('إنشاء وحفظ', 'جاري إنشاء الصفحة وحفظها...');

                    // تسلسل عمليات الإنشاء ثم الحفظ
                    setTimeout(() => {
                        updateProgress(20);
                        generateFromAI();
                    }, 1000);

                    setTimeout(() => {
                        updateProgress(40);
                        generateSEO();
                    }, 3000);

                    setTimeout(() => {
                        updateProgress(60);
                        enhanceWithAI();
                    }, 6000);

                    setTimeout(() => {
                        updateProgress(80);
                        checkGrammar();
                    }, 9000);

                    setTimeout(() => {
                        updateProgress(100);
                        setTimeout(() => {
                            hideLoading();
                            $('#createPageForm').submit();
                        }, 1000);
                    }, 12000);
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
                    $('#createPageForm')[0].reset();
                    $('#content').summernote('code', '');
                    $('#aiPrompt').val('');
                    $('.template-card').removeClass('active');
                    $('.template-card[data-template="custom"]').addClass('active');
                    $('.page-type-btn').removeClass('active');
                    $('.page-type-btn[data-type="regular"]').addClass('active');

                    updateCharCount('title', '');
                    updateCharCount('content', '');
                    updateCharCount('meta_title', '');
                    updateCharCount('meta_description', '');
                    updatePreview();

                    showToast('تمت الإعادة', 'تم إعادة تعيين النموذج بنجاح', 'success');
                }
            });
        }
    </script>
@endsection
