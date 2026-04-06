@extends('admin.layout.master')

@section('title', 'إضافة باقة')

@section('css')
    <style>
        :root {
            --primary-color: #696cff;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --dark-bg: #1e1e2d;
            --dark-card: #2b3b4c;
            --dark-input: rgba(255, 255, 255, .05);
            --dark-border: rgba(255, 255, 255, .1);
        }

        body {
            font-family: "Cairo", sans-serif !important;
            background: var(--dark-bg);
            color: #fff;
        }

        .main-card {
            background: var(--dark-card);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .25);
            border: 1px solid var(--dark-border);
        }

        .main-header {
            background: var(--primary-gradient);
            color: #fff;
            padding: 24px 28px;
        }

        .form-body {
            padding: 30px;
        }

        .section-title {
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--dark-border);
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #f1f1f1;
        }

        .form-control,
        .form-select,
        textarea {
            background: var(--dark-input) !important;
            border: 1px solid var(--dark-border) !important;
            color: #fff !important;
            border-radius: 10px !important;
            min-height: 46px;
        }

        .form-control::placeholder,
        textarea::placeholder {
            color: rgba(255, 255, 255, .55);
        }

        .form-control:focus,
        .form-select:focus,
        textarea:focus {
            background: rgba(255, 255, 255, .08) !important;
            color: #fff !important;
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 .25rem rgba(105, 108, 255, .25) !important;
        }

        .form-select option {
            color: #000;
        }

        .form-check-input {
            width: 3rem;
            height: 1.5rem;
        }

        .form-check-label {
            margin-inline-start: 8px;
            font-weight: 600;
        }

        .note-box {
            background: rgba(255, 255, 255, .04);
            border: 1px dashed rgba(255, 255, 255, .15);
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            color: rgba(255, 255, 255, .8);
        }

        .invalid-feedback {
            display: block;
            color: #ffb3b3;
        }

        .btn-light {
            font-weight: 600;
        }

        .sticky-actions {
            position: sticky;
            bottom: 0;
            background: var(--dark-card);
            padding-top: 16px;
            margin-top: 10px;
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
                    <a href="{{ route('admin.packages.index') }}">الباقات</a>
                </li>
                <li class="breadcrumb-item active">إضافة باقة</li>
            </ol>
        </nav>

        <div class="main-card">
            <div class="main-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-1">إضافة باقة جديدة</h5>
                    <small class="opacity-75">إدخال بيانات الباقة بما يتوافق مع الموديل والجدول</small>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.packages.create-with-ai') }}" class="btn btn-light">
                        إنشاء بالذكاء الاصطناعي
                    </a>
                    <a href="{{ route('admin.packages.index') }}" class="btn btn-light">
                        رجوع
                    </a>
                </div>
            </div>

            <div class="form-body">
                <form action="{{ route('admin.packages.store') }}" method="POST">
                    @csrf

                    {{-- بيانات أساسية --}}
                    <div class="section-title">البيانات الأساسية</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">عنوان الباقة</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}" placeholder="مثال: 7 Day Cairo and Nile Cruise by Flight">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">العنوان الفرعي</label>
                            <input type="text" name="subtitle"
                                class="form-control @error('subtitle') is-invalid @enderror" value="{{ old('subtitle') }}"
                                placeholder="مثال: Private Cairo, Aswan & Luxor Journey">
                            @error('subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                                value="{{ old('slug') }}" placeholder="example-package-slug">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">التصنيف</label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">اختر التصنيف</option>
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

                        {{-- الوجهة فقط لتحديد الدولة الأساسية تلقائيًا --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">الوجهة</label>
                            <select id="destination_selector" class="form-select">
                                <option value="">اختر الوجهة</option>
                                @foreach ($destinations ?? collect() as $destination)
                                    <option value="{{ $destination->id }}"
                                        data-country-id="{{ $destination->country_id }}"
                                        {{ old('destination_id') == $destination->id ? 'selected' : '' }}>
                                        {{ adminTrans($destination->name) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="note-box mt-2">
                                هذا الحقل للمساعدة فقط. عند الاختيار سيتم تعبئة الدولة الأساسية تلقائيًا في
                                <code>primary_country_id</code>.
                            </div>
                        </div>

                        <input type="hidden" name="primary_country_id" id="primary_country_id"
                            value="{{ old('primary_country_id') }}">

                        <div class="col-md-4 mb-3">
                            <label class="form-label">نوع الباقة</label>
                            <select name="package_type" class="form-select @error('package_type') is-invalid @enderror">
                                <option value="">اختر النوع</option>
                                <option value="travel_package"
                                    {{ old('package_type') == 'travel_package' ? 'selected' : '' }}>Travel Package</option>
                                <option value="nile_cruise" {{ old('package_type') == 'nile_cruise' ? 'selected' : '' }}>
                                    Nile Cruise</option>
                                <option value="day_tour" {{ old('package_type') == 'day_tour' ? 'selected' : '' }}>Day Tour
                                </option>
                                <option value="shore_excursion"
                                    {{ old('package_type') == 'shore_excursion' ? 'selected' : '' }}>Shore Excursion
                                </option>
                                <option value="tailor_made" {{ old('package_type') == 'tailor_made' ? 'selected' : '' }}>
                                    Tailor Made</option>
                            </select>
                            @error('package_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">العملة</label>
                            <select name="currency_id" class="form-select @error('currency_id') is-invalid @enderror">
                                <option value="">اختر العملة</option>
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

                        <div class="col-md-4 mb-3">
                            <label class="form-label">نظام الحجز</label>
                            <select name="booking_mode" class="form-select @error('booking_mode') is-invalid @enderror">
                                <option value="">اختر النظام</option>
                                <option value="request" {{ old('booking_mode') == 'request' ? 'selected' : '' }}>Request
                                </option>
                                <option value="instant" {{ old('booking_mode') == 'instant' ? 'selected' : '' }}>Instant
                                </option>
                            </select>
                            @error('booking_mode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- الوصف --}}
                    <div class="section-title mt-4">النصوص والوصف</div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">وصف مختصر</label>
                            <textarea name="short_description" rows="3" class="form-control @error('short_description') is-invalid @enderror"
                                placeholder="وصف مختصر يظهر في القوائم وبطاقات العرض">{{ old('short_description') }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">الوصف الكامل</label>
                            <textarea name="description" rows="6" class="form-control @error('description') is-invalid @enderror"
                                placeholder="الوصف الكامل للباقة">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- المدة والأسعار --}}
                    <div class="section-title mt-4">المدة والأسعار</div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">عدد الأيام</label>
                            <input type="number" name="duration_days"
                                class="form-control @error('duration_days') is-invalid @enderror"
                                value="{{ old('duration_days') }}">
                            @error('duration_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">عدد الليالي</label>
                            <input type="number" name="duration_nights"
                                class="form-control @error('duration_nights') is-invalid @enderror"
                                value="{{ old('duration_nights') }}">
                            @error('duration_nights')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">السعر يبدأ من</label>
                            <input type="number" step="0.01" name="start_from_price"
                                class="form-control @error('start_from_price') is-invalid @enderror"
                                value="{{ old('start_from_price') }}">
                            @error('start_from_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">سعر المقارنة</label>
                            <input type="number" step="0.01" name="compare_price"
                                class="form-control @error('compare_price') is-invalid @enderror"
                                value="{{ old('compare_price') }}">
                            @error('compare_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- بيانات الرحلة --}}
                    <div class="section-title mt-4">تفاصيل الرحلة</div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">الجدول</label>
                            <input type="text" name="schedule_text"
                                class="form-control @error('schedule_text') is-invalid @enderror"
                                value="{{ old('schedule_text') }}" placeholder="مثال: Every Day">
                            @error('schedule_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">مكان الاستلام</label>
                            <input type="text" name="pickup_location"
                                class="form-control @error('pickup_location') is-invalid @enderror"
                                value="{{ old('pickup_location') }}" placeholder="مثال: Cairo Airport or Hotel in Cairo">
                            @error('pickup_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">مكان الانتهاء</label>
                            <input type="text" name="dropoff_location"
                                class="form-control @error('dropoff_location') is-invalid @enderror"
                                value="{{ old('dropoff_location') }}" placeholder="مثال: Cairo Airport">
                            @error('dropoff_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">الوجهات</label>
                            <input type="text" name="destinations_text"
                                class="form-control @error('destinations_text') is-invalid @enderror"
                                value="{{ old('destinations_text') }}" placeholder="مثال: Cairo / Aswan / Luxor / Cairo">
                            @error('destinations_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">ملخص الموقع</label>
                            <input type="text" name="location_summary"
                                class="form-control @error('location_summary') is-invalid @enderror"
                                value="{{ old('location_summary') }}"
                                placeholder="مثال: Cairo, Aswan, Kom Ombo, Edfu, Luxor">
                            @error('location_summary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">نوع الجولة</label>
                            <select name="tour_type" class="form-select @error('tour_type') is-invalid @enderror">
                                <option value="">اختر النوع</option>
                                <option value="private" {{ old('tour_type') == 'private' ? 'selected' : '' }}>Private
                                </option>
                                <option value="group" {{ old('tour_type') == 'group' ? 'selected' : '' }}>Group</option>
                                <option value="shared" {{ old('tour_type') == 'shared' ? 'selected' : '' }}>Shared
                                </option>
                                <option value="custom" {{ old('tour_type') == 'custom' ? 'selected' : '' }}>Custom
                                </option>
                            </select>
                            @error('tour_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">مستوى الصعوبة</label>
                            <select name="difficulty_level"
                                class="form-select @error('difficulty_level') is-invalid @enderror">
                                <option value="">اختر المستوى</option>
                                <option value="easy" {{ old('difficulty_level') == 'easy' ? 'selected' : '' }}>Easy
                                </option>
                                <option value="moderate" {{ old('difficulty_level') == 'moderate' ? 'selected' : '' }}>
                                    Moderate</option>
                                <option value="hard" {{ old('difficulty_level') == 'hard' ? 'selected' : '' }}>Hard
                                </option>
                            </select>
                            @error('difficulty_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">رابط الفيديو</label>
                            <input type="text" name="video_url"
                                class="form-control @error('video_url') is-invalid @enderror"
                                value="{{ old('video_url') }}" placeholder="https://example.com/video">
                            @error('video_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- المشاركة والتقييم --}}
                    <div class="section-title mt-4">المشاركون والتقييم</div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">الحد الأدنى للمشاركين</label>
                            <input type="number" name="min_participants"
                                class="form-control @error('min_participants') is-invalid @enderror"
                                value="{{ old('min_participants') }}">
                            @error('min_participants')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">الحد الأقصى للمشاركين</label>
                            <input type="number" name="max_participants"
                                class="form-control @error('max_participants') is-invalid @enderror"
                                value="{{ old('max_participants') }}">
                            @error('max_participants')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">أيام الحجز المسبق</label>
                            <input type="number" name="booking_lead_days"
                                class="form-control @error('booking_lead_days') is-invalid @enderror"
                                value="{{ old('booking_lead_days') }}">
                            @error('booking_lead_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">التقييم</label>
                            <input type="number" step="0.01" name="rating_avg"
                                class="form-control @error('rating_avg') is-invalid @enderror"
                                value="{{ old('rating_avg') }}">
                            @error('rating_avg')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">عدد المراجعات</label>
                            <input type="number" name="reviews_count"
                                class="form-control @error('reviews_count') is-invalid @enderror"
                                value="{{ old('reviews_count') }}">
                            @error('reviews_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- السياسات --}}
                    <div class="section-title mt-4">السياسات والشروط</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">سياسة الإلغاء</label>
                            <textarea name="cancellation_policy" rows="4"
                                class="form-control @error('cancellation_policy') is-invalid @enderror" placeholder="أدخل سياسة الإلغاء">{{ old('cancellation_policy') }}</textarea>
                            @error('cancellation_policy')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">الشروط والأحكام</label>
                            <textarea name="terms_conditions" rows="4"
                                class="form-control @error('terms_conditions') is-invalid @enderror" placeholder="أدخل الشروط والأحكام">{{ old('terms_conditions') }}</textarea>
                            @error('terms_conditions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- النشر والترتيب --}}
                    <div class="section-title mt-4">النشر والإعدادات</div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">تاريخ النشر</label>
                            <input type="date" name="published_at"
                                class="form-control @error('published_at') is-invalid @enderror"
                                value="{{ old('published_at') }}">
                            @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">الترتيب</label>
                            <input type="number" name="sort_order"
                                class="form-control @error('sort_order') is-invalid @enderror"
                                value="{{ old('sort_order', 0) }}">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-12 mb-3 d-flex flex-wrap gap-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="1" name="is_active"
                                    id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">مفعلة</label>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="1" name="is_featured"
                                    id="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">مميزة</label>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="1" name="is_best_seller"
                                    id="is_best_seller" {{ old('is_best_seller') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_best_seller">الأكثر مبيعًا</label>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="1" name="is_ultra_luxury"
                                    id="is_ultra_luxury" {{ old('is_ultra_luxury') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_ultra_luxury">فاخرة جدًا</label>
                            </div>
                        </div>
                    </div>

                    {{-- SEO --}}
                    <div class="section-title mt-4">SEO</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">عنوان SEO</label>
                            <input type="text" name="seo_title"
                                class="form-control @error('seo_title') is-invalid @enderror"
                                value="{{ old('seo_title') }}">
                            @error('seo_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">عنوان المسار الظاهري</label>
                            <input type="text" name="breadcrumb_title"
                                class="form-control @error('breadcrumb_title') is-invalid @enderror"
                                value="{{ old('breadcrumb_title') }}">
                            @error('breadcrumb_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8 mb-3">
                            <label class="form-label">وصف SEO</label>
                            <textarea name="seo_description" rows="3" class="form-control @error('seo_description') is-invalid @enderror">{{ old('seo_description') }}</textarea>
                            @error('seo_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Canonical URL</label>
                            <input type="text" name="canonical_url"
                                class="form-control @error('canonical_url') is-invalid @enderror"
                                value="{{ old('canonical_url') }}">
                            @error('canonical_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="sticky-actions d-flex gap-2 mt-4">
                        <button class="btn btn-primary" type="submit">حفظ الباقة</button>
                        <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const destinationSelector = document.getElementById('destination_selector');
            const primaryCountryInput = document.getElementById('primary_country_id');

            function syncCountryFromDestination() {
                const selected = destinationSelector.options[destinationSelector.selectedIndex];
                const countryId = selected ? selected.getAttribute('data-country-id') : '';
                primaryCountryInput.value = countryId || '';
            }

            if (destinationSelector) {
                destinationSelector.addEventListener('change', syncCountryFromDestination);

                if (destinationSelector.value && !primaryCountryInput.value) {
                    syncCountryFromDestination();
                }
            }
        });
    </script>
@endsection
