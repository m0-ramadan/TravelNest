@extends('Admin.layout.master')

@section('title', 'تعديل المنتج')

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
                <li class="breadcrumb-item active">تعديل المنتج: {{ $product->getTranslation('name', 'ar') }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- العمود الرئيسي -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2"></i>
                            تعديل المنتج
                        </h5>
                        <div class="btn-group">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-right me-1"></i> رجوع
                            </a>
                            <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-eye me-1"></i> عرض
                            </a>
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
                                            المنتج مدعوم بـ {{ $languages->count() }} لغات
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

                        <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                            enctype="multipart/form-data" id="editProductForm">
                            @csrf
                            @method('POST')

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
                                        إنشاء نسخة محسنة بالذكاء الاصطناعي
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
                                                        <input type="text" class="form-control" id="name_ar"
                                                            name="name[ar]"
                                                            value="{{ old('name.ar', $product->getTranslation('name', 'ar')) }}"
                                                            required placeholder="اسم المنتج بالعربية">
                                                    </div>
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
                                                        <textarea class="form-control summernote" id="description_ar" name="description[ar]" rows="6"
                                                            placeholder="وصف المنتج بالعربية">{{ old('description.ar', $product->getTranslation('description', 'ar')) }}</textarea>
                                                    </div>
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
                                                        <input type="text" class="form-control" id="price_text_ar"
                                                            name="price_text[ar]"
                                                            value="{{ old('price_text.ar', $product->getTranslation('price_text', 'ar')) }}"
                                                            required placeholder="نص السعر بالعربية">
                                                    </div>
                                                    <small class="text-muted">مثال: 100 ريال</small>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="sku" class="form-label">رمز المنتج (SKU)</label>
                                                    <input type="text" class="form-control" id="sku"
                                                        name="sku" value="{{ old('sku', $product->sku) }}"
                                                        placeholder="رمز المنتج">
                                                    <small class="text-muted">اختياري - معرف فريد للمنتج</small>
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
                                                                class="form-label">اسم المنتج</label>
                                                            <input type="text" class="form-control"
                                                                id="name_{{ $language->code }}"
                                                                name="name[{{ $language->code }}]"
                                                                value="{{ old('name.' . $language->code, $product->getTranslation('name', $language->code)) }}"
                                                                placeholder="اسم المنتج بـ{{ $language->name }}">
                                                        </div>

                                                        <div class="col-md-12 mb-3">
                                                            <label for="description_{{ $language->code }}"
                                                                class="form-label">وصف المنتج</label>
                                                            <textarea class="form-control" id="description_{{ $language->code }}" name="description[{{ $language->code }}]"
                                                                rows="6" placeholder="وصف المنتج بـ{{ $language->name }}">{{ old('description.' . $language->code, $product->getTranslation('description', $language->code)) }}</textarea>
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label for="price_text_{{ $language->code }}"
                                                                class="form-label">نص السعر</label>
                                                            <input type="text" class="form-control"
                                                                id="price_text_{{ $language->code }}"
                                                                name="price_text[{{ $language->code }}]"
                                                                value="{{ old('price_text.' . $language->code, $product->getTranslation('price_text', $language->code)) }}"
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
                                            <select class="form-select select2" id="category_id" name="category_id"
                                                required>
                                                <option value="">اختر القسم</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->getTranslation('name', 'ar') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label for="price" class="form-label required">السعر</label>
                                            <input type="number" class="form-control" id="price" name="price"
                                                value="{{ old('price', $product->price) }}" step="0.01"
                                                min="0" required>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label for="stock" class="form-label required">الكمية</label>
                                            <input type="number" class="form-control" id="stock" name="stock"
                                                value="{{ old('stock', $product->stock) }}" min="0" required>
                                        </div>
                                    </div>

                                    <!-- الخصم -->
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="has_discount"
                                                    name="has_discount" value="1"
                                                    {{ old('has_discount', $product->has_discount) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_discount">يحتوي على خصم؟</label>
                                            </div>
                                        </div>

                                        <div id="discount_fields"
                                            style="{{ old('has_discount', $product->has_discount) ? '' : 'display: none;' }}">
                                            <div class="col-md-4 mb-3">
                                                <label for="discount_type" class="form-label">نوع الخصم</label>
                                                <select class="form-select" id="discount_type" name="discount_type">
                                                    <option value="percentage"
                                                        {{ old('discount_type', optional($product->discount)->discount_type) == 'percentage' ? 'selected' : '' }}>
                                                        نسبة مئوية</option>
                                                    <option value="fixed"
                                                        {{ old('discount_type', optional($product->discount)->discount_type) == 'fixed' ? 'selected' : '' }}>
                                                        قيمة ثابتة</option>
                                                </select>
                                            </div>

                                            <div class="col-md-5 mb-3">
                                                <label for="discount_value" class="form-label">قيمة الخصم</label>
                                                <input type="number" class="form-control" id="discount_value"
                                                    name="discount_value"
                                                    value="{{ old('discount_value', optional($product->discount)->discount_value) }}"
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
                                                    {{ old('includes_tax', $product->includes_tax) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="includes_tax">يشمل الضريبة</label>
                                            </div>
                                        </div>


                                        <div class="col-md-4 mb-3">
                                            <label for="status_id" class="form-label required">الحالة</label>
                                            <select class="form-select" id="status_id" name="status_id" required>
                                                <option value="1"
                                                    {{ old('status_id', $product->status_id) == 1 ? 'selected' : '' }}>نشط
                                                </option>
                                                <option value="2"
                                                    {{ old('status_id', $product->status_id) == 2 ? 'selected' : '' }}>غير
                                                    نشط
                                                </option>
                                                <option value="3"
                                                    {{ old('status_id', $product->status_id) == 3 ? 'selected' : '' }}>
                                                    مسودة
                                                </option>
                                            </select>
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
                                                        name="meta_title[ar]"
                                                        value="{{ old('meta_title.ar', $product->getTranslation('meta_title', 'ar')) }}"
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
                                                        placeholder="وصف محسن لمحركات البحث">{{ old('meta_description.ar', $product->getTranslation('meta_description', 'ar')) }}</textarea>
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
                                                        name="meta_keywords[ar]"
                                                        value="{{ old('meta_keywords.ar', $product->getTranslation('meta_keywords', 'ar')) }}"
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
                                                            value="{{ old('meta_title.' . $language->code, $product->getTranslation('meta_title', $language->code)) }}"
                                                            placeholder="عنوان SEO بـ{{ $language->name }}">
                                                    </div>

                                                    <div class="col-md-12 mb-3">
                                                        <label for="meta_description_{{ $language->code }}"
                                                            class="form-label">وصف SEO</label>
                                                        <textarea class="form-control" id="meta_description_{{ $language->code }}"
                                                            name="meta_description[{{ $language->code }}]" rows="3" placeholder="وصف SEO بـ{{ $language->name }}">{{ old('meta_description.' . $language->code, $product->getTranslation('meta_description', $language->code)) }}</textarea>
                                                    </div>

                                                    <div class="col-md-12 mb-3">
                                                        <label for="meta_keywords_{{ $language->code }}"
                                                            class="form-label">الكلمات المفتاحية</label>
                                                        <input type="text" class="form-control"
                                                            id="meta_keywords_{{ $language->code }}"
                                                            name="meta_keywords[{{ $language->code }}]"
                                                            value="{{ old('meta_keywords.' . $language->code, $product->getTranslation('meta_keywords', $language->code)) }}"
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
                                                @if ($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                        alt="الصورة الرئيسية" class="image-preview"
                                                        id="main_image_preview">
                                                @else
                                                    <img src="https://via.placeholder.com/150x150?text=صورة+المنتج"
                                                        alt="الصورة الرئيسية" class="image-preview"
                                                        id="main_image_preview">
                                                @endif
                                                <div class="image-overlay" onclick="$('#image').click()">
                                                    <i class="fas fa-camera"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <input type="file" class="form-control" id="image" name="image"
                                                accept="image/*" onchange="previewMainImage(this)">
                                            <small class="text-muted">الحجم الموصى به: 800×800 بكسل. الصيغ المسموحة: JPEG,
                                                PNG, JPG, GIF, SVG, WEBP.</small>
                                            @if ($product->image)
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" id="delete_image"
                                                        name="delete_image">
                                                    <label class="form-check-label text-danger" for="delete_image">
                                                        <i class="fas fa-trash me-1"></i>
                                                        حذف الصورة الحالية
                                                    </label>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- الصور الإضافية -->
                                    <h6 class="mb-3">الصور الإضافية</h6>
                                    <div class="additional-images-preview mb-3" id="additional_images_preview">
                                        @foreach ($product->images->where('type', 'additional') as $image)
                                            <div class="additional-image-item">
                                                <img src="{{ asset('storage/' . $image->path) }}" alt="صورة إضافية">
                                                <div class="remove-image"
                                                    onclick="removeExistingImage(this, {{ $image->id }})">
                                                    <i class="fas fa-times"></i>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div>
                                        <input type="file" class="form-control" id="additional_images"
                                            name="additional_images[]" multiple accept="image/*"
                                            onchange="previewAdditionalImages(this)">
                                        <small class="text-muted">يمكنك اختيار عدة صور دفعة واحدة</small>
                                    </div>
                                    <input type="hidden" name="removed_images" id="removed_images">
                                </div>
                            </div>

                            <!-- النصوص الإعلانية -->
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-bullhorn me-2"></i>
                                        النصوص الإعلانية
                                        <span class="badge-count">{{ $product->adsText->count() }}</span>
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
                                        @foreach ($product->adsText as $index => $textAd)
                                            @php
                                                $adText = json_decode($textAd->name, true);
                                            @endphp
                                            <div class="text-ad-item">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="mb-0">النص الإعلاني {{ $index + 1 }}</h6>
                                                    <div>
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-primary ai-enhance-btn me-1"
                                                            data-target="#text_ad_{{ $index }}"
                                                            data-type="text_ad">
                                                            <i class="fas fa-wand-magic-sparkles"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="removeTextAdField(this)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- تبادل اللغات للنص الإعلاني -->
                                                <div class="language-field-tabs">
                                                    <button type="button" class="language-tab active"
                                                        data-text-ad-target="ar_{{ $index }}">العربية</button>
                                                    @foreach ($languages as $language)
                                                        @if ($language->code != 'ar')
                                                            <button type="button" class="language-tab"
                                                                data-text-ad-target="{{ $language->code }}_{{ $index }}">
                                                                {{ $language->name }}
                                                            </button>
                                                        @endif
                                                    @endforeach
                                                </div>

                                                <!-- العربية -->
                                                <div class="language-field-content active"
                                                    data-lang="ar_{{ $index }}">
                                                    <textarea class="form-control" id="text_ad_{{ $index }}" name="text_ads[{{ $index }}][name]"
                                                        rows="3" placeholder="النص الإعلاني بالعربية">{{ $adText['ar'] ?? '' }}</textarea>
                                                </div>

                                                <!-- اللغات الأخرى -->
                                                @foreach ($languages as $language)
                                                    @if ($language->code != 'ar')
                                                        <div class="language-field-content"
                                                            data-lang="{{ $language->code }}_{{ $index }}">
                                                            <input type="text" class="form-control"
                                                                name="text_ads[{{ $index }}][{{ $language->code }}]"
                                                                placeholder="النص الإعلاني بال{{ $language->name }}"
                                                                value="{{ $adText[$language->code] ?? '' }}">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- العروض -->
                            {{-- <div class="card mb-4">
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
                                                {{ $product->offers->contains($offer->id) ? 'selected' : '' }}>
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
                                    <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                                        <i class="fas fa-trash me-1"></i>
                                        حذف المنتج
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submit-btn">
                                        <i class="fas fa-save me-1"></i>
                                        حفظ التعديلات
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- العمود الجانبي -->
            <div class="col-md-4">
                <!-- بطاقة المعلومات -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            معلومات المنتج
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-lg me-3">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->getTranslation('name', 'ar') }}" class="rounded-circle"
                                        style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="avatar-initial bg-label-primary rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 60px; height: 60px;">
                                        <i class="fas fa-box fa-lg"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $product->getTranslation('name', 'ar') }}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-tag me-1"></i>
                                    {{ $product->category->getTranslation('name', 'ar') ?? 'غير مصنف' }}
                                </small>
                            </div>
                        </div>

                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span><i class="fas fa-hashtag me-2"></i>رقم المعرف</span>
                                <span class="badge bg-primary">{{ $product->id }}</span>
                            </div>
                            @if ($product->sku)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><i class="fas fa-barcode me-2"></i>رمز SKU</span>
                                    <span class="badge bg-info">{{ $product->sku }}</span>
                                </div>
                            @endif
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span><i class="fas fa-calendar-alt me-2"></i>تاريخ الإنشاء</span>
                                <span>{{ $product->created_at->format('Y/m/d') }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span><i class="fas fa-history me-2"></i>آخر تحديث</span>
                                <span>{{ $product->updated_at->format('Y/m/d H:i') }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span><i class="fas fa-star me-2"></i>التقييم</span>
                                <span>
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= round($product->average_rating))
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                    ({{ $product->reviews->count() }})
                                </span>
                            </div>
                        </div>
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
                            <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-2"></i>
                                عرض المنتج
                            </a>
                            <button type="button" class="btn btn-outline-info" onclick="duplicateProduct()">
                                <i class="fas fa-copy me-2"></i>
                                نسخ المنتج
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="toggleStatus()">
                                <i class="fas fa-toggle-on me-2"></i>
                                {{ $product->status_id == 1 ? 'تعطيل' : 'تفعيل' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- مودال تأكيد الحذف -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        تأكيد الحذف
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                        <h5>هل أنت متأكد من حذف هذا المنتج؟</h5>
                        <p class="text-muted">سيتم حذف المنتج
                            "<strong>{{ $product->getTranslation('name', 'ar') }}</strong>" بشكل دائم.</p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            هذا المنتج يحتوي على {{ $product->reviews->count() }} تقييم و
                            {{ $product->favorites->count() }} إضافة إلى المفضلة.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        إلغاء
                    </button>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>
                            حذف المنتج
                        </button>
                    </form>
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

            // تبادل اللغات للنصوص الإعلانية
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
                    $('#discount_fields').show();
                } else {
                    $('#discount_fields').hide();
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

            // تهيئة العدادات
            setTimeout(function() {
                $('#meta_title_ar').trigger('input');
                $('#meta_description_ar').trigger('input');
            }, 100);

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

            // إزالة صورة جديدة (لم يتم رفعها بعد)
            window.removeNewImage = function(button) {
                $(button).closest('.additional-image-item').remove();
            };

            // إزالة صورة موجودة (مرفوعة مسبقاً)
            window.removeExistingImage = function(button, imageId) {
                const $item = $(button).closest('.additional-image-item');
                $item.remove();

                let removedImages = $('#removed_images').val();
                if (removedImages) {
                    removedImages += ',' + imageId;
                } else {
                    removedImages = imageId.toString();
                }
                $('#removed_images').val(removedImages);
            };

            // متغير للعداد
            window.textAdCounter = {{ $product->adsText->count() }};

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
                        style: $('#translation_style').val(),
                        product_id: {{ $product->id }}
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
                        name: name,
                        description: description,
                        price_text: priceText,
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
                                    <strong>الاسم:</strong> ${truncateText(response.translated.name, 50)}<br>
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
                            name: name,
                            description: $('#description_ar').val().trim(),
                            price_text: $('#price_text_ar').val().trim(),
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
                        name: name,
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

            // زر إنشاء نسخة محسنة كاملة
            $('#generate-with-ai').on('click', function(e) {
                e.preventDefault();
                const $button = $(this);
                const name = $('#name_ar').val().trim();
                const description = $('#description_ar').val().trim();

                if (!name) {
                    showToast('تنبيه', 'الرجاء إدخال اسم المنتج أولاً', 'warning');
                    $('#name_ar').focus();
                    return;
                }

                $button.prop('disabled', true);
                $button.html('<i class="fas fa-spinner spin me-1"></i> جاري الإنشاء...');

                showAIMessage('جاري تحسين المنتج بالذكاء الاصطناعي...');

                $.ajax({
                    url: '{{ route('admin.products.ai-enhance-full') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: name,
                        description: description,
                        product_id: {{ $product->id }},
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
                            if (response.data.price_text) {
                                $('#price_text_ar').val(response.data.price_text);
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

                            showToast('تم', 'تم تحسين المنتج بنجاح', 'success');
                        } else {
                            showToast('خطأ', response.message || 'حدث خطأ أثناء التحسين',
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
                            '<i class="fas fa-wand-magic-sparkles me-1"></i> إنشاء نسخة محسنة بالذكاء الاصطناعي'
                        );
                    }
                });
            });

            // تأكيد حذف المنتج
            window.confirmDelete = function() {
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                deleteModal.show();
            };

            // نسخ المنتج
            window.duplicateProduct = function() {
                Swal.fire({
                    title: 'نسخ المنتج',
                    text: 'أدخل اسم للمنتج المنسوخ:',
                    input: 'text',
                    inputValue: '{{ $product->getTranslation('name', 'ar') }} - نسخة',
                    showCancelButton: true,
                    confirmButtonText: 'نسخ',
                    cancelButtonText: 'إلغاء',
                    showLoaderOnConfirm: true,
                    preConfirm: (name) => {
                        if (!name) {
                            Swal.showValidationMessage('يجب إدخال اسم للمنتج');
                            return false;
                        }

                        return $.ajax({
                            url: '{{ route('admin.products.duplicate', $product->id) }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                name: name
                            }
                        }).then(response => {
                            if (!response.success) {
                                throw new Error(response.message || 'حدث خطأ أثناء النسخ');
                            }
                            return response;
                        }).catch(error => {
                            Swal.showValidationMessage(error.message ||
                                'حدث خطأ في الاتصال');
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'تم النسخ!',
                            text: 'تم نسخ المنتج بنجاح',
                            icon: 'success',
                            confirmButtonText: 'حسناً'
                        }).then(() => {
                            window.location.href = '/admin/products/' + result.value.data.id +
                                '/edit';
                        });
                    }
                });
            };

            // تبديل حالة المنتج
            window.toggleStatus = function() {
                $.ajax({
                    url: '{{ route('admin.products.toggle-status', $product->id) }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'تم!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'حسناً'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('خطأ!', response.message || 'حدث خطأ أثناء تغيير الحالة',
                                'error');
                        }
                    },
                    error: function() {
                        Swal.fire('خطأ!', 'حدث خطأ أثناء تغيير الحالة', 'error');
                    }
                });
            };

            // التحقق من صحة النموذج
            $('#editProductForm').on('submit', function(e) {
                const name = $('#name_ar').val().trim();
                const price = $('#price').val();
                const stock = $('#stock').val();

                if (!name) {
                    e.preventDefault();
                    Swal.fire('خطأ', 'يرجى إدخال اسم المنتج', 'error');
                    $('#name_ar').focus();
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

                const submitBtn = $('#submit-btn');
                submitBtn.html('<i class="fas fa-spinner spin me-1"></i> جاري الحفظ...');
                submitBtn.prop('disabled', true);

                showAIMessage('جاري حفظ التعديلات...');

                return true;
            });

            // دوال مساعدة
            function getActionText(action) {
                const actions = {
                    'enhance': 'تحسين',
                    'complete': 'إكمال',
                    'seo': 'تحسين SEO',
                    'add_features': 'إضافة مميزات',
                    'add_benefits': 'إضافة فوائد',
                    'add_call_to_action': 'إضافة دعوة للعمل',
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
