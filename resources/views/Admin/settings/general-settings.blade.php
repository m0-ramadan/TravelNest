@extends('Admin.layout.master')

@section('title', 'الإعدادات العامة')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: "Cairo", sans-serif !important;
        }

        .settings-card {
            background: var(--bs-card-bg);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #696cff;
        }

        .settings-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--bs-border-color);
        }

        .settings-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-left: 15px;
        }

        .settings-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--bs-heading-color);
            margin-bottom: 5px;
        }

        .settings-description {
            color: var(--bs-secondary-color);
            font-size: 14px;
        }

        .required::after {
            content: " *";
            color: #dc3545;
        }

        .alert-guide {
            background: var(--bs-info-bg-subtle);
            border-right: 4px solid #696cff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-guide h6 {
            color: #696cff;
            margin-bottom: 10px;
        }

        .alert-guide ul {
            margin-bottom: 0;
            padding-left: 20px;
        }

        .alert-guide li {
            margin-bottom: 5px;
            font-size: 14px;
        }

        .toggle-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toggle-switch {
            position: relative;
            width: 60px;
            height: 30px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.toggle-slider {
            background-color: #696cff;
        }

        input:checked+.toggle-slider:before {
            transform: translateX(30px);
        }

        .toggle-label {
            font-weight: 500;
            color: var(--bs-body-color);
        }

        .language-flag {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            margin-left: 10px;
            object-fit: cover;
        }

        .currency-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-right: 5px;
            background: var(--bs-primary);
            color: white;
        }

        .logo-preview {
            width: 150px;
            height: 150px;
            border: 2px dashed var(--bs-border-color);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 15px;
            position: relative;
        }

        .logo-preview img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .logo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .logo-preview:hover .logo-overlay {
            opacity: 1;
        }

        .timezone-select {
            height: 150px !important;
        }

        @media (max-width: 768px) {
            .settings-header {
                flex-direction: column;
                text-align: center;
            }

            .settings-icon {
                margin-left: 0;
                margin-bottom: 15px;
            }
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
                    <a href="{{ route('admin.settings.index') }}">الإعدادات</a>
                </li>
                <li class="breadcrumb-item active">الإعدادات العامة</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">الإعدادات العامة</h5>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i> رجوع للإعدادات
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- Quick Guide -->
                        <div class="alert-guide">
                            <h6><i class="fas fa-lightbulb me-2"></i>معلومات مهمة:</h6>
                            <ul>
                                <li>هذه الإعدادات تؤثر على المظهر العام للموقع وسلوكه</li>
                                <li>سيتم تطبيق التغييرات فور حفظها</li>
                                <li>الأحقل ذات العلامة (*) إلزامية</li>
                                <li>الصور الموصى بها: شعار 200×200 بكسل، أيقونة 100×100 بكسل</li>
                            </ul>
                        </div>

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

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data" id="generalSettingsForm">
                            @csrf
                            @method('PUT')

                            <!-- Website Settings -->
                    @php
    $langs = \App\Models\Language::all();
@endphp

<div class="settings-card">
    <div class="settings-header">
        <div class="settings-icon">
            <i class="fas fa-globe"></i>
        </div>
        <div>
            <h5 class="settings-title">إعدادات الموقع</h5>
            <p class="settings-description">المعلومات الأساسية والهوية البصرية للموقع</p>
        </div>
    </div>

    {{-- Tabs Nav --}}
    <ul class="nav nav-tabs mb-3" role="tablist">
        @foreach ($langs as $index => $lang)
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link {{ $index === 0 ? 'active' : '' }}"
                    id="lang-tab-{{ $lang->code }}"
                    data-bs-toggle="tab"
                    data-bs-target="#lang-pane-{{ $lang->code }}"
                    type="button"
                    role="tab"
                    aria-controls="lang-pane-{{ $lang->code }}"
                    aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                >
                    {{ $lang->name }} ({{ $lang->code }})
                </button>
            </li>
        @endforeach
    </ul>

    {{-- Tabs Content --}}
    <div class="tab-content">
        @foreach ($langs as $index => $lang)
            <div
                class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                id="lang-pane-{{ $lang->code }}"
                role="tabpanel"
                aria-labelledby="lang-tab-{{ $lang->code }}"
            >
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="site_name_{{ $lang->code }}" class="form-label required">
                            اسم الموقع ({{ $lang->name }})
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="site_name_{{ $lang->code }}"
                            name="site_name_{{ $lang->code }}"
                            value="{{ old("site_name_{$lang->code}", $settings["site_name_{$lang->code}"] ?? '') }}"
                            required
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="site_title_{{ $lang->code }}" class="form-label required">
                            عنوان الموقع ({{ $lang->name }})
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="site_title_{{ $lang->code }}"
                            name="site_title_{{ $lang->code }}"
                            value="{{ old("site_title_{$lang->code}", $settings["site_title_{$lang->code}"] ?? '') }}"
                            required
                        >
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="site_description_{{ $lang->code }}" class="form-label">
                            وصف الموقع ({{ $lang->name }})
                        </label>
                        <textarea
                            class="form-control"
                            id="site_description_{{ $lang->code }}"
                            name="site_description_{{ $lang->code }}"
                            rows="3"
                        >{{ old("site_description_{$lang->code}", $settings["site_description_{$lang->code}"] ?? '') }}</textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="site_keywords_{{ $lang->code }}" class="form-label">
                            الكلمات المفتاحية ({{ $lang->name }})
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="site_keywords_{{ $lang->code }}"
                            name="site_keywords_{{ $lang->code }}"
                            value="{{ old("site_keywords_{$lang->code}", $settings["site_keywords_{$lang->code}"] ?? '') }}"
                            placeholder="keyword 1, keyword 2, ..."
                        >
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Non-translatable fields --}}
    <div class="row mt-3">
        <div class="col-md-6 mb-3">
            <label for="site_url" class="form-label required">رابط الموقع</label>
            <input
                type="url"
                class="form-control"
                id="site_url"
                name="site_url"
                value="{{ old('site_url', $settings['site_url'] ?? '') }}"
                required
            >
        </div>
    </div>
</div>

                            <!-- Logo & Favicon -->
                            <div class="settings-card">
                                <div class="settings-header">
                                    <div class="settings-icon">
                                        <i class="fas fa-images"></i>
                                    </div>
                                    <div>
                                        <h5 class="settings-title">الشعار والأيقونة</h5>
                                        <p class="settings-description">الهوية البصرية للموقع</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">شعار الموقع</label>
                                        <div class="logo-preview" id="logoPreview">
                                            @if($settings['site_logo'] ?? false)
                                                <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="شعار الموقع" id="logoPreviewImg">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                                    <i class="fas fa-image fa-3x"></i>
                                                </div>
                                            @endif
                                            <div class="logo-overlay">
                                                <button type="button" class="btn btn-info btn-sm" onclick="document.getElementById('site_logo').click()">
                                                    <i class="fas fa-upload me-1"></i> تغيير
                                                </button>
                                            </div>
                                        </div>
                                        <input type="file" id="site_logo" name="site_logo" accept="image/*" 
                                            style="display: none;" onchange="previewLogo(this)">
                                        <small class="text-muted">الحجم الموصى به: 200×200 بكسل، الصيغ المسموحة: PNG, JPG, SVG</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">أيقونة الموقع (Favicon)</label>
                                        <div class="logo-preview" id="faviconPreview">
                                            @if($settings['site_favicon'] ?? false)
                                                <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="أيقونة الموقع" id="faviconPreviewImg">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                                    <i class="fas fa-flag fa-3x"></i>
                                                </div>
                                            @endif
                                            <div class="logo-overlay">
                                                <button type="button" class="btn btn-info btn-sm" onclick="document.getElementById('site_favicon').click()">
                                                    <i class="fas fa-upload me-1"></i> تغيير
                                                </button>
                                            </div>
                                        </div>
                                        <input type="file" id="site_favicon" name="site_favicon" accept="image/*" 
                                            style="display: none;" onchange="previewFavicon(this)">
                                        <small class="text-muted">الحجم الموصى به: 100×100 بكسل، الصيغة المفضلة: PNG</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Regional Settings -->
                            <div class="settings-card">
                                <div class="settings-header">
                                    <div class="settings-icon">
                                        <i class="fas fa-flag"></i>
                                    </div>
                                    <div>
                                        <h5 class="settings-title">الإعدادات الإقليمية</h5>
                                        <p class="settings-description">إعدادات اللغة والعملة والمنطقة الزمنية</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="site_language" class="form-label required">اللغة الافتراضية</label>
                                        <select class="form-select select2" id="site_language" name="site_language" required>
                                            <option value="">اختر اللغة</option>
                                            @foreach($languages as $code => $language)
                                                <option value="{{ $code }}" 
                                                    {{ old('site_language', $settings['site_language'] ?? '') == $code ? 'selected' : '' }}>
                                                    {{ $language['name'] }}
                                                    @if(isset($language['flag']))
                                                        <img src="{{ asset($language['flag']) }}" class="language-flag">
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="site_currency" class="form-label required">العملة الافتراضية</label>
                                        <select class="form-select select2" id="site_currency" name="site_currency" required>
                                            <option value="">اختر العملة</option>
                                            @foreach($currencies as $code => $currency)
                                                <option value="{{ $code }}" 
                                                    {{ old('site_currency', $settings['site_currency'] ?? '') == $code ? 'selected' : '' }}>
                                                    {{ $currency['name'] }} ({{ $currency['symbol'] }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="site_timezone" class="form-label required">المنطقة الزمنية</label>
                                        <select class="form-select select2 timezone-select" id="site_timezone" name="site_timezone" required>
                                            <option value="">اختر المنطقة الزمنية</option>
                                            @foreach($timezones as $timezone)
                                                <option value="{{ $timezone }}" 
                                                    {{ old('site_timezone', $settings['site_timezone'] ?? '') == $timezone ? 'selected' : '' }}>
                                                    {{ $timezone }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="date_format" class="form-label required">تنسيق التاريخ</label>
                                        <select class="form-select" id="date_format" name="date_format" required>
                                            <option value="Y-m-d" {{ old('date_format', $settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD (2024-01-15)</option>
                                            <option value="d-m-Y" {{ old('date_format', $settings['date_format'] ?? '') == 'd-m-Y' ? 'selected' : '' }}>DD-MM-YYYY (15-01-2024)</option>
                                            <option value="m/d/Y" {{ old('date_format', $settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY (01/15/2024)</option>
                                            <option value="d/m/Y" {{ old('date_format', $settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY (15/01/2024)</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="time_format" class="form-label required">تنسيق الوقت</label>
                                        <select class="form-select" id="time_format" name="time_format" required>
                                            <option value="12" {{ old('time_format', $settings['time_format'] ?? '') == '12' ? 'selected' : '' }}>12 ساعة (02:30 PM)</option>
                                            <option value="24" {{ old('time_format', $settings['time_format'] ?? '') == '24' ? 'selected' : '' }}>24 ساعة (14:30)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- System Settings -->
                            <div class="settings-card">
                                <div class="settings-header">
                                    <div class="settings-icon">
                                        <i class="fas fa-cog"></i>
                                    </div>
                                    <div>
                                        <h5 class="settings-title">إعدادات النظام</h5>
                                        <p class="settings-description">إعدادات التحكم في سلوك النظام</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="toggle-container">
                                            <label class="toggle-switch">
                                                <input type="checkbox" id="site_maintenance" name="site_maintenance"
                                                    {{ old('site_maintenance', $settings['site_maintenance'] ?? false) ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                            <span class="toggle-label">وضع الصيانة</span>
                                        </div>
                                        <small class="text-muted d-block mt-1">عند تفعيله، لن يتمكن الزوار من الوصول للموقع</small>
                                    </div>

 

                                    <div class="col-md-6 mb-3">
                                        <div class="toggle-container">
                                            <label class="toggle-switch">
                                                <input type="checkbox" id="email_verification" name="email_verification"
                                                    {{ old('email_verification', $settings['email_verification'] ?? false) ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                            <span class="toggle-label">التحقق من البريد الإلكتروني</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="items_per_page" class="form-label required">عدد العناصر في الصفحة</label>
                                        <input type="number" class="form-control" id="items_per_page" name="items_per_page"
                                            value="{{ old('items_per_page', $settings['items_per_page'] ?? 15) }}" min="5" max="100" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="cache_ttl" class="form-label">مدة التخزين المؤقت (دقيقة)</label>
                                        <input type="number" class="form-control" id="cache_ttl" name="cache_ttl"
                                            value="{{ old('cache_ttl', $settings['cache_ttl'] ?? 60) }}" min="1" max="1440">
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="settings-card">
                                <div class="settings-header">
                                    <div class="settings-icon">
                                        <i class="fas fa-address-book"></i>
                                    </div>
                                    <div>
                                        <h5 class="settings-title">معلومات التواصل</h5>
                                        <p class="settings-description">معلومات التواصل الخاصة بالموقع</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="contact_email" class="form-label required">البريد الإلكتروني للتواصل</label>
                                        <input type="email" class="form-control" id="contact_email" name="contact_email"
                                            value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="contact_phone" class="form-label">رقم الهاتف</label>
                                        <input type="text" class="form-control" id="contact_phone" name="contact_phone"
                                            value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}">
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="contact_address" class="form-label">العنوان</label>
                                        <textarea class="form-control" id="contact_address" name="contact_address" rows="2">{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo me-1"></i> إعادة تعيين
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i> حفظ الإعدادات
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: 'اختر الخيار',
                allowClear: true
            });

            // Form submission
            $('#generalSettingsForm').on('submit', function(e) {
                // Show loading
                Swal.fire({
                    title: 'جاري حفظ الإعدادات...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });
        });

        function previewLogo(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#logoPreviewImg').remove();
                    $('#logoPreview').prepend(`<img src="${e.target.result}" alt="شعار الموقع" id="logoPreviewImg">`);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewFavicon(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#faviconPreviewImg').remove();
                    $('#faviconPreview').prepend(`<img src="${e.target.result}" alt="أيقونة الموقع" id="faviconPreviewImg">`);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Validate form before submission
        function validateForm() {
            const siteName = $('#site_name').val();
            const siteTitle = $('#site_title').val();
            const siteUrl = $('#site_url').val();
            const siteLanguage = $('#site_language').val();
            const siteCurrency = $('#site_currency').val();
            const siteTimezone = $('#site_timezone').val();

            if (!siteName || !siteTitle || !siteUrl || !siteLanguage || !siteCurrency || !siteTimezone) {
                Swal.fire({
                    icon: 'error',
                    title: 'بيانات ناقصة',
                    text: 'يرجى ملء جميع الحقول المطلوبة',
                    confirmButtonText: 'حسناً'
                });
                return false;
            }

            return true;
        }
    </script>
@endsection