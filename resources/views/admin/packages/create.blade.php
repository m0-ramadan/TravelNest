@include('admin.i18n.locale')
@extends('admin.layout.master')

@section('title', admin_t('إضافة رحلة جديدة'))

@php
    $locale = app()->getLocale();
    $isRtl = $locale === 'ar';
    $viewErrors = $errors ?? new \Illuminate\Support\ViewErrorBag();

    $facilities = old('facilities', []);
    $itinerary = old('itinerary', []);
    $included = old('included', []);
    $excluded = old('excluded', []);
    $prices = old('prices', []);

    $steps = [
        1 => [
            'title' => admin_t('البيانات الأساسية'),
            'description' => admin_t('أدخل المعلومات الرئيسية والتصنيف الخاص بالرحلة.'),
        ],
        2 => [
            'title' => admin_t('الوصف والصور'),
            'description' => admin_t('أضف وصف الرحلة والصور التي ستظهر للعملاء.'),
        ],
        3 => [
            'title' => admin_t('المسار والمدة'),
            'description' => admin_t('حدد مدة الرحلة والبرنامج اليومي ومسار الرحلة.'),
        ],
        4 => [
            'title' => admin_t('الأسعار والسياسات'),
            'description' => admin_t('حدد أسعار الرحلة وما هو مشمول وسياسات الحجز.'),
        ],
        5 => [
            'title' => admin_t('النشر وSEO'),
            'description' => admin_t('راجع بيانات الرحلة وحدد إعدادات النشر ومحركات البحث.'),
        ],
    ];

    $errorFieldStepMap = [
        'title' => 1,
        'subtitle' => 1,
        'slug' => 1,
        'category_id' => 1,
        'destination_id' => 1,
        'primary_country_id' => 1,
        'package_type' => 1,
        'tour_type' => 1,
        'currency_id' => 1,
        'booking_mode' => 1,
        'short_description' => 2,
        'description' => 2,
        'featured_image' => 2,
        'gallery_images' => 2,
        'duration_type' => 3,
        'duration_days' => 3,
        'duration_nights' => 3,
        'duration_hours' => 3,
        'duration_text' => 3,
        'schedule_text' => 3,
        'route_text' => 3,
        'pickup_location' => 3,
        'dropoff_location' => 3,
        'destinations_text' => 3,
        'location_summary' => 3,
        'itinerary' => 3,
        'facilities' => 4,
        'included' => 4,
        'excluded' => 4,
        'start_from_price' => 4,
        'compare_price' => 4,
        'pricing_information' => 4,
        'children_policy' => 4,
        'pickup_policy' => 4,
        'cancellation_policy' => 4,
        'terms_conditions' => 4,
        'prices' => 4,
        'min_participants' => 5,
        'max_participants' => 5,
        'booking_lead_days' => 5,
        'rating_avg' => 5,
        'reviews_count' => 5,
        'difficulty_level' => 5,
        'video_url' => 5,
        'published_at' => 5,
        'sort_order' => 5,
        'is_active' => 5,
        'is_featured' => 5,
        'is_best_seller' => 5,
        'is_ultra_luxury' => 5,
        'seo_title' => 5,
        'seo_description' => 5,
        'breadcrumb_title' => 5,
        'canonical_url' => 5,
    ];

    $initialStep = 1;

    if ($viewErrors->any()) {
        foreach ($viewErrors->keys() as $errorKey) {
            $matchedStep = null;

            foreach ($errorFieldStepMap as $field => $stepNumber) {
                if ($errorKey === $field || str_starts_with($errorKey, $field . '.')) {
                    $matchedStep = $stepNumber;
                    break;
                }
            }

            if ($matchedStep !== null) {
                $initialStep = $matchedStep;
                break;
            }
        }
    }
@endphp

@section('css')
    <style>
        :root {
            --wizard-primary: #7c3aed;
            --wizard-primary-soft: rgba(124, 58, 237, 0.16);
            --wizard-page: #1e1b2e;
            --wizard-card: #2a3148;
            --wizard-card-soft: #323b56;
            --wizard-border: rgba(255, 255, 255, 0.08);
            --wizard-muted: rgba(255, 255, 255, 0.68);
            --wizard-input: rgba(255, 255, 255, 0.05);
            --wizard-success: #10b981;
            --wizard-danger: #ef4444;
            --wizard-warning: #f59e0b;
        }

        body {
            background: var(--wizard-page);
        }

        .package-wizard {
            color: #fff;
        }

        .wizard-shell {
            background: linear-gradient(180deg, rgba(124, 58, 237, 0.18), rgba(42, 49, 72, 0.96) 22%);
            border: 1px solid var(--wizard-border);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 22px 60px rgba(0, 0, 0, 0.28);
        }

        .wizard-hero {
            padding: 28px 28px 18px;
            border-bottom: 1px solid var(--wizard-border);
            background:
                radial-gradient(circle at top {{ $isRtl ? 'right' : 'left' }}, rgba(167, 139, 250, 0.35), transparent 30%),
                linear-gradient(135deg, rgba(124, 58, 237, 0.24), rgba(42, 49, 72, 0.88));
        }

        .wizard-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            color: #f4ecff;
            font-size: 13px;
            margin-bottom: 14px;
        }

        .wizard-title {
            font-size: 28px;
            font-weight: 800;
            margin: 0 0 10px;
        }

        .wizard-subtitle {
            color: var(--wizard-muted);
            max-width: 760px;
            margin: 0;
        }

        .wizard-top-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: flex-end;
        }

        .wizard-top-actions .btn {
            border-radius: 12px;
            min-height: 46px;
        }

        .wizard-stepper {
            padding: 22px 28px 10px;
            border-bottom: 1px solid var(--wizard-border);
        }

        .wizard-steps {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 14px;
        }

        .wizard-step {
            position: relative;
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 16px;
            border-radius: 18px;
            border: 1px solid var(--wizard-border);
            background: rgba(255, 255, 255, 0.02);
            cursor: pointer;
            transition: .25s ease;
            text-align: {{ $isRtl ? 'right' : 'left' }};
        }

        .wizard-step:hover {
            border-color: rgba(124, 58, 237, 0.5);
            transform: translateY(-2px);
        }

        .wizard-step.is-disabled {
            opacity: .62;
            cursor: not-allowed;
        }

        .wizard-step.is-active {
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.28), rgba(124, 58, 237, 0.08));
            border-color: rgba(167, 139, 250, 0.55);
            box-shadow: inset 0 0 0 1px rgba(167, 139, 250, 0.18);
        }

        .wizard-step.is-complete {
            background: rgba(16, 185, 129, 0.08);
            border-color: rgba(16, 185, 129, 0.28);
        }

        .wizard-step-badge {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            flex: 0 0 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            font-weight: 700;
        }

        .wizard-step.is-active .wizard-step-badge {
            background: #fff;
            color: var(--wizard-primary);
        }

        .wizard-step.is-complete .wizard-step-badge {
            background: var(--wizard-success);
        }

        .wizard-step-title {
            font-size: 15px;
            font-weight: 700;
            margin: 0 0 4px;
        }

        .wizard-step-description {
            margin: 0;
            color: var(--wizard-muted);
            font-size: 12px;
            line-height: 1.55;
        }

        .wizard-mobile-progress {
            display: none;
        }

        .wizard-body {
            padding: 28px;
        }

        .wizard-panel {
            display: none;
        }

        .wizard-panel.is-active {
            display: block;
        }

        .wizard-panel-header {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .wizard-panel-title {
            margin: 0 0 6px;
            font-size: 24px;
            font-weight: 800;
        }

        .wizard-panel-copy {
            color: var(--wizard-muted);
            margin: 0;
        }

        .wizard-panel-pill {
            padding: 10px 14px;
            border-radius: 999px;
            border: 1px solid var(--wizard-border);
            background: rgba(255, 255, 255, 0.04);
            color: #fff;
            white-space: nowrap;
        }

        .wizard-grid {
            display: grid;
            gap: 18px;
        }

        .wizard-grid.two-columns {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .form-section-card {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0.02));
            border: 1px solid var(--wizard-border);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .section-header {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 18px 20px;
            border-bottom: 1px solid var(--wizard-border);
            background: rgba(255, 255, 255, 0.03);
        }

        .section-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--wizard-primary-soft);
            color: #e9dcff;
            flex: 0 0 44px;
        }

        .section-header h3 {
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 4px;
        }

        .section-header p {
            margin: 0;
            color: var(--wizard-muted);
            font-size: 13px;
        }

        .section-body {
            padding: 20px;
        }

        .fields-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .fields-grid.two-up {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .field-span-2 {
            grid-column: span 2;
        }

        .field-span-3 {
            grid-column: span 3;
        }

        .form-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 8px;
            font-weight: 700;
            color: #f5f3ff;
        }

        .required-mark {
            color: #fb7185;
            font-size: 15px;
        }

        .field-help {
            display: block;
            color: var(--wizard-muted);
            font-size: 12px;
            margin-top: 7px;
        }

        .form-control,
        .form-select,
        textarea {
            min-height: 48px;
            border-radius: 13px !important;
            border: 1px solid var(--wizard-border) !important;
            background: var(--wizard-input) !important;
            color: #fff !important;
            box-shadow: none !important;
        }

        textarea.form-control {
            min-height: auto;
        }

        .form-control::placeholder,
        textarea::placeholder {
            color: rgba(255, 255, 255, 0.46);
        }

        .form-control:focus,
        .form-select:focus,
        textarea:focus {
            border-color: rgba(167, 139, 250, 0.7) !important;
            box-shadow: 0 0 0 .22rem rgba(124, 58, 237, 0.18) !important;
        }

        .form-select option {
            color: #111827;
        }

        .form-control.is-invalid,
        .form-select.is-invalid,
        textarea.is-invalid,
        .field-error {
            border-color: rgba(239, 68, 68, 0.92) !important;
            box-shadow: 0 0 0 .18rem rgba(239, 68, 68, 0.12) !important;
        }

        .invalid-feedback {
            display: block;
            font-size: 12px;
            margin-top: 8px;
            color: #fecaca;
        }

        .counter-line {
            display: flex;
            justify-content: flex-end;
            margin-top: 7px;
            color: var(--wizard-muted);
            font-size: 12px;
        }

        .choice-row {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 8px;
        }

        .choice-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 999px;
            border: 1px solid var(--wizard-border);
            background: rgba(255, 255, 255, 0.03);
        }

        .repeat-box {
            background: rgba(255, 255, 255, 0.035);
            border: 1px solid var(--wizard-border);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 14px;
        }

        .repeat-box-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .repeat-box-title strong {
            font-size: 15px;
        }

        .empty-state {
            border: 1px dashed rgba(255, 255, 255, 0.14);
            border-radius: 16px;
            padding: 26px 18px;
            text-align: center;
            color: var(--wizard-muted);
            background: rgba(255, 255, 255, 0.02);
        }

        .facility-suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 14px;
        }

        .facility-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(167, 139, 250, 0.24);
            background: rgba(124, 58, 237, 0.10);
            color: #f4ecff;
            border-radius: 999px;
            padding: 9px 14px;
            cursor: pointer;
            transition: .2s ease;
        }

        .facility-chip:hover {
            transform: translateY(-1px);
            background: rgba(124, 58, 237, 0.18);
        }

        .upload-zone {
            position: relative;
            display: grid;
            place-items: center;
            min-height: 220px;
            border: 1px dashed rgba(167, 139, 250, 0.34);
            border-radius: 18px;
            background: rgba(124, 58, 237, 0.07);
            text-align: center;
            padding: 22px;
            cursor: pointer;
            overflow: hidden;
        }

        .upload-zone input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }

        .upload-zone h4 {
            font-size: 18px;
            font-weight: 700;
            margin: 14px 0 8px;
        }

        .upload-zone p {
            margin: 0;
            color: var(--wizard-muted);
            max-width: 360px;
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 14px;
            margin-top: 18px;
        }

        .preview-card {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--wizard-border);
            background: rgba(255, 255, 255, 0.04);
        }

        .preview-card img {
            width: 100%;
            height: 138px;
            object-fit: cover;
            display: block;
        }

        .preview-card-footer {
            padding: 10px 12px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
            align-items: center;
            font-size: 12px;
        }

        .preview-remove {
            border: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(239, 68, 68, 0.16);
            color: #fecaca;
        }

        .split-card {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .summary-item {
            border: 1px solid var(--wizard-border);
            border-radius: 15px;
            padding: 14px;
            background: rgba(255, 255, 255, 0.03);
        }

        .summary-label {
            display: block;
            color: var(--wizard-muted);
            font-size: 12px;
            margin-bottom: 6px;
        }

        .summary-value {
            font-size: 15px;
            font-weight: 700;
        }

        .review-list {
            display: grid;
            gap: 12px;
        }

        .review-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 14px;
            border: 1px solid var(--wizard-border);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.03);
        }

        .review-meta small {
            color: var(--wizard-muted);
            display: block;
            margin-top: 3px;
        }

        .wizard-actions {
            position: sticky;
            bottom: 0;
            z-index: 20;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-top: 26px;
            padding: 18px 22px;
            border-radius: 18px;
            border: 1px solid var(--wizard-border);
            background: rgba(25, 28, 43, 0.92);
            backdrop-filter: blur(10px);
        }

        .wizard-actions-meta {
            color: var(--wizard-muted);
            font-size: 13px;
        }

        .wizard-actions-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .wizard-actions .btn {
            min-height: 46px;
            border-radius: 12px;
            padding-inline: 18px;
        }

        .btn-wizard-primary {
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
            border: 0;
            color: #fff;
        }

        .btn-wizard-primary:hover {
            color: #fff;
            opacity: .95;
        }

        .btn-wizard-outline {
            background: transparent;
            border: 1px solid var(--wizard-border);
            color: #fff;
        }

        .btn-wizard-outline:hover {
            border-color: rgba(167, 139, 250, 0.55);
            color: #fff;
        }

        .btn-icon-text {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .d-none-force {
            display: none !important;
        }

        @media (max-width: 1199px) {
            .wizard-steps,
            .fields-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .field-span-3 {
                grid-column: span 2;
            }
        }

        @media (max-width: 991px) {
            .wizard-grid.two-columns,
            .split-card,
            .summary-grid {
                grid-template-columns: 1fr;
            }

            .wizard-steps {
                display: none;
            }

            .wizard-mobile-progress {
                display: block;
                margin-top: 4px;
            }

            .wizard-mobile-bar {
                height: 8px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.08);
                overflow: hidden;
                margin-top: 10px;
            }

            .wizard-mobile-bar > span {
                display: block;
                height: 100%;
                border-radius: inherit;
                background: linear-gradient(90deg, #8b5cf6, #7c3aed);
                transition: width .25s ease;
            }
        }

        @media (max-width: 767px) {
            .wizard-hero,
            .wizard-stepper,
            .wizard-body {
                padding-inline: 18px;
            }

            .wizard-title {
                font-size: 24px;
            }

            .wizard-panel-header {
                flex-direction: column;
            }

            .fields-grid,
            .fields-grid.two-up,
            .field-span-2,
            .field-span-3 {
                grid-template-columns: 1fr;
                grid-column: span 1;
            }

            .wizard-actions {
                padding: 16px;
            }

            .wizard-actions-group {
                width: 100%;
            }

            .wizard-actions-group .btn {
                flex: 1 1 auto;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y package-wizard" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}">{{ admin_t('الرئيسية') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.packages.index') }}">{{ admin_t('الرحلات') }}</a>
                </li>
                <li class="breadcrumb-item active">{{ admin_t('إضافة رحلة جديدة') }}</li>
            </ol>
        </nav>

        <div class="wizard-shell">
            <div class="wizard-hero">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <span class="wizard-eyebrow">
                            <i class="ti ti-route"></i>
                            {{ admin_t('مراجعة ورفع') }}
                        </span>
                        <h1 class="wizard-title">{{ admin_t('إضافة رحلة جديدة') }}</h1>
                        <p class="wizard-subtitle">{{ admin_t('أضف جميع تفاصيل الرحلة خطوة بخطوة ثم قم بنشرها.') }}</p>
                    </div>

                    <div class="wizard-top-actions">
                        @if (Route::has('admin.packages.create-with-ai'))
                            <a href="{{ route('admin.packages.create-with-ai') }}" class="btn btn-light">
                                <span class="btn-icon-text">
                                    <i class="ti ti-sparkles"></i>
                                    {{ admin_t('إنشاء بالذكاء الاصطناعي') }}
                                </span>
                            </a>
                        @endif

                        <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-light" id="cancelWizardBtn">
                            <span class="btn-icon-text">
                                <i class="ti ti-arrow-back-up"></i>
                                {{ admin_t('الرجوع لقائمة الرحلات') }}
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="wizard-stepper">
                <div class="wizard-steps" id="wizardSteps">
                    @foreach ($steps as $number => $step)
                        <button type="button" class="wizard-step" data-step-trigger="{{ $number }}">
                            <span class="wizard-step-badge">{{ $number }}</span>
                            <span>
                                <span class="wizard-step-title">{{ $step['title'] }}</span>
                                <span class="wizard-step-description">{{ $step['description'] }}</span>
                            </span>
                        </button>
                    @endforeach
                </div>

                <div class="wizard-mobile-progress">
                    <strong id="mobileStepTitle">{{ $steps[$initialStep]['title'] }}</strong>
                    <div class="wizard-actions-meta mt-1" id="mobileStepCounter"></div>
                    <div class="wizard-mobile-bar">
                        <span id="mobileStepBar" style="width: {{ ($initialStep / count($steps)) * 100 }}%"></span>
                    </div>
                </div>
            </div>

            <div class="wizard-body">
                <form id="packageWizardForm" action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="wizard-panel" data-step-panel="1">
                        <div class="wizard-panel-header">
                            <div>
                                <h2 class="wizard-panel-title">{{ admin_t('البيانات الأساسية') }}</h2>
                                <p class="wizard-panel-copy">{{ admin_t('أدخل المعلومات الرئيسية والتصنيف الخاص بالرحلة.') }}</p>
                            </div>
                            <div class="wizard-panel-pill">{{ admin_t('الخطوة :current من :total', ['current' => 1, 'total' => count($steps)]) }}</div>
                        </div>

                        <div class="wizard-grid">
                            <div class="form-section-card">
                                <div class="section-header">
                                    <div class="section-icon"><i class="ti ti-briefcase"></i></div>
                                    <div>
                                        <h3>{{ admin_t('معلومات الرحلة الأساسية') }}</h3>
                                        <p>{{ admin_t('عرّف هوية الرحلة والجهة المرتبطة بها وإعدادات الحجز الأساسية.') }}</p>
                                    </div>
                                </div>

                                <div class="section-body">
                                    <div class="fields-grid">
                                        <div>
                                            <label class="form-label" for="title">
                                                {{ admin_t('عنوان الرحلة') }}
                                                <span class="required-mark">*</span>
                                            </label>
                                            <input id="title" type="text" name="title"
                                                class="form-control @error('title') is-invalid @enderror"
                                                value="{{ old('title') }}"
                                                placeholder="{{ admin_t('اكتب عنوانًا واضحًا للرحلة') }}"
                                                data-required-step="1">
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="form-label" for="subtitle">{{ admin_t('العنوان الفرعي') }}</label>
                                            <input id="subtitle" type="text" name="subtitle"
                                                class="form-control @error('subtitle') is-invalid @enderror"
                                                value="{{ old('subtitle') }}"
                                                placeholder="{{ admin_t('أضف سطرًا تعريفيا قصيرًا') }}">
                                            @error('subtitle')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="form-label" for="slug">{{ admin_t('Slug') }}</label>
                                            <input id="slug" type="text" name="slug"
                                                class="form-control @error('slug') is-invalid @enderror"
                                                value="{{ old('slug') }}"
                                                placeholder="{{ admin_t('يتم توليده تلقائيًا إذا تركته فارغًا') }}">
                                            @error('slug')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="form-label" for="category_id">
                                                {{ admin_t('التصنيف') }}
                                                <span class="required-mark">*</span>
                                            </label>
                                            <select id="category_id" name="category_id"
                                                class="form-select @error('category_id') is-invalid @enderror"
                                                data-required-step="1">
                                                <option value="">{{ admin_t('اختر التصنيف') }}</option>
                                                @foreach ($categories ?? collect() as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ adminTrans($category->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="form-label" for="destination_selector">
                                                {{ admin_t('الوجهة') }}
                                                <span class="required-mark">*</span>
                                            </label>
                                            <select id="destination_selector" name="destination_id"
                                                class="form-select @error('destination_id') is-invalid @enderror"
                                                data-required-step="1">
                                                <option value="">{{ admin_t('اختر الوجهة') }}</option>
                                                @foreach ($destinations ?? collect() as $destination)
                                                    <option value="{{ $destination->id }}"
                                                        data-country-id="{{ $destination->country_id }}"
                                                        data-destination-name="{{ adminTrans($destination->name) }}"
                                                        {{ old('destination_id') == $destination->id ? 'selected' : '' }}>
                                                        {{ adminTrans($destination->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="primary_country_id" id="primary_country_id"
                                                value="{{ old('primary_country_id') }}">
                                            @error('destination_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="form-label" for="package_type">
                                                {{ admin_t('نوع الرحلة') }}
                                                <span class="required-mark">*</span>
                                            </label>
                                            <select id="package_type" name="package_type"
                                                class="form-select @error('package_type') is-invalid @enderror"
                                                data-required-step="1">
                                                <option value="">{{ admin_t('اختر النوع') }}</option>
                                                <option value="travel_package" {{ old('package_type') == 'travel_package' ? 'selected' : '' }}>
                                                    {{ admin_t('باقة سفر') }}
                                                </option>
                                                <option value="nile_cruise" {{ old('package_type') == 'nile_cruise' ? 'selected' : '' }}>
                                                    {{ admin_t('رحلة نيلية') }}
                                                </option>
                                                <option value="day_tour" {{ old('package_type') == 'day_tour' ? 'selected' : '' }}>
                                                    {{ admin_t('جولة يومية') }}
                                                </option>
                                                <option value="shore_excursion" {{ old('package_type') == 'shore_excursion' ? 'selected' : '' }}>
                                                    {{ admin_t('رحلة شاطئية') }}
                                                </option>
                                                <option value="tailor_made" {{ old('package_type') == 'tailor_made' ? 'selected' : '' }}>
                                                    {{ admin_t('رحلة مخصصة') }}
                                                </option>
                                            </select>
                                            @error('package_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="form-label" for="tour_type">{{ admin_t('نوع الجولة') }}</label>
                                            <select id="tour_type" name="tour_type"
                                                class="form-select @error('tour_type') is-invalid @enderror">
                                                <option value="">{{ admin_t('اختر نوع الجولة') }}</option>
                                                <option value="private" {{ old('tour_type') == 'private' ? 'selected' : '' }}>
                                                    {{ admin_t('خاصة') }}
                                                </option>
                                                <option value="group" {{ old('tour_type') == 'group' ? 'selected' : '' }}>
                                                    {{ admin_t('مجموعة صغيرة') }}
                                                </option>
                                                <option value="shared" {{ old('tour_type') == 'shared' ? 'selected' : '' }}>
                                                    {{ admin_t('مشتركة') }}
                                                </option>
                                                <option value="custom" {{ old('tour_type') == 'custom' ? 'selected' : '' }}>
                                                    {{ admin_t('مخصصة') }}
                                                </option>
                                            </select>
                                            @error('tour_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="form-label" for="currency_id">{{ admin_t('العملة') }}</label>
                                            <select id="currency_id" name="currency_id"
                                                class="form-select @error('currency_id') is-invalid @enderror">
                                                <option value="">{{ admin_t('اختر العملة') }}</option>
                                                @foreach ($currencies ?? collect() as $currency)
                                                    <option value="{{ $currency->id }}"
                                                        {{ old('currency_id') == $currency->id ? 'selected' : '' }}>
                                                        {{ $currency->code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('currency_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="form-label" for="booking_mode">{{ admin_t('نظام الحجز') }}</label>
                                            <select id="booking_mode" name="booking_mode"
                                                class="form-select @error('booking_mode') is-invalid @enderror">
                                                <option value="">{{ admin_t('اختر نظام الحجز') }}</option>
                                                <option value="request" {{ old('booking_mode') == 'request' ? 'selected' : '' }}>
                                                    {{ admin_t('طلب') }}
                                                </option>
                                                <option value="instant" {{ old('booking_mode') == 'instant' ? 'selected' : '' }}>
                                                    {{ admin_t('فوري') }}
                                                </option>
                                            </select>
                                            @error('booking_mode')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="form-label" for="difficulty_level">{{ admin_t('مستوى الصعوبة') }}</label>
                                            <select id="difficulty_level" name="difficulty_level"
                                                class="form-select @error('difficulty_level') is-invalid @enderror">
                                                <option value="">{{ admin_t('اختر المستوى') }}</option>
                                                <option value="easy" {{ old('difficulty_level') == 'easy' ? 'selected' : '' }}>
                                                    {{ admin_t('سهل') }}
                                                </option>
                                                <option value="moderate" {{ old('difficulty_level') == 'moderate' ? 'selected' : '' }}>
                                                    {{ admin_t('متوسط') }}
                                                </option>
                                                <option value="hard" {{ old('difficulty_level') == 'hard' ? 'selected' : '' }}>
                                                    {{ admin_t('صعب') }}
                                                </option>
                                            </select>
                                            @error('difficulty_level')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wizard-panel" data-step-panel="2">
                        <div class="wizard-panel-header">
                            <div>
                                <h2 class="wizard-panel-title">{{ admin_t('الوصف والصور') }}</h2>
                                <p class="wizard-panel-copy">{{ admin_t('أضف وصف الرحلة والصور التي ستظهر للعملاء.') }}</p>
                            </div>
                            <div class="wizard-panel-pill">{{ admin_t('الخطوة :current من :total', ['current' => 2, 'total' => count($steps)]) }}</div>
                        </div>

                        <div class="wizard-grid">
                            <div class="form-section-card">
                                <div class="section-header">
                                    <div class="section-icon"><i class="ti ti-writing"></i></div>
                                    <div>
                                        <h3>{{ admin_t('الوصف والنصوص') }}</h3>
                                        <p>{{ admin_t('اكتب المحتوى الذي سيظهر للعميل في صفحة الرحلة.') }}</p>
                                    </div>
                                </div>

                                <div class="section-body">
                                    <div class="fields-grid two-up">
                                        <div class="field-span-2">
                                            <label class="form-label" for="short_description">{{ admin_t('وصف مختصر') }}</label>
                                            <textarea id="short_description" name="short_description" rows="4"
                                                class="form-control @error('short_description') is-invalid @enderror"
                                                placeholder="{{ admin_t('الوصف المختصر يظهر في القوائم ونتائج البحث.') }}"
                                                data-counter-max="150">{{ old('short_description') }}</textarea>
                                            <div class="counter-line"><span data-counter-for="short_description">0 / 150</span></div>
                                            @error('short_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="field-span-2">
                                            <label class="form-label" for="description">{{ admin_t('الوصف الكامل') }}</label>
                                            <textarea id="description" name="description" rows="8"
                                                class="form-control @error('description') is-invalid @enderror"
                                                placeholder="{{ admin_t('أضف وصفًا تفصيليًا غنيًا يساعد العميل على اتخاذ القرار.') }}">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section-card">
                                <div class="section-header">
                                    <div class="section-icon"><i class="ti ti-photo"></i></div>
                                    <div>
                                        <h3>{{ admin_t('الصور والمعرض') }}</h3>
                                        <p>{{ admin_t('ارفع الصورة الرئيسية وصور المعرض مع معاينة مباشرة قبل الحفظ.') }}</p>
                                    </div>
                                </div>

                                <div class="section-body">
                                    <div class="split-card">
                                        <div>
                                            <label class="form-label">{{ admin_t('الصورة الرئيسية') }}</label>
                                            <label class="upload-zone" for="featured_image">
                                                <input id="featured_image" type="file" name="featured_image" accept="image/*">
                                                <div>
                                                    <i class="ti ti-cloud-upload" style="font-size: 42px;"></i>
                                                    <h4>{{ admin_t('اسحب الصور هنا أو اضغط للاختيار') }}</h4>
                                                    <p>{{ admin_t('الامتدادات المسموحة: JPG, PNG, WEBP - الحد الأقصى 5MB لكل صورة.') }}</p>
                                                </div>
                                            </label>
                                            @error('featured_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="preview-grid" id="featuredPreview"></div>
                                        </div>

                                        <div>
                                            <label class="form-label">{{ admin_t('صور المعرض') }}</label>
                                            <label class="upload-zone" for="gallery_images">
                                                <input id="gallery_images" type="file" name="gallery_images[]" accept="image/*" multiple>
                                                <div>
                                                    <i class="ti ti-photos" style="font-size: 42px;"></i>
                                                    <h4>{{ admin_t('اسحب الصور هنا أو اضغط للاختيار') }}</h4>
                                                    <p>{{ admin_t('الامتدادات المسموحة: JPG, PNG, WEBP - الحد الأقصى 5MB لكل صورة.') }}</p>
                                                </div>
                                            </label>
                                            @error('gallery_images')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @error('gallery_images.*')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="preview-grid" id="galleryPreview">
                                                <div class="empty-state" id="galleryEmptyState">{{ admin_t('لا توجد صور في المعرض حتى الآن.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wizard-panel" data-step-panel="3">
                        <div class="wizard-panel-header">
                            <div>
                                <h2 class="wizard-panel-title">{{ admin_t('المسار والمدة') }}</h2>
                                <p class="wizard-panel-copy">{{ admin_t('حدد مدة الرحلة والبرنامج اليومي ومسار الرحلة.') }}</p>
                            </div>
                            <div class="wizard-panel-pill">{{ admin_t('الخطوة :current من :total', ['current' => 3, 'total' => count($steps)]) }}</div>
                        </div>

                        <div class="wizard-grid">
                            <div class="form-section-card">
                                <div class="section-header">
                                    <div class="section-icon"><i class="ti ti-clock-hour-4"></i></div>
                                    <div>
                                        <h3>{{ admin_t('المدة والمعلومات الزمنية') }}</h3>
                                        <p>{{ admin_t('حدد شكل المدة وطريقة عرضها داخل الموقع.') }}</p>
                                    </div>
                                </div>

                                <div class="section-body">
                                    <div class="mb-4">
                                        <label class="form-label">{{ admin_t('نوع المدة') }}</label>
                                        <div class="choice-row">
                                            <label class="choice-pill">
                                                <input type="radio" name="duration_type" value="days"
                                                    {{ old('duration_type', 'days') === 'days' ? 'checked' : '' }}>
                                                <span>{{ admin_t('أيام / ليالي') }}</span>
                                            </label>
                                            <label class="choice-pill">
                                                <input type="radio" name="duration_type" value="hours"
                                                    {{ old('duration_type') === 'hours' ? 'checked' : '' }}>
                                                <span>{{ admin_t('ساعات') }}</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="fields-grid">
                                        <div id="daysFieldWrapper">
                                            <label class="form-label" for="duration_days">{{ admin_t('عدد الأيام') }}</label>
                                            <input id="duration_days" type="number" name="duration_days" class="form-control"
                                                value="{{ old('duration_days') }}">
                                        </div>

                                        <div id="nightsFieldWrapper">
                                            <label class="form-label" for="duration_nights">{{ admin_t('عدد الليالي') }}</label>
                                            <input id="duration_nights" type="number" name="duration_nights" class="form-control"
                                                value="{{ old('duration_nights') }}">
                                        </div>

                                        <div id="hoursFieldWrapper">
                                            <label class="form-label" for="duration_hours">{{ admin_t('عدد الساعات') }}</label>
                                            <input id="duration_hours" type="number" name="duration_hours" class="form-control"
                                                value="{{ old('duration_hours') }}">
                                        </div>

                                        <div>
                                            <label class="form-label" for="duration_text">{{ admin_t('نص المدة المعروض') }}</label>
                                            <input id="duration_text" type="text" name="duration_text" class="form-control"
                                                value="{{ old('duration_text') }}"
                                                placeholder="{{ admin_t('مثال: 5 أيام / 4 ليالٍ') }}">
                                        </div>

                                        <div>
                                            <label class="form-label" for="schedule_text">{{ admin_t('الجدول') }}</label>
                                            <input id="schedule_text" type="text" name="schedule_text" class="form-control"
                                                value="{{ old('schedule_text') }}"
                                                placeholder="{{ admin_t('مثال: يوميًا / كل سبت / حسب الطلب') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section-card">
                                <div class="section-header">
                                    <div class="section-icon"><i class="ti ti-map-route"></i></div>
                                    <div>
                                        <h3>{{ admin_t('المسار والتنقل') }}</h3>
                                        <p>{{ admin_t('أضف نقاط البداية والوصول ومسار الرحلة بشكل واضح.') }}</p>
                                    </div>
                                </div>

                                <div class="section-body">
                                    <div class="fields-grid">
                                        <div>
                                            <label class="form-label" for="route_text">{{ admin_t('المسار') }}</label>
                                            <input id="route_text" type="text" name="route_text" class="form-control"
                                                value="{{ old('route_text') }}">
                                        </div>

                                        <div>
                                            <label class="form-label" for="pickup_location">{{ admin_t('مكان الاستلام') }}</label>
                                            <input id="pickup_location" type="text" name="pickup_location" class="form-control"
                                                value="{{ old('pickup_location') }}">
                                        </div>

                                        <div>
                                            <label class="form-label" for="dropoff_location">{{ admin_t('مكان الانتهاء') }}</label>
                                            <input id="dropoff_location" type="text" name="dropoff_location" class="form-control"
                                                value="{{ old('dropoff_location') }}">
                                        </div>

                                        <div class="field-span-2">
                                            <label class="form-label" for="destinations_text">{{ admin_t('الوجهات') }}</label>
                                            <input id="destinations_text" type="text" name="destinations_text" class="form-control"
                                                value="{{ old('destinations_text') }}"
                                                placeholder="{{ admin_t('افصل بين الوجهات بفاصلة') }}">
                                        </div>

                                        <div>
                                            <label class="form-label" for="location_summary">{{ admin_t('ملخص الموقع') }}</label>
                                            <input id="location_summary" type="text" name="location_summary" class="form-control"
                                                value="{{ old('location_summary') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section-card">
                                <div class="section-header">
                                    <div class="section-icon"><i class="ti ti-calendar-event"></i></div>
                                    <div>
                                        <h3>{{ admin_t('البرنامج اليومي') }}</h3>
                                        <p>{{ admin_t('قسّم الرحلة إلى أيام أو محطات مع تفاصيل الوجبات والنشاطات.') }}</p>
                                    </div>
                                </div>

                                <div class="section-body">
                                    <div id="itinerary-wrapper">
                                        @forelse ($itinerary as $i => $day)
                                            <div class="repeat-box itinerary-item">
                                                <div class="repeat-box-title">
                                                    <strong>{{ admin_t('يوم رقم :number', ['number' => ($day['day_number'] ?? ($i + 1))]) }}</strong>
                                                    <button type="button" class="btn btn-sm btn-outline-danger js-remove">
                                                        {{ admin_t('حذف') }}
                                                    </button>
                                                </div>
                                                <div class="fields-grid">
                                                    <div>
                                                        <label class="form-label">{{ admin_t('نوع البرنامج') }}</label>
                                                        <input type="text" name="itinerary[{{ $i }}][duration]" class="form-control"
                                                            value="{{ $day['duration'] ?? '' }}">
                                                    </div>

                                                    <div>
                                                        <label class="form-label">{{ admin_t('رقم اليوم') }}</label>
                                                        <input type="number" name="itinerary[{{ $i }}][day_number]" class="form-control"
                                                            value="{{ $day['day_number'] ?? '' }}">
                                                    </div>

                                                    <div>
                                                        <label class="form-label">{{ admin_t('عنوان اليوم') }}</label>
                                                        <input type="text" name="itinerary[{{ $i }}][title]" class="form-control"
                                                            value="{{ $day['title'] ?? '' }}">
                                                    </div>

                                                    <div class="field-span-2">
                                                        <label class="form-label">{{ admin_t('تفاصيل اليوم') }}</label>
                                                        <textarea name="itinerary[{{ $i }}][description]" rows="4" class="form-control">{{ $day['description'] ?? '' }}</textarea>
                                                    </div>

                                                    <div>
                                                        <label class="form-label">{{ admin_t('الوجبات') }}</label>
                                                        <div class="choice-row">
                                                            <label class="choice-pill">
                                                                <input type="checkbox" name="itinerary[{{ $i }}][meals_breakfast]" value="1"
                                                                    {{ !empty($day['meals_breakfast']) ? 'checked' : '' }}>
                                                                <span>{{ admin_t('فطار') }}</span>
                                                            </label>
                                                            <label class="choice-pill">
                                                                <input type="checkbox" name="itinerary[{{ $i }}][meals_lunch]" value="1"
                                                                    {{ !empty($day['meals_lunch']) ? 'checked' : '' }}>
                                                                <span>{{ admin_t('غداء') }}</span>
                                                            </label>
                                                            <label class="choice-pill">
                                                                <input type="checkbox" name="itinerary[{{ $i }}][meals_dinner]" value="1"
                                                                    {{ !empty($day['meals_dinner']) ? 'checked' : '' }}>
                                                                <span>{{ admin_t('عشاء') }}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="empty-state" id="itineraryEmptyState">{{ admin_t('لا يوجد برنامج يومي حتى الآن.') }}</div>
                                        @endforelse
                                    </div>

                                    <button type="button" class="btn btn-wizard-outline mt-2" id="addItineraryBtn">
                                        <span class="btn-icon-text">
                                            <i class="ti ti-plus"></i>
                                            {{ admin_t('إضافة يوم جديد') }}
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wizard-panel" data-step-panel="4">
                        <div class="wizard-panel-header">
                            <div>
                                <h2 class="wizard-panel-title">{{ admin_t('الأسعار والسياسات') }}</h2>
                                <p class="wizard-panel-copy">{{ admin_t('حدد أسعار الرحلة وما هو مشمول وسياسات الحجز.') }}</p>
                            </div>
                            <div class="wizard-panel-pill">{{ admin_t('الخطوة :current من :total', ['current' => 4, 'total' => count($steps)]) }}</div>
                        </div>

                        <div class="wizard-grid">
                            <div class="form-section-card">
                                <div class="section-header">
                                    <div class="section-icon"><i class="ti ti-cash"></i></div>
                                    <div>
                                        <h3>{{ admin_t('الأسعار والباقات') }}</h3>
                                        <p>{{ admin_t('أضف أسعارًا مرنة حسب الموسم أو نوع الغرفة أو الفترة الزمنية.') }}</p>
                                    </div>
                                </div>

                                <div class="section-body">
                                    <div class="fields-grid">
                                        <div>
                                            <label class="form-label" for="start_from_price">{{ admin_t('السعر يبدأ من') }}</label>
                                            <input id="start_from_price" type="number" step="0.01" name="start_from_price" class="form-control"
                                                value="{{ old('start_from_price') }}">
                                        </div>

                                        <div>
                                            <label class="form-label" for="compare_price">{{ admin_t('سعر المقارنة') }}</label>
                                            <input id="compare_price" type="number" step="0.01" name="compare_price" class="form-control"
                                                value="{{ old('compare_price') }}">
                                        </div>
                                    </div>

                                    <div id="prices-wrapper" class="mt-3">
                                        @forelse ($prices as $i => $price)
                                            <div class="repeat-box price-item">
                                                <div class="repeat-box-title">
                                                    <strong>{{ admin_t('سعر رقم :number', ['number' => $i + 1]) }}</strong>
                                                    <button type="button" class="btn btn-sm btn-outline-danger js-remove">
                                                        {{ admin_t('حذف') }}
                                                    </button>
                                                </div>
                                                <div class="fields-grid">
                                                    <div>
                                                        <label class="form-label">{{ admin_t('العنوان') }}</label>
                                                        <input type="text" name="prices[{{ $i }}][label]" class="form-control"
                                                            value="{{ $price['label'] ?? '' }}">
                                                    </div>

                                                    <div>
                                                        <label class="form-label">{{ admin_t('الموسم') }}</label>
                                                        <input type="text" name="prices[{{ $i }}][season_name]" class="form-control"
                                                            value="{{ $price['season_name'] ?? '' }}">
                                                    </div>

                                                    <div>
                                                        <label class="form-label">{{ admin_t('نوع السعر') }}</label>
                                                        <select name="prices[{{ $i }}][price_type]" class="form-select">
                                                            <option value="from" {{ ($price['price_type'] ?? '') === 'from' ? 'selected' : '' }}>{{ admin_t('يبدأ من') }}</option>
                                                            <option value="fixed" {{ ($price['price_type'] ?? '') === 'fixed' ? 'selected' : '' }}>{{ admin_t('ثابت') }}</option>
                                                            <option value="seasonal" {{ ($price['price_type'] ?? '') === 'seasonal' ? 'selected' : '' }}>{{ admin_t('موسمي') }}</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="form-label">{{ admin_t('نوع الغرفة') }}</label>
                                                        <input type="text" name="prices[{{ $i }}][room_type]" class="form-control"
                                                            value="{{ $price['room_type'] ?? '' }}">
                                                    </div>

                                                    <div>
                                                        <label class="form-label">{{ admin_t('المبلغ') }}</label>
                                                        <input type="number" step="0.01" name="prices[{{ $i }}][amount]" class="form-control"
                                                            value="{{ $price['amount'] ?? '' }}">
                                                    </div>

                                                    <div>
                                                        <label class="form-label">{{ admin_t('العملة') }}</label>
                                                        <select name="prices[{{ $i }}][currency_id]" class="form-select">
                                                            <option value="">{{ admin_t('اختر العملة') }}</option>
                                                            @foreach ($currencies ?? collect() as $currency)
                                                                <option value="{{ $currency->id }}"
                                                                    {{ ($price['currency_id'] ?? old('currency_id')) == $currency->id ? 'selected' : '' }}>
                                                                    {{ $currency->code }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="form-label">{{ admin_t('من تاريخ') }}</label>
                                                        <input type="date" name="prices[{{ $i }}][valid_from]" class="form-control"
                                                            value="{{ $price['valid_from'] ?? '' }}">
                                                    </div>

                                                    <div>
                                                        <label class="form-label">{{ admin_t('إلى تاريخ') }}</label>
                                                        <input type="date" name="prices[{{ $i }}][valid_to]" class="form-control"
                                                            value="{{ $price['valid_to'] ?? '' }}">
                                                    </div>

                                                    <div class="field-span-3">
                                                        <label class="form-label">{{ admin_t('ملاحظات') }}</label>
                                                        <textarea name="prices[{{ $i }}][notes]" rows="3" class="form-control">{{ $price['notes'] ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="empty-state" id="pricesEmptyState">{{ admin_t('لا توجد أسعار مضافة حتى الآن.') }}</div>
                                        @endforelse
                                    </div>

                                    <button type="button" class="btn btn-wizard-outline mt-2" id="addPriceBtn">
                                        <span class="btn-icon-text">
                                            <i class="ti ti-plus"></i>
                                            {{ admin_t('+ إضافة سعر') }}
                                        </span>
                                    </button>

                                    <div class="mt-4">
                                        <label class="form-label" for="pricing_information">{{ admin_t('ملاحظات الأسعار') }}</label>
                                        <textarea id="pricing_information" name="pricing_information" rows="4" class="form-control">{{ old('pricing_information') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section-card">
                                <div class="section-header">
                                    <div class="section-icon"><i class="ti ti-stars"></i></div>
                                    <div>
                                        <h3>{{ admin_t('مرافق الرحلة') }}</h3>
                                        <p>{{ admin_t('أضف أبرز المرافق أو استخدم الاقتراحات السريعة.') }}</p>
                                    </div>
                                </div>

                                <div class="section-body">
                                    <strong class="d-block mb-3">{{ admin_t('اقتراحات سريعة') }}</strong>
                                    <div class="facility-suggestions" id="facilitySuggestions">
                                        @foreach ([admin_t('فندق'), admin_t('وجبات'), admin_t('مواصلات'), admin_t('مرشد سياحي'), admin_t('تذاكر دخول'), admin_t('استقبال وتوديع'), admin_t('تأمين سفر')] as $suggestion)
                                            <button type="button" class="facility-chip" data-facility-suggestion="{{ $suggestion }}">{{ $suggestion }}</button>
                                        @endforeach
                                    </div>

                                    <div id="facilities-wrapper">
                                        @forelse ($facilities as $i => $facility)
                                            <div class="repeat-box facility-item">
                                                <div class="fields-grid">
                                                    <div class="field-span-2">
                                                        <label class="form-label">{{ admin_t('المرفق') }}</label>
                                                        <input type="text" name="facilities[{{ $i }}][title]" class="form-control"
                                                            value="{{ $facility['title'] ?? '' }}"
                                                            placeholder="{{ admin_t('مثال: مرشد سياحي خاص') }}">
                                                    </div>
                                                    <div>
                                                        <label class="form-label">{{ admin_t('الترتيب') }}</label>
                                                        <div class="d-flex gap-2">
                                                            <input type="number" name="facilities[{{ $i }}][sort_order]" class="form-control"
                                                                value="{{ $facility['sort_order'] ?? $i }}">
                                                            <button type="button" class="btn btn-outline-danger js-remove">{{ admin_t('حذف') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="empty-state" id="facilitiesEmptyState">{{ admin_t('لا توجد مرافق مضافة حتى الآن.') }}</div>
                                        @endforelse
                                    </div>

                                    <button type="button" class="btn btn-wizard-outline mt-2" id="addFacilityBtn">
                                        <span class="btn-icon-text">
                                            <i class="ti ti-plus"></i>
                                            {{ admin_t('+ إضافة مرفق') }}
                                        </span>
                                    </button>
                                </div>
                            </div>

                            <div class="form-section-card">
                                <div class="section-header">
                                    <div class="section-icon"><i class="ti ti-list-check"></i></div>
                                    <div>
                                        <h3>{{ admin_t('المشمول وغير المشمول') }}</h3>
                                        <p>{{ admin_t('قسّم ما يحصل عليه العميل وما لا يشمله السعر.') }}</p>
                                    </div>
                                </div>

                                <div class="section-body">
                                    <div class="split-card">
                                        <div>
                                            <h5 class="mb-3">{{ admin_t('المشمول في الرحلة') }}</h5>
                                            <div id="included-wrapper">
                                                @forelse ($included as $i => $item)
                                                    <div class="repeat-box included-item">
                                                        <div class="d-flex gap-2">
                                                            <input type="text" name="included[{{ $i }}][title]" class="form-control"
                                                                value="{{ $item['title'] ?? '' }}">
                                                            <button type="button" class="btn btn-outline-danger js-remove">{{ admin_t('حذف') }}</button>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="empty-state" id="includedEmptyState">{{ admin_t('لا يوجد عناصر مشمولة حتى الآن.') }}</div>
                                                @endforelse
                                            </div>
                                            <button type="button" class="btn btn-wizard-outline" id="addIncludedBtn">
                                                <span class="btn-icon-text">
                                                    <i class="ti ti-plus"></i>
                                                    {{ admin_t('+ إضافة بند') }}
                                                </span>
                                            </button>
                                        </div>

                                        <div>
                                            <h5 class="mb-3">{{ admin_t('غير المشمول') }}</h5>
                                            <div id="excluded-wrapper">
                                                @forelse ($excluded as $i => $item)
                                                    <div class="repeat-box excluded-item">
                                                        <div class="d-flex gap-2">
                                                            <input type="text" name="excluded[{{ $i }}][title]" class="form-control"
                                                                value="{{ $item['title'] ?? '' }}">
                                                            <button type="button" class="btn btn-outline-danger js-remove">{{ admin_t('حذف') }}</button>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="empty-state" id="excludedEmptyState">{{ admin_t('لا يوجد عناصر غير مشمولة حتى الآن.') }}</div>
                                                @endforelse
                                            </div>
                                            <button type="button" class="btn btn-wizard-outline" id="addExcludedBtn">
                                                <span class="btn-icon-text">
                                                    <i class="ti ti-plus"></i>
                                                    {{ admin_t('+ إضافة بند') }}
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section-card">
                                <div class="section-header">
                                    <div class="section-icon"><i class="ti ti-shield-check"></i></div>
                                    <div>
                                        <h3>{{ admin_t('الشروط والسياسات') }}</h3>
                                        <p>{{ admin_t('وضح السياسات المهمة قبل الحجز لتقليل الاستفسارات.') }}</p>
                                    </div>
                                </div>

                                <div class="section-body">
                                    <div class="fields-grid two-up">
                                        <div>
                                            <label class="form-label" for="children_policy">{{ admin_t('سياسة الأطفال') }}</label>
                                            <textarea id="children_policy" name="children_policy" rows="5" class="form-control">{{ old('children_policy') }}</textarea>
                                        </div>

                                        <div>
                                            <label class="form-label" for="pickup_policy">{{ admin_t('سياسة الاستلام والتوصيل') }}</label>
                                            <textarea id="pickup_policy" name="pickup_policy" rows="5" class="form-control">{{ old('pickup_policy') }}</textarea>
                                        </div>

                                        <div>
                                            <label class="form-label" for="cancellation_policy">{{ admin_t('سياسة الإلغاء') }}</label>
                                            <textarea id="cancellation_policy" name="cancellation_policy" rows="5" class="form-control">{{ old('cancellation_policy') }}</textarea>
                                        </div>

                                        <div>
                                            <label class="form-label" for="terms_conditions">{{ admin_t('الشروط والأحكام') }}</label>
                                            <textarea id="terms_conditions" name="terms_conditions" rows="5" class="form-control">{{ old('terms_conditions') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wizard-panel" data-step-panel="5">
                        <div class="wizard-panel-header">
                            <div>
                                <h2 class="wizard-panel-title">{{ admin_t('النشر وSEO') }}</h2>
                                <p class="wizard-panel-copy">{{ admin_t('راجع بيانات الرحلة وحدد إعدادات النشر ومحركات البحث.') }}</p>
                            </div>
                            <div class="wizard-panel-pill">{{ admin_t('الخطوة :current من :total', ['current' => 5, 'total' => count($steps)]) }}</div>
                        </div>

                        <div class="wizard-grid">
                            <div class="wizard-grid two-columns">
                                <div class="form-section-card">
                                    <div class="section-header">
                                        <div class="section-icon"><i class="ti ti-users"></i></div>
                                        <div>
                                            <h3>{{ admin_t('المشاركون والتقييم') }}</h3>
                                            <p>{{ admin_t('راجع أرقام السعة والتقييم قبل النشر.') }}</p>
                                        </div>
                                    </div>

                                    <div class="section-body">
                                        <div class="fields-grid">
                                            <div>
                                                <label class="form-label" for="min_participants">{{ admin_t('الحد الأدنى للمشاركين') }}</label>
                                                <input id="min_participants" type="number" name="min_participants" class="form-control"
                                                    value="{{ old('min_participants') }}">
                                            </div>

                                            <div>
                                                <label class="form-label" for="max_participants">{{ admin_t('الحد الأقصى للمشاركين') }}</label>
                                                <input id="max_participants" type="number" name="max_participants" class="form-control"
                                                    value="{{ old('max_participants') }}">
                                            </div>

                                            <div>
                                                <label class="form-label" for="booking_lead_days">{{ admin_t('أيام الحجز المسبق') }}</label>
                                                <input id="booking_lead_days" type="number" name="booking_lead_days" class="form-control"
                                                    value="{{ old('booking_lead_days') }}">
                                            </div>

                                            <div>
                                                <label class="form-label" for="rating_avg">{{ admin_t('التقييم') }}</label>
                                                <input id="rating_avg" type="number" step="0.01" name="rating_avg" class="form-control"
                                                    value="{{ old('rating_avg') }}">
                                            </div>

                                            <div>
                                                <label class="form-label" for="reviews_count">{{ admin_t('عدد المراجعات') }}</label>
                                                <input id="reviews_count" type="number" name="reviews_count" class="form-control"
                                                    value="{{ old('reviews_count') }}">
                                            </div>

                                            <div class="field-span-2">
                                                <label class="form-label" for="video_url">{{ admin_t('رابط الفيديو') }}</label>
                                                <input id="video_url" type="text" name="video_url" class="form-control"
                                                    value="{{ old('video_url') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section-card">
                                    <div class="section-header">
                                        <div class="section-icon"><i class="ti ti-settings"></i></div>
                                        <div>
                                            <h3>{{ admin_t('إعدادات النشر') }}</h3>
                                            <p>{{ admin_t('تحكم في حالة الظهور والتمييز وتاريخ النشر.') }}</p>
                                        </div>
                                    </div>

                                    <div class="section-body">
                                        <div class="fields-grid two-up">
                                            <div>
                                                <label class="form-label" for="published_at">{{ admin_t('تاريخ النشر') }}</label>
                                                <input id="published_at" type="date" name="published_at" class="form-control"
                                                    value="{{ old('published_at') }}">
                                            </div>

                                            <div>
                                                <label class="form-label" for="sort_order">{{ admin_t('الترتيب') }}</label>
                                                <input id="sort_order" type="number" name="sort_order" class="form-control"
                                                    value="{{ old('sort_order') }}">
                                            </div>
                                        </div>

                                        <div class="choice-row mt-3">
                                            <label class="choice-pill">
                                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                                <span>{{ admin_t('مفعلة') }}</span>
                                            </label>
                                            <label class="choice-pill">
                                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                                <span>{{ admin_t('مميزة') }}</span>
                                            </label>
                                            <label class="choice-pill">
                                                <input type="checkbox" name="is_best_seller" value="1" {{ old('is_best_seller') ? 'checked' : '' }}>
                                                <span>{{ admin_t('الأكثر مبيعًا') }}</span>
                                            </label>
                                            <label class="choice-pill">
                                                <input type="checkbox" name="is_ultra_luxury" value="1" {{ old('is_ultra_luxury') ? 'checked' : '' }}>
                                                <span>{{ admin_t('فاخرة جدًا') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section-card">
                                <div class="section-header">
                                    <div class="section-icon"><i class="ti ti-world-search"></i></div>
                                    <div>
                                        <h3>{{ admin_t('إعدادات SEO') }}</h3>
                                        <p>{{ admin_t('حسّن ظهور الرحلة في محركات البحث ومنصات المشاركة.') }}</p>
                                    </div>
                                </div>

                                <div class="section-body">
                                    <div class="fields-grid">
                                        <div class="field-span-2">
                                            <label class="form-label" for="seo_title">{{ admin_t('عنوان SEO') }}</label>
                                            <input id="seo_title" type="text" name="seo_title" class="form-control"
                                                value="{{ old('seo_title') }}" data-counter-max="60">
                                            <div class="counter-line"><span data-counter-for="seo_title">0 / 60</span></div>
                                        </div>

                                        <div>
                                            <label class="form-label" for="breadcrumb_title">{{ admin_t('عنوان مسار التنقل') }}</label>
                                            <input id="breadcrumb_title" type="text" name="breadcrumb_title" class="form-control"
                                                value="{{ old('breadcrumb_title') }}">
                                        </div>

                                        <div class="field-span-2">
                                            <label class="form-label" for="seo_description">{{ admin_t('وصف SEO') }}</label>
                                            <textarea id="seo_description" name="seo_description" rows="4" class="form-control" data-counter-max="160">{{ old('seo_description') }}</textarea>
                                            <div class="counter-line"><span data-counter-for="seo_description">0 / 160</span></div>
                                        </div>

                                        <div>
                                            <label class="form-label" for="canonical_url">{{ admin_t('Canonical URL') }}</label>
                                            <input id="canonical_url" type="text" name="canonical_url" class="form-control"
                                                value="{{ old('canonical_url') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section-card">
                                <div class="section-header">
                                    <div class="section-icon"><i class="ti ti-checklist"></i></div>
                                    <div>
                                        <h3>{{ admin_t('مراجعة سريعة') }}</h3>
                                        <p>{{ admin_t('ملخص نهائي قبل حفظ الرحلة ونشرها.') }}</p>
                                    </div>
                                </div>

                                <div class="section-body">
                                    <div class="summary-grid mb-4">
                                        <div class="summary-item">
                                            <span class="summary-label">{{ admin_t('العنوان') }}</span>
                                            <span class="summary-value" data-summary="title">-</span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="summary-label">{{ admin_t('الوجهة') }}</span>
                                            <span class="summary-value" data-summary="destination">-</span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="summary-label">{{ admin_t('المدة') }}</span>
                                            <span class="summary-value" data-summary="duration">-</span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="summary-label">{{ admin_t('السعر') }}</span>
                                            <span class="summary-value" data-summary="price">-</span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="summary-label">{{ admin_t('التصنيف') }}</span>
                                            <span class="summary-value" data-summary="category">-</span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="summary-label">{{ admin_t('الحالة') }}</span>
                                            <span class="summary-value" data-summary="status">-</span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="summary-label">{{ admin_t('عدد الصور') }}</span>
                                            <span class="summary-value" data-summary="images">0</span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="summary-label">{{ admin_t('عدد الأيام') }}</span>
                                            <span class="summary-value" data-summary="daysCount">0</span>
                                        </div>
                                    </div>

                                    <div class="review-list">
                                        <div class="review-row">
                                            <div class="review-meta">
                                                <strong>{{ admin_t('البيانات الأساسية') }}</strong>
                                                <small>{{ admin_t('تأكد من العنوان والوجهة والنوع قبل النشر.') }}</small>
                                            </div>
                                            <button type="button" class="btn btn-wizard-outline" data-jump-step="1">{{ admin_t('تعديل') }}</button>
                                        </div>
                                        <div class="review-row">
                                            <div class="review-meta">
                                                <strong>{{ admin_t('الوصف والصور') }}</strong>
                                                <small>{{ admin_t('تحقق من الوصف المختصر والصورة الرئيسية.') }}</small>
                                            </div>
                                            <button type="button" class="btn btn-wizard-outline" data-jump-step="2">{{ admin_t('تعديل') }}</button>
                                        </div>
                                        <div class="review-row">
                                            <div class="review-meta">
                                                <strong>{{ admin_t('المسار والمدة') }}</strong>
                                                <small>{{ admin_t('راجع مدة الرحلة وبرنامجها اليومي.') }}</small>
                                            </div>
                                            <button type="button" class="btn btn-wizard-outline" data-jump-step="3">{{ admin_t('تعديل') }}</button>
                                        </div>
                                        <div class="review-row">
                                            <div class="review-meta">
                                                <strong>{{ admin_t('الأسعار والسياسات') }}</strong>
                                                <small>{{ admin_t('تأكد من الأسعار والعناصر المشمولة والسياسات.') }}</small>
                                            </div>
                                            <button type="button" class="btn btn-wizard-outline" data-jump-step="4">{{ admin_t('تعديل') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wizard-actions">
                        <div>
                            <div class="wizard-actions-meta" id="wizardStepLabel"></div>
                            <div class="wizard-actions-meta mt-1">{{ admin_t('استخدم هذا الزر لحفظ نسخة محلية مؤقتة داخل المتصفح.') }}</div>
                        </div>

                        <div class="wizard-actions-group">
                            <a href="{{ route('admin.packages.index') }}" class="btn btn-wizard-outline" id="cancelActionBtn">{{ admin_t('إلغاء') }}</a>
                            <button type="button" class="btn btn-wizard-outline" id="saveDraftBtn">{{ admin_t('حفظ كمسودة') }}</button>
                            <button type="button" class="btn btn-wizard-outline" id="prevStepBtn">{{ admin_t('السابق') }}</button>
                            <button type="button" class="btn btn-wizard-primary" id="nextStepBtn">
                                <span class="btn-icon-text">
                                    <span>{{ admin_t('التالي') }}</span>
                                    <i class="ti ti-arrow-{{ $isRtl ? 'left' : 'right' }}"></i>
                                </span>
                            </button>
                            <button type="submit" class="btn btn-wizard-primary d-none-force" id="submitWizardBtn">
                                <span class="btn-icon-text">
                                    <i class="ti ti-device-floppy"></i>
                                    <span>{{ admin_t('حفظ ونشر الرحلة') }}</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('packageWizardForm');
            const stepButtons = Array.from(document.querySelectorAll('[data-step-trigger]'));
            const stepPanels = Array.from(document.querySelectorAll('[data-step-panel]'));
            const prevBtn = document.getElementById('prevStepBtn');
            const nextBtn = document.getElementById('nextStepBtn');
            const submitBtn = document.getElementById('submitWizardBtn');
            const saveDraftBtn = document.getElementById('saveDraftBtn');
            const stepLabel = document.getElementById('wizardStepLabel');
            const mobileStepTitle = document.getElementById('mobileStepTitle');
            const mobileStepCounter = document.getElementById('mobileStepCounter');
            const mobileStepBar = document.getElementById('mobileStepBar');
            const galleryInput = document.getElementById('gallery_images');
            const featuredInput = document.getElementById('featured_image');
            const galleryPreview = document.getElementById('galleryPreview');
            const featuredPreview = document.getElementById('featuredPreview');
            const destinationSelector = document.getElementById('destination_selector');
            const primaryCountryInput = document.getElementById('primary_country_id');
            const totalSteps = {{ count($steps) }};
            const initialStep = {{ $initialStep }};
            const draftKey = 'travelnest-package-create-draft';
            let currentStep = initialStep;
            let highestStep = initialStep;
            let isDirty = {{ $viewErrors->any() || old() ? 'true' : 'false' }};
            let isSubmitting = false;
            let featuredFile = null;
            let galleryFiles = [];

            const texts = {
                complete: @json(admin_t('مكتملة')),
                incomplete: @json(admin_t('غير مكتملة')),
                requiredMessage: @json(admin_t('يرجى استكمال الحقول المطلوبة.')),
                saveDraftSuccess: @json(admin_t('تم حفظ المسودة محليًا.')),
                saveDraftError: @json(admin_t('تعذر حفظ المسودة.')),
                restoreDraft: @json(admin_t('يوجد نموذج غير مكتمل محفوظ مسبقًا. هل تريد استكماله؟')),
                leavePage: @json(admin_t('لديك تغييرات غير محفوظة. هل تريد مغادرة الصفحة؟')),
                saving: @json(admin_t('جارٍ حفظ الرحلة...')),
                noData: '-',
                active: @json(admin_t('مفعلة')),
                inactive: @json(admin_t('غير مفعلة')),
                dayFormat: @json(admin_t('الخطوة :current من :total')),
                imagePreview: @json(admin_t('معاينة الصورة الرئيسية')),
                galleryPreview: @json(admin_t('معاينة المعرض')),
                noGallery: @json(admin_t('لا توجد صور في المعرض حتى الآن.')),
                noItinerary: @json(admin_t('لا يوجد برنامج يومي حتى الآن.')),
                noFacilities: @json(admin_t('لا توجد مرافق مضافة حتى الآن.')),
                noIncluded: @json(admin_t('لا يوجد عناصر مشمولة حتى الآن.')),
                noExcluded: @json(admin_t('لا يوجد عناصر غير مشمولة حتى الآن.')),
                noPrices: @json(admin_t('لا توجد أسعار مضافة حتى الآن.')),
                remove: @json(admin_t('إزالة')),
                dayTitle: @json(admin_t('يوم رقم :number')),
            };

            const stepTitles = @json(array_column($steps, 'title'));
            const requiredFieldsByStep = {
                1: ['title', 'category_id', 'destination_id', 'package_type'],
                2: [],
                3: [],
                4: [],
                5: []
            };

            const facilitySuggestions = document.getElementById('facilitySuggestions');

            function notify(message, type = 'success') {
                if (window.Swal) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2200,
                        timerProgressBar: true,
                        icon: type,
                        title: message
                    });
                    return;
                }

                window.alert(message);
            }

            function replacePlaceholders(text, values) {
                return Object.entries(values).reduce((result, [key, value]) => {
                    return result.replace(':' + key, value);
                }, text);
            }

            function scrollToTopOfWizard() {
                form.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            function markStepState() {
                stepButtons.forEach((button, index) => {
                    const step = index + 1;
                    button.classList.toggle('is-active', step === currentStep);
                    button.classList.toggle('is-complete', step < currentStep || step <= highestStep && step !== currentStep);
                    button.classList.toggle('is-disabled', step > highestStep + 1);
                    button.querySelector('.wizard-step-badge').innerHTML = step < currentStep || (step < highestStep && step !== currentStep) ? '<i class="ti ti-check"></i>' : step;
                });
            }

            function updateActionState() {
                stepLabel.textContent = replacePlaceholders(texts.dayFormat, {
                    current: currentStep,
                    total: totalSteps
                }) + ' - ' + stepTitles[currentStep - 1];

                mobileStepTitle.textContent = stepTitles[currentStep - 1];
                mobileStepCounter.textContent = replacePlaceholders(texts.dayFormat, {
                    current: currentStep,
                    total: totalSteps
                });
                mobileStepBar.style.width = `${(currentStep / totalSteps) * 100}%`;

                prevBtn.disabled = currentStep === 1;
                nextBtn.classList.toggle('d-none-force', currentStep === totalSteps);
                submitBtn.classList.toggle('d-none-force', currentStep !== totalSteps);
            }

            function showStep(step) {
                currentStep = step;
                if (currentStep > highestStep) {
                    highestStep = currentStep;
                }

                stepPanels.forEach(panel => {
                    panel.classList.toggle('is-active', Number(panel.dataset.stepPanel) === currentStep);
                });

                markStepState();
                updateActionState();
                updateSummary();
                scrollToTopOfWizard();
            }

            function focusFirstInvalid(step) {
                const panel = document.querySelector(`[data-step-panel="${step}"]`);
                const invalidField = panel.querySelector('.field-error, .is-invalid');
                if (invalidField) {
                    invalidField.focus({
                        preventScroll: true
                    });
                }
            }

            function validateStep(step) {
                const requiredFields = requiredFieldsByStep[step] || [];
                const panel = document.querySelector(`[data-step-panel="${step}"]`);
                let isValid = true;
                let firstInvalid = null;

                panel.querySelectorAll('.field-error').forEach(field => field.classList.remove('field-error'));

                requiredFields.forEach(name => {
                    const field = form.querySelector(`[name="${name}"]`);
                    if (!field) {
                        return;
                    }

                    const value = field.value ? field.value.trim() : '';
                    const filled = field.type === 'checkbox' ? field.checked : value !== '';

                    if (!filled) {
                        field.classList.add('field-error');
                        isValid = false;
                        if (!firstInvalid) {
                            firstInvalid = field;
                        }
                    }
                });

                if (!isValid) {
                    notify(texts.requiredMessage, 'error');
                    if (firstInvalid) {
                        firstInvalid.focus({
                            preventScroll: true
                        });
                    }
                }

                return isValid;
            }

            function serializeDraft() {
                const data = {};
                Array.from(form.elements).forEach(element => {
                    if (!element.name || element.type === 'file' || element.type === 'password' || element.disabled) {
                        return;
                    }

                    if (element.type === 'checkbox') {
                        data[element.name] = element.checked;
                        return;
                    }

                    if (element.type === 'radio') {
                        if (element.checked) {
                            data[element.name] = element.value;
                        }
                        return;
                    }

                    data[element.name] = element.value;
                });

                return data;
            }

            function restoreDraft(data) {
                Object.entries(data).forEach(([name, value]) => {
                    const field = form.querySelector(`[name="${CSS.escape(name)}"]`);
                    if (!field) {
                        return;
                    }

                    if (field.type === 'checkbox') {
                        field.checked = Boolean(value);
                        return;
                    }

                    if (field.type === 'radio') {
                        const selected = form.querySelector(`[name="${CSS.escape(name)}"][value="${value}"]`);
                        if (selected) {
                            selected.checked = true;
                        }
                        return;
                    }

                    field.value = value;
                });
            }

            function saveDraft() {
                try {
                    localStorage.setItem(draftKey, JSON.stringify(serializeDraft()));
                    notify(texts.saveDraftSuccess, 'success');
                } catch (error) {
                    notify(texts.saveDraftError, 'error');
                }
            }

            function maybeRestoreDraft() {
                if ({{ old() ? 'true' : 'false' }}) {
                    return;
                }

                const storedDraft = localStorage.getItem(draftKey);
                if (!storedDraft) {
                    return;
                }

                if (!window.confirm(texts.restoreDraft)) {
                    localStorage.removeItem(draftKey);
                    return;
                }

                try {
                    restoreDraft(JSON.parse(storedDraft));
                } catch (error) {
                    localStorage.removeItem(draftKey);
                }
            }

            function syncCountryFromDestination() {
                if (!destinationSelector || !primaryCountryInput) {
                    return;
                }

                const selected = destinationSelector.options[destinationSelector.selectedIndex];
                const countryId = selected ? selected.getAttribute('data-country-id') : '';
                primaryCountryInput.value = countryId || '';
            }

            function updateDurationFields() {
                const selected = form.querySelector('input[name="duration_type"]:checked');
                const type = selected ? selected.value : 'days';
                document.getElementById('daysFieldWrapper').classList.toggle('d-none-force', type !== 'days');
                document.getElementById('nightsFieldWrapper').classList.toggle('d-none-force', type !== 'days');
                document.getElementById('hoursFieldWrapper').classList.toggle('d-none-force', type !== 'hours');
            }

            function updateCounter(input) {
                const max = Number(input.dataset.counterMax || 0);
                if (!max) {
                    return;
                }

                const counter = document.querySelector(`[data-counter-for="${input.id}"]`);
                if (!counter) {
                    return;
                }

                counter.textContent = `${input.value.length} / ${max}`;
            }

            function renderFeaturedPreview() {
                featuredPreview.innerHTML = '';

                if (!featuredFile) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    featuredPreview.innerHTML = `
                        <div class="preview-card">
                            <img src="${event.target.result}" alt="">
                            <div class="preview-card-footer">
                                <span>${texts.imagePreview}</span>
                                <button type="button" class="preview-remove" data-remove-featured>
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        </div>
                    `;
                };
                reader.readAsDataURL(featuredFile);
            }

            function syncGalleryInput() {
                const dataTransfer = new DataTransfer();
                galleryFiles.forEach(file => dataTransfer.items.add(file));
                galleryInput.files = dataTransfer.files;
            }

            function renderGalleryPreview() {
                galleryPreview.innerHTML = '';

                if (!galleryFiles.length) {
                    galleryPreview.innerHTML = `<div class="empty-state" id="galleryEmptyState">${texts.noGallery}</div>`;
                    return;
                }

                galleryFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const card = document.createElement('div');
                        card.className = 'preview-card';
                        card.innerHTML = `
                            <img src="${event.target.result}" alt="">
                            <div class="preview-card-footer">
                                <span>${file.name}</span>
                                <button type="button" class="preview-remove" data-gallery-index="${index}">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        `;
                        galleryPreview.appendChild(card);
                    };
                    reader.readAsDataURL(file);
                });
            }

            function ensureEmptyState(wrapperSelector, itemSelector, emptyId, emptyText) {
                const wrapper = document.querySelector(wrapperSelector);
                if (!wrapper) {
                    return;
                }

                const hasItems = wrapper.querySelectorAll(itemSelector).length > 0;
                const empty = wrapper.querySelector(`#${emptyId}`);

                if (!hasItems && !empty) {
                    const div = document.createElement('div');
                    div.className = 'empty-state';
                    div.id = emptyId;
                    div.textContent = emptyText;
                    wrapper.appendChild(div);
                }

                if (hasItems && empty) {
                    empty.remove();
                }
            }

            function createRemoveButton() {
                return `<button type="button" class="btn btn-outline-danger js-remove">${@json(admin_t('حذف'))}</button>`;
            }

            let facilityIndex = {{ count($facilities) }};
            let itineraryIndex = {{ count($itinerary) }};
            let includedIndex = {{ count($included) }};
            let excludedIndex = {{ count($excluded) }};
            let priceIndex = {{ count($prices) }};

            function addFacility(title = '') {
                document.getElementById('facilities-wrapper').insertAdjacentHTML('beforeend', `
                    <div class="repeat-box facility-item">
                        <div class="fields-grid">
                            <div class="field-span-2">
                                <label class="form-label">${@json(admin_t('المرفق'))}</label>
                                <input type="text" name="facilities[${facilityIndex}][title]" class="form-control" value="${title}" placeholder="${@json(admin_t('مثال: مرشد سياحي خاص'))}">
                            </div>
                            <div>
                                <label class="form-label">${@json(admin_t('الترتيب'))}</label>
                                <div class="d-flex gap-2">
                                    <input type="number" name="facilities[${facilityIndex}][sort_order]" class="form-control" value="${facilityIndex}">
                                    ${createRemoveButton()}
                                </div>
                            </div>
                        </div>
                    </div>
                `);
                facilityIndex++;
                ensureEmptyState('#facilities-wrapper', '.facility-item', 'facilitiesEmptyState', texts.noFacilities);
            }

            function addItinerary() {
                document.getElementById('itinerary-wrapper').insertAdjacentHTML('beforeend', `
                    <div class="repeat-box itinerary-item">
                        <div class="repeat-box-title">
                            <strong>${replacePlaceholders(texts.dayTitle, { number: itineraryIndex + 1 })}</strong>
                            ${createRemoveButton()}
                        </div>
                        <div class="fields-grid">
                            <div>
                                <label class="form-label">${@json(admin_t('نوع البرنامج'))}</label>
                                <input type="text" name="itinerary[${itineraryIndex}][duration]" class="form-control">
                            </div>
                            <div>
                                <label class="form-label">${@json(admin_t('رقم اليوم'))}</label>
                                <input type="number" name="itinerary[${itineraryIndex}][day_number]" class="form-control" value="${itineraryIndex + 1}">
                            </div>
                            <div>
                                <label class="form-label">${@json(admin_t('عنوان اليوم'))}</label>
                                <input type="text" name="itinerary[${itineraryIndex}][title]" class="form-control">
                            </div>
                            <div class="field-span-2">
                                <label class="form-label">${@json(admin_t('تفاصيل اليوم'))}</label>
                                <textarea name="itinerary[${itineraryIndex}][description]" rows="4" class="form-control"></textarea>
                            </div>
                            <div>
                                <label class="form-label">${@json(admin_t('الوجبات'))}</label>
                                <div class="choice-row">
                                    <label class="choice-pill">
                                        <input type="checkbox" name="itinerary[${itineraryIndex}][meals_breakfast]" value="1">
                                        <span>${@json(admin_t('فطار'))}</span>
                                    </label>
                                    <label class="choice-pill">
                                        <input type="checkbox" name="itinerary[${itineraryIndex}][meals_lunch]" value="1">
                                        <span>${@json(admin_t('غداء'))}</span>
                                    </label>
                                    <label class="choice-pill">
                                        <input type="checkbox" name="itinerary[${itineraryIndex}][meals_dinner]" value="1">
                                        <span>${@json(admin_t('عشاء'))}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
                itineraryIndex++;
                ensureEmptyState('#itinerary-wrapper', '.itinerary-item', 'itineraryEmptyState', texts.noItinerary);
            }

            function addInclusion(type) {
                const wrapper = document.getElementById(`${type}-wrapper`);
                const index = type === 'included' ? includedIndex++ : excludedIndex++;
                wrapper.insertAdjacentHTML('beforeend', `
                    <div class="repeat-box ${type}-item">
                        <div class="d-flex gap-2">
                            <input type="text" name="${type}[${index}][title]" class="form-control">
                            ${createRemoveButton()}
                        </div>
                    </div>
                `);
                ensureEmptyState(`#${type}-wrapper`, `.${type}-item`, `${type}EmptyState`, type === 'included' ? texts.noIncluded : texts.noExcluded);
            }

            function addPrice() {
                document.getElementById('prices-wrapper').insertAdjacentHTML('beforeend', `
                    <div class="repeat-box price-item">
                        <div class="repeat-box-title">
                            <strong>${@json(admin_t('سعر جديد'))}</strong>
                            ${createRemoveButton()}
                        </div>
                        <div class="fields-grid">
                            <div>
                                <label class="form-label">${@json(admin_t('العنوان'))}</label>
                                <input type="text" name="prices[${priceIndex}][label]" class="form-control">
                            </div>
                            <div>
                                <label class="form-label">${@json(admin_t('الموسم'))}</label>
                                <input type="text" name="prices[${priceIndex}][season_name]" class="form-control">
                            </div>
                            <div>
                                <label class="form-label">${@json(admin_t('نوع السعر'))}</label>
                                <select name="prices[${priceIndex}][price_type]" class="form-select">
                                    <option value="from">${@json(admin_t('يبدأ من'))}</option>
                                    <option value="fixed">${@json(admin_t('ثابت'))}</option>
                                    <option value="seasonal">${@json(admin_t('موسمي'))}</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">${@json(admin_t('نوع الغرفة'))}</label>
                                <input type="text" name="prices[${priceIndex}][room_type]" class="form-control">
                            </div>
                            <div>
                                <label class="form-label">${@json(admin_t('المبلغ'))}</label>
                                <input type="number" step="0.01" name="prices[${priceIndex}][amount]" class="form-control">
                            </div>
                            <div>
                                <label class="form-label">${@json(admin_t('العملة'))}</label>
                                <select name="prices[${priceIndex}][currency_id]" class="form-select">
                                    <option value="">${@json(admin_t('اختر العملة'))}</option>
                                    @foreach ($currencies ?? collect() as $currency)
                                        <option value="{{ $currency->id }}">{{ $currency->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">${@json(admin_t('من تاريخ'))}</label>
                                <input type="date" name="prices[${priceIndex}][valid_from]" class="form-control">
                            </div>
                            <div>
                                <label class="form-label">${@json(admin_t('إلى تاريخ'))}</label>
                                <input type="date" name="prices[${priceIndex}][valid_to]" class="form-control">
                            </div>
                            <div class="field-span-3">
                                <label class="form-label">${@json(admin_t('ملاحظات'))}</label>
                                <textarea name="prices[${priceIndex}][notes]" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                `);
                priceIndex++;
                ensureEmptyState('#prices-wrapper', '.price-item', 'pricesEmptyState', texts.noPrices);
            }

            function updateSummary() {
                const destinationOption = destinationSelector?.options[destinationSelector.selectedIndex];
                const categoryOption = document.getElementById('category_id')?.options[document.getElementById('category_id').selectedIndex];
                const durationText = document.getElementById('duration_text').value || [
                    document.getElementById('duration_days').value ? document.getElementById('duration_days').value + ' {{ admin_t('يوم') }}' : '',
                    document.getElementById('duration_nights').value ? document.getElementById('duration_nights').value + ' {{ admin_t('ليلة') }}' : '',
                    document.getElementById('duration_hours').value ? document.getElementById('duration_hours').value + ' {{ admin_t('ساعة') }}' : ''
                ].filter(Boolean).join(' / ');
                const imagesCount = (featuredFile ? 1 : 0) + galleryFiles.length;
                const itineraryCount = document.querySelectorAll('.itinerary-item').length;

                const summary = {
                    title: document.getElementById('title').value || texts.noData,
                    destination: destinationOption && destinationOption.value ? destinationOption.textContent.trim() : texts.noData,
                    duration: durationText || texts.noData,
                    price: document.getElementById('start_from_price').value || texts.noData,
                    category: categoryOption && categoryOption.value ? categoryOption.textContent.trim() : texts.noData,
                    status: form.querySelector('input[name="is_active"]').checked ? texts.active : texts.inactive,
                    images: imagesCount,
                    daysCount: itineraryCount
                };

                Object.entries(summary).forEach(([key, value]) => {
                    const target = document.querySelector(`[data-summary="${key}"]`);
                    if (target) {
                        target.textContent = value;
                    }
                });
            }

            maybeRestoreDraft();
            syncCountryFromDestination();
            updateDurationFields();
            renderGalleryPreview();
            renderFeaturedPreview();
            document.querySelectorAll('[data-counter-max]').forEach(updateCounter);
            showStep(currentStep);
            ensureEmptyState('#itinerary-wrapper', '.itinerary-item', 'itineraryEmptyState', texts.noItinerary);
            ensureEmptyState('#facilities-wrapper', '.facility-item', 'facilitiesEmptyState', texts.noFacilities);
            ensureEmptyState('#included-wrapper', '.included-item', 'includedEmptyState', texts.noIncluded);
            ensureEmptyState('#excluded-wrapper', '.excluded-item', 'excludedEmptyState', texts.noExcluded);
            ensureEmptyState('#prices-wrapper', '.price-item', 'pricesEmptyState', texts.noPrices);

            document.querySelectorAll('[data-counter-max]').forEach(input => {
                input.addEventListener('input', () => updateCounter(input));
            });

            stepButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const targetStep = Number(button.dataset.stepTrigger);
                    if (targetStep > highestStep + 1) {
                        return;
                    }
                    if (targetStep > currentStep && !validateStep(currentStep)) {
                        return;
                    }
                    showStep(targetStep);
                });
            });

            prevBtn.addEventListener('click', () => {
                if (currentStep > 1) {
                    showStep(currentStep - 1);
                }
            });

            nextBtn.addEventListener('click', () => {
                if (!validateStep(currentStep)) {
                    focusFirstInvalid(currentStep);
                    return;
                }
                if (currentStep < totalSteps) {
                    showStep(currentStep + 1);
                }
            });

            saveDraftBtn.addEventListener('click', saveDraft);

            destinationSelector?.addEventListener('change', syncCountryFromDestination);

            form.querySelectorAll('input[name="duration_type"]').forEach(radio => {
                radio.addEventListener('change', updateDurationFields);
            });

            form.addEventListener('input', function(event) {
                isDirty = true;
                const input = event.target;
                if (input.matches('[data-counter-max]')) {
                    updateCounter(input);
                }
                updateSummary();
            });

            form.addEventListener('change', function() {
                isDirty = true;
                updateSummary();
            });

            featuredInput.addEventListener('change', function() {
                featuredFile = this.files[0] || null;
                renderFeaturedPreview();
                updateSummary();
            });

            galleryInput.addEventListener('change', function() {
                galleryFiles = Array.from(this.files);
                renderGalleryPreview();
                updateSummary();
            });

            featuredPreview.addEventListener('click', function(event) {
                const removeButton = event.target.closest('[data-remove-featured]');
                if (!removeButton) {
                    return;
                }
                featuredFile = null;
                featuredInput.value = '';
                renderFeaturedPreview();
                updateSummary();
            });

            galleryPreview.addEventListener('click', function(event) {
                const removeButton = event.target.closest('[data-gallery-index]');
                if (!removeButton) {
                    return;
                }
                const index = Number(removeButton.dataset.galleryIndex);
                galleryFiles.splice(index, 1);
                syncGalleryInput();
                renderGalleryPreview();
                updateSummary();
            });

            document.getElementById('addFacilityBtn').addEventListener('click', () => addFacility());
            document.getElementById('addItineraryBtn').addEventListener('click', addItinerary);
            document.getElementById('addIncludedBtn').addEventListener('click', () => addInclusion('included'));
            document.getElementById('addExcludedBtn').addEventListener('click', () => addInclusion('excluded'));
            document.getElementById('addPriceBtn').addEventListener('click', addPrice);

            facilitySuggestions?.addEventListener('click', function(event) {
                const chip = event.target.closest('[data-facility-suggestion]');
                if (!chip) {
                    return;
                }
                addFacility(chip.dataset.facilitySuggestion || '');
            });

            document.addEventListener('click', function(event) {
                const removeButton = event.target.closest('.js-remove');
                if (!removeButton) {
                    return;
                }

                const box = removeButton.closest('.repeat-box');
                if (box) {
                    box.remove();
                }

                ensureEmptyState('#itinerary-wrapper', '.itinerary-item', 'itineraryEmptyState', texts.noItinerary);
                ensureEmptyState('#facilities-wrapper', '.facility-item', 'facilitiesEmptyState', texts.noFacilities);
                ensureEmptyState('#included-wrapper', '.included-item', 'includedEmptyState', texts.noIncluded);
                ensureEmptyState('#excluded-wrapper', '.excluded-item', 'excludedEmptyState', texts.noExcluded);
                ensureEmptyState('#prices-wrapper', '.price-item', 'pricesEmptyState', texts.noPrices);
                updateSummary();
            });

            document.querySelectorAll('[data-jump-step]').forEach(button => {
                button.addEventListener('click', function() {
                    showStep(Number(this.dataset.jumpStep));
                });
            });

            form.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' && event.target.tagName !== 'TEXTAREA' && currentStep !== totalSteps) {
                    event.preventDefault();
                }
            });

            form.addEventListener('submit', function(event) {
                if (currentStep !== totalSteps) {
                    event.preventDefault();
                    return;
                }

                if (!validateStep(currentStep)) {
                    event.preventDefault();
                    return;
                }

                isSubmitting = true;
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<span class="btn-icon-text"><span class="spinner-border spinner-border-sm"></span><span>${texts.saving}</span></span>`;
                localStorage.removeItem(draftKey);
            });

            window.addEventListener('beforeunload', function(event) {
                if (!isDirty || isSubmitting) {
                    return;
                }

                event.preventDefault();
                event.returnValue = texts.leavePage;
            });

            ['cancelWizardBtn', 'cancelActionBtn'].forEach(id => {
                document.getElementById(id)?.addEventListener('click', function(event) {
                    if (!isDirty || isSubmitting) {
                        return;
                    }

                    if (!window.confirm(texts.leavePage)) {
                        event.preventDefault();
                    }
                });
            });

            if ({{ $viewErrors->any() ? 'true' : 'false' }}) {
                focusFirstInvalid(initialStep);
            }
        });
    </script>
@endsection
