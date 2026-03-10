@extends('Admin.layout.master')

@section('title', 'إنشاء منتج جديد')

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

        .image-preview {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .additional-images-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .additional-image-item {
            position: relative;
            width: 100px;
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
        }

        .additional-image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #dc3545;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .text-ad-item {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: rgba(105, 108, 255, 0.2);
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

        .badge-count {
            background: #696cff;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            margin-right: 5px;
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

        .language-field-tabs {
            display: flex;
            gap: 5px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .language-field-content {
            display: none;
        }

        .language-field-content.active {
            display: block;
        }

        .progress {
            height: 5px;
        }

        .progress-bar {
            transition: width 0.3s ease;
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
                    <a href="{{ route('admin.products.index') }}">المنتجات</a>
                </li>
                <li class="breadcrumb-item active">إنشاء منتج جديد</li>
            </ol>
        </nav>

        <div class="row">
            <!-- العمود الرئيسي -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>
                            إنشاء منتج جديد
                        </h5>
                        <div class="btn-group">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-right me-1"></i> رجوع للقائمة
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                                            المنتج سيدعم {{ $languages->count() }} لغات
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

                        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
                            id="createProductForm">
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
                                        إنشاء منتج كامل بالذكاء الاصطناعي
                                    </button>
                                    <button type="button" class="btn btn-outline-light" id="translate-all-btn">
                                        <i class="fas fa-language me-1"></i>
                                        ترجمة لجميع اللغات (بعد الإنشاء)
                                    </button>
                                </div>
                            </div>

                            <!-- تبادل اللغات الرئيسي -->
                            <div class="mb-4">
                                <div class="language-tabs d-flex border-bottom">
                                    <button type="button" class="language-tab active" data-target="ar">
                                        <i class="fas fa-language me-1"></i> العربية (الأساسية)
                                    </button>
                                    @foreach ($languages as $language)
                                        @if ($language->code != 'ar')
                                            <button type="button" class="language-tab" data-target="{{ $language->code }}">
                                                <i class="fas fa-globe me-1"></i> {{ $language->name }}
                                            </button>
                                        @endif
                                    @endforeach
                                </div>

                                <!-- المحتوى العربي (إلزامي) -->
                                <div class="language-content active" data-lang="ar">
                                    <div class="card ai-enhanced">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0">
                                                <i class="fas fa-star me-2"></i>
                                                اللغة الأساسية (مطلوبة)
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label for="name_ar" class="form-label required">اسم المنتج</label>
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
                                                        <input type="text"
                                                            class="form-control @error('name.ar') is-invalid @enderror"
                                                            id="name_ar" name="name[ar]" value="{{ old('name.ar') }}"
                                                            required placeholder="اسم المنتج بالعربية">
                                                    </div>
                                                    @error('name.ar')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">اسم واضح ومعبر عن المنتج</small>
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <label for="description_ar" class="form-label">وصف المنتج</label>
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
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-info ai-optimize-btn"
                                                                data-target="#description_ar" data-type="description">
                                                                <i class="fas fa-rocket"></i>
                                                            </button>
                                                        </div>
                                                        <textarea class="form-control summernote @error('description.ar') is-invalid @enderror" id="description_ar"
                                                            name="description[ar]" rows="6" placeholder="وصف المنتج بالعربية">{{ old('description.ar') }}</textarea>
                                                    </div>
                                                    @error('description.ar')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                    <div class="ai-action-buttons mt-2">
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-secondary ai-action-btn"
                                                            data-target="#description_ar" data-action="add_features">
                                                            <i class="fas fa-list-check"></i> إضافة مميزات
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-secondary ai-action-btn"
                                                            data-target="#description_ar" data-action="add_benefits">
                                                            <i class="fas fa-gift"></i> إضافة فوائد
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-secondary ai-action-btn"
                                                            data-target="#description_ar"
                                                            data-action="add_call_to_action">
                                                            <i class="fas fa-bullhorn"></i> إضافة دعوة للعمل
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="price_text_ar" class="form-label required">نص
                                                        السعر</label>
                                                    <div class="input-group-with-ai">
                                                        <div class="ai-buttons">
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-primary ai-enhance-btn"
                                                                data-target="#price_text_ar" data-type="price_text">
                                                                <i class="fas fa-wand-magic-sparkles"></i>
                                                            </button>
                                                        </div>
                                                        <input type="text"
                                                            class="form-control @error('price_text.ar') is-invalid @enderror"
                                                            id="price_text_ar" name="price_text[ar]"
                                                            value="{{ old('price_text.ar') }}" required
                                                            placeholder="نص السعر بالعربية">
                                                    </div>
                                                    @error('price_text.ar')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">مثال: 100 ريال</small>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="sku" class="form-label">رمز المنتج (SKU)</label>
                                                    <input type="text"
                                                        class="form-control @error('sku') is-invalid @enderror"
                                                        id="sku" name="sku" value="{{ old('sku') }}"
                                                        placeholder="رمز المنتج">
                                                    @error('sku')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">اختياري - معرف فريد للمنتج</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- محتوى اللغات الأخرى (اختياري - ستتم الترجمة تلقائياً) -->
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
                                                        ترجمة تلقائية الآن
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12 mb-3">
                                                            <label for="name_{{ $language->code }}"
                                                                class="form-label">اسم المنتج</label>
                                                            <input type="text" class="form-control"
                                                                id="name_{{ $language->code }}"
                                                                name="name[{{ $language->code }}]"
                                                                value="{{ old('name.' . $language->code) }}"
                                                                placeholder="اسم المنتج بـ{{ $language->name }}">
                                                        </div>

                                                        <div class="col-md-12 mb-3">
                                                            <label for="description_{{ $language->code }}"
                                                                class="form-label">وصف المنتج</label>
                                                            <textarea class="form-control" id="description_{{ $language->code }}" name="description[{{ $language->code }}]"
                                                                rows="6" placeholder="وصف المنتج بـ{{ $language->name }}">{{ old('description.' . $language->code) }}</textarea>
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label for="price_text_{{ $language->code }}"
                                                                class="form-label">نص السعر</label>
                                                            <input type="text" class="form-control"
                                                                id="price_text_{{ $language->code }}"
                                                                name="price_text[{{ $language->code }}]"
                                                                value="{{ old('price_text.' . $language->code) }}"
                                                                placeholder="نص السعر بـ{{ $language->name }}">
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

                            <!-- البيانات الأساسية -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        البيانات الأساسية
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="category_id" class="form-label required">القسم</label>
                                            <select class="form-select select2 @error('category_id') is-invalid @enderror"
                                                id="category_id" name="category_id" required>
                                                <option value="">اختر القسم</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->getTranslation('name', 'ar') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label for="price" class="form-label required">السعر</label>
                                            <input type="number"
                                                class="form-control @error('price') is-invalid @enderror" id="price"
                                                name="price" value="{{ old('price') }}" step="0.01"
                                                min="0" required>
                                            @error('price')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label for="stock" class="form-label required">الكمية</label>
                                            <input type="number"
                                                class="form-control @error('stock') is-invalid @enderror" id="stock"
                                                name="stock" value="{{ old('stock', 0) }}" min="0" required>
                                            @error('stock')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- الخصم -->
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="has_discount"
                                                    name="has_discount" value="1"
                                                    {{ old('has_discount') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_discount">يحتوي على خصم؟</label>
                                            </div>
                                        </div>

                                        <div id="discount_fields"
                                            style="{{ old('has_discount') ? '' : 'display: none;' }}">
                                            <div class="col-md-4 mb-3">
                                                <label for="discount_type" class="form-label">نوع الخصم</label>
                                                <select class="form-select" id="discount_type" name="discount_type">
                                                    <option value="percentage"
                                                        {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>
                                                        نسبة مئوية</option>
                                                    <option value="fixed"
                                                        {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>
                                                        قيمة ثابتة</option>
                                                </select>
                                            </div>

                                            <div class="col-md-5 mb-3">
                                                <label for="discount_value" class="form-label">قيمة الخصم</label>
                                                <input type="number" class="form-control" id="discount_value"
                                                    name="discount_value" value="{{ old('discount_value') }}"
                                                    step="0.01" min="0">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- خيارات إضافية -->
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="includes_tax"
                                                    name="includes_tax" value="1"
                                                    {{ old('includes_tax') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="includes_tax">يشمل الضريبة</label>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="status_id" class="form-label required">الحالة</label>
                                            <select class="form-select @error('status_id') is-invalid @enderror"
                                                id="status_id" name="status_id" required>
                                                <option value="1" {{ old('status_id', 1) == 1 ? 'selected' : '' }}>
                                                    نشط
                                                </option>
                                                <option value="2" {{ old('status_id') == 2 ? 'selected' : '' }}>غير
                                                    نشط
                                                </option>
                                                <option value="3" {{ old('status_id') == 3 ? 'selected' : '' }}>مسودة
                                                </option>
                                            </select>
                                            @error('status_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
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

                            <!-- الصور -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-images me-2"></i>
                                        صور المنتج
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <!-- الصورة الرئيسية -->
                                    <h6 class="mb-3">الصورة الرئيسية</h6>
                                    <div class="d-flex align-items-start mb-4">
                                        <div class="me-3">
                                            <div class="image-upload-container">
                                                <img src="https://via.placeholder.com/150x150?text=صورة+المنتج"
                                                    alt="الصورة الرئيسية" class="image-preview" id="main_image_preview">
                                                <div class="image-overlay" onclick="$('#image').click()">
                                                    <i class="fas fa-camera"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <input type="file"
                                                class="form-control @error('image') is-invalid @enderror" id="image"
                                                name="image" accept="image/*" onchange="previewMainImage(this)"
                                                required>
                                            @error('image')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">الحجم الموصى به: 800×800 بكسل. الصيغ المسموحة: JPEG,
                                                PNG, JPG, GIF, SVG, WEBP.</small>
                                        </div>
                                    </div>

                                    <!-- الصور الإضافية -->
                                    <h6 class="mb-3">الصور الإضافية</h6>
                                    <div class="additional-images-preview mb-3" id="additional_images_preview"></div>
                                    <div>
                                        <input type="file" class="form-control" id="additional_images"
                                            name="additional_images[]" multiple accept="image/*"
                                            onchange="previewAdditionalImages(this)">
                                        <small class="text-muted">يمكنك اختيار عدة صور دفعة واحدة</small>
                                    </div>
                                </div>
                            </div>

                            <!-- النصوص الإعلانية -->
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-bullhorn me-2"></i>
                                        النصوص الإعلانية
                                    </h5>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-primary me-2"
                                            onclick="addTextAdField()">
                                            <i class="fas fa-plus me-1"></i> إضافة نص
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-success"
                                            id="generate-text-ads">
                                            <i class="fas fa-robot me-1"></i>
                                            إنشاء نصوص إعلانية
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="text_ads_container">
                                        <!-- سيتم إضافة النصوص الإعلانية هنا ديناميكياً -->
                                    </div>
                                </div>
                            </div>

                            {{-- <!-- العروض -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-tags me-2"></i>
                                        العروض
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <select class="form-control select2" id="offers" name="offers[]" multiple>
                                        @foreach ($offers as $offer)
                                            <option value="{{ $offer->id }}"
                                                {{ in_array($offer->id, old('offers', [])) ? 'selected' : '' }}>
                                                {{ $offer->getTranslation('name', 'ar') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">اختر العروض التي سيتم تضمين المنتج فيها</small>
                                </div>
                            </div> --}}

                            <!-- أزرار الحفظ -->
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                        <i class="fas fa-undo me-1"></i>
                                        إعادة تعيين
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submit-btn">
                                        <i class="fas fa-save me-1"></i>
                                        إنشاء المنتج بجميع اللغات
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="saveAsDraft()">
                                        <i class="fas fa-file-alt me-1"></i>
                                        حفظ كمسودة
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- العمود الجانبي -->
            <div class="col-md-4">
                <!-- بطاقة التعليمات -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-lightbulb me-2"></i>
                            نصائح سريعة
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>الاسم:</strong> اجعله واضحاً ومعبراً عن المنتج
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>الوصف:</strong> اذكر المميزات والفوائد بالتفصيل
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>الصور:</strong> استخدم صور عالية الجودة
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>SEO:</strong> حسّن محتوى SEO لظهور أفضل
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>النصوص الإعلانية:</strong> استخدم نصوصاً جذابة ومقنعة
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- إجراءات سريعة -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt me-2"></i>
                            إجراءات سريعة
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary" onclick="fillWithSampleData()">
                                <i class="fas fa-file-import me-2"></i>
                                تعبئة ببيانات تجريبية
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="showAIAssistant()">
                                <i class="fas fa-robot me-2"></i>
                                مساعد الذكاء الاصطناعي
                            </button>
                        </div>
                    </div>
                </div>
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
                placeholder: 'اختر القسم',
                allowClear: true,
                dir: 'rtl'
            });

            $('#offers').select2({
                placeholder: 'اختر العروض',
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

            // تبادل اللغات للنصوص الإعلانية (سيتم ربطها ديناميكياً)
            $(document).on('click', '.language-tab[data-text-ad-target]', function() {
                const targetLang = $(this).data('text-ad-target');
                const $parent = $(this).closest('.text-ad-item');

                $parent.find('.language-tab[data-text-ad-target]').removeClass('active');
                $(this).addClass('active');

                $parent.find('.language-field-content').removeClass('active');
                $parent.find(`.language-field-content[data-lang="${targetLang}"]`).addClass('active');
            });

            // تفعيل/تعطيل حقل الخصم
            $('#has_discount').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#discount_fields').slideDown();
                } else {
                    $('#discount_fields').slideUp();
                }
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

            // معاينة الصورة الرئيسية
            window.previewMainImage = function(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#main_image_preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            };

            // معاينة الصور الإضافية
            window.previewAdditionalImages = function(input) {
                const previewContainer = $('#additional_images_preview');

                if (input.files) {
                    for (let i = 0; i < input.files.length; i++) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewItem = $(`
                                <div class="additional-image-item">
                                    <img src="${e.target.result}" alt="صورة إضافية">
                                    <div class="remove-image" onclick="removeNewImage(this)">
                                        <i class="fas fa-times"></i>
                                    </div>
                                </div>
                            `);
                            previewContainer.append(previewItem);
                        }
                        reader.readAsDataURL(input.files[i]);
                    }
                }
            };

            // إزالة صورة جديدة
            window.removeNewImage = function(button) {
                $(button).closest('.additional-image-item').remove();
            };

            // متغير للعداد
            window.textAdCounter = 0;

            // إضافة حقل نص إعلاني جديد
            window.addTextAdField = function() {
                const container = $('#text_ads_container');
                const index = textAdCounter++;

                const languagesHtml = @json(
                    $languages->map(function ($lang) {
                            return ['code' => $lang->code, 'name' => $lang->name];
                        })->toArray());

                let languageTabs = '<div class="language-field-tabs">';
                let languageContents = '';

                languagesHtml.forEach((language, langIndex) => {
                    const isActive = language.code === 'ar';
                    const langId = `${language.code}_${index}`;

                    languageTabs += `
                        <button type="button" class="language-tab ${isActive ? 'active' : ''}" 
                                data-text-ad-target="${langId}">
                            ${language.name}
                        </button>
                    `;

                    if (language.code === 'ar') {
                        languageContents += `
                            <div class="language-field-content ${isActive ? 'active' : ''}" data-lang="${langId}">
                                <textarea class="form-control" id="text_ad_${index}" name="text_ads[${index}][name]" 
                                          rows="3" placeholder="النص الإعلاني بالعربية"></textarea>
                            </div>
                        `;
                    } else {
                        languageContents += `
                            <div class="language-field-content" data-lang="${langId}">
                                <input type="text" class="form-control" name="text_ads[${index}][${language.code}]" 
                                       placeholder="النص الإعلاني بال${language.name}">
                            </div>
                        `;
                    }
                });

                languageTabs += '</div>';

                const fieldHtml = `
                    <div class="text-ad-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">النص الإعلاني ${index + 1}</h6>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-primary ai-enhance-btn me-1"
                                    data-target="#text_ad_${index}" data-type="text_ad">
                                    <i class="fas fa-wand-magic-sparkles"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeTextAdField(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        ${languageTabs}
                        ${languageContents}
                    </div>
                `;

                container.append(fieldHtml);
            };

            // إزالة حقل نص إعلاني
            window.removeTextAdField = function(button) {
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: 'سيتم حذف هذا النص الإعلاني',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(button).closest('.text-ad-item').remove();
                        Swal.fire('تم الحذف!', 'تم حذف النص الإعلاني بنجاح', 'success');
                    }
                });
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

            // زر إجراء محدد
            $(document).on('click', '.ai-action-btn', function(e) {
                e.preventDefault();
                const $button = $(this);
                const target = $button.data('target');
                const action = $button.data('action');
                const $targetElement = $(target);
                const text = $targetElement.val().trim();

                if (!text) {
                    showToast('تنبيه', 'الرجاء إدخال نص أولاً', 'warning');
                    return;
                }

                enhanceWithAI(text, target, 'description', action, $button);
            });

            // دالة تحسين النص بالذكاء الاصطناعي
            function enhanceWithAI(text, target, type, action, $button) {
                const originalText = $button.html();
                $button.prop('disabled', true);
                $button.html('<i class="fas fa-spinner spin me-1"></i>');

                showAIMessage(`جاري ${getActionText(action)} النص...`);

                $.ajax({
                    url: '{{ route('admin.products.ai-enhance') }}',
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
                    beforeSend: function() {
                        $('.ai-enhance-btn, .ai-complete-btn, .ai-seo-btn, .ai-action-btn').prop(
                            'disabled', true);
                    },
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
                    error: function(xhr, status, error) {
                        console.error('خطأ AJAX:', error);

                        let errorMessage = 'حدث خطأ في الاتصال بالخادم';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showToast('خطأ', errorMessage, 'error');
                    },
                    complete: function() {
                        hideAIMessage();
                        $button.prop('disabled', false);
                        $button.html(originalText);
                        $('.ai-enhance-btn, .ai-complete-btn, .ai-seo-btn, .ai-action-btn').prop(
                            'disabled', false);
                    }
                });
            }

            // زر ترجمة تلقائية
            $(document).on('click', '.translate-btn', function(e) {
                e.preventDefault();
                const $button = $(this);
                const targetLang = $button.data('lang');
                const name = $('#name_ar').val().trim();
                const description = $('#description_ar').val().trim();
                const priceText = $('#price_text_ar').val().trim();

                if (!name) {
                    showToast('تنبيه', 'الرجاء إدخال اسم المنتج بالعربية أولاً', 'warning');
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
                    url: '{{ route('admin.products.ai-translate') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        title: name,
                        description: description,
                        price_text: priceText,
                        target_lang: targetLang,
                        style: $('#translation_style').val(),
                        tone: $('#tone').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $(`#name_${targetLang}`).val(response.translated.title || '');

                            if (response.translated.description) {
                                $(`#description_${targetLang}`).val(response.translated
                                    .description);
                            }

                            if (response.translated.price_text) {
                                $(`#price_text_${targetLang}`).val(response.translated
                                    .price_text);
                            }

                            $(`#preview_${targetLang}`).html(`
                                <div class="translation-completed">
                                    <h6 class="text-success mb-2">
                                        <i class="fas fa-check-circle me-2"></i>
                                        تمت الترجمة بنجاح
                                    </h6>
                                    <strong>الاسم:</strong> ${truncateText(response.translated.title, 50)}<br>
                                    <strong>نص السعر:</strong> ${truncateText(response.translated.price_text || '', 30)}
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
                    showToast('تنبيه', 'الرجاء إدخال اسم المنتج بالعربية أولاً', 'warning');
                    return;
                }

                $button.prop('disabled', true);
                $button.html('<i class="fas fa-spinner spin me-1"></i> جاري الترجمة...');

                showAIMessage(`جاري الترجمة إلى ${languages.length} لغات...`);

                let completed = 0;
                languages.forEach(function(lang) {
                    $.ajax({
                        url: '{{ route('admin.products.ai-translate') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            title: name,
                            description: $('#description_ar').val().trim(),
                            price_text: $('#price_text_ar').val().trim(),
                            target_lang: lang,
                            style: $('#translation_style').val(),
                            tone: $('#tone').val()
                        },
                        success: function(response) {
                            completed++;
                            if (response.success) {
                                $(`#name_${lang}`).val(response.translated.title || '');

                                if (response.translated.description) {
                                    $(`#description_${lang}`).val(response.translated
                                        .description);
                                }

                                if (response.translated.price_text) {
                                    $(`#price_text_${lang}`).val(response.translated
                                        .price_text);
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

            // زر إنشاء SEO
            $('#generate-seo').on('click', function(e) {
                e.preventDefault();
                const $button = $(this);
                const name = $('#name_ar').val().trim();
                const description = $('#description_ar').val().trim();
                const category = $('#category_id option:selected').text();

                if (!name) {
                    showToast('تنبيه', 'الرجاء إدخال اسم المنتج أولاً', 'warning');
                    return;
                }

                $button.prop('disabled', true);
                $button.html('<i class="fas fa-spinner spin me-1"></i> جاري الإنشاء...');

                showAIMessage('جاري إنشاء محتوى SEO محسن...');

                $.ajax({
                    url: '{{ route('admin.products.ai-generate-seo') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        title: name,
                        description: description,
                        category: category,
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

            // زر إنشاء نصوص إعلانية
            $('#generate-text-ads').on('click', function(e) {
                e.preventDefault();
                const $button = $(this);
                const name = $('#name_ar').val().trim();
                const description = $('#description_ar').val().trim();
                const price = $('#price').val();
                const priceText = $('#price_text_ar').val();

                if (!name) {
                    showToast('تنبيه', 'الرجاء إدخال اسم المنتج أولاً', 'warning');
                    return;
                }

                $button.prop('disabled', true);
                $button.html('<i class="fas fa-spinner spin me-1"></i> جاري الإنشاء...');

                showAIMessage('جاري إنشاء نصوص إعلانية محسنة...');

                $.ajax({
                    url: '{{ route('admin.products.ai-generate-text-ads') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        title: name,
                        description: description,
                        price: price,
                        price_text: priceText,
                        tone: $('#tone').val(),
                        style: $('#translation_style').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#text_ads_container').empty();
                            textAdCounter = 0;

                            response.text_ads.forEach((ad, index) => {
                                addTextAdField();
                                $(`#text_ad_${index}`).val(ad);
                            });

                            showToast('تم',
                                `تم إنشاء ${response.text_ads.length} نص إعلاني بنجاح`,
                                'success');
                        } else {
                            showToast('خطأ', response.message ||
                                'حدث خطأ أثناء إنشاء النصوص الإعلانية', 'error');
                        }
                    },
                    error: function() {
                        showToast('خطأ', 'حدث خطأ أثناء إنشاء النصوص الإعلانية', 'error');
                    },
                    complete: function() {
                        hideAIMessage();
                        $button.prop('disabled', false);
                        $button.html('<i class="fas fa-robot me-1"></i> إنشاء نصوص إعلانية');
                    }
                });
            });

            // زر إنشاء منتج كامل بالذكاء الاصطناعي
            $('#generate-with-ai').on('click', function(e) {
                e.preventDefault();
                const $button = $(this);
                const name = $('#name_ar').val().trim();
                const categoryId = $('#category_id').val();

                if (!name) {
                    showToast('تنبيه', 'الرجاء إدخال اسم المنتج أولاً', 'warning');
                    $('#name_ar').focus();
                    return;
                }

                if (!categoryId) {
                    showToast('تنبيه', 'الرجاء اختيار القسم أولاً', 'warning');
                    $('#category_id').focus();
                    return;
                }

                $button.prop('disabled', true);
                $button.html('<i class="fas fa-spinner spin me-1"></i> جاري الإنشاء...');

                showAIMessage('جاري إنشاء المنتج بالذكاء الاصطناعي...');

                // إظهار مؤشر التحميل في جميع معاينات اللغات
                $('.preview-content').each(function() {
                    const $preview = $(this);
                    const langCode = $preview.attr('id').replace('preview_', '');

                    if (langCode !== 'ar') {
                        $preview.html(`
                            <div class="loading-translation">
                                <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                <span class="text-muted">جاري الإنشاء...</span>
                            </div>
                        `);
                    }
                });

                $.ajax({
                    url: '{{ route('admin.products.ai-enhance-full') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        title: name,
                        category_id: categoryId,
                        current_description: $('#description_ar').val().trim(),
                        tone: $('#tone').val(),
                        style: $('#translation_style').val(),
                        generate_all: true
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#generate-with-ai, #submit-btn').prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.success) {
                            // تعبئة الحقول العربية بالنص المولد
                            $('#name_ar').val(response.data.title || '');
                            $('#description_ar').summernote('code', response.data.description ||
                                '');
                            $('#price_text_ar').val(response.data.price_text || '');

                            // تعبئة SEO إذا كان موجوداً
                            if (response.data.seo) {
                                $('#meta_title_ar').val(response.data.seo.meta_title || '');
                                $('#meta_description_ar').val(response.data.seo
                                    .meta_description || '');
                                $('#meta_keywords_ar').val(response.data.seo.meta_keywords ||
                                    '');
                                $('#meta_title_ar').trigger('input');
                                $('#meta_description_ar').trigger('input');
                            }

                            // إضافة نصوص إعلانية إذا كانت موجودة
                            if (response.data.text_ads && response.data.text_ads.length > 0) {
                                $('#text_ads_container').empty();
                                textAdCounter = 0;

                                response.data.text_ads.forEach((ad, index) => {
                                    addTextAdField();
                                    $(`#text_ad_${index}`).val(ad);
                                });
                            }

                            // تحديث معاينة اللغات الأخرى
                            if (response.translations) {
                                let completedTranslations = 0;
                                const totalTranslations = Object.keys(response.translations
                                        .title || {})
                                    .filter(lang => lang !== 'ar').length;

                                Object.keys(response.translations.title || {}).forEach(function(
                                    langCode) {
                                    if (langCode !== 'ar') {
                                        setTimeout(() => {
                                            const previewElement = $(
                                                `#preview_${langCode}`);
                                            if (previewElement.length) {
                                                const title = response
                                                    .translations.title[
                                                        langCode] || '';
                                                const priceText = response
                                                    .translations.price_text[
                                                        langCode] || '';

                                                completedTranslations++;
                                                const percentage = Math.round((
                                                        completedTranslations /
                                                        totalTranslations) *
                                                    100);

                                                previewElement.html(`
                                                    <div class="translation-completed">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                            <h6 class="text-success mb-0">
                                                                <i class="fas fa-check-circle me-2"></i>
                                                                تم الإنشاء بنجاح
                                                            </h6>
                                                            <span class="badge bg-success">${percentage}%</span>
                                                        </div>
                                                        <div class="progress mb-2" style="height: 5px;">
                                                            <div class="progress-bar bg-success" role="progressbar" 
                                                                 style="width: ${percentage}%" 
                                                                 aria-valuenow="${percentage}" 
                                                                 aria-valuemin="0" 
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                        <strong>العنوان:</strong> ${truncateText(title, 50)}<br>
                                                        <strong>نص السعر:</strong> ${truncateText(priceText, 30)}
                                                    </div>
                                                `);

                                                // تعبئة الحقول بلغة الهدف
                                                $(`#name_${langCode}`).val(
                                                    title);
                                                $(`#price_text_${langCode}`)
                                                    .val(priceText);

                                                if (response.translations
                                                    .description && response
                                                    .translations.description[
                                                        langCode]) {
                                                    $(`#description_${langCode}`)
                                                        .val(response
                                                            .translations
                                                            .description[
                                                                langCode]);
                                                }
                                            }
                                        }, 300 * completedTranslations);
                                    }
                                });
                            }

                            showToast('تم', 'تم إنشاء المنتج بنجاح', 'success');
                        } else {
                            $('.loading-translation').html(
                                '<div class="text-center py-3 text-danger">فشل في الإنشاء</div>'
                            );
                            showToast('خطأ', response.message || 'حدث خطأ أثناء الإنشاء',
                                'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('خطأ AJAX:', error);
                        $('.loading-translation').html(
                            '<div class="text-center py-3 text-danger">فشل في الإنشاء</div>'
                        );

                        let errorMessage = 'حدث خطأ في الاتصال بالخادم';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        showToast('خطأ', errorMessage, 'error');
                    },
                    complete: function() {
                        hideAIMessage();
                        $button.prop('disabled', false);
                        $button.html(
                            '<i class="fas fa-wand-magic-sparkles me-1"></i> إنشاء منتج كامل بالذكاء الاصطناعي'
                        );
                        $('#generate-with-ai, #submit-btn').prop('disabled', false);
                    }
                });
            });

            // دوال مساعدة
            function getActionText(action) {
                const actions = {
                    'enhance': 'تحسين',
                    'complete': 'إكمال',
                    'seo': 'تحسين SEO',
                    'add_features': 'إضافة مميزات',
                    'add_benefits': 'إضافة فوائد',
                    'add_call_to_action': 'إضافة دعوة للعمل'
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

            // حفظ كمسودة
            window.saveAsDraft = function() {
                $('#status_id').val('3');
                $('#createProductForm').submit();
            };

            // إعادة تعيين النموذج
            window.resetForm = function() {
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: 'سيتم مسح جميع البيانات المدخلة',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، إعادة تعيين',
                    cancelButtonText: 'إلغاء',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#createProductForm')[0].reset();
                        $('.summernote').summernote('code', '');
                        $('#additional_images_preview').empty();
                        $('#text_ads_container').empty();
                        textAdCounter = 0;
                        $('.select2').val(null).trigger('change');
                        $('#main_image_preview').attr('src',
                            'https://via.placeholder.com/150x150?text=صورة+المنتج');
                        Swal.fire('تم!', 'تم إعادة تعيين النموذج', 'success');
                    }
                });
            };

            // تعبئة ببيانات تجريبية
            window.fillWithSampleData = function() {
                $('#name_ar').val('منتج تجريبي مميز');
                $('#price_text_ar').val('١٠٠ ريال');
                $('#description_ar').summernote('code',
                    '<p>هذا منتج تجريبي يتميز بجودة عالية وسعر مميز. مناسب لجميع الاستخدامات اليومية.</p><ul><li>ميزة 1: جودة عالية</li><li>ميزة 2: سعر مناسب</li><li>ميزة 3: تصميم عصري</li></ul>'
                );
                $('#price').val('100');
                $('#stock').val('50');
                $('#meta_title_ar').val('منتج تجريبي مميز - أفضل العروض');
                $('#meta_description_ar').val(
                    'اكتشف منتجنا التجريبي المميز بجودة عالية وسعر مناسب. تسوق الآن واستفد من العروض الحصرية.'
                );
                $('#meta_keywords_ar').val('منتج, تجريبي, عروض, تخفيضات');
                $('#meta_title_ar').trigger('input');
                $('#meta_description_ar').trigger('input');
                showToast('تم', 'تم تعبئة البيانات التجريبية', 'success');
            };

            // مساعد الذكاء الاصطناعي
            window.showAIAssistant = function() {
                Swal.fire({
                    title: 'مساعد الذكاء الاصطناعي',
                    html: `
                        <div class="text-center">
                            <i class="fas fa-robot fa-3x text-primary mb-3"></i>
                            <p>كيف يمكنني مساعدتك اليوم؟</p>
                            <div class="list-group text-start">
                                <button class="list-group-item list-group-item-action" onclick="aiAction('improve_name')">
                                    <i class="fas fa-magic text-primary me-2"></i> تحسين اسم المنتج
                                </button>
                                <button class="list-group-item list-group-item-action" onclick="aiAction('improve_description')">
                                    <i class="fas fa-magic text-primary me-2"></i> تحسين وصف المنتج
                                </button>
                                <button class="list-group-item list-group-item-action" onclick="aiAction('generate_seo')">
                                    <i class="fas fa-search text-primary me-2"></i> إنشاء SEO كامل
                                </button>
                                <button class="list-group-item list-group-item-action" onclick="aiAction('generate_ads')">
                                    <i class="fas fa-bullhorn text-primary me-2"></i> إنشاء نصوص إعلانية
                                </button>
                                <button class="list-group-item list-group-item-action" onclick="aiAction('translate_all')">
                                    <i class="fas fa-language text-primary me-2"></i> ترجمة لجميع اللغات
                                </button>
                            </div>
                        </div>
                    `,
                    showConfirmButton: false,
                    showCloseButton: true
                });
            };

            window.aiAction = function(action) {
                Swal.close();
                switch (action) {
                    case 'improve_name':
                        if ($('#name_ar').val()) {
                            $('#name_ar').trigger('focus');
                            $('.ai-enhance-btn[data-target="#name_ar"]').click();
                        } else {
                            showToast('تنبيه', 'الرجاء إدخال اسم المنتج أولاً', 'warning');
                        }
                        break;
                    case 'improve_description':
                        if ($('#description_ar').val()) {
                            $('#description_ar').trigger('focus');
                            $('.ai-enhance-btn[data-target="#description_ar"]').click();
                        } else {
                            showToast('تنبيه', 'الرجاء إدخال وصف المنتج أولاً', 'warning');
                        }
                        break;
                    case 'generate_seo':
                        $('#generate-seo').click();
                        break;
                    case 'generate_ads':
                        $('#generate-text-ads').click();
                        break;
                    case 'translate_all':
                        $('#translate-all-btn').click();
                        break;
                }
            };

            // التحقق من صحة النموذج
            $('#createProductForm').on('submit', function(e) {
                const name = $('#name_ar').val().trim();
                const priceText = $('#price_text_ar').val().trim();
                const price = $('#price').val();
                const stock = $('#stock').val();
                const category = $('#category_id').val();
                const image = $('#image').val();

                if (!name) {
                    e.preventDefault();
                    Swal.fire('خطأ', 'يرجى إدخال اسم المنتج', 'error');
                    $('.language-tab[data-target="ar"]').click();
                    $('#name_ar').focus();
                    return false;
                }

                if (!priceText) {
                    e.preventDefault();
                    Swal.fire('خطأ', 'يرجى إدخال نص السعر', 'error');
                    $('.language-tab[data-target="ar"]').click();
                    $('#price_text_ar').focus();
                    return false;
                }

                if (!category) {
                    e.preventDefault();
                    Swal.fire('خطأ', 'يرجى اختيار القسم', 'error');
                    $('#category_id').focus();
                    return false;
                }

                if (!price || price <= 0) {
                    e.preventDefault();
                    Swal.fire('خطأ', 'يرجى إدخال سعر صحيح', 'error');
                    $('#price').focus();
                    return false;
                }

                if (!stock || stock < 0) {
                    e.preventDefault();
                    Swal.fire('خطأ', 'يرجى إدخال كمية صحيحة', 'error');
                    $('#stock').focus();
                    return false;
                }

                if (!image) {
                    e.preventDefault();
                    Swal.fire('خطأ', 'يرجى رفع الصورة الرئيسية', 'error');
                    return false;
                }

                const submitBtn = $('#submit-btn');
                submitBtn.html('<i class="fas fa-spinner spin me-1"></i> جاري الإنشاء...');
                submitBtn.prop('disabled', true);

                showAIMessage('جاري إنشاء المنتج بجميع اللغات...');

                return true;
            });
        });
    </script>
@endsection
