@extends('admin.layout.master')

@section('title', 'تعديل رحلة')

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

        .current-image {
            width: 90px;
            height: 70px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid var(--dark-border);
            margin-top: 8px;
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
    </style>
@endsection

@section('content')
    @php
        $packageTitle = adminTrans($package->title ?? ($package->name ?? ''));

        $facilities = old(
            'facilities',
            isset($package->facilities)
                ? $package->facilities
                    ->map(
                        fn($item) => [
                            'title' => $item->title,
                            'sort_order' => $item->sort_order ?? 0,
                        ],
                    )
                    ->toArray()
                : [],
        );

        $itinerary = old(
            'itinerary',
            isset($package->itineraries)
                ? $package->itineraries
                    ->map(
                        fn($item) => [
                            'id' => $item->id,
                            'duration' => $item->duration ?? '',
                            'day_number' => $item->day_number,
                            'title' => $item->title,
                            'description' => $item->description,
                            'meals_breakfast' => $item->meals_breakfast ?? false,
                            'meals_lunch' => $item->meals_lunch ?? false,
                            'meals_dinner' => $item->meals_dinner ?? false,
                        ],
                    )
                    ->toArray()
                : [],
        );

        $included = old(
            'included',
            isset($package->inclusions)
                ? $package->inclusions
                    ->where('type', 'included')
                    ->map(
                        fn($item) => [
                            'id' => $item->id,
                            'title' => $item->title,
                            'sort_order' => $item->sort_order ?? 0,
                        ],
                    )
                    ->values()
                    ->toArray()
                : [],
        );

        $excluded = old(
            'excluded',
            isset($package->inclusions)
                ? $package->inclusions
                    ->where('type', 'excluded')
                    ->map(
                        fn($item) => [
                            'id' => $item->id,
                            'title' => $item->title,
                            'sort_order' => $item->sort_order ?? 0,
                        ],
                    )
                    ->values()
                    ->toArray()
                : [],
        );

        $prices = old(
            'prices',
            isset($package->prices)
                ? $package->prices
                    ->map(
                        fn($item) => [
                            'id' => $item->id,
                            'label' => $item->label,
                            'season_name' => $item->season_name,
                            'price_type' => $item->price_type,
                            'room_type' => $item->room_type,
                            'amount' => $item->amount,
                            'currency_id' => $item->currency_id,
                            'valid_from' => $item->valid_from,
                            'valid_to' => $item->valid_to,
                            'notes' => $item->notes,
                        ],
                    )
                    ->toArray()
                : [],
        );

        $galleryImages = old('gallery_images', $package->gallery_images ?? []);
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
                <li class="breadcrumb-item active">تعديل رحلة</li>
            </ol>
        </nav>

        <div class="main-card">
            <div class="main-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-1">تعديل رحلة</h5>
                    <small class="opacity-75">{{ $packageTitle }}</small>
                </div>

                <a href="{{ route('admin.packages.index') }}" class="btn btn-light">
                    رجوع
                </a>
            </div>

            <div class="form-body">
                <form action="{{ route('admin.packages.update', $package) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- البيانات الأساسية --}}
                    <div class="section-title">البيانات الأساسية</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">عنوان الرحلة</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', adminTrans($package->title ?? ($package->name ?? ''))) }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">العنوان الفرعي</label>
                            <input type="text" name="subtitle"
                                class="form-control @error('subtitle') is-invalid @enderror"
                                value="{{ old('subtitle', adminTrans($package->subtitle ?? '')) }}">
                            @error('subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                                value="{{ old('slug', $package->slug) }}">
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
                                        {{ old('category_id', $package->category_id) == $category->id ? 'selected' : '' }}>
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
                                        {{ old('destination_id', $package->destination_id ?? null) == $destination->id ? 'selected' : '' }}>
                                        {{ adminTrans($destination->name) }}
                                    </option>
                                @endforeach
                            </select>

                            <input type="hidden" name="primary_country_id" id="primary_country_id"
                                value="{{ old('primary_country_id', $package->primary_country_id ?? '') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">نوع الرحلة</label>
                            <select name="package_type" class="form-select @error('package_type') is-invalid @enderror">
                                <option value="travel_package"
                                    {{ old('package_type', $package->package_type) == 'travel_package' ? 'selected' : '' }}>
                                    Travel Package</option>
                                <option value="nile_cruise"
                                    {{ old('package_type', $package->package_type) == 'nile_cruise' ? 'selected' : '' }}>
                                    Nile Cruise</option>
                                <option value="day_tour"
                                    {{ old('package_type', $package->package_type) == 'day_tour' ? 'selected' : '' }}>Day
                                    Tour</option>
                                <option value="shore_excursion"
                                    {{ old('package_type', $package->package_type) == 'shore_excursion' ? 'selected' : '' }}>
                                    Shore Excursion</option>
                                <option value="tailor_made"
                                    {{ old('package_type', $package->package_type) == 'tailor_made' ? 'selected' : '' }}>
                                    Tailor Made</option>
                            </select>
                            @error('package_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">نوع الجولة</label>
                            <select name="tour_type" class="form-select @error('tour_type') is-invalid @enderror">
                                <option value="private"
                                    {{ old('tour_type', $package->tour_type) == 'private' ? 'selected' : '' }}>Private
                                </option>
                                <option value="group"
                                    {{ old('tour_type', $package->tour_type) == 'group' ? 'selected' : '' }}>Small Group
                                    Tour</option>
                                <option value="shared"
                                    {{ old('tour_type', $package->tour_type) == 'shared' ? 'selected' : '' }}>Shared
                                </option>
                                <option value="custom"
                                    {{ old('tour_type', $package->tour_type) == 'custom' ? 'selected' : '' }}>Custom
                                </option>
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
                                        {{ old('currency_id', $package->currency_id) == $currency->id ? 'selected' : '' }}>
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
                                <option value="request"
                                    {{ old('booking_mode', $package->booking_mode) == 'request' ? 'selected' : '' }}>
                                    Request</option>
                                <option value="instant"
                                    {{ old('booking_mode', $package->booking_mode) == 'instant' ? 'selected' : '' }}>
                                    Instant</option>
                            </select>
                            @error('booking_mode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- الوصف --}}
                    <div class="section-title">النصوص والوصف</div>

                    <div class="mb-3">
                        <label class="form-label">وصف مختصر</label>
                        <textarea name="short_description" rows="3" class="form-control">{{ old('short_description', adminTrans($package->short_description ?? '')) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الوصف الكامل</label>
                        <textarea name="description" rows="7" class="form-control">{{ old('description', adminTrans($package->description ?? '')) }}</textarea>
                    </div>

                    {{-- الصور --}}
                    <div class="section-title">الصور والمعرض</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الصورة الرئيسية</label>
                            <input type="file" name="featured_image" class="form-control">

                            @if (!empty($package->featured_image))
                                <img src="{{ asset($package->featured_image) }}" class="current-image" alt="featured">
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">صور المعرض</label>
                            <input type="file" name="gallery_images[]" class="form-control" multiple>

                            @if (is_array($galleryImages) && count($galleryImages))
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach ($galleryImages as $img)
                                        <img src="{{ asset($img) }}" class="current-image" alt="gallery">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- المدة والمسار --}}
                    <div class="section-title">المدة والمسار</div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">عدد الأيام</label>
                            <input type="number" name="duration_days" class="form-control"
                                value="{{ old('duration_days', $package->duration_days) }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">عدد الليالي</label>
                            <input type="number" name="duration_nights" class="form-control"
                                value="{{ old('duration_nights', $package->duration_nights) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">نص المدة المعروض</label>
                            <input type="text" name="duration_text" class="form-control"
                                value="{{ old('duration_text', $package->duration_text ?? '') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">الجدول</label>
                            <input type="text" name="schedule_text" class="form-control"
                                value="{{ old('schedule_text', adminTrans($package->schedule_text) ?? '') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">المسار</label>
                            <input type="text" name="route_text" class="form-control"
                                value="{{ old('route_text', $package->route_text ?? '') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">مكان الاستلام</label>
                            <input type="text" name="pickup_location" class="form-control"
                                value="{{ old('pickup_location', adminTrans($package->pickup_location) ?? '') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">مكان الانتهاء</label>
                            <input type="text" name="dropoff_location" class="form-control"
                                value="{{ old('dropoff_location', adminTrans($package->dropoff_location) ?? '') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">الوجهات</label>
                            <input type="text" name="destinations_text" class="form-control"
                                value="{{ old('destinations_text', adminTrans($package->destinations_text) ?? '') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">ملخص الموقع</label>
                            <input type="text" name="location_summary" class="form-control"
                                value="{{ old('location_summary', adminTrans($package->location_summary) ?? '') }}">
                        </div>
                    </div>

                    {{-- المرافق --}}
                    <div class="section-title">مرافق الرحلة / Cruise Facilities</div>

                    <div id="facilities-wrapper">
                        @foreach ($facilities as $i => $facility)
                            <div class="repeat-box">
                                <div class="row">
                                    <div class="col-md-9 mb-2">
                                        <input type="text" name="facilities[{{ $i }}][title]"
                                            class="form-control" value="{{ $facility['title'] ?? '' }}"
                                            placeholder="Facility">
                                    </div>

                                    <div class="col-md-2 mb-2">
                                        <input type="number" name="facilities[{{ $i }}][sort_order]"
                                            class="form-control" value="{{ $facility['sort_order'] ?? $i }}">
                                    </div>

                                    <div class="col-md-1 mb-2">
                                        <button type="button"
                                            class="btn btn-danger w-100 remove-btn js-remove">X</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-light" onclick="addFacility()">+ إضافة مرفق</button>

                    {{-- البرنامج --}}
                    <div class="section-title">برنامج الرحلة / Itinerary</div>

                    <div id="itinerary-wrapper">
                        @foreach ($itinerary as $i => $day)
                            <div class="repeat-box">
                                <input type="hidden" name="itinerary[{{ $i }}][id]"
                                    value="{{ $day['id'] ?? '' }}">

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
                                        <label class="form-label d-block">الوجبات</label>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox"
                                                name="itinerary[{{ $i }}][meals_breakfast]" value="1"
                                                {{ !empty($day['meals_breakfast']) ? 'checked' : '' }}>
                                            <label class="form-check-label">Breakfast</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox"
                                                name="itinerary[{{ $i }}][meals_lunch]" value="1"
                                                {{ !empty($day['meals_lunch']) ? 'checked' : '' }}>
                                            <label class="form-check-label">Lunch</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox"
                                                name="itinerary[{{ $i }}][meals_dinner]" value="1"
                                                {{ !empty($day['meals_dinner']) ? 'checked' : '' }}>
                                            <label class="form-check-label">Dinner</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-light" onclick="addItinerary()">+ إضافة يوم</button>

                    {{-- شامل وغير شامل --}}
                    <div class="section-title">المشمول وغير المشمول</div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Included in Your Journey</h6>

                            <div id="included-wrapper">
                                @foreach ($included as $i => $item)
                                    <div class="repeat-box">
                                        <input type="hidden" name="included[{{ $i }}][id]"
                                            value="{{ $item['id'] ?? '' }}">

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
                                        <input type="hidden" name="excluded[{{ $i }}][id]"
                                            value="{{ $item['id'] ?? '' }}">

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

                    {{-- الأسعار --}}
                    <div class="section-title">Pricing & Packages</div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">السعر يبدأ من</label>
                            <input type="number" step="0.01" name="start_from_price" class="form-control"
                                value="{{ old('start_from_price', $package->start_from_price ?? ($package->base_price ?? '')) }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">سعر المقارنة</label>
                            <input type="number" step="0.01" name="compare_price" class="form-control"
                                value="{{ old('compare_price', $package->compare_price ?? '') }}">
                        </div>
                    </div>

                    <div id="prices-wrapper">
                        @foreach ($prices as $i => $price)
                            <div class="repeat-box">
                                <input type="hidden" name="prices[{{ $i }}][id]"
                                    value="{{ $price['id'] ?? '' }}">

                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">المدة / Label</label>
                                        <input type="text" name="prices[{{ $i }}][label]"
                                            class="form-control" value="{{ $price['label'] ?? '' }}">
                                    </div>

                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">الموسم</label>
                                        <input type="text" name="prices[{{ $i }}][season_name]"
                                            class="form-control" value="{{ $price['season_name'] ?? '' }}">
                                    </div>

                                    <div class="col-md-2 mb-2">
                                        <label class="form-label">نوع السعر</label>
                                        <select name="prices[{{ $i }}][price_type]" class="form-select">
                                            <option value="from"
                                                {{ ($price['price_type'] ?? '') == 'from' ? 'selected' : '' }}>From
                                            </option>
                                            <option value="fixed"
                                                {{ ($price['price_type'] ?? '') == 'fixed' ? 'selected' : '' }}>Fixed
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 mb-2">
                                        <label class="form-label">الغرفة</label>
                                        <select name="prices[{{ $i }}][room_type]" class="form-select">
                                            <option value="">N/A</option>
                                            <option value="double"
                                                {{ ($price['room_type'] ?? '') == 'double' ? 'selected' : '' }}>Double
                                            </option>
                                            <option value="single"
                                                {{ ($price['room_type'] ?? '') == 'single' ? 'selected' : '' }}>Single
                                            </option>
                                            <option value="triple"
                                                {{ ($price['room_type'] ?? '') == 'triple' ? 'selected' : '' }}>Triple
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 mb-2">
                                        <label class="form-label">السعر</label>
                                        <input type="number" step="0.01" name="prices[{{ $i }}][amount]"
                                            class="form-control" value="{{ $price['amount'] ?? '' }}">
                                    </div>

                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">العملة</label>
                                        <select name="prices[{{ $i }}][currency_id]" class="form-select">
                                            <option value="">اختر العملة</option>
                                            @foreach ($currencies ?? collect() as $currency)
                                                <option value="{{ $currency->id }}"
                                                    {{ ($price['currency_id'] ?? $package->currency_id) == $currency->id ? 'selected' : '' }}>
                                                    {{ $currency->code }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">من تاريخ</label>
                                        <input type="date" name="prices[{{ $i }}][valid_from]"
                                            class="form-control" value="{{ $price['valid_from'] ?? '' }}">
                                    </div>

                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">إلى تاريخ</label>
                                        <input type="date" name="prices[{{ $i }}][valid_to]"
                                            class="form-control" value="{{ $price['valid_to'] ?? '' }}">
                                    </div>

                                    <div class="col-md-2 mb-2">
                                        <label class="form-label">حذف</label>
                                        <button type="button"
                                            class="btn btn-danger w-100 remove-btn js-remove">حذف</button>
                                    </div>

                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">ملاحظات</label>
                                        <textarea name="prices[{{ $i }}][notes]" rows="2" class="form-control">{{ $price['notes'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-light" onclick="addPrice()">+ إضافة سعر</button>

                    <div class="mt-3">
                        <label class="form-label">ملاحظات الأسعار</label>
                        <textarea name="pricing_information" rows="3" class="form-control">{{ old('pricing_information', $package->pricing_information ?? '') }}</textarea>
                    </div>

                    {{-- الأطفال والسياسات --}}
                    <div class="section-title">سياسة الأطفال والشروط</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">سياسة الأطفال</label>
                            <textarea name="children_policy" rows="6" class="form-control">{{ old('children_policy', $package->children_policy ?? '') }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">سياسة الاستلام والتوصيل</label>
                            <textarea name="pickup_policy" rows="6" class="form-control">{{ old('pickup_policy', $package->pickup_policy ?? '') }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">سياسة الإلغاء</label>
                            <textarea name="cancellation_policy" rows="4" class="form-control">{{ old('cancellation_policy', adminTrans($package->cancellation_policy) ?? '') }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">الشروط والأحكام</label>
                            <textarea name="terms_conditions" rows="4" class="form-control">{{ old('terms_conditions', adminTrans($package->terms_conditions) ?? '') }}</textarea>
                        </div>
                    </div>

                    {{-- المشاركون والتقييم --}}
                    <div class="section-title">المشاركون والتقييم</div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">الحد الأدنى للمشاركين</label>
                            <input type="number" name="min_participants" class="form-control"
                                value="{{ old('min_participants', $package->min_participants ?? '') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">الحد الأقصى للمشاركين</label>
                            <input type="number" name="max_participants" class="form-control"
                                value="{{ old('max_participants', $package->max_participants ?? '') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">أيام الحجز المسبق</label>
                            <input type="number" name="booking_lead_days" class="form-control"
                                value="{{ old('booking_lead_days', $package->booking_lead_days ?? '') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">التقييم</label>
                            <input type="number" step="0.01" name="rating_avg" class="form-control"
                                value="{{ old('rating_avg', $package->rating_avg ?? '') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">عدد المراجعات</label>
                            <input type="number" name="reviews_count" class="form-control"
                                value="{{ old('reviews_count', $package->reviews_count ?? '') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">مستوى الصعوبة</label>
                            <select name="difficulty_level" class="form-select">
                                <option value="easy"
                                    {{ old('difficulty_level', $package->difficulty_level) == 'easy' ? 'selected' : '' }}>
                                    Easy</option>
                                <option value="moderate"
                                    {{ old('difficulty_level', $package->difficulty_level) == 'moderate' ? 'selected' : '' }}>
                                    Moderate</option>
                                <option value="hard"
                                    {{ old('difficulty_level', $package->difficulty_level) == 'hard' ? 'selected' : '' }}>
                                    Hard</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">رابط الفيديو</label>
                            <input type="text" name="video_url" class="form-control"
                                value="{{ old('video_url', $package->video_url ?? '') }}">
                        </div>
                    </div>

                    {{-- النشر --}}
                    <div class="section-title">النشر والإعدادات</div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">تاريخ النشر</label>
                            <input type="date" name="published_at" class="form-control"
                                value="{{ old('published_at', optional($package->published_at)->format('Y-m-d')) }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">الترتيب</label>
                            <input type="number" name="sort_order" class="form-control"
                                value="{{ old('sort_order', $package->sort_order ?? 0) }}">
                        </div>
                    </div>

                    <div class="mb-3 d-flex flex-wrap gap-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" value="1" name="is_active"
                                id="is_active" {{ old('is_active', $package->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">مفعلة</label>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" value="1" name="is_featured"
                                id="is_featured"
                                {{ old('is_featured', $package->is_featured ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">مميزة</label>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" value="1" name="is_best_seller"
                                id="is_best_seller"
                                {{ old('is_best_seller', $package->is_best_seller ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_best_seller">الأكثر مبيعًا</label>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" value="1" name="is_ultra_luxury"
                                id="is_ultra_luxury"
                                {{ old('is_ultra_luxury', $package->is_ultra_luxury ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_ultra_luxury">فاخرة جدًا</label>
                        </div>
                    </div>

                    {{-- SEO --}}
                    <div class="section-title">SEO</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">عنوان SEO</label>
                            <input type="text" name="seo_title" class="form-control"
                                value="{{ old('seo_title', adminTrans($package->seo_title ?? '')) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Breadcrumb Title</label>
                            <input type="text" name="breadcrumb_title" class="form-control"
                                value="{{ old('breadcrumb_title', adminTrans($package->breadcrumb_title ?? '')) }}">
                        </div>

                        <div class="col-md-8 mb-3">
                            <label class="form-label">وصف SEO</label>
                            <textarea name="seo_description" rows="3" class="form-control">{{ old('seo_description', adminTrans($package->seo_description ?? '')) }}</textarea>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Canonical URL</label>
                            <input type="text" name="canonical_url" class="form-control"
                                value="{{ old('canonical_url', $package->canonical_url ?? '') }}">
                        </div>
                    </div>

                    <div class="sticky-actions d-flex gap-2">
                        <button class="btn btn-primary" type="submit">حفظ التعديلات</button>
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
                    <div class="col-md-9 mb-2">
                        <input type="text" name="facilities[${facilityIndex}][title]" class="form-control" placeholder="Facility">
                    </div>

                    <div class="col-md-2 mb-2">
                        <input type="number" name="facilities[${facilityIndex}][sort_order]" class="form-control" value="${facilityIndex}">
                    </div>

                    <div class="col-md-1 mb-2">
                        <button type="button" class="btn btn-danger w-100 remove-btn js-remove">X</button>
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
                        <input type="text" name="itinerary[${itineraryIndex}][duration]" class="form-control" placeholder="4 Days - Aswan / Luxor">
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
                        <label class="form-label d-block">الوجبات</label>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="itinerary[${itineraryIndex}][meals_breakfast]" value="1">
                            <label class="form-check-label">Breakfast</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="itinerary[${itineraryIndex}][meals_lunch]" value="1">
                            <label class="form-check-label">Lunch</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="itinerary[${itineraryIndex}][meals_dinner]" value="1">
                            <label class="form-check-label">Dinner</label>
                        </div>
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
                        <label class="form-label">المدة / Label</label>
                        <input type="text" name="prices[${priceIndex}][label]" class="form-control" placeholder="3 Nights 4 Days">
                    </div>

                    <div class="col-md-3 mb-2">
                        <label class="form-label">الموسم</label>
                        <input type="text" name="prices[${priceIndex}][season_name]" class="form-control" placeholder="May to August">
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="form-label">نوع السعر</label>
                        <select name="prices[${priceIndex}][price_type]" class="form-select">
                            <option value="from">From</option>
                            <option value="fixed">Fixed</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="form-label">الغرفة</label>
                        <select name="prices[${priceIndex}][room_type]" class="form-select">
                            <option value="">N/A</option>
                            <option value="double">Double</option>
                            <option value="single">Single</option>
                            <option value="triple">Triple</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="form-label">السعر</label>
                        <input type="number" step="0.01" name="prices[${priceIndex}][amount]" class="form-control">
                    </div>

                    <div class="col-md-3 mb-2">
                        <label class="form-label">العملة</label>
                        <select name="prices[${priceIndex}][currency_id]" class="form-select">
                            <option value="">اختر العملة</option>
                            @foreach ($currencies ?? collect() as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label class="form-label">من تاريخ</label>
                        <input type="date" name="prices[${priceIndex}][valid_from]" class="form-control">
                    </div>

                    <div class="col-md-3 mb-2">
                        <label class="form-label">إلى تاريخ</label>
                        <input type="date" name="prices[${priceIndex}][valid_to]" class="form-control">
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="form-label">حذف</label>
                        <button type="button" class="btn btn-danger w-100 remove-btn js-remove">حذف</button>
                    </div>

                    <div class="col-md-12 mb-2">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="prices[${priceIndex}][notes]" rows="2" class="form-control"></textarea>
                    </div>
                </div>
            </div>
        `);

            priceIndex++;
        }
    </script>
@endsection
