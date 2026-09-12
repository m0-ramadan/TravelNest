@include('admin.i18n.locale')
@extends('admin.layout.master')

@section('title', admin_t('Edit Shore Excursion'))

@section('css')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #0ea5e9 0%, #1e40af 100%);
            --dark-bg: #1e1e2d;
            --dark-card: #2b3b4c;
            --dark-box: rgba(255, 255, 255, .05);
            --dark-border: rgba(255, 255, 255, .08);
        }

        body {
            font-family: "Cairo", sans-serif !important;
            background: var(--dark-bg);
            color: #fff;
        }

        .main-card {
            background: var(--dark-card);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .3);
            border: 1px solid rgba(255, 255, 255, .1);
            overflow: hidden;
            margin-bottom: 25px;
        }

        .main-header {
            background: var(--primary-gradient);
            color: #fff;
            padding: 20px 30px;
        }

        .card-body-custom {
            padding: 25px 30px;
        }

        .form-section-title {
            color: #38bdf8;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 18px;
            padding-bottom: 8px;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 600;
            font-size: 13.5px;
            margin-bottom: 6px;
        }

        .form-control,
        .form-select {
            background-color: rgba(0, 0, 0, 0.25) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            color: #fff !important;
            border-radius: 8px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #38bdf8 !important;
            box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.25) !important;
        }

        .pricing-tier-card {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 12px;
        }

        .dynamic-item-row {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 10px;
        }

        .current-thumb {
            width: 120px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: #111;
        }

        .gallery-item-wrapper {
            position: relative;
            display: inline-block;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .gallery-thumb-item {
            width: 85px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: #111;
            display: block;
        }

        .remove-gallery-btn {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 22px;
            height: 22px;
            padding: 0;
            border-radius: 50%;
            background: #ef4444;
            color: #fff;
            border: 2px solid #2b3b4c;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
            transition: transform 0.15s ease, background-color 0.15s ease;
            z-index: 10;
        }

        .remove-gallery-btn:hover {
            background: #dc2626;
            transform: scale(1.2);
            color: #fff;
        }
    </style>
@endsection

@section('content')
    @php
        $includedItems = $package->inclusions->where('type', 'included');
        $excludedItems = $package->inclusions->where('type', 'excluded');
        $addons = $package->addons;

        // Extract pricing tiers
        $tiers = is_array($package->group_pricing_tiers) ? $package->group_pricing_tiers : [];
        $tierSolo =
            collect($tiers)->firstWhere('min', 1)['price_per_person'] ??
            ($package->price_1_person ?: $package->price_from);
        $tierSmall = collect($tiers)->firstWhere('min', 2)['price_per_person'] ?? $package->price_2_persons;
        $tierMed = collect($tiers)->firstWhere('min', 4)['price_per_person'] ?? $package->price_4_persons;
        $tierLarge = collect($tiers)->firstWhere('min', 6)['price_per_person'] ?? $package->price_6_plus_persons;
    @endphp

    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.shore-excursions.index') }}">Shore Excursions</a></li>
                <li class="breadcrumb-item active">Edit Shore Excursion</li>
            </ol>
        </nav>

        <form method="POST" action="{{ route('admin.shore-excursions.update', $package) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="main-card">
                <div class="main-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="mb-0 text-white"><i class="fas fa-anchor me-2"></i> Edit Shore Excursion:
                            {{ $package->title['en'] ?? ($package->title['ar'] ?? $package->name) }}</h5>
                        <small class="opacity-75">Update cruise port shore excursion details, itinerary, and group
                            pricing</small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.shore-excursions.package-bookings', $package) }}"
                            class="btn {{ $bookingsCount > 0 ? 'btn-warning text-dark fw-bold' : 'btn-outline-light' }}">
                            <i class="fas fa-calendar-check me-1"></i> Bookings ({{ $bookingsCount }})
                        </a>
                        <a href="{{ route('website.tours.show', $package->slug) }}" target="_blank"
                            class="btn btn-info text-white">
                            <i class="fas fa-external-link-alt me-1"></i> Preview on Website
                        </a>
                        <a href="{{ route('admin.shore-excursions.index') }}" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body-custom">
                    {{-- 1. Basic Info --}}
                    <div class="form-section-title">
                        <i class="fas fa-info-circle"></i> Basic Details & Port Selection
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Excursion Title (English) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title[en]"
                                value="{{ old('title.en', $package->title['en'] ?? '') }}" required>
                            @error('title.en')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Excursion Title (Arabic)</label>
                            <input type="text" class="form-control" name="title[ar]"
                                value="{{ old('title.ar', $package->title['ar'] ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Main Port / Section <span class="text-danger">*</span></label>
                            <select name="port_section" class="form-select" required id="portSectionSelect">
                                @foreach ($sections as $key => $sec)
                                    <option value="{{ $key }}" data-pickup="{{ $sec['default_pickup'] }}"
                                        {{ old('port_section', $currentPortKey) === $key ? 'selected' : '' }}>
                                        {{ $sec['title_en'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Permanent URL (Slug) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control font-monospace" name="slug"
                                value="{{ old('slug', $package->slug) }}" required>
                            @error('slug')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Port Meeting Point / Pickup Location (English)</label>
                            <input type="text" class="form-control" name="pickup_location[en]" id="pickupLocationEn"
                                value="{{ old('pickup_location.en', is_array($package->pickup_location) ? $package->pickup_location['en'] ?? 'Port Marine Terminal' : ($package->pickup_location ?: 'Port Marine Terminal')) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Port Meeting Point / Pickup Location (Arabic)</label>
                            <input type="text" class="form-control" name="pickup_location[ar]"
                                value="{{ old('pickup_location.ar', is_array($package->pickup_location) ? $package->pickup_location['ar'] ?? 'رصيف الميناء ومحطة الركاب البحرية' : 'رصيف الميناء ومحطة الركاب البحرية') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Duration Hours (e.g. 8)</label>
                            <input type="number" class="form-control" name="duration_hours"
                                value="{{ old('duration_hours', $package->duration_hours ?: 8) }}" min="1"
                                max="72">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Duration Days (e.g. 1)</label>
                            <input type="number" class="form-control" name="duration_days"
                                value="{{ old('duration_days', $package->duration_days ?: 1) }}" min="1"
                                max="10">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Currency</label>
                            <select name="currency_id" class="form-select">
                                @foreach ($currencies as $curr)
                                    <option value="{{ $curr->id }}"
                                        {{ old('currency_id', $package->currency_id) == $curr->id ? 'selected' : '' }}>
                                        {{ $curr->code }} ({{ $curr->symbol }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Short Description (English)</label>
                            <textarea class="form-control" name="short_description[en]" rows="3">{{ old('short_description.en', $package->short_description['en'] ?? '') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Short Description (Arabic)</label>
                            <textarea class="form-control" name="short_description[ar]" rows="3">{{ old('short_description.ar', $package->short_description['ar'] ?? '') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Overview & Full Details (English)</label>
                            <textarea class="form-control" name="description[en]" rows="6">{{ old('description.en', $package->description['en'] ?? '') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Overview & Full Details (Arabic)</label>
                            <textarea class="form-control" name="description[ar]" rows="6">{{ old('description.ar', $package->description['ar'] ?? '') }}</textarea>
                        </div>
                    </div>

                    {{-- 2. Group Pricing Tiers --}}
                    <div class="form-section-title">
                        <i class="fas fa-users"></i> Group Pricing Tiers (Per Person Rates)
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="pricing-tier-card">
                                <div class="fw-bold text-info mb-1"><i class="fas fa-user me-1"></i> Solo Traveler (1
                                    Person)</div>
                                <div class="small opacity-75 mb-2">Per person rate for 1 traveler</div>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" name="price_1_person"
                                        value="{{ old('price_1_person', $tierSolo) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="pricing-tier-card">
                                <div class="fw-bold text-info mb-1"><i class="fas fa-user-friends me-1"></i> Small Group
                                    (2-3 Persons)</div>
                                <div class="small opacity-75 mb-2">Per person rate for 2-3 guests</div>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" name="price_2_persons"
                                        value="{{ old('price_2_persons', $tierSmall) }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="pricing-tier-card">
                                <div class="fw-bold text-info mb-1"><i class="fas fa-users me-1"></i> Medium Group (4-5
                                    Persons)</div>
                                <div class="small opacity-75 mb-2">Per person rate for 4-5 guests</div>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" name="price_4_persons"
                                        value="{{ old('price_4_persons', $tierMed) }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="pricing-tier-card">
                                <div class="fw-bold text-info mb-1"><i class="fas fa-users-cog me-1"></i> Large Group (6+
                                    Persons)</div>
                                <div class="small opacity-75 mb-2">Per person rate for 6+ guests</div>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control"
                                        name="price_6_plus_persons"
                                        value="{{ old('price_6_plus_persons', $tierLarge) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Inclusions & Exclusions --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="form-section-title">
                                <i class="fas fa-check-circle text-success"></i> What's Included
                            </div>
                            <div id="inclusionsContainer">
                                @forelse ($includedItems as $inc)
                                    <div class="dynamic-item-row d-flex gap-2 align-items-center">
                                        <input type="text" class="form-control" name="inclusions[]"
                                            value="{{ $inc->title }}">
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="this.closest('.dynamic-item-row').remove()">×</button>
                                    </div>
                                @empty
                                    <div class="dynamic-item-row d-flex gap-2 align-items-center">
                                        <input type="text" class="form-control" name="inclusions[]"
                                            placeholder="e.g. Round-trip port transfers by private A/C vehicle">
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="this.closest('.dynamic-item-row').remove()">×</button>
                                    </div>
                                @endforelse
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-info mt-2" onclick="addInclusionRow()">
                                <i class="fas fa-plus me-1"></i> Add Included Item
                            </button>
                        </div>

                        <div class="col-md-6">
                            <div class="form-section-title">
                                <i class="fas fa-times-circle text-danger"></i> What's Excluded
                            </div>
                            <div id="exclusionsContainer">
                                @forelse ($excludedItems as $exc)
                                    <div class="dynamic-item-row d-flex gap-2 align-items-center">
                                        <input type="text" class="form-control" name="exclusions[]"
                                            value="{{ $exc->title }}">
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="this.closest('.dynamic-item-row').remove()">×</button>
                                    </div>
                                @empty
                                    <div class="dynamic-item-row d-flex gap-2 align-items-center">
                                        <input type="text" class="form-control" name="exclusions[]"
                                            placeholder="e.g. Personal expenses, optional tours, and tipping">
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="this.closest('.dynamic-item-row').remove()">×</button>
                                    </div>
                                @endforelse
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-info mt-2" onclick="addExclusionRow()">
                                <i class="fas fa-plus me-1"></i> Add Excluded Item
                            </button>
                        </div>
                    </div>

                    {{-- 4. Optional Add-ons --}}
                    <div class="form-section-title">
                        <i class="fas fa-plus-circle text-warning"></i> Optional Add-ons (Upgrades & Extras)
                    </div>
                    <div id="addonsContainer" class="mb-3">
                        @forelse ($addons as $index => $addon)
                            <div class="dynamic-item-row">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control form-control-sm"
                                            name="addons[{{ $index }}][title]" value="{{ $addon->title }}"
                                            placeholder="Add-on Name">
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" class="form-control"
                                                name="addons[{{ $index }}][price]" value="{{ $addon->price }}"
                                                placeholder="Price">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select form-select-sm"
                                            name="addons[{{ $index }}][price_unit]">
                                            <option value="per_person"
                                                {{ $addon->price_unit === 'per_person' ? 'selected' : '' }}>Per Person
                                                (per_person)
                                            </option>
                                            <option value="per_booking"
                                                {{ $addon->price_unit === 'per_booking' ? 'selected' : '' }}>Per Booking
                                                (per_booking)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 text-end">
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="this.closest('.dynamic-item-row').remove()">×</button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="dynamic-item-row">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control form-control-sm"
                                            name="addons[0][title]" placeholder="Add-on Name (e.g. VIP Private Vehicle)">
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" class="form-control"
                                                name="addons[0][price]" placeholder="Price">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select form-select-sm" name="addons[0][price_unit]">
                                            <option value="per_person">Per Person (per_person)</option>
                                            <option value="per_booking">Per Booking (per_booking)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 text-end">
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="this.closest('.dynamic-item-row').remove()">×</button>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-warning mb-4" onclick="addAddonRow()">
                        <i class="fas fa-plus me-1"></i> Add Another Add-on
                    </button>

                    {{-- 5. Media & Images --}}
                    <div class="form-section-title">
                        <i class="fas fa-camera"></i> Excursion Media & Images
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Featured Image (Thumbnail)</label>
                            @if ($package->featured_image)
                                <div class="mb-2">
                                    <img src="{{ get_package_image($package->featured_image) }}" alt="Featured Image"
                                        class="current-thumb"
                                        onerror="this.src='{{ asset('website/photos/home2.webp') }}'">
                                </div>
                            @endif
                            <input type="file" class="form-control" name="featured_image" accept="image/*">
                            <small class="text-white-50">Upload a new image to replace the current featured image</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gallery Images (Multiple)</label>
                            <input type="hidden" name="gallery_section_present" value="1">
                            @if (!empty($package->gallery_images) && is_array($package->gallery_images))
                                <div class="d-flex gap-2 mb-2 flex-wrap" id="galleryContainer">
                                    @foreach ($package->gallery_images as $index => $gImg)
                                        <div class="gallery-item-wrapper" id="gallery-item-{{ $index }}">
                                            <img src="{{ get_package_image($gImg) }}" alt="Gallery Image"
                                                class="gallery-thumb-item"
                                                onerror="this.src='{{ asset('website/photos/home2.webp') }}'">
                                            <input type="hidden" name="existing_gallery[]" value="{{ $gImg }}">
                                            <button type="button" class="remove-gallery-btn"
                                                onclick="removeGalleryItem('gallery-item-{{ $index }}')"
                                                title="Delete photo">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" class="form-control" name="gallery_images[]" multiple
                                accept="image/*">
                            <small class="text-white-50">Upload additional gallery photos</small>
                        </div>
                    </div>

                    {{-- 6. SEO & Status --}}
                    <div class="form-section-title">
                        <i class="fas fa-search"></i> Visibility & SEO Settings
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Meta Title (English)</label>
                            <input type="text" class="form-control" name="seo_title[en]"
                                value="{{ old('seo_title.en', $package->seo_title['en'] ?? '') }}"
                                placeholder="Page title for search engines">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Meta Description (English)</label>
                            <input type="text" class="form-control" name="seo_description[en]"
                                value="{{ old('seo_description.en', $package->seo_description['en'] ?? '') }}"
                                placeholder="Brief summary for search engines">
                        </div>
                        <div class="col-md-3">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    id="isActiveSwitch" {{ old('is_active', $package->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="isActiveSwitch">Active (Available for
                                    Booking)</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                    id="isFeaturedSwitch"
                                    {{ old('is_featured', $package->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="isFeaturedSwitch">Featured Excursion</label>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="border-top pt-4 text-end">
                        <a href="{{ route('admin.shore-excursions.index') }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5 fw-bold">
                            <i class="fas fa-save me-1"></i> Save Changes
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        function addInclusionRow() {
            const div = document.createElement('div');
            div.className = 'dynamic-item-row d-flex gap-2 align-items-center';
            div.innerHTML = `
                <input type="text" class="form-control" name="inclusions[]" placeholder="Enter included item...">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.dynamic-item-row').remove()">×</button>
            `;
            document.getElementById('inclusionsContainer').appendChild(div);
        }

        function addExclusionRow() {
            const div = document.createElement('div');
            div.className = 'dynamic-item-row d-flex gap-2 align-items-center';
            div.innerHTML = `
                <input type="text" class="form-control" name="exclusions[]" placeholder="Enter excluded item...">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.dynamic-item-row').remove()">×</button>
            `;
            document.getElementById('exclusionsContainer').appendChild(div);
        }

        let addonIndex = {{ count($addons) > 0 ? count($addons) : 1 }};

        function addAddonRow() {
            const div = document.createElement('div');
            div.className = 'dynamic-item-row';
            div.innerHTML = `
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <input type="text" class="form-control form-control-sm" name="addons[${addonIndex}][title]" placeholder="Add-on Name">
                    </div>
                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control" name="addons[${addonIndex}][price]" placeholder="Price">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select form-select-sm" name="addons[${addonIndex}][price_unit]">
                            <option value="per_person">Per Person (per_person)</option>
                            <option value="per_booking">Per Booking (per_booking)</option>
                        </select>
                    </div>
                    <div class="col-md-1 text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.dynamic-item-row').remove()">×</button>
                    </div>
                </div>
            `;
            document.getElementById('addonsContainer').appendChild(div);
            addonIndex++;
        }

        function removeGalleryItem(elementId) {
            const item = document.getElementById(elementId);
            if (item) {
                item.style.transition = 'all 0.2s ease';
                item.style.opacity = '0';
                item.style.transform = 'scale(0.8)';
                setTimeout(() => item.remove(), 200);
            }
        }

        document.getElementById('portSectionSelect')?.addEventListener('change', function() {
            const selectedOpt = this.options[this.selectedIndex];
            const defaultPickup = selectedOpt.getAttribute('data-pickup');
            const pickupEnInput = document.getElementById('pickupLocationEn');
            if (defaultPickup && pickupEnInput && (!pickupEnInput.value || pickupEnInput.value ===
                    'Port Marine Terminal')) {
                pickupEnInput.value = defaultPickup;
            }
        });
    </script>
@endsection
