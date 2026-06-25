@include('admin.i18n.locale')
@extends('admin.layout.master')

@section('title', admin_t('إضافة رحلة'))

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
            margin: 30px 0 18px;
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
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 .25rem rgba(105, 108, 255, .25) !important;
        }

        .form-select option {
            color: #000;
        }

        .repeat-box {
            background: rgba(255, 255, 255, .035);
            border: 1px solid var(--dark-border);
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 14px;
        }

        .form-check-input {
            width: 3rem;
            height: 1.5rem;
        }

        .form-check-label {
            margin-inline-start: 8px;
            font-weight: 600;
        }

        .sticky-actions {
            position: sticky;
            bottom: 0;
            background: var(--dark-card);
            padding-top: 16px;
            margin-top: 20px;
            z-index: 10;
        }

        .remove-btn {
            height: 46px;
        }

        .invalid-feedback {
            display: block;
            color: #ffb3b3;
        }
    </style>
@endsection

@section('content')
    @php
        $facilities = old('facilities', []);
        $itinerary = old('itinerary', []);
        $included = old('included', []);
        $excluded = old('excluded', []);
        $prices = old('prices', []);
    @endphp

    <div class="container-xxl flex-grow-1 container-p-y">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}">الرئيسية</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.packages.index') }}">الرحلات</a>
                </li>
                <li class="breadcrumb-item active">إضافة رحلة</li>
            </ol>
        </nav>

        <div class="main-card">
            <div class="main-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-1">إضافة رحلة جديدة</h5>
                    <small class="opacity-75">إنشاء رحلة كاملة بالتفاصيل، البرنامج، الأسعار، المرافق والسياسات</small>
                </div>

                <div class="d-flex gap-2">
                    @if (Route::has('admin.packages.create-with-ai'))
                        <a href="{{ route('admin.packages.create-with-ai') }}" class="btn btn-light">
                            إنشاء بالذكاء الاصطناعي
                        </a>
                    @endif

                    <a href="{{ route('admin.packages.index') }}" class="btn btn-light">
                        رجوع
                    </a>
                </div>
            </div>

            <div class="form-body">
                <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="section-title">البيانات الأساسية</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">عنوان الرحلة</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">العنوان الفرعي</label>
                            <input type="text" name="subtitle"
                                class="form-control @error('subtitle') is-invalid @enderror" value="{{ old('subtitle') }}">
                            @error('subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                                value="{{ old('slug') }}">
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

                        <div class="col-md-4 mb-3">
                            <label class="form-label">الوجهة</label>
                            <select id="destination_selector" name="destination_id" class="form-select">
                                <option value="">اختر الوجهة</option>
                                @foreach ($destinations ?? collect() as $destination)
                                    <option value="{{ $destination->id }}"
                                        data-country-id="{{ $destination->country_id }}"
                                        {{ old('destination_id') == $destination->id ? 'selected' : '' }}>
                                        {{ adminTrans($destination->name) }}
                                    </option>
                                @endforeach
                            </select>

                            <input type="hidden" name="primary_country_id" id="primary_country_id"
                                value="{{ old('primary_country_id') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">نوع الرحلة</label>
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
                            <label class="form-label">نوع الجولة</label>
                            <select name="tour_type" class="form-select @error('tour_type') is-invalid @enderror">
                                <option value="">اختر نوع الجولة</option>
                                <option value="private" {{ old('tour_type') == 'private' ? 'selected' : '' }}>Private
                                </option>
                                <option value="group" {{ old('tour_type') == 'group' ? 'selected' : '' }}>Small Group Tour
                                </option>
                                <option value="shared" {{ old('tour_type') == 'shared' ? 'selected' : '' }}>Shared</option>
                                <option value="custom" {{ old('tour_type') == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                            @error('tour_type')
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
                                <option value="">اختر نظام الحجز</option>
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

                    <div class="section-title">النصوص والوصف</div>

                    <div class="mb-3">
                        <label class="form-label">وصف مختصر</label>
                        <textarea name="short_description" rows="3" class="form-control">{{ old('short_description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الوصف الكامل</label>
                        <textarea name="description" rows="7" class="form-control">{{ old('description') }}</textarea>
                    </div>

                    <div class="section-title">الصور والمعرض</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الصورة الرئيسية</label>
                            <input type="file" name="featured_image" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">صور المعرض</label>
                            <input type="file" name="gallery_images[]" class="form-control" multiple>
                        </div>
                    </div>

                    <div class="section-title">المدة والمسار</div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">نوع المدة</label><br>

                            <input type="radio" name="duration_type" value="days" checked
                                onclick="toggleDuration()">
                            أيام / ليالي

                            <input type="radio" name="duration_type" value="hours" class="ms-3"
                                onclick="toggleDuration()">
                            ساعات
                        </div>
                        <div class="row" id="daysFields">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">عدد الأيام</label>
                                <input type="number" name="duration_days" class="form-control">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">عدد الليالي</label>
                                <input type="number" name="duration_nights" class="form-control">
                            </div>
                        </div>

                        <div class="row d-none" id="hoursFields">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">عدد الساعات</label>
                                <input type="number" name="duration_hours" class="form-control">
                            </div>
                        </div>



                        <div class="col-md-6 mb-3">
                            <label class="form-label">نص المدة المعروض</label>
                            <input type="text" name="duration_text" class="form-control"
                                value="{{ old('duration_text') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">الجدول</label>
                            <input type="text" name="schedule_text" class="form-control"
                                value="{{ old('schedule_text') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">المسار</label>
                            <input type="text" name="route_text" class="form-control"
                                value="{{ old('route_text') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">مكان الاستلام</label>
                            <input type="text" name="pickup_location" class="form-control"
                                value="{{ old('pickup_location') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">مكان الانتهاء</label>
                            <input type="text" name="dropoff_location" class="form-control"
                                value="{{ old('dropoff_location') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">الوجهات</label>
                            <input type="text" name="destinations_text" class="form-control"
                                value="{{ old('destinations_text') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">ملخص الموقع</label>
                            <input type="text" name="location_summary" class="form-control"
                                value="{{ old('location_summary') }}">
                        </div>
                    </div>

                    <div class="section-title">مرافق الرحلة / Cruise Facilities</div>

                    <div id="facilities-wrapper">
                        @foreach ($facilities as $i => $facility)
                            <div class="repeat-box">
                                <div class="row">
                                    <div class="col-md-10 mb-2">
                                        <input type="text" name="facilities[{{ $i }}][title]"
                                            class="form-control" value="{{ $facility['title'] ?? '' }}"
                                            placeholder="Facility">
                                    </div>

                                    <div class="col-md-2 mb-2">
                                        <button type="button"
                                            class="btn btn-danger w-100 remove-btn js-remove">حذف</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-light" onclick="addFacility()">+ إضافة مرفق</button>

                    <div class="section-title">برنامج الرحلة / Itinerary</div>

                    <div id="itinerary-wrapper">
                        @foreach ($itinerary as $i => $day)
                            <div class="repeat-box">
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label">نوع البرنامج</label>
                                        <input type="text" name="itinerary[{{ $i }}][duration]"
                                            class="form-control" value="{{ $day['duration'] ?? '' }}">
                                    </div>

                                    <div class="col-md-2 mb-2">
                                        <label class="form-label">رقم اليوم</label>
                                        <input type="number" name="itinerary[{{ $i }}][day_number]"
                                            class="form-control" value="{{ $day['day_number'] ?? '' }}">
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label class="form-label">عنوان اليوم</label>
                                        <input type="text" name="itinerary[{{ $i }}][title]"
                                            class="form-control" value="{{ $day['title'] ?? '' }}">
                                    </div>

                                    <div class="col-md-2 mb-2">
                                        <label class="form-label">حذف</label>
                                        <button type="button"
                                            class="btn btn-danger w-100 remove-btn js-remove">حذف</button>
                                    </div>

                                    <div class="col-md-8 mb-2">
                                        <label class="form-label">تفاصيل اليوم</label>
                                        <textarea name="itinerary[{{ $i }}][description]" rows="4" class="form-control">{{ $day['description'] ?? '' }}</textarea>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label class="form-label">الوجبات</label>
                                        <input type="text" name="itinerary[{{ $i }}][meals]"
                                            class="form-control" value="{{ $day['meals'] ?? '' }}"
                                            placeholder="Breakfast, Lunch, Dinner">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-light" onclick="addItinerary()">+ إضافة يوم</button>

                    <div class="section-title">المشمول وغير المشمول</div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Included in Your Journey</h6>

                            <div id="included-wrapper">
                                @foreach ($included as $i => $item)
                                    <div class="repeat-box">
                                        <div class="row">
                                            <div class="col-md-10 mb-2">
                                                <input type="text" name="included[{{ $i }}][title]"
                                                    class="form-control" value="{{ $item['title'] ?? '' }}">
                                            </div>

                                            <div class="col-md-2 mb-2">
                                                <button type="button"
                                                    class="btn btn-danger w-100 remove-btn js-remove">حذف</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-light mb-3" onclick="addIncluded()">+ إضافة
                                بند</button>
                        </div>

                        <div class="col-md-6">
                            <h6>Not Included</h6>

                            <div id="excluded-wrapper">
                                @foreach ($excluded as $i => $item)
                                    <div class="repeat-box">
                                        <div class="row">
                                            <div class="col-md-10 mb-2">
                                                <input type="text" name="excluded[{{ $i }}][title]"
                                                    class="form-control" value="{{ $item['title'] ?? '' }}">
                                            </div>

                                            <div class="col-md-2 mb-2">
                                                <button type="button"
                                                    class="btn btn-danger w-100 remove-btn js-remove">حذف</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-light mb-3" onclick="addExcluded()">+ إضافة
                                بند</button>
                        </div>
                    </div>

                    <div class="section-title">Pricing & Packages</div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">السعر يبدأ من</label>
                            <input type="number" step="0.01" name="start_from_price" class="form-control"
                                value="{{ old('start_from_price') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">سعر المقارنة</label>
                            <input type="number" step="0.01" name="compare_price" class="form-control"
                                value="{{ old('compare_price') }}">
                        </div>
                    </div>

                    <div id="prices-wrapper">
                        @foreach ($prices as $i => $price)
                            <div class="repeat-box">
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">المدة</label>
                                        <input type="text" name="prices[{{ $i }}][duration]"
                                            class="form-control" value="{{ $price['duration'] ?? '' }}">
                                    </div>

                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">الموسم</label>
                                        <input type="text" name="prices[{{ $i }}][season]"
                                            class="form-control" value="{{ $price['season'] ?? '' }}">
                                    </div>

                                    <div class="col-md-2 mb-2">
                                        <label class="form-label">From</label>
                                        <input type="number" step="0.01"
                                            name="prices[{{ $i }}][from_price]" class="form-control"
                                            value="{{ $price['from_price'] ?? '' }}">
                                    </div>

                                    <div class="col-md-2 mb-2">
                                        <label class="form-label">Double</label>
                                        <input type="number" step="0.01"
                                            name="prices[{{ $i }}][double_price]" class="form-control"
                                            value="{{ $price['double_price'] ?? '' }}">
                                    </div>

                                    <div class="col-md-1 mb-2">
                                        <label class="form-label">Single</label>
                                        <input type="number" step="0.01"
                                            name="prices[{{ $i }}][single_price]" class="form-control"
                                            value="{{ $price['single_price'] ?? '' }}">
                                    </div>

                                    <div class="col-md-1 mb-2">
                                        <label class="form-label">حذف</label>
                                        <button type="button"
                                            class="btn btn-danger w-100 remove-btn js-remove">X</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-light" onclick="addPrice()">+ إضافة سعر</button>

                    <div class="mt-3">
                        <label class="form-label">ملاحظات الأسعار</label>
                        <textarea name="pricing_information" rows="3" class="form-control">{{ old('pricing_information') }}</textarea>
                    </div>

                    <div class="section-title">سياسة الأطفال والشروط</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">سياسة الأطفال</label>
                            <textarea name="children_policy" rows="6" class="form-control">{{ old('children_policy') }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">سياسة الاستلام والتوصيل</label>
                            <textarea name="pickup_policy" rows="6" class="form-control">{{ old('pickup_policy') }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">سياسة الإلغاء</label>
                            <textarea name="cancellation_policy" rows="4" class="form-control">{{ old('cancellation_policy') }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">الشروط والأحكام</label>
                            <textarea name="terms_conditions" rows="4" class="form-control">{{ old('terms_conditions') }}</textarea>
                        </div>
                    </div>

                    <div class="section-title">المشاركون والتقييم</div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">الحد الأدنى للمشاركين</label>
                            <input type="number" name="min_participants" class="form-control"
                                value="{{ old('min_participants') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">الحد الأقصى للمشاركين</label>
                            <input type="number" name="max_participants" class="form-control"
                                value="{{ old('max_participants') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">أيام الحجز المسبق</label>
                            <input type="number" name="booking_lead_days" class="form-control"
                                value="{{ old('booking_lead_days') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">التقييم</label>
                            <input type="number" step="0.01" name="rating_avg" class="form-control"
                                value="{{ old('rating_avg') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">عدد المراجعات</label>
                            <input type="number" name="reviews_count" class="form-control"
                                value="{{ old('reviews_count') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">مستوى الصعوبة</label>
                            <select name="difficulty_level" class="form-select">
                                <option value="">اختر المستوى</option>
                                <option value="easy" {{ old('difficulty_level') == 'easy' ? 'selected' : '' }}>Easy
                                </option>
                                <option value="moderate" {{ old('difficulty_level') == 'moderate' ? 'selected' : '' }}>
                                    Moderate</option>
                                <option value="hard" {{ old('difficulty_level') == 'hard' ? 'selected' : '' }}>Hard
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">رابط الفيديو</label>
                            <input type="text" name="video_url" class="form-control" value="{{ old('video_url') }}">
                        </div>
                    </div>

                    <div class="section-title">النشر والإعدادات</div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">تاريخ النشر</label>
                            <input type="date" name="published_at" class="form-control"
                                value="{{ old('published_at') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">الترتيب</label>
                            <input type="number" name="sort_order" class="form-control"
                                value="{{ old('sort_order') }}">
                        </div>
                    </div>

                    <div class="mb-3 d-flex flex-wrap gap-4">
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

                    <div class="section-title">SEO</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">عنوان SEO</label>
                            <input type="text" name="seo_title" class="form-control" value="{{ old('seo_title') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Breadcrumb Title</label>
                            <input type="text" name="breadcrumb_title" class="form-control"
                                value="{{ old('breadcrumb_title') }}">
                        </div>

                        <div class="col-md-8 mb-3">
                            <label class="form-label">وصف SEO</label>
                            <textarea name="seo_description" rows="3" class="form-control">{{ old('seo_description') }}</textarea>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Canonical URL</label>
                            <input type="text" name="canonical_url" class="form-control"
                                value="{{ old('canonical_url') }}">
                        </div>
                    </div>

                    <div class="sticky-actions d-flex gap-2">
                        <button class="btn btn-primary" type="submit">حفظ الرحلة</button>
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
                if (!destinationSelector || !primaryCountryInput) return;

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

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('js-remove')) {
                    const box = e.target.closest('.repeat-box');
                    if (box) box.remove();
                }
            });
        });

        let facilityIndex = {{ count($facilities ?? []) }};
        let itineraryIndex = {{ count($itinerary ?? []) }};
        let includedIndex = {{ count($included ?? []) }};
        let excludedIndex = {{ count($excluded ?? []) }};
        let priceIndex = {{ count($prices ?? []) }};

        function addFacility() {
            document.getElementById('facilities-wrapper').insertAdjacentHTML('beforeend', `
            <div class="repeat-box">
                <div class="row">
                    <div class="col-md-10 mb-2">
                        <input type="text" name="facilities[${facilityIndex}][title]" class="form-control" placeholder="Facility">
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="button" class="btn btn-danger w-100 remove-btn js-remove">حذف</button>
                    </div>
                </div>
            </div>
        `);

            facilityIndex++;
        }

        function addItinerary() {
            document.getElementById('itinerary-wrapper').insertAdjacentHTML('beforeend', `
            <div class="repeat-box">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label class="form-label">نوع البرنامج</label>
                        <input type="text" name="itinerary[${itineraryIndex}][duration]" class="form-control">
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="form-label">رقم اليوم</label>
                        <input type="number" name="itinerary[${itineraryIndex}][day_number]" class="form-control">
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="form-label">عنوان اليوم</label>
                        <input type="text" name="itinerary[${itineraryIndex}][title]" class="form-control">
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="form-label">حذف</label>
                        <button type="button" class="btn btn-danger w-100 remove-btn js-remove">حذف</button>
                    </div>

                    <div class="col-md-8 mb-2">
                        <label class="form-label">تفاصيل اليوم</label>
                        <textarea name="itinerary[${itineraryIndex}][description]" rows="4" class="form-control"></textarea>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="form-label">الوجبات</label>
                        <input type="text" name="itinerary[${itineraryIndex}][meals]" class="form-control" placeholder="Breakfast, Lunch, Dinner">
                    </div>
                </div>
            </div>
        `);

            itineraryIndex++;
        }

        function addIncluded() {
            document.getElementById('included-wrapper').insertAdjacentHTML('beforeend', `
            <div class="repeat-box">
                <div class="row">
                    <div class="col-md-10 mb-2">
                        <input type="text" name="included[${includedIndex}][title]" class="form-control" placeholder="Included item">
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="button" class="btn btn-danger w-100 remove-btn js-remove">حذف</button>
                    </div>
                </div>
            </div>
        `);

            includedIndex++;
        }

        function addExcluded() {
            document.getElementById('excluded-wrapper').insertAdjacentHTML('beforeend', `
            <div class="repeat-box">
                <div class="row">
                    <div class="col-md-10 mb-2">
                        <input type="text" name="excluded[${excludedIndex}][title]" class="form-control" placeholder="Excluded item">
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="button" class="btn btn-danger w-100 remove-btn js-remove">حذف</button>
                    </div>
                </div>
            </div>
        `);

            excludedIndex++;
        }

        function addPrice() {
            document.getElementById('prices-wrapper').insertAdjacentHTML('beforeend', `
            <div class="repeat-box">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <label class="form-label">المدة</label>
                        <input type="text" name="prices[${priceIndex}][duration]" class="form-control">
                    </div>

                    <div class="col-md-3 mb-2">
                        <label class="form-label">الموسم</label>
                        <input type="text" name="prices[${priceIndex}][season]" class="form-control">
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="form-label">From</label>
                        <input type="number" step="0.01" name="prices[${priceIndex}][from_price]" class="form-control">
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="form-label">Double</label>
                        <input type="number" step="0.01" name="prices[${priceIndex}][double_price]" class="form-control">
                    </div>

                    <div class="col-md-1 mb-2">
                        <label class="form-label">Single</label>
                        <input type="number" step="0.01" name="prices[${priceIndex}][single_price]" class="form-control">
                    </div>

                    <div class="col-md-1 mb-2">
                        <label class="form-label">حذف</label>
                        <button type="button" class="btn btn-danger w-100 remove-btn js-remove">X</button>
                    </div>
                </div>
            </div>
        `);

            priceIndex++;
        }
    </script>
    <script>
        function toggleDuration() {
            let type = document.querySelector('input[name="duration_type"]:checked').value;

            if (type === 'days') {
                document.getElementById('daysFields').classList.remove('d-none');
                document.getElementById('hoursFields').classList.add('d-none');
            } else {
                document.getElementById('daysFields').classList.add('d-none');
                document.getElementById('hoursFields').classList.remove('d-none');
            }
        }
    </script>
@endsection
