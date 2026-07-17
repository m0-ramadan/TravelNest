@include('admin.i18n.locale')
@extends('admin.layout.master')

@section('title', admin_t('تعديل رحلة'))

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

        .attractions-picker-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 12px;
            max-height: 430px;
            overflow-y: auto;
            padding: 4px;
        }

        .attraction-choice {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px;
            border: 1px solid var(--dark-border);
            border-radius: 14px;
            background: rgba(255, 255, 255, .035);
            cursor: pointer;
        }

        .attraction-choice:has(input:checked) {
            border-color: var(--primary-color);
            background: rgba(105, 108, 255, .16);
        }

        .attraction-choice input {
            width: 18px;
            height: 18px;
            flex: 0 0 18px;
            accent-color: var(--primary-color);
        }

        .attraction-choice-copy {
            min-width: 0;
        }

        .attraction-choice-copy strong,
        .attraction-choice-copy small {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .attraction-choice-copy small {
            margin-top: 4px;
            color: rgba(255, 255, 255, .6);
        }

        .attraction-choice.is-filtered-out {
            display: none;
        }

        .itinerary-sequence {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 12px;
            margin-bottom: 14px;
            border-radius: 999px;
            background: rgba(105, 108, 255, .18);
            color: #dddfff;
            font-size: 13px;
            font-weight: 700;
        }

        .d-none-force {
            display: none !important;
        }
    </style>
@endsection

@section('content')
    @php
        $packageTitle = adminTrans($package->title ?? ($package->name ?? ''));
        $durationType = old(
            'duration_type',
            $package->duration_type ?? (!empty($package->duration_hours) ? 'hours' : 'days'),
        );

        $selectedAttractionIds = collect(
            old(
                'attraction_ids',
                $package->packageAttractions?->pluck('attraction_id')->all() ?? [],
            ),
        )->map(fn($id) => (int) $id)->all();

        $itinerary = old(
            'itinerary',
            isset($package->itineraries)
                ? $package->itineraries
                    ->map(
                        fn($item) => [
                            'id' => $item->id,
                            'duration' => $item->duration ?? '',
                            'day_number' => $item->day_number,
                            'title' => adminTrans($item->title),
                            'description' => adminTrans($item->description),
                            'meals_breakfast' => $item->meals_breakfast ?? false,
                            'meals_lunch' => $item->meals_lunch ?? false,
                            'meals_dinner' => $item->meals_dinner ?? false,
                        ],
                    )
                    ->toArray()
                : [],
        );

        if (!is_array($itinerary) || $itinerary === []) {
            $itinerary = [['day_number' => 1]];
        }

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

        $faqItems = old('faq_json', $package->faq_json ?? []);

        $adultMinAge = old('adult_min_age', $package->adult_min_age ?? 12);
        $childMinAge = old('child_min_age', $package->child_min_age ?? 2);
        $childMaxAge = old('child_max_age', $package->child_max_age ?? 11);
        $infantMinAge = old('infant_min_age', $package->infant_min_age ?? 0);
        $infantMaxAge = old('infant_max_age', $package->infant_max_age ?? 1);

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
                            <label class="form-label">المدينة</label>
                            <select id="destination_selector" name="destination_id" class="form-select">
                                <option value="">اختر المدينة</option>
                                @foreach ($destinations ?? collect() as $destination)
                                    <option value="{{ $destination->id }}"
                                        data-country-id="{{ $destination->country_id }}"
                                        {{ old('destination_id', optional(optional($package->destination)->city)->id) == $destination->id ? 'selected' : '' }}>
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
                                <option value="custom"
                                    {{ old('package_type', $package->package_type) == 'custom' ? 'selected' : '' }}>
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
                        <div class="col-12 mb-3">
                            <label class="form-label d-block">Type Duration</label>
                            <div class="d-flex flex-wrap gap-4">
                                <label class="d-flex align-items-center gap-2">
                                    <input type="radio" name="duration_type" value="days"
                                        {{ $durationType === 'days' ? 'checked' : '' }}>
                                    <span>Days / Nights</span>
                                </label>
                                <label class="d-flex align-items-center gap-2">
                                    <input type="radio" name="duration_type" value="hours"
                                        {{ $durationType === 'hours' ? 'checked' : '' }}>
                                    <span>Hours</span>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3" id="daysFieldWrapper">
                            <label class="form-label">عدد الأيام</label>
                            <input type="number" name="duration_days" class="form-control"
                                value="{{ old('duration_days', $package->duration_days) }}">
                        </div>

                        <div class="col-md-3 mb-3" id="nightsFieldWrapper">
                            <label class="form-label">عدد الليالي</label>
                            <input type="number" name="duration_nights" class="form-control"
                                value="{{ old('duration_nights', $package->duration_nights) }}">
                        </div>

                        <div class="col-md-3 mb-3" id="hoursFieldWrapper">
                            <label class="form-label">عدد الساعات</label>
                            <input type="number" name="duration_hours" class="form-control"
                                value="{{ old('duration_hours', $package->duration_hours) }}">
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

                    <div class="section-title">Trip Facilities</div>
                    <p class="text-white-50 mb-3">Select the attractions associated with this trip.</p>

                    <div class="mb-3">
                        <input type="search" class="form-control" id="attractionSearch"
                            placeholder="Search attractions by name or city..." autocomplete="off">
                    </div>

                    <div class="attractions-picker-grid" id="attractionsPicker">
                        @forelse ($attractions ?? collect() as $attraction)
                            @php
                                $attractionName = adminTrans($attraction->name) ?: 'Attraction #' . $attraction->id;
                                $cityName = adminTrans($attraction->city?->name) ?: 'No city';
                                $searchText = mb_strtolower($attractionName . ' ' . $cityName);
                            @endphp
                            <label class="attraction-choice" data-attraction-choice
                                data-attraction-search="{{ $searchText }}">
                                <input type="checkbox" name="attraction_ids[]" value="{{ $attraction->id }}"
                                    {{ in_array((int) $attraction->id, $selectedAttractionIds, true) ? 'checked' : '' }}>
                                <span class="attraction-choice-copy">
                                    <strong>{{ $attractionName }}</strong>
                                    <small>{{ $cityName }}</small>
                                </span>
                            </label>
                        @empty
                            <div class="text-white-50">No active attractions are available.</div>
                        @endforelse
                    </div>

                    @error('attraction_ids')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                    @error('attraction_ids.*')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror

                    {{-- البرنامج --}}
                    <div class="section-title" id="itinerary-section-title">Daily Itinerary</div>
                    <p class="text-white-50" id="itinerary-section-copy">Add the content for each day in order.</p>

                    <div id="itinerary-wrapper">
                        @foreach ($itinerary as $i => $day)
                            <div class="repeat-box" data-itinerary-item>
                                <input type="hidden" name="itinerary[{{ $i }}][id]"
                                    value="{{ $day['id'] ?? '' }}">
                                <input type="hidden" name="itinerary[{{ $i }}][day_number]"
                                    value="{{ $i + 1 }}" data-itinerary-number-input>

                                <span class="itinerary-sequence">
                                    <span data-itinerary-unit>{{ $durationType === 'hours' ? 'Step' : 'Day' }}</span>
                                    <span data-itinerary-number>{{ $i + 1 }}</span>
                                </span>

                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label" data-itinerary-duration-label>Date / Day label</label>
                                        <input type="text" name="itinerary[{{ $i }}][duration]"
                                            class="form-control" value="{{ $day['duration'] ?? '' }}"
                                            data-itinerary-duration-input>
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label class="form-label" data-itinerary-title-label>Day title / Place</label>
                                        <input type="text" name="itinerary[{{ $i }}][title]"
                                            class="form-control" value="{{ $day['title'] ?? '' }}">
                                    </div>

                                    <div class="col-md-2 mb-2">
                                        <label class="form-label">حذف</label>
                                        <button type="button"
                                            class="btn btn-danger w-100 remove-btn js-remove">حذف</button>
                                    </div>

                                    <div class="col-md-8 mb-2">
                                        <label class="form-label" data-itinerary-details-label>Day details and activities</label>
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

                    <button type="button" class="btn btn-light" id="add-itinerary-btn">+ Add New Day</button>

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

                            <button type="button" class="btn btn-light mb-3" id="add-included-btn">+ إضافة
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

                            <button type="button" class="btn btn-light mb-3" id="add-excluded-btn">+ إضافة
                                بند</button>
                        </div>
                    </div>

                    {{-- الأسعار --}}
                    <div class="section-title">Pricing & Packages</div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">سعر البالغ</label>
                            <input id="adult_price" type="number" step="0.01" min="0" name="adult_price"
                                class="form-control" value="{{ old('adult_price', $package->adult_price ?? '') }}">
                            @error('adult_price')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">سعر الطفل</label>
                            <input id="child_price" type="number" step="0.01" min="0" name="child_price"
                                class="form-control" value="{{ old('child_price', $package->child_price ?? '') }}">
                            @error('child_price')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">سعر الرضيع</label>
                            <input id="infant_price" type="number" step="0.01" min="0" name="infant_price"
                                class="form-control" value="{{ old('infant_price', $package->infant_price ?? '') }}">
                            @error('infant_price')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">سعر المقارنة</label>
                            <input type="number" step="0.01" name="compare_price" class="form-control"
                                value="{{ old('compare_price', $package->compare_price ?? '') }}">
                        </div>
                    </div>

                    <div class="section-title">{{ __('trips.age_policy') }}</div>

                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label class="form-label">سن البالغ يبدأ من</label>
                            <input type="number" min="0" name="adult_min_age" class="form-control"
                                value="{{ $adultMinAge }}">
                            @error('adult_min_age')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">سن الطفل من</label>
                            <input type="number" min="0" name="child_min_age" class="form-control"
                                value="{{ $childMinAge }}">
                            @error('child_min_age')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">سن الطفل إلى</label>
                            <input type="number" min="0" name="child_max_age" class="form-control"
                                value="{{ $childMaxAge }}">
                            @error('child_max_age')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">سن الرضيع من</label>
                            <input type="number" min="0" name="infant_min_age" class="form-control"
                                value="{{ $infantMinAge }}">
                            @error('infant_min_age')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">سن الرضيع إلى</label>
                            <input type="number" min="0" name="infant_max_age" class="form-control"
                                value="{{ $infantMaxAge }}">
                            @error('infant_max_age')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div id="prices-wrapper" class="mt-3">
                        @foreach ($prices as $i => $price)
                            <div class="repeat-box price-item">
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

                    <button type="button" class="btn btn-light" id="add-price-btn">+ إضافة سعر</button>

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

                    <div class="section-title">FAQs</div>

                    <p class="text-white-50 mb-3">Add questions and answers for this trip to appear on the website.</p>

                    <div id="faq-wrapper">
                        @forelse ($faqItems as $i => $faq)
                            <div class="repeat-box faq-item">
                                <div class="row">
                                    <div class="col-md-5 mb-2">
                                        <label class="form-label">Question</label>
                                        <input type="text" name="faq_json[{{ $i }}][question]" class="form-control"
                                            value="{{ is_array($faq['question'] ?? null) ? ($faq['question'][app()->getLocale()] ?? $faq['question']['en'] ?? '') : ($faq['question'] ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Answer</label>
                                        <textarea name="faq_json[{{ $i }}][answer]" rows="3" class="form-control">{{ is_array($faq['answer'] ?? null) ? ($faq['answer'][app()->getLocale()] ?? $faq['answer']['en'] ?? '') : ($faq['answer'] ?? '') }}</textarea>
                                    </div>
                                    <div class="col-md-1 mb-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger w-100 remove-btn js-remove">Remove</button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-white-50 mb-3" id="faq-empty-state">No FAQs added yet.</div>
                        @endforelse
                    </div>

                    <button type="button" class="btn btn-light mb-3" id="add-faq-btn">+ Add FAQ</button>

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

            const attractionSearch = document.getElementById('attractionSearch');
            const attractionsPicker = document.getElementById('attractionsPicker');

            attractionSearch?.addEventListener('input', function() {
                const query = this.value.trim().toLocaleLowerCase();

                attractionsPicker?.querySelectorAll('[data-attraction-choice]').forEach(choice => {
                    const searchText = (choice.dataset.attractionSearch || '').toLocaleLowerCase();
                    choice.classList.toggle('is-filtered-out', query !== '' && !searchText.includes(query));
                });
            });

            document.addEventListener('click', function(e) {
                const removeButton = e.target.closest('.js-remove');
                if (!removeButton) return;

                const box = removeButton.closest('.repeat-box');
                if (box) {
                    box.remove();
                    syncFaqEmptyState();
                    renumberItineraryItems();
                }
            });

            document.getElementById('add-itinerary-btn')?.addEventListener('click', addItinerary);
            document.getElementById('add-included-btn')?.addEventListener('click', addIncluded);
            document.getElementById('add-excluded-btn')?.addEventListener('click', addExcluded);
            document.getElementById('add-price-btn')?.addEventListener('click', addPrice);
            document.getElementById('add-faq-btn')?.addEventListener('click', addFaq);

            document.querySelectorAll('input[name="duration_type"]').forEach(input => {
                input.addEventListener('change', updateItineraryMode);
            });

            syncFaqEmptyState();
            updateItineraryMode();
        });

        let itineraryIndex = {{ count($itinerary ?? []) }};
        let includedIndex = {{ count($included ?? []) }};
        let excludedIndex = {{ count($excluded ?? []) }};
        let priceIndex = {{ count($prices ?? []) }};
        let faqIndex = {{ count($faqItems ?? []) }};

        function syncFaqEmptyState() {
            const wrapper = document.getElementById('faq-wrapper');
            if (!wrapper) return;

            const hasItems = wrapper.querySelector('.faq-item');
            let emptyState = document.getElementById('faq-empty-state');

            if (hasItems && emptyState) {
                emptyState.remove();
            }

            if (!hasItems && !emptyState) {
                wrapper.insertAdjacentHTML('beforeend',
                    '<div class="text-white-50 mb-3" id="faq-empty-state">No FAQs added yet.</div>');
            }
        }

        function focusLastField(wrapperId) {
            const wrapper = document.getElementById(wrapperId);
            if (!wrapper) return;

            const lastField = wrapper.querySelector(
                '.repeat-box:last-child input, .repeat-box:last-child textarea, .repeat-box:last-child select'
            );

            if (lastField) {
                lastField.focus();
            }
        }

        function renumberItineraryItems() {
            document.querySelectorAll('[data-itinerary-item]').forEach((item, index) => {
                const number = index + 1;
                const numberLabel = item.querySelector('[data-itinerary-number]');
                const numberInput = item.querySelector('[data-itinerary-number-input]');

                if (numberLabel) numberLabel.textContent = number;
                if (numberInput) numberInput.value = number;
            });
        }

        function updateItineraryMode() {
            const type = document.querySelector('input[name="duration_type"]:checked')?.value || 'days';
            const isHourly = type === 'hours';

            document.getElementById('daysFieldWrapper')?.classList.toggle('d-none-force', isHourly);
            document.getElementById('nightsFieldWrapper')?.classList.toggle('d-none-force', isHourly);
            document.getElementById('hoursFieldWrapper')?.classList.toggle('d-none-force', !isHourly);

            const title = document.getElementById('itinerary-section-title');
            const copy = document.getElementById('itinerary-section-copy');
            const addButton = document.getElementById('add-itinerary-btn');

            if (title) title.textContent = isHourly ? 'Trip Steps' : 'Daily Itinerary';
            if (copy) {
                copy.textContent = isHourly
                    ? 'Add every trip step in order with its time and full details.'
                    : 'Add the content for each day in order.';
            }
            if (addButton) addButton.textContent = isHourly ? '+ Add New Step' : '+ Add New Day';

            document.querySelectorAll('[data-itinerary-item]').forEach(item => {
                item.querySelector('[data-itinerary-unit]').textContent = isHourly ? 'Step' : 'Day';
                item.querySelector('[data-itinerary-duration-label]').textContent = isHourly
                    ? 'Time / Duration'
                    : 'Date / Day label';
                item.querySelector('[data-itinerary-title-label]').textContent = isHourly
                    ? 'Step title / Place'
                    : 'Day title / Place';
                item.querySelector('[data-itinerary-details-label]').textContent = isHourly
                    ? 'Step details and activities'
                    : 'Day details and activities';

                const durationInput = item.querySelector('[data-itinerary-duration-input]');
                durationInput.placeholder = isHourly
                    ? 'Example: 09:00 AM - 10:30 AM'
                    : 'Optional date or day label';
            });

            renumberItineraryItems();
        }

        function addItinerary() {
            const isHourly = document.querySelector('input[name="duration_type"]:checked')?.value === 'hours';
            document.getElementById('itinerary-wrapper').insertAdjacentHTML('beforeend', `
                <div class="repeat-box" data-itinerary-item>
                    <input type="hidden" name="itinerary[${itineraryIndex}][day_number]" value="${itineraryIndex + 1}" data-itinerary-number-input>
                    <span class="itinerary-sequence">
                        <span data-itinerary-unit>${isHourly ? 'Step' : 'Day'}</span>
                        <span data-itinerary-number>${itineraryIndex + 1}</span>
                    </span>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label" data-itinerary-duration-label>${isHourly ? 'Time / Duration' : 'Date / Day label'}</label>
                            <input type="text" name="itinerary[${itineraryIndex}][duration]" class="form-control" data-itinerary-duration-input placeholder="${isHourly ? 'Example: 09:00 AM - 10:30 AM' : 'Optional date or day label'}">
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label" data-itinerary-title-label>${isHourly ? 'Step title / Place' : 'Day title / Place'}</label>
                            <input type="text" name="itinerary[${itineraryIndex}][title]" class="form-control">
                        </div>

                        <div class="col-md-2 mb-2">
                            <label class="form-label">حذف</label>
                            <button type="button" class="btn btn-danger w-100 remove-btn js-remove">حذف</button>
                        </div>

                        <div class="col-md-8 mb-2">
                            <label class="form-label" data-itinerary-details-label>${isHourly ? 'Step details and activities' : 'Day details and activities'}</label>
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
            updateItineraryMode();
            focusLastField('itinerary-wrapper');
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
            focusLastField('included-wrapper');
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
            focusLastField('excluded-wrapper');
        }

        function addPrice() {
            document.getElementById('prices-wrapper').insertAdjacentHTML('beforeend', `
                <div class="repeat-box price-item">
                    <div class="row">
                        <div class="col-md-10 mb-2">
                            <input type="text" name="prices[${priceIndex}][label]" class="form-control" placeholder="Price title">
                        </div>

                        <div class="col-md-2 mb-2">
                            <button type="button" class="btn btn-danger w-100 remove-btn js-remove">حذف</button>
                        </div>

                        <div class="col-md-4 mb-2">
                            <input type="text" name="prices[${priceIndex}][season_name]" class="form-control" placeholder="Season">
                        </div>

                        <div class="col-md-4 mb-2">
                            <input type="number" step="0.01" name="prices[${priceIndex}][amount]" class="form-control" placeholder="Amount">
                        </div>

                        <div class="col-md-4 mb-2">
                            <select name="prices[${priceIndex}][currency_id]" class="form-select">
                                <option value="">Currency</option>
                                @foreach ($currencies ?? collect() as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->code }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <select name="prices[${priceIndex}][price_type]" class="form-select">
                                <option value="from">From</option>
                                <option value="fixed">Fixed</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <select name="prices[${priceIndex}][room_type]" class="form-select">
                                <option value="">Room Type</option>
                                <option value="double">Double</option>
                                <option value="single">Single</option>
                                <option value="triple">Triple</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <input type="date" name="prices[${priceIndex}][valid_from]" class="form-control">
                        </div>

                        <div class="col-md-6 mb-2">
                            <input type="date" name="prices[${priceIndex}][valid_to]" class="form-control">
                        </div>

                        <div class="col-md-6 mb-2">
                            <textarea name="prices[${priceIndex}][notes]" rows="2" class="form-control" placeholder="Notes"></textarea>
                        </div>
                    </div>
                </div>
            `);

            priceIndex++;
            focusLastField('prices-wrapper');
        }

        function addFaq() {
            const emptyState = document.getElementById('faq-empty-state');
            if (emptyState) {
                emptyState.remove();
            }

            document.getElementById('faq-wrapper').insertAdjacentHTML('beforeend', `
                <div class="repeat-box faq-item">
                    <div class="row">
                        <div class="col-md-10 mb-2">
                            <input type="text" name="faq_json[${faqIndex}][question]" class="form-control" placeholder="Question">
                        </div>

                        <div class="col-md-2 mb-2">
                            <button type="button" class="btn btn-danger w-100 remove-btn js-remove">Remove</button>
                        </div>

                        <div class="col-md-12 mb-2">
                            <textarea name="faq_json[${faqIndex}][answer]" rows="3" class="form-control" placeholder="Answer"></textarea>
                        </div>
                    </div>
                </div>
            `);

            faqIndex++;
            focusLastField('faq-wrapper');
        }
    </script>
@endsection
