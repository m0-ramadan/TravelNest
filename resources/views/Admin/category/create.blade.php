@extends('Admin.layout.master')

@section('title', 'إضافة قسم جديد')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: "Cairo", sans-serif !important;
        }

        .language-tab {
            padding: 8px 15px;
            border: 1px solid #dee2e6;
            background: rgba(105, 108, 255, 0.2);
            cursor: pointer;
            transition: all 0.3s;
            border-radius: 5px 5px 0 0;
            margin-right: 5px;
        }

        .language-tab.active {
            background: rgba(135, 136, 182, 0.2);
            border-bottom-color: #fff;
            color: #696cff;
            font-weight: 600;
        }

        .language-content {
            display: none;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 0 5px 5px 5px;
            background: rgba(105, 108, 255, 0.2);
        }

        .language-content.active {
            display: block;
        }

        .category-image-preview {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            padding: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #dee2e6;
        }

        .category-image-preview:hover {
            border-color: #696cff;
            transform: scale(1.02);
        }

        .image-upload-container {
            position: relative;
            display: inline-block;
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }

        .image-upload-container:hover .image-overlay {
            opacity: 1;
        }

        .image-overlay i {
            color: white;
            font-size: 24px;
        }

        .slug-input-group {
            position: relative;
        }

        .slug-input-group .form-control {
            padding-right: 40px;
        }

        .slug-input-group .btn {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .nav-tabs .nav-link {
            border: none;
            padding: 10px 20px;
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            color: #696cff;
            border-bottom: 2px solid #696cff;
            background-color: transparent;
        }

        .form-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
        }

        .required::after {
            content: " *";
            color: #dc3545;
        }

        .slug-preview {
            background: #e9ecef;
            padding: 8px 12px;
            border-radius: 5px;
            margin-top: 5px;
            font-family: monospace;
            font-size: 14px;
        }

        .slug-preview a {
            color: #696cff;
            text-decoration: none;
        }

        .slug-preview a:hover {
            text-decoration: underline;
        }

        .ai-features-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            color: white;
        }

        .ai-features-section label {
            color: white;
        }

        .input-group-with-ai {
            position: relative;
        }

        .input-group-with-ai .ai-buttons {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            gap: 5px;
            z-index: 5;
        }

        .input-group-with-ai .form-control {
            padding-left: 100px;
        }

        .textarea-with-ai {
            position: relative;
        }

        .textarea-with-ai .ai-buttons {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 5;
            display: flex;
            gap: 5px;
        }

        .textarea-with-ai .form-control {
            padding-top: 50px;
        }

        .preview-content {
            min-height: 50px;
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 10px;
            margin-top: 10px;
        }

        .translation-completed {
            animation: slideIn 0.5s ease-out;
            padding: 15px;
            background: linear-gradient(90deg, #d4edda 0%, #c3e6cb 100%);
            border-radius: 8px;
            border: 1px solid #c3e6cb;
            margin-bottom: 10px;
        }

        .loading-translation {
            background-color: rgba(248, 249, 250, 0.8);
            border-radius: 8px;
            transition: all 0.3s ease;
            padding: 20px;
            text-align: center;
            border: 2px dashed #dee2e6;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .spin {
            animation: spin 1s linear infinite;
            display: inline-block;
        }

        .toast-container {
            z-index: 99999;
        }

        .toast {
            min-width: 350px;
            max-width: 500px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.375rem;
        }

        .card.ai-enhanced {
            border-left: 4px solid #696cff;
            box-shadow: 0 0.125rem 0.25rem rgba(105, 108, 255, 0.2);
        }

        .progress-bar {
            transition: width 0.3s ease;
        }

        .badge-count {
            background: #696cff;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            margin-right: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}">الرئيسية</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.categories.index') }}">الأقسام</a>
                </li>
                <li class="breadcrumb-item active">إضافة قسم جديد</li>
            </ol>
        </nav>

        <div class="row">
            <!-- العمود الرئيسي -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>
                            إضافة قسم جديد
                        </h5>
                        <div class="btn-group">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-right me-1"></i> رجوع
                            </a>
                            @if (request('parent_id'))
                                <a href="{{ route('admin.categories.edit', request('parent_id')) }}"
                                    class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-level-up-alt me-1"></i> القسم الرئيسي
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- رسائل الذكاء الاصطناعي -->
                        <div id="ai-messages" style="display: none;">
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                <span id="ai-message-text"></span>
                            </div>
                        </div>

                        <!-- إحصائيات اللغات -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between bg-light p-3 rounded">
                                    <div>
                                        <h6 class="mb-1">اللغات المتاحة</h6>
                                        <p class="text-muted mb-0" id="languages-count">
                                            القسم مدعوم بـ {{ $languages->count() }} لغات
                                        </p>
                                    </div>
                                    <div class="d-flex gap-2">
                                        @foreach ($languages as $language)
                                            <span class="badge bg-primary">{{ $language->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data"
                            id="createCategoryForm">
                            @csrf

                            <!-- قسم إعدادات الذكاء الاصطناعي -->
                            <div class="ai-features-section">
                                <h6 class="mb-3">
                                    <i class="fas fa-robot me-2"></i>
                                    إعدادات الذكاء الاصطناعي
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="translation_style" class="form-label">أسلوب الترجمة</label>
                                        <select class="form-select" id="translation_style" name="translation_style">
                                            <option value="formal" selected>ترجمة رسمية ومهنية</option>
                                            <option value="simplified">ترجمة مبسطة وسهلة</option>
                                            <option value="seo">ترجمة محسنة لمحركات البحث</option>
                                            <option value="creative">ترجمة إبداعية وجذابة</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tone" class="form-label">نبرة النص</label>
                                        <select class="form-select" id="tone" name="tone">
                                            <option value="neutral" selected>محايد</option>
                                            <option value="friendly">ودود</option>
                                            <option value="professional">مهني</option>
                                            <option value="enthusiastic">متحمس</option>
                                            <option value="persuasive">مقنع</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="text-center mt-2">
                                    <button type="button" class="btn btn-light" id="generate-with-ai">
                                        <i class="fas fa-wand-magic-sparkles me-1"></i>
                                        إنشاء قسم بالذكاء الاصطناعي
                                    </button>
                                    <button type="button" class="btn btn-outline-light" id="translate-all-btn">
                                        <i class="fas fa-language me-1"></i>
                                        ترجمة لجميع اللغات
                                    </button>
                                </div>
                            </div>

                            <!-- تبادل اللغات الرئيسي -->
                            <div class="mb-4">
                                <div class="language-tabs d-flex border-bottom">
                                    <button type="button" class="language-tab active" data-target="ar">
                                        <i class="fas fa-language me-1"></i> العربية
                                    </button>
                                    @foreach ($languages as $language)
                                        @if ($language->code != 'ar')
                                            <button type="button" class="language-tab"
                                                data-target="{{ $language->code }}">
                                                <i class="fas fa-globe me-1"></i> {{ $language->name }}
                                            </button>
                                        @endif
                                    @endforeach
                                </div>

                                <!-- المحتوى العربي -->
                                <div class="language-content active" data-lang="ar">
                                    <div class="card ai-enhanced">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label for="name_ar" class="form-label required">اسم القسم</label>
                                                    <div class="input-group-with-ai">
                                                        <div class="ai-buttons">
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-primary ai-enhance-btn"
                                                                data-target="#name_ar" data-type="title">
                                                                <i class="fas fa-wand-magic-sparkles"></i>
                                                            </button>
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-success ai-seo-btn"
                                                                data-target="#name_ar" data-type="seo_title">
                                                                <i class="fas fa-search"></i>
                                                            </button>
                                                        </div>
                                                        <input type="text" class="form-control" id="name_ar"
                                                            name="name[ar]" value="{{ old('name.ar') }}" required
                                                            placeholder="اسم القسم بالعربية">
                                                    </div>
                                                    <small class="text-muted">اسم واضح ومعبر عن محتوى القسم</small>
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <label for="parent_id" class="form-label">القسم الرئيسي</label>
                                                    <select class="form-select select2" id="parent_id" name="parent_id">
                                                        <option value="">قسم رئيسي (بدون أب)</option>
                                                        @foreach ($parentCategories as $parent)
                                                            <option value="{{ $parent->id }}"
                                                                {{ old('parent_id', request('parent_id')) == $parent->id ? 'selected' : '' }}>
                                                                {{ $parent->getTranslation('name', 'ar') }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-muted">اختياري - لإنشاء هيكل هرمي للأقسام</small>
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <label for="description_ar" class="form-label">الوصف</label>
                                                    <div class="textarea-with-ai">
                                                        <div class="ai-buttons">
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-primary ai-enhance-btn"
                                                                data-target="#description_ar" data-type="description">
                                                                <i class="fas fa-wand-magic-sparkles"></i>
                                                            </button>
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-success ai-complete-btn"
                                                                data-target="#description_ar" data-type="description">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                        <textarea class="form-control summernote" id="description_ar" name="description[ar]" rows="4"
                                                            placeholder="وصف القسم بالعربية">{{ old('description.ar') }}</textarea>
                                                    </div>
                                                    <small class="text-muted">وصف مختصر يظهر في صفحات القسم</small>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="order" class="form-label">ترتيب العرض</label>
                                                    <input type="number" class="form-control" id="order"
                                                        name="order" value="{{ old('order', 0) }}" min="0">
                                                    <small class="text-muted">رقم يحدد ترتيب ظهور القسم</small>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="status_id" class="form-label required">الحالة</label>
                                                    <select class="form-select" id="status_id" name="status_id" required>
                                                        <option value="1"
                                                            {{ old('status_id') == 1 ? 'selected' : '' }}>
                                                            <i class="fas fa-check-circle"></i> نشط
                                                        </option>
                                                        <option value="2"
                                                            {{ old('status_id') == 2 ? 'selected' : '' }}>
                                                            <i class="fas fa-times-circle"></i> غير نشط
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- محتوى اللغات الأخرى -->
                                @foreach ($languages as $language)
                                    @if ($language->code != 'ar')
                                        <div class="language-content" data-lang="{{ $language->code }}">
                                            <div class="card">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0">
                                                        <i class="fas fa-globe me-2"></i>
                                                        {{ $language->name }}
                                                        <span class="badge bg-info ms-2">ترجمة تلقائية</span>
                                                    </h6>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary translate-btn"
                                                        data-lang="{{ $language->code }}">
                                                        <i class="fas fa-robot me-1"></i>
                                                        ترجمة تلقائية
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12 mb-3">
                                                            <label for="name_{{ $language->code }}"
                                                                class="form-label">اسم القسم</label>
                                                            <input type="text" class="form-control"
                                                                id="name_{{ $language->code }}"
                                                                name="name[{{ $language->code }}]"
                                                                value="{{ old('name.' . $language->code) }}"
                                                                placeholder="اسم القسم بـ{{ $language->name }}">
                                                        </div>

                                                        <div class="col-md-12 mb-3">
                                                            <label for="description_{{ $language->code }}"
                                                                class="form-label">الوصف</label>
                                                            <textarea class="form-control" id="description_{{ $language->code }}" name="description[{{ $language->code }}]"
                                                                rows="4" placeholder="وصف القسم بـ{{ $language->name }}">{{ old('description.' . $language->code) }}</textarea>
                                                        </div>
                                                    </div>

                                                    <!-- معاينة الترجمة -->
                                                    <div id="preview_{{ $language->code }}" class="preview-content">
                                                        <p class="text-muted mb-0">سيتم عرض الترجمة هنا...</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- الصور -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-images me-2"></i>
                                        الصور
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="mb-3">صورة القسم الرئيسية</h6>
                                            <div class="text-center mb-3">
                                                <div class="image-upload-container">
                                                    <img src="https://via.placeholder.com/200x200?text=صورة+القسم"
                                                        alt="صورة القسم" class="category-image-preview"
                                                        id="imagePreview">
                                                    <div class="image-overlay" onclick="$('#image').click()">
                                                        <i class="fas fa-camera"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="image" class="form-label">الصورة</label>
                                                <input type="file" class="form-control" id="image" name="image"
                                                    accept="image/*" onchange="previewImage(this, 'imagePreview')">
                                                <small class="text-muted">الصيغ المسموحة: JPEG, PNG, JPG, GIF, SVG, WEBP.
                                                    الحد الأقصى: 2 ميجابايت</small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <h6 class="mb-3">الصورة الفرعية</h6>
                                            <div class="text-center mb-3">
                                                <div class="image-upload-container">
                                                    <img src="https://via.placeholder.com/200x200?text=صورة+فرعية"
                                                        alt="صورة فرعية" class="category-image-preview"
                                                        id="subImagePreview">
                                                    <div class="image-overlay" onclick="$('#sub_image').click()">
                                                        <i class="fas fa-camera"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="sub_image" class="form-label">الصورة الفرعية</label>
                                                <input type="file" class="form-control" id="sub_image"
                                                    name="sub_image" accept="image/*"
                                                    onchange="previewImage(this, 'subImagePreview')">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="appear_in_home"
                                                    value="1" id="appear_in_home"
                                                    {{ old('appear_in_home') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="appear_in_home">
                                                    <i class="fas fa-home me-1"></i>
                                                    إظهار كاسلايدر في صفحة الهوم
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SEO بالذكاء الاصطناعي -->
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-search me-2"></i>
                                        تحسين محركات البحث (SEO)
                                    </h5>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="generate-seo">
                                        <i class="fas fa-robot me-1"></i>
                                        إنشاء SEO بالذكاء الاصطناعي
                                    </button>
                                </div>
                                <div class="card-body">
                                    <!-- تبادل اللغات لـ SEO -->
                                    <div class="language-tabs d-flex border-bottom mb-3">
                                        <button type="button" class="language-tab active"
                                            data-seo-target="ar">العربية</button>
                                        @foreach ($languages as $language)
                                            @if ($language->code != 'ar')
                                                <button type="button" class="language-tab"
                                                    data-seo-target="{{ $language->code }}">
                                                    {{ $language->name }}
                                                </button>
                                            @endif
                                        @endforeach
                                    </div>

                                    <!-- SEO العربي -->
                                    <div class="language-seo-content active" data-lang="ar">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="slug" class="form-label">الرابط (Slug)</label>
                                                <div class="slug-input-group">
                                                    <input type="text" class="form-control" id="slug"
                                                        name="slug" value="{{ old('slug') }}" dir="ltr">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        onclick="generateSlug()">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </div>
                                                <small class="text-muted">رابط SEO الخاص بالقسم. اتركه فارغاً لإنشاء رابط
                                                    تلقائي</small>
                                                <div class="slug-preview mt-2" id="slugPreview">
                                                    رابط القسم:
                                                    <a href="#" target="_blank">
                                                        {{ url('/categories') }}/<span id="slugValue">رابط-القسم</span>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <label for="meta_title_ar" class="form-label">عنوان الصفحة (Meta
                                                    Title)</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="meta_title_ar"
                                                        name="meta_title[ar]" value="{{ old('meta_title.ar') }}"
                                                        placeholder="عنوان محسن لمحركات البحث">
                                                    <button type="button" class="btn btn-outline-primary ai-seo-btn"
                                                        data-target="#meta_title_ar" data-type="meta_title">
                                                        <i class="fas fa-robot"></i>
                                                    </button>
                                                </div>
                                                <small class="text-muted">الطول الموصى به: 50-60 حرفاً</small>
                                                <div class="progress mt-1" style="height: 5px;">
                                                    <div class="progress-bar" id="titleProgress" role="progressbar">
                                                    </div>
                                                </div>
                                                <small class="text-muted text-end d-block" id="titleCount">0/60</small>
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <label for="meta_description_ar" class="form-label">وصف الصفحة (Meta
                                                    Description)</label>
                                                <div class="input-group">
                                                    <textarea class="form-control" id="meta_description_ar" name="meta_description[ar]" rows="3"
                                                        placeholder="وصف محسن لمحركات البحث">{{ old('meta_description.ar') }}</textarea>
                                                    <button type="button" class="btn btn-outline-primary ai-seo-btn"
                                                        data-target="#meta_description_ar" data-type="meta_description">
                                                        <i class="fas fa-robot"></i>
                                                    </button>
                                                </div>
                                                <small class="text-muted">الطول الموصى به: 150-160 حرفاً</small>
                                                <div class="progress mt-1" style="height: 5px;">
                                                    <div class="progress-bar" id="descProgress" role="progressbar"></div>
                                                </div>
                                                <small class="text-muted text-end d-block" id="descCount">0/160</small>
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <label for="meta_keywords_ar" class="form-label">الكلمات المفتاحية</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="meta_keywords_ar"
                                                        name="meta_keywords[ar]" value="{{ old('meta_keywords.ar') }}"
                                                        placeholder="كلمات مفتاحية مفصولة بفواصل">
                                                    <button type="button" class="btn btn-outline-primary ai-seo-btn"
                                                        data-target="#meta_keywords_ar" data-type="meta_keywords">
                                                        <i class="fas fa-robot"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SEO للغات الأخرى -->
                                    @foreach ($languages as $language)
                                        @if ($language->code != 'ar')
                                            <div class="language-seo-content" data-lang="{{ $language->code }}">
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label for="meta_title_{{ $language->code }}"
                                                            class="form-label">عنوان SEO</label>
                                                        <input type="text" class="form-control"
                                                            id="meta_title_{{ $language->code }}"
                                                            name="meta_title[{{ $language->code }}]"
                                                            value="{{ old('meta_title.' . $language->code) }}"
                                                            placeholder="عنوان SEO بـ{{ $language->name }}">
                                                    </div>

                                                    <div class="col-md-12 mb-3">
                                                        <label for="meta_description_{{ $language->code }}"
                                                            class="form-label">وصف SEO</label>
                                                        <textarea class="form-control" id="meta_description_{{ $language->code }}"
                                                            name="meta_description[{{ $language->code }}]" rows="3" placeholder="وصف SEO بـ{{ $language->name }}">{{ old('meta_description.' . $language->code) }}</textarea>
                                                    </div>

                                                    <div class="col-md-12 mb-3">
                                                        <label for="meta_keywords_{{ $language->code }}"
                                                            class="form-label">الكلمات المفتاحية</label>
                                                        <input type="text" class="form-control"
                                                            id="meta_keywords_{{ $language->code }}"
                                                            name="meta_keywords[{{ $language->code }}]"
                                                            value="{{ old('meta_keywords.' . $language->code) }}"
                                                            placeholder="كلمات مفتاحية بـ{{ $language->name }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <!-- أزرار الحفظ -->
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                        <i class="fas fa-undo me-1"></i>
                                        إعادة تعيين
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submit-btn">
                                        <i class="fas fa-save me-1"></i>
                                        إضافة القسم
                                    </button>
                                    <button type="submit" name="save_and_add_another" value="1"
                                        class="btn btn-success">
                                        <i class="fas fa-plus-circle me-1"></i>
                                        حفظ وإضافة جديد
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- العمود الجانبي -->
            <div class="col-md-4">
                <!-- بطاقة المساعدة -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            معلومات المساعدة
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-primary">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>نصائح سريعة:</strong>
                            <ul class="mt-2 mb-0">
                                <li>اختر اسماً واضحاً ومعبراً للقسم</li>
                                <li>استخدم وصفاً مختصراً يصف محتوى القسم</li>
                                <li>يمكنك ترك الرابط ليتولد تلقائياً</li>
                                <li>استخدم الذكاء الاصطناعي لتحسين المحتوى</li>
                                <li>الصورة المثالية للقسم تكون مقاس 500x500 بكسل</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- الأقسام الرئيسية -->
                @if ($parentCategories->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-sitemap me-2"></i>
                                الأقسام الرئيسية
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach ($parentCategories->take(5) as $parent)
                                    <a href="{{ route('admin.categories.create', ['parent_id' => $parent->id]) }}"
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-folder me-2"></i>
                                            {{ $parent->getTranslation('name', 'ar') }}
                                        </span>
                                        <span
                                            class="badge bg-primary rounded-pill">{{ $parent->children_count ?? 0 }}</span>
                                    </a>
                                @endforeach
                                @if ($parentCategories->count() > 5)
                                    <a href="{{ route('admin.categories.index', ['parent_id' => 'null']) }}"
                                        class="list-group-item list-group-item-action text-center">
                                        عرض الكل ({{ $parentCategories->count() }})
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- آخر الأقسام المضافة -->
                @if ($recentCategories->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-history me-2"></i>
                                آخر الأقسام المضافة
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach ($recentCategories as $recent)
                                    <a href="{{ route('admin.categories.edit', $recent->id) }}"
                                        class="list-group-item list-group-item-action">
                                        <div class="d-flex align-items-center">
                                            @if ($recent->image)
                                                <img src="{{ get_user_image($recent->image) }}"
                                                    alt="{{ $recent->getTranslation('name', 'ar') }}"
                                                    class="rounded-circle me-2"
                                                    style="width: 30px; height: 30px; object-fit: cover;">
                                            @else
                                                <div class="avatar-initial bg-label-primary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 30px; height: 30px;">
                                                    <i class="fas fa-folder fa-xs"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $recent->getTranslation('name', 'ar') }}</strong>
                                                <div class="text-muted small">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    {{ $recent->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // تهيئة Summernote
            $('.summernote').summernote({
                height: 200,
                lang: 'ar-AR',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview']]
                ],
                placeholder: 'أدخل النص هنا...'
            });

            // تهيئة Select2
            $('.select2').select2({
                placeholder: 'اختر القسم الرئيسي',
                allowClear: true,
                dir: 'rtl'
            });

            // تبادل اللغات الرئيسي
            $('.language-tab[data-target]').on('click', function() {
                const targetLang = $(this).data('target');

                $('.language-tab[data-target]').removeClass('active');
                $(this).addClass('active');

                $('.language-content[data-lang]').removeClass('active');
                $(`.language-content[data-lang="${targetLang}"]`).addClass('active');
            });

            // تبادل اللغات لـ SEO
            $('.language-tab[data-seo-target]').on('click', function() {
                const targetLang = $(this).data('seo-target');

                $('.language-tab[data-seo-target]').removeClass('active');
                $(this).addClass('active');

                $('.language-seo-content').removeClass('active');
                $(`.language-seo-content[data-lang="${targetLang}"]`).addClass('active');
            });

            // عدادات SEO
            function updateCounter(input, progress, countElement, max) {
                const length = input.val().length;
                const percentage = Math.min((length / max) * 100, 100);

                progress.css('width', percentage + '%');

                if (length <= max) {
                    progress.removeClass('bg-danger').addClass('bg-success');
                } else {
                    progress.removeClass('bg-success').addClass('bg-danger');
                }

                countElement.text(length + '/' + max);
            }

            $('#meta_title_ar').on('input', function() {
                updateCounter($(this), $('#titleProgress'), $('#titleCount'), 60);
            });

            $('#meta_description_ar').on('input', function() {
                updateCounter($(this), $('#descProgress'), $('#descCount'), 160);
            });

            // توليد Slug تلقائياً
            window.generateSlug = function() {
                const name = $('#name_ar').val();
                if (name) {
                    $.ajax({
                        url: '{{ route('admin.categories.generate-slug') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            name: name
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#slug').val(response.slug);
                                $('#slugValue').text(response.slug);
                                showToast('تم', 'تم توليد الرابط بنجاح', 'success');
                            }
                        },
                        error: function() {
                            // Fallback to client-side generation
                            let slug = name
                                .toLowerCase()
                                .replace(/[^\u0600-\u06FF\w\s-]/g, '')
                                .replace(/\s+/g, '-')
                                .replace(/[-\s]+/g, '-')
                                .trim();

                            $('#slug').val(slug);
                            $('#slugValue').text(slug);
                        }
                    });
                }
            };

            // تحديث معاينة Slug
            $('#slug').on('input', function() {
                $('#slugValue').text($(this).val() || 'رابط-القسم');
            });

            // معاينة الصور
            window.previewImage = function(input, previewId) {
                const preview = document.getElementById(previewId);
                const file = input.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            };

            // زر تحسين بالذكاء الاصطناعي
            $(document).on('click', '.ai-enhance-btn', function(e) {
                e.preventDefault();
                const $button = $(this);
                const target = $button.data('target');
                const type = $button.data('type');
                const $targetElement = $(target);
                const text = $targetElement.val().trim();

                if (!text) {
                    showToast('تنبيه', 'الرجاء إدخال نص أولاً قبل التحسين', 'warning');
                    return;
                }

                enhanceWithAI(text, target, type, 'enhance', $button);
            });

            // زر إكمال بالذكاء الاصطناعي
            $(document).on('click', '.ai-complete-btn', function(e) {
                e.preventDefault();
                const $button = $(this);
                const target = $button.data('target');
                const type = $button.data('type');
                const $targetElement = $(target);
                const text = $targetElement.val().trim();

                if (!text) {
                    showToast('تنبيه', 'الرجاء إدخال نص أولاً قبل الإكمال', 'warning');
                    return;
                }

                enhanceWithAI(text, target, type, 'complete', $button);
            });

            // زر تحسين SEO
            $(document).on('click', '.ai-seo-btn', function(e) {
                e.preventDefault();
                const $button = $(this);
                const target = $button.data('target');
                const type = $button.data('type');
                const $targetElement = $(target);
                let text = $targetElement.val().trim();

                if (!text) {
                    text = $('#name_ar').val().trim();
                }

                if (!text) {
                    showToast('تنبيه', 'الرجاء إدخال نص أولاً قبل تحسين SEO', 'warning');
                    return;
                }

                enhanceWithAI(text, target, type, 'seo', $button);
            });

            // دالة تحسين النص بالذكاء الاصطناعي
            function enhanceWithAI(text, target, type, action, $button) {
                const originalText = $button.html();
                $button.prop('disabled', true);
                $button.html('<i class="fas fa-spinner spin me-1"></i>');

                showAIMessage(`جاري ${getActionText(action)} النص...`);

                $.ajax({
                    url: '{{ route('admin.categories.ai-enhance') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        text: text,
                        type: type,
                        action: action,
                        tone: $('#tone').val(),
                        style: $('#translation_style').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $(target).val(response.enhanced_text);
                            if ($(target).hasClass('summernote')) {
                                $(target).summernote('code', response.enhanced_text);
                            }
                            showToast('تم', `تم ${getActionText(action)} النص بنجاح`, 'success');

                            if (action === 'seo' && target === '#meta_title_ar') {
                                $('#meta_title_ar').trigger('input');
                            }
                        } else {
                            showToast('خطأ', response.message || 'حدث خطأ أثناء المعالجة', 'error');
                        }
                    },
                    error: function() {
                        showToast('خطأ', 'حدث خطأ في الاتصال بالخادم', 'error');
                    },
                    complete: function() {
                        hideAIMessage();
                        $button.prop('disabled', false);
                        $button.html(originalText);
                    }
                });
            }

            // دالة ترجمة تلقائية
            $('.translate-btn').on('click', function(e) {
                e.preventDefault();
                const $button = $(this);
                const targetLang = $button.data('lang');
                const name = $('#name_ar').val().trim();
                const description = $('#description_ar').val().trim();

                if (!name) {
                    showToast('تنبيه', 'الرجاء إدخال اسم القسم بالعربية أولاً', 'warning');
                    return;
                }

                $button.prop('disabled', true);
                $button.html('<i class="fas fa-spinner spin me-1"></i> جاري الترجمة...');

                showAIMessage(`جاري الترجمة إلى ${targetLang}...`);

                $(`#preview_${targetLang}`).html(`
                    <div class="loading-translation">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                        <span class="text-muted">جاري الترجمة...</span>
                    </div>
                `);

                $.ajax({
                    url: '{{ route('admin.categories.ai-translate') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: name,
                        description: description,
                        target_lang: targetLang,
                        style: $('#translation_style').val(),
                        tone: $('#tone').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $(`#name_${targetLang}`).val(response.translated.name || '');
                            if (response.translated.description) {
                                $(`#description_${targetLang}`).val(response.translated
                                    .description);
                            }

                            $(`#preview_${targetLang}`).html(`
                                <div class="translation-completed">
                                    <h6 class="text-success mb-2">
                                        <i class="fas fa-check-circle me-2"></i>
                                        تمت الترجمة بنجاح
                                    </h6>
                                    <strong>الاسم:</strong> ${truncateText(response.translated.name, 50)}<br>
                                    <strong>الوصف:</strong> ${truncateText(response.translated.description || '', 50)}
                                </div>
                            `);

                            showToast('تم', `تمت الترجمة إلى ${targetLang} بنجاح`, 'success');
                        } else {
                            $(`#preview_${targetLang}`).html(
                                '<div class="text-center py-3 text-danger">فشل في الترجمة</div>'
                                );
                            showToast('خطأ', response.message || 'حدث خطأ أثناء الترجمة',
                                'error');
                        }
                    },
                    error: function() {
                        $(`#preview_${targetLang}`).html(
                            '<div class="text-center py-3 text-danger">فشل في الترجمة</div>'
                            );
                        showToast('خطأ', 'حدث خطأ أثناء الترجمة', 'error');
                    },
                    complete: function() {
                        hideAIMessage();
                        $button.prop('disabled', false);
                        $button.html('<i class="fas fa-robot me-1"></i> ترجمة تلقائية');
                    }
                });
            });

            // زر ترجمة لجميع اللغات
            $('#translate-all-btn').on('click', function(e) {
                e.preventDefault();
                const $button = $(this);

                const languages = [];
                @foreach ($languages as $language)
                    @if ($language->code != 'ar')
                        languages.push('{{ $language->code }}');
                    @endif
                @endforeach

                if (languages.length === 0) {
                    showToast('تنبيه', 'لا توجد لغات أخرى للترجمة', 'warning');
                    return;
                }

                const name = $('#name_ar').val().trim();
                if (!name) {
                    showToast('تنبيه', 'الرجاء إدخال اسم القسم بالعربية أولاً', 'warning');
                    return;
                }

                $button.prop('disabled', true);
                $button.html('<i class="fas fa-spinner spin me-1"></i> جاري الترجمة...');

                showAIMessage(`جاري الترجمة إلى ${languages.length} لغات...`);

                let completed = 0;
                languages.forEach(function(lang) {
                    $.ajax({
                        url: '{{ route('admin.categories.ai-translate') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            name: name,
                            description: $('#description_ar').val().trim(),
                            target_lang: lang,
                            style: $('#translation_style').val(),
                            tone: $('#tone').val()
                        },
                        success: function(response) {
                            completed++;
                            if (response.success) {
                                $(`#name_${lang}`).val(response.translated.name || '');
                                if (response.translated.description) {
                                    $(`#description_${lang}`).val(response.translated
                                        .description);
                                }

                                $(`#preview_${lang}`).html(`
                                    <div class="translation-completed">
                                        <h6 class="text-success mb-2">
                                            <i class="fas fa-check-circle me-2"></i>
                                            تمت الترجمة بنجاح
                                        </h6>
                                    </div>
                                `);
                            }
                        },
                        error: function() {
                            completed++;
                        },
                        complete: function() {
                            if (completed === languages.length) {
                                hideAIMessage();
                                $button.prop('disabled', false);
                                $button.html(
                                    '<i class="fas fa-language me-1"></i> ترجمة لجميع اللغات'
                                    );
                                showToast('تم', 'تمت الترجمة لجميع اللغات بنجاح',
                                    'success');
                            }
                        }
                    });
                });
            });

            // زر إنشاء قسم بالذكاء الاصطناعي
            $('#generate-with-ai').on('click', function(e) {
                e.preventDefault();
                const $button = $(this);
                const name = $('#name_ar').val().trim();

                if (!name) {
                    showToast('تنبيه', 'الرجاء إدخال اسم القسم أولاً', 'warning');
                    $('#name_ar').focus();
                    return;
                }

                $button.prop('disabled', true);
                $button.html('<i class="fas fa-spinner spin me-1"></i> جاري الإنشاء...');

                showAIMessage('جاري إنشاء القسم بالذكاء الاصطناعي...');

                $.ajax({
                    url: '{{ route('admin.categories.ai-enhance-full') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: name,
                        description: $('#description_ar').val().trim(),
                        tone: $('#tone').val(),
                        style: $('#translation_style').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            if (response.data.name) {
                                $('#name_ar').val(response.data.name);
                            }
                            if (response.data.description) {
                                $('#description_ar').summernote('code', response.data
                                    .description);
                            }
                            if (response.data.slug) {
                                $('#slug').val(response.data.slug);
                                $('#slugValue').text(response.data.slug);
                            }
                            if (response.data.meta_title) {
                                $('#meta_title_ar').val(response.data.meta_title);
                                $('#meta_title_ar').trigger('input');
                            }
                            if (response.data.meta_description) {
                                $('#meta_description_ar').val(response.data.meta_description);
                                $('#meta_description_ar').trigger('input');
                            }
                            if (response.data.meta_keywords) {
                                $('#meta_keywords_ar').val(response.data.meta_keywords);
                            }

                            showToast('تم', 'تم إنشاء القسم بنجاح', 'success');
                        } else {
                            showToast('خطأ', response.message || 'حدث خطأ أثناء الإنشاء',
                                'error');
                        }
                    },
                    error: function() {
                        showToast('خطأ', 'حدث خطأ في الاتصال بالخادم', 'error');
                    },
                    complete: function() {
                        hideAIMessage();
                        $button.prop('disabled', false);
                        $button.html(
                            '<i class="fas fa-wand-magic-sparkles me-1"></i> إنشاء قسم بالذكاء الاصطناعي'
                            );
                    }
                });
            });

            // زر إنشاء SEO
            $('#generate-seo').on('click', function(e) {
                e.preventDefault();
                const $button = $(this);
                const name = $('#name_ar').val().trim();
                const description = $('#description_ar').val().trim();

                if (!name) {
                    showToast('تنبيه', 'الرجاء إدخال اسم القسم أولاً', 'warning');
                    return;
                }

                $button.prop('disabled', true);
                $button.html('<i class="fas fa-spinner spin me-1"></i> جاري الإنشاء...');

                showAIMessage('جاري إنشاء محتوى SEO محسن...');

                $.ajax({
                    url: '{{ route('admin.categories.ai-generate-seo') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: name,
                        description: description,
                        tone: $('#tone').val(),
                        style: $('#translation_style').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#meta_title_ar').val(response.data.meta_title || '');
                            $('#meta_description_ar').val(response.data.meta_description || '');
                            $('#meta_keywords_ar').val(response.data.meta_keywords || '');

                            $('#meta_title_ar').trigger('input');
                            $('#meta_description_ar').trigger('input');

                            showToast('تم', 'تم إنشاء محتوى SEO بنجاح', 'success');
                        } else {
                            showToast('خطأ', response.message || 'حدث خطأ أثناء إنشاء SEO',
                                'error');
                        }
                    },
                    error: function() {
                        showToast('خطأ', 'حدث خطأ أثناء إنشاء SEO', 'error');
                    },
                    complete: function() {
                        hideAIMessage();
                        $button.prop('disabled', false);
                        $button.html(
                            '<i class="fas fa-robot me-1"></i> إنشاء SEO بالذكاء الاصطناعي');
                    }
                });
            });

            // إعادة تعيين النموذج
            window.resetForm = function() {
                Swal.fire({
                    title: 'إعادة تعيين',
                    text: 'هل أنت متأكد من إعادة تعيين جميع الحقول؟',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، إعادة تعيين',
                    cancelButtonText: 'إلغاء',
                    confirmButtonColor: '#696cff',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#createCategoryForm')[0].reset();
                        $('#imagePreview').attr('src',
                            'https://via.placeholder.com/200x200?text=صورة+القسم');
                        $('#subImagePreview').attr('src',
                            'https://via.placeholder.com/200x200?text=صورة+فرعية');
                        $('.summernote').summernote('code', '');
                        showToast('تم', 'تم إعادة تعيين النموذج', 'success');
                    }
                });
            };

            // التحقق من صحة النموذج
            $('#createCategoryForm').on('submit', function(e) {
                const name = $('#name_ar').val().trim();
                if (!name) {
                    e.preventDefault();
                    Swal.fire('خطأ', 'يرجى إدخال اسم القسم', 'error');
                    $('#name_ar').focus();
                    return false;
                }

                const submitBtn = $('#submit-btn');
                submitBtn.html('<i class="fas fa-spinner spin me-1"></i> جاري الحفظ...');
                submitBtn.prop('disabled', true);

                showAIMessage('جاري حفظ القسم...');

                return true;
            });

            // دوال مساعدة
            function getActionText(action) {
                const actions = {
                    'enhance': 'تحسين',
                    'complete': 'إكمال',
                    'seo': 'تحسين SEO',
                    'translate': 'ترجمة'
                };
                return actions[action] || action;
            }

            function showAIMessage(message) {
                $('#ai-message-text').text(message);
                $('#ai-messages').fadeIn(300);
            }

            function hideAIMessage() {
                $('#ai-messages').fadeOut(300);
            }

            function truncateText(text, maxLength) {
                if (!text) return '';
                return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
            }

            function showToast(title, message, type = 'info') {
                const toastId = 'toast-' + Date.now();
                const bgColor = {
                    'success': 'bg-success',
                    'error': 'bg-danger',
                    'warning': 'bg-warning',
                    'info': 'bg-info'
                } [type] || 'bg-info';

                const toastHtml = `
                    <div id="${toastId}" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
                        <div class="toast align-items-center text-white ${bgColor} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    <strong>${title}:</strong> ${message}
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                `;

                $('body').append(toastHtml);
                const toastElement = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastElement.querySelector('.toast'), {
                    delay: 5000,
                    autohide: true
                });
                toast.show();

                toastElement.addEventListener('hidden.bs.toast', function() {
                    $(this).remove();
                });
            }
        });
    </script>
@endsection
