@extends('Admin.layout.master')

@section('title', 'تفاصيل المنتج')

@section('css')
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

        .product-detail-card {
            background: var(--dark-card);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .product-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }

        .additional-images {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .additional-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s;
            border: 2px solid transparent;
        }

        .additional-image:hover {
            transform: scale(1.05);
            border-color: var(--primary-color);
        }

        .info-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .info-label {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 5px;
            font-size: 13px;
        }

        .info-value {
            color: #fff;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .tag-badge {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 5px 12px;
            border-radius: 20px;
            margin-right: 5px;
            margin-bottom: 5px;
            display: inline-block;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: rgba(21, 87, 36, 0.2);
            color: #20c997;
            border: 1px solid rgba(32, 201, 151, 0.3);
        }

        .status-inactive {
            background: rgba(133, 100, 4, 0.2);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .status-draft {
            background: rgba(56, 61, 65, 0.2);
            color: #adb5bd;
            border: 1px solid rgba(173, 181, 189, 0.3);
        }

        .price-box {
            background: var(--primary-gradient);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
        }

        .original-price {
            text-decoration: line-through;
            color: rgba(255, 255, 255, 0.7);
            font-size: 16px;
        }

        .final-price {
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0;
        }

        .discount-badge {
            background: rgba(220, 53, 69, 0.3);
            color: #ff6b6b;
            padding: 5px 15px;
            border-radius: 25px;
            font-size: 14px;
            display: inline-block;
            border: 1px solid rgba(220, 53, 69, 0.5);
        }

        .language-tabs {
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .language-tab {
            padding: 10px 20px;
            border: none;
            background: none;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            border-bottom: 2px solid transparent;
        }

        .language-tab.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            background: rgba(105, 108, 255, 0.1);
        }

        .language-tab:hover:not(.active) {
            color: #fff;
            border-bottom-color: rgba(255, 255, 255, 0.3);
        }

        .language-content {
            display: none;
        }

        .language-content.active {
            display: block;
        }

        .text-ads-container {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            padding: 15px;
            margin-top: 10px;
        }

        .text-ad-item {
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            margin-bottom: 10px;
            border-right: 4px solid var(--primary-color);
            color: #fff;
        }

        .image-gallery-item {
            position: relative;
            margin-bottom: 15px;
        }

        .image-gallery-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .primary-badge {
            position: absolute;
            top: 5px;
            left: 5px;
            background: var(--primary-gradient);
            color: white;
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 11px;
        }

        .review-item {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
        }

        .star-rating {
            color: #ffc107;
        }

        .breadcrumb {
            background: rgba(255, 255, 255, 0.05);
            padding: 10px 15px;
            border-radius: 8px;
        }

        .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: var(--primary-color);
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4a9a 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(105, 108, 255, 0.4);
        }

        .btn-outline-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .btn-outline-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .alert {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .alert-success {
            background: rgba(21, 87, 36, 0.2);
            border-color: rgba(32, 201, 151, 0.3);
            color: #20c997;
        }

        .alert-danger {
            background: rgba(133, 100, 4, 0.2);
            border-color: rgba(255, 193, 7, 0.3);
            color: #ffc107;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}">
                        <i class="fas fa-home me-1"></i>الرئيسية
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.products.index') }}">
                        <i class="fas fa-box me-1"></i>المنتجات
                    </a>
                </li>
                <li class="breadcrumb-item active">تفاصيل المنتج</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="product-detail-card">
                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="background: rgba(0,0,0,0.2); border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-box-open me-2"></i>
                            تفاصيل المنتج: {{ $product->getTranslatedValue('name', 'ar') }}
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit me-1"></i> تعديل
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-right me-1"></i> رجوع
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- الرسائل -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row">
                            <!-- الصور -->
                            <div class="col-md-5 mb-4">
                                <div class="info-card">
                                    <h6 class="info-label mb-3">
                                        <i class="fas fa-image me-2"></i>الصورة الرئيسية
                                    </h6>
                                    @php
                                        $primaryImage =
                                            $product->images->where('type', 'main')->first() ??
                                            $product->images->first();
                                    @endphp
                                    @if ($primaryImage)
                                        <img src="{{ asset('storage/' . $primaryImage->path) }}"
                                            alt="{{ $product->getTranslatedValue('name', 'ar') }}" class="product-image">
                                    @elseif($product->image_path)
                                        <img src="{{ asset('storage/' . $product->image_path) }}"
                                            alt="{{ $product->getTranslatedValue('name', 'ar') }}" class="product-image">
                                    @else
                                        <div class="text-center py-5 border rounded"
                                            style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.1) !important;">
                                            <i class="fas fa-image fa-4x text-muted mb-3"></i>
                                            <p class="text-muted">لا توجد صورة رئيسية</p>
                                        </div>
                                    @endif

                                    <!-- الصور الإضافية -->
                                    @php
                                        $additionalImages = $product->images->where('type', 'additional');
                                    @endphp
                                    @if ($additionalImages->count() > 0)
                                        <div class="additional-images">
                                            @foreach ($additionalImages as $image)
                                                <img src="{{ asset('storage/' . $image->path) }}" alt="صورة إضافية"
                                                    class="additional-image"
                                                    onclick="viewImage('{{ asset('storage/' . $image->path) }}')"
                                                    data-bs-toggle="tooltip" title="اضغط للتكبير">
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- المعلومات الأساسية -->
                            <div class="col-md-7">
                                <div class="info-card">
                                    <!-- تبديل اللغات -->
                                    <div class="language-tabs">
                                        <button class="language-tab active" data-lang="ar">
                                            <i class="fas fa-flag me-1"></i>العربية
                                        </button>
                                        @php
                                            $languages = \App\Models\Language::where('is_active', 1)->get();
                                        @endphp
                                        @foreach ($languages as $language)
                                            @if ($language->code != 'ar')
                                                <button class="language-tab" data-lang="{{ $language->code }}">
                                                    <i class="fas fa-language me-1"></i>{{ $language->name }}
                                                </button>
                                            @endif
                                        @endforeach
                                    </div>

                                    <!-- المحتوى العربي -->
                                    <div class="language-content active" data-lang="ar">
                                        <h4 class="mb-3 text-white">{{ $product->getTranslatedValue('name', 'ar') }}</h4>

                                        @if ($product->getTranslatedValue('description', 'ar'))
                                            <div class="mb-4">
                                                <h6 class="info-label">الوصف:</h6>
                                                <div class="info-value p-3"
                                                    style="background: rgba(0,0,0,0.2); border-radius: 8px;">
                                                    {!! nl2br(e($product->getTranslatedValue('description', 'ar'))) !!}
                                                </div>
                                            </div>
                                        @endif

                                        @if ($product->price_text)
                                            @php
                                                $priceText = json_decode($product->price_text, true);
                                            @endphp
                                            @if (isset($priceText['ar']) && !empty($priceText['ar']))
                                                <div class="mb-4">
                                                    <h6 class="info-label">نص السعر:</h6>
                                                    <div class="info-value p-3"
                                                        style="background: rgba(0,0,0,0.2); border-radius: 8px;">
                                                        {{ $priceText['ar'] }}
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>

                                    <!-- محتوى اللغات الأخرى -->
                                    @foreach ($languages as $language)
                                        @if ($language->code != 'ar')
                                            <div class="language-content" data-lang="{{ $language->code }}">
                                                <h4 class="mb-3 text-white">
                                                    {{ $product->getTranslatedValue('name', $language->code) ?: 'لا يوجد اسم' }}
                                                </h4>

                                                @php
                                                    $description = $product->getTranslatedValue(
                                                        'description',
                                                        $language->code,
                                                    );
                                                @endphp
                                                @if ($description)
                                                    <div class="mb-4">
                                                        <h6 class="info-label">الوصف:</h6>
                                                        <div class="info-value p-3"
                                                            style="background: rgba(0,0,0,0.2); border-radius: 8px;">
                                                            {!! nl2br(e($description)) !!}
                                                        </div>
                                                    </div>
                                                @endif

                                                @if ($product->price_text)
                                                    @php
                                                        $priceText = json_decode($product->price_text, true);
                                                    @endphp
                                                    @if (isset($priceText[$language->code]) && !empty($priceText[$language->code]))
                                                        <div class="mb-4">
                                                            <h6 class="info-label">نص السعر:</h6>
                                                            <div class="info-value p-3"
                                                                style="background: rgba(0,0,0,0.2); border-radius: 8px;">
                                                                {{ $priceText[$language->code] }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach

                                    <!-- السعر -->
                                    <div class="price-box mb-3">
                                        @if ($product->has_discount && $product->discount)
                                            <div class="original-price">{{ number_format($product->price, 2) }} ج.م</div>
                                            <div class="final-price">{{ number_format($product->final_price, 2) }} ج.م
                                            </div>
                                            <div class="discount-badge">
                                                @if ($product->discount->discount_type == 'percentage')
                                                    <i
                                                        class="fas fa-percent me-1"></i>{{ $product->discount->discount_value }}%
                                                    خصم
                                                @else
                                                    <i
                                                        class="fas fa-tag me-1"></i>{{ number_format($product->discount->discount_value, 2) }}
                                                    ج.م خصم
                                                @endif
                                            </div>
                                        @else
                                            <div class="final-price">{{ number_format($product->price, 2) }} ج.م</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- التفاصيل الإضافية -->
                        <div class="row mt-4">
                            <div class="col-md-4 mb-3">
                                <div class="info-card">
                                    <h6 class="info-label mb-3">
                                        <i class="fas fa-info-circle me-2"></i>معلومات أساسية
                                    </h6>

                                    <div class="mb-3">
                                        <span class="info-label">القسم:</span>
                                        <div class="info-value">
                                            @if ($product->category)
                                                <a href="{{ route('admin.categories.show', $product->category_id) }}"
                                                    class="text-white">
                                                    <i class="fas fa-folder me-1"></i>{{ $product->category->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <span class="info-label">الكمية المتاحة:</span>
                                        <div class="info-value">
                                            @if ($product->stock > 0)
                                                <span class="badge bg-success">{{ $product->stock }} قطعة</span>
                                            @else
                                                <span class="badge bg-danger">غير متوفر</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <span class="info-label">SKU:</span>
                                        <div class="info-value">
                                            <code>{{ $product->sku ?: 'غير محدد' }}</code>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="info-card">
                                    <h6 class="info-label mb-3">
                                        <i class="fas fa-toggle-on me-2"></i>الحالة والإعدادات
                                    </h6>

                                    <div class="mb-3">
                                        <span class="info-label">حالة المنتج:</span>
                                        <div class="info-value">
                                            @php
                                                $statusMap = [
                                                    1 => ['text' => 'نشط', 'class' => 'status-active'],
                                                    2 => ['text' => 'غير نشط', 'class' => 'status-inactive'],
                                                    3 => ['text' => 'مسودة', 'class' => 'status-draft'],
                                                ];
                                                $status = $statusMap[$product->status_id] ?? $statusMap[2];
                                            @endphp
                                            <span class="status-badge {{ $status['class'] }}">
                                                <i
                                                    class="fas {{ $product->status_id == 1 ? 'fa-check-circle' : ($product->status_id == 2 ? 'fa-times-circle' : 'fa-clock') }} me-1"></i>
                                                {{ $status['text'] }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <span class="info-label">يشمل الضريبة:</span>
                                        <div class="info-value">
                                            @if ($product->includes_tax)
                                                <span class="badge bg-info">نعم</span>
                                            @else
                                                <span class="badge bg-secondary">لا</span>
                                            @endif
                                        </div>
                                    </div>


                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="info-card">
                                    <h6 class="info-label mb-3">
                                        <i class="fas fa-calendar me-2"></i>التواريخ
                                    </h6>

                                    <div class="mb-3">
                                        <span class="info-label">تاريخ الإنشاء:</span>
                                        <div class="info-value">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ $product->created_at->format('Y-m-d') }}
                                            <br>
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i>
                                                {{ $product->created_at->format('h:i A') }}
                                            </small>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <span class="info-label">آخر تحديث:</span>
                                        <div class="info-value">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ $product->updated_at->format('Y-m-d') }}
                                            <br>
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i>
                                                {{ $product->updated_at->format('h:i A') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- التقييمات -->
                        @if ($product->reviews && $product->reviews->count() > 0)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="info-card">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="info-label mb-0">
                                                <i class="fas fa-star me-2"></i>التقييمات
                                            </h6>
                                            <span class="badge bg-primary">{{ $product->reviews->count() }} تقييم</span>
                                        </div>

                                        @php
                                            $totalRating = $product->reviews->avg('rating') ?? 0;
                                        @endphp
                                        <div class="text-center mb-4">
                                            <div class="display-4 text-white">{{ number_format($totalRating, 1) }}</div>
                                            <div class="star-rating mb-2">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($totalRating))
                                                        <i class="fas fa-star"></i>
                                                    @elseif($i - 0.5 <= $totalRating)
                                                        <i class="fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <small class="text-muted">من أصل 5 نجوم</small>
                                        </div>

                                        @foreach ($product->reviews as $review)
                                            <div class="review-item">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div>
                                                        <strong
                                                            class="text-white">{{ $review->user->name ?? 'مستخدم' }}</strong>
                                                        <div class="star-rating">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($i <= $review->rating)
                                                                    <i class="fas fa-star"></i>
                                                                @else
                                                                    <i class="far fa-star"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="far fa-calendar-alt me-1"></i>
                                                        {{ $review->created_at->format('Y-m-d') }}
                                                    </small>
                                                </div>
                                                <p class="mb-0 text-white-50">{{ $review->comment }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- النصوص الإعلانية -->
                        @if ($product->adsText && $product->adsText->count() > 0)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="info-card">
                                        <h6 class="info-label mb-3">
                                            <i class="fas fa-ad me-2"></i>النصوص الإعلانية
                                        </h6>

                                        <!-- تبادل اللغات للنصوص الإعلانية -->
                                        <div class="language-tabs mb-3">
                                            <button class="language-tab active" data-ads-lang="ar">العربية</button>
                                            @foreach ($languages as $language)
                                                @if ($language->code != 'ar')
                                                    <button class="language-tab" data-ads-lang="{{ $language->code }}">
                                                        {{ $language->name }}
                                                    </button>
                                                @endif
                                            @endforeach
                                        </div>

                                        <!-- النصوص الإعلانية العربية -->
                                        <div class="text-ads-container language-ads-content active" data-lang="ar">
                                            @foreach ($product->adsText as $textAd)
                                                @php
                                                    $adText = json_decode($textAd->name, true);
                                                @endphp
                                                <div class="text-ad-item">
                                                    <i class="fas fa-quote-right me-2"
                                                        style="color: var(--primary-color);"></i>
                                                    {{ $adText['ar'] ?? 'لا يوجد نص' }}
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- النصوص الإعلانية بلغات أخرى -->
                                        @foreach ($languages as $language)
                                            @if ($language->code != 'ar')
                                                <div class="text-ads-container language-ads-content"
                                                    data-lang="{{ $language->code }}">
                                                    @foreach ($product->adsText as $textAd)
                                                        @php
                                                            $adText = json_decode($textAd->name, true);
                                                        @endphp
                                                        <div class="text-ad-item">
                                                            <i class="fas fa-quote-right me-2"
                                                                style="color: var(--primary-color);"></i>
                                                            {{ $adText[$language->code] ?? 'لا يوجد نص' }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- العروض -->
                        @if ($product->offers && $product->offers->count() > 0)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="info-card">
                                        <h6 class="info-label mb-3">
                                            <i class="fas fa-tags me-2"></i>العروض
                                        </h6>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($product->offers as $offer)
                                                <span class="tag-badge">
                                                    <i class="fas fa-tag me-1"></i>
                                                    {{ $offer->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- جميع الصور -->
                        @if ($product->images && $product->images->count() > 0)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="info-card">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="info-label mb-0">
                                                <i class="fas fa-images me-2"></i>معرض الصور
                                            </h6>
                                            <span class="badge bg-primary">{{ $product->images->count() }} صورة</span>
                                        </div>
                                        <div class="row">
                                            @foreach ($product->images as $image)
                                                <div class="col-md-3 col-sm-6">
                                                    <div class="image-gallery-item">
                                                        <img src="{{ asset('storage/' . $image->path) }}"
                                                            alt="صورة المنتج"
                                                            onclick="viewImage('{{ asset('storage/' . $image->path) }}')"
                                                            style="cursor: pointer;">
                                                        @if ($image->is_primary || $image->type == 'main')
                                                            <span class="primary-badge">
                                                                <i class="fas fa-star"></i> رئيسية
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- SEO -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="info-card">
                                    <h6 class="info-label mb-3">
                                        <i class="fas fa-chart-line me-2"></i>معلومات SEO
                                    </h6>

                                    <!-- تبادل لغات SEO -->
                                    <div class="language-tabs mb-3">
                                        <button class="language-tab active" data-seo-lang="ar">العربية</button>
                                        @foreach ($languages as $language)
                                            @if ($language->code != 'ar')
                                                <button class="language-tab" data-seo-lang="{{ $language->code }}">
                                                    {{ $language->name }}
                                                </button>
                                            @endif
                                        @endforeach
                                    </div>

                                    <!-- محتوى SEO العربي -->
                                    <div class="seo-content active" data-seo-lang="ar">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <span class="info-label">Meta Title:</span>
                                                <div class="info-value p-2"
                                                    style="background: rgba(0,0,0,0.2); border-radius: 5px;">
                                                    {{ $product->getTranslatedValue('meta_title', 'ar') ?: 'لا يوجد' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <span class="info-label">Meta Description:</span>
                                                <div class="info-value p-2"
                                                    style="background: rgba(0,0,0,0.2); border-radius: 5px;">
                                                    {{ $product->getTranslatedValue('meta_description', 'ar') ?: 'لا يوجد' }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <span class="info-label">Meta Keywords:</span>
                                                <div class="info-value p-2"
                                                    style="background: rgba(0,0,0,0.2); border-radius: 5px;">
                                                    {{ $product->getTranslatedValue('meta_keywords', 'ar') ?: 'لا يوجد' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- محتوى SEO للغات الأخرى -->
                                    @foreach ($languages as $language)
                                        @if ($language->code != 'ar')
                                            <div class="seo-content" data-seo-lang="{{ $language->code }}"
                                                style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <span class="info-label">Meta Title:</span>
                                                        <div class="info-value p-2"
                                                            style="background: rgba(0,0,0,0.2); border-radius: 5px;">
                                                            {{ $product->getTranslatedValue('meta_title', $language->code) ?: 'لا يوجد' }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <span class="info-label">Meta Description:</span>
                                                        <div class="info-value p-2"
                                                            style="background: rgba(0,0,0,0.2); border-radius: 5px;">
                                                            {{ $product->getTranslatedValue('meta_description', $language->code) ?: 'لا يوجد' }}
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <span class="info-label">Meta Keywords:</span>
                                                        <div class="info-value p-2"
                                                            style="background: rgba(0,0,0,0.2); border-radius: 5px;">
                                                            {{ $product->getTranslatedValue('meta_keywords', $language->code) ?: 'لا يوجد' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لعرض الصورة -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="background: var(--dark-card); border: 1px solid rgba(255,255,255,0.1);">
                <div class="modal-body p-0">
                    <img id="modalImage" src="" alt="صورة المنتج" class="img-fluid w-100">
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.1);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>إغلاق
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // تفعيل tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // تبديل المحتوى بين اللغات للمنتج
            $('.language-tab[data-lang]').on('click', function() {
                const lang = $(this).data('lang');

                // إزالة النشط من جميع الألسنة
                $('.language-tab[data-lang]').removeClass('active');
                $(this).addClass('active');

                // إخفاء جميع المحتويات
                $('.language-content[data-lang]').removeClass('active');
                $('.language-content[data-lang="' + lang + '"]').addClass('active');
            });

            // تبديل النصوص الإعلانية بين اللغات
            $('[data-ads-lang]').on('click', function() {
                const lang = $(this).data('ads-lang');

                // إزالة النشط من جميع الألسنة
                $('[data-ads-lang]').removeClass('active');
                $(this).addClass('active');

                // إخفاء جميع المحتويات
                $('.language-ads-content').removeClass('active');
                $('.language-ads-content[data-lang="' + lang + '"]').addClass('active');
            });

            // تبديل محتوى SEO بين اللغات
            $('[data-seo-lang]').on('click', function() {
                const lang = $(this).data('seo-lang');

                // إزالة النشط من جميع الألسنة
                $('[data-seo-lang]').removeClass('active');
                $(this).addClass('active');

                // إخفاء جميع المحتويات
                $('.seo-content').hide();
                $('.seo-content[data-seo-lang="' + lang + '"]').show();
            });

            // عرض الصور الكبيرة
            window.viewImage = function(src) {
                $('#modalImage').attr('src', src);
                new bootstrap.Modal(document.getElementById('imageModal')).show();
            };

            // إغلاق التنبيهات تلقائياً
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
@endsection
