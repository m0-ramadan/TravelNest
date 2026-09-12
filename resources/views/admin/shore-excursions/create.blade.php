@include('admin.i18n.locale')
@extends('admin.layout.master')

@section('title', admin_t('Add New Shore Excursion'))

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
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.shore-excursions.index') }}">Shore Excursions</a></li>
                <li class="breadcrumb-item active">Add New Excursion</li>
            </ol>
        </nav>

        <form method="POST" action="{{ route('admin.shore-excursions.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="main-card">
                <div class="main-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="mb-0 text-white"><i class="fas fa-anchor me-2"></i> Add New Shore Excursion</h5>
                        <small class="opacity-75">Tailored form for cruise ship and port passenger shore excursions</small>
                    </div>
                    <a href="{{ route('admin.shore-excursions.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>

                <div class="card-body-custom">
                    {{-- 1. Basic Info --}}
                    <div class="form-section-title">
                        <i class="fas fa-info-circle"></i> Basic Details & Port Selection
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Excursion Title (English) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title[en]" value="{{ old('title.en') }}"
                                placeholder="e.g. Cairo & Pyramids Tour from Alexandria Port" required>
                            @error('title.en')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Excursion Title (Arabic)</label>
                            <input type="text" class="form-control" name="title[ar]" value="{{ old('title.ar') }}"
                                placeholder="مثال: جولة القاهرة والأهرامات من ميناء الإسكندرية">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Main Port / Section <span class="text-danger">*</span></label>
                            <select name="port_section" class="form-select" required id="portSectionSelect">
                                <option value="">-- Select Cruise Port --</option>
                                @foreach ($sections as $key => $sec)
                                    <option value="{{ $key }}" data-pickup="{{ $sec['default_pickup'] }}"
                                        {{ old('port_section') === $key ? 'selected' : '' }}>
                                        {{ $sec['title_en'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Port Meeting Point / Pickup Location (English)</label>
                            <input type="text" class="form-control" name="pickup_location[en]" id="pickupLocationEn"
                                value="{{ old('pickup_location.en', 'Alexandria Port Marine Passenger Terminal') }}"
                                placeholder="e.g. Port Marine Passenger Terminal Gate">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Port Meeting Point / Pickup Location (Arabic)</label>
                            <input type="text" class="form-control" name="pickup_location[ar]"
                                value="{{ old('pickup_location.ar', 'رصيف الميناء ومحطة الركاب البحرية') }}"
                                placeholder="مثال: رصيف الميناء ومحطة الركاب البحرية">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Duration Hours (e.g. 8)</label>
                            <input type="number" class="form-control" name="duration_hours"
                                value="{{ old('duration_hours', 8) }}" min="1" max="72">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Duration Days (e.g. 1)</label>
                            <input type="number" class="form-control" name="duration_days"
                                value="{{ old('duration_days', 1) }}" min="1" max="10">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Currency</label>
                            <select name="currency_id" class="form-select">
                                @foreach ($currencies as $curr)
                                    <option value="{{ $curr->id }}"
                                        {{ old('currency_id', $defaultCurrency?->id) == $curr->id ? 'selected' : '' }}>
                                        {{ $curr->code }} ({{ $curr->symbol }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Short Description (English)</label>
                            <textarea class="form-control" name="short_description[en]" rows="3"
                                placeholder="Brief overview of the excursion for cards and highlights">{{ old('short_description.en') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Short Description (Arabic)</label>
                            <textarea class="form-control" name="short_description[ar]" rows="3"
                                placeholder="وصف مختصر للرحلة يظهر في البطاقات والمقدمة">{{ old('short_description.ar') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Overview & Full Details (English)</label>
                            <textarea class="form-control" name="description[en]" rows="6"
                                placeholder="Full itinerary, timeline, and complete excursion details">{{ old('description.en') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Overview & Full Details (Arabic)</label>
                            <textarea class="form-control" name="description[ar]" rows="6" placeholder="تفاصيل وبرنامج الجولة بالكامل">{{ old('description.ar') }}</textarea>
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
                                        value="{{ old('price_1_person', '180.00') }}" required>
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
                                        value="{{ old('price_2_persons', '110.00') }}">
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
                                        value="{{ old('price_4_persons', '85.00') }}">
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
                                        name="price_6_plus_persons" value="{{ old('price_6_plus_persons', '65.00') }}">
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
                                <div class="dynamic-item-row d-flex gap-2 align-items-center">
                                    <input type="text" class="form-control" name="inclusions[]"
                                        value="Pick up services from port passenger terminal and return">
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="this.closest('.dynamic-item-row').remove()">×</button>
                                </div>
                                <div class="dynamic-item-row d-flex gap-2 align-items-center">
                                    <input type="text" class="form-control" name="inclusions[]"
                                        value="All transfers by private modern air-conditioned vehicle">
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="this.closest('.dynamic-item-row').remove()">×</button>
                                </div>
                                <div class="dynamic-item-row d-flex gap-2 align-items-center">
                                    <input type="text" class="form-control" name="inclusions[]"
                                        value="Licensed English-speaking Egyptologist tour guide">
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="this.closest('.dynamic-item-row').remove()">×</button>
                                </div>
                                <div class="dynamic-item-row d-flex gap-2 align-items-center">
                                    <input type="text" class="form-control" name="inclusions[]"
                                        value="Entrance fees to all mentioned historical sites">
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="this.closest('.dynamic-item-row').remove()">×</button>
                                </div>
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
                                <div class="dynamic-item-row d-flex gap-2 align-items-center">
                                    <input type="text" class="form-control" name="exclusions[]"
                                        value="Personal expenses and optional activities">
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="this.closest('.dynamic-item-row').remove()">×</button>
                                </div>
                                <div class="dynamic-item-row d-flex gap-2 align-items-center">
                                    <input type="text" class="form-control" name="exclusions[]"
                                        value="Tipping / Gratuities for guide and driver">
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="this.closest('.dynamic-item-row').remove()">×</button>
                                </div>
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
                        <div class="dynamic-item-row">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-5">
                                    <input type="text" class="form-control form-control-sm" name="addons[0][title]"
                                        value="Lunch at High Quality Local Restaurant" placeholder="Add-on Name">
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" class="form-control"
                                            name="addons[0][price]" value="15.00" placeholder="Price">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select form-select-sm" name="addons[0][price_unit]">
                                        <option value="per_person" selected>Per Person (per_person)</option>
                                        <option value="per_booking">Per Booking (per_booking)</option>
                                    </select>
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="this.closest('.dynamic-item-row').remove()">×</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-warning mb-4" onclick="addAddonRow()">
                        <i class="fas fa-plus me-1"></i> Add Another Add-on
                    </button>

                    {{-- 5. Media & Images --}}
                    <div class="form-section-title">
                        <i class="fas fa-camera"></i> Excursion Media & Photos
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Featured Image</label>
                            <input type="file" class="form-control" name="featured_image" accept="image/*">
                            <small class="text-white-50">High quality image representing the port or excursion
                                destination</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gallery Images</label>
                            <input type="file" class="form-control" name="gallery_images[]" multiple
                                accept="image/*">
                            <small class="text-white-50">You can select multiple photos for the gallery</small>
                        </div>
                    </div>

                    {{-- 6. SEO & Status --}}
                    <div class="form-section-title">
                        <i class="fas fa-search"></i> SEO & Publishing Settings
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">SEO Meta Title (English)</label>
                            <input type="text" class="form-control" name="seo_title[en]"
                                value="{{ old('seo_title.en') }}" placeholder="Page title for search engines">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">SEO Meta Description (English)</label>
                            <input type="text" class="form-control" name="seo_description[en]"
                                value="{{ old('seo_description.en') }}" placeholder="Brief summary for search engines">
                        </div>
                        <div class="col-md-3">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    id="isActiveSwitch" checked>
                                <label class="form-check-label fw-bold" for="isActiveSwitch">Active & Available for
                                    Booking</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                    id="isFeaturedSwitch">
                                <label class="form-check-label fw-bold" for="isFeaturedSwitch">Featured Excursion</label>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="border-top pt-4 text-end">
                        <a href="{{ route('admin.shore-excursions.index') }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5 fw-bold">
                            <i class="fas fa-save me-1"></i> Save & Publish Excursion
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

        let addonIndex = 1;

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

        document.getElementById('portSectionSelect')?.addEventListener('change', function() {
            const selectedOpt = this.options[this.selectedIndex];
            const defaultPickup = selectedOpt.getAttribute('data-pickup');
            const pickupEnInput = document.getElementById('pickupLocationEn');
            if (defaultPickup && pickupEnInput && (!pickupEnInput.value || pickupEnInput.value.includes(
                    'Alexandria Port'))) {
                pickupEnInput.value = defaultPickup;
            }
        });
    </script>
@endsection
