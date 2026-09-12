@include('admin.i18n.locale')
@extends('admin.layout.master')

@section('title', admin_t('عرض الحجز'))

@section('css')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .profile-card {
            background: var(--dark-card);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .3);
            border: 1px solid rgba(255, 255, 255, .1);
        }

        .profile-header {
            background: var(--primary-gradient);
            color: #fff;
            padding: 25px 30px;
        }

        .profile-body {
            padding: 30px;
        }

        .info-box {
            background: var(--dark-box);
            border: 1px solid var(--dark-border);
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 15px;
            height: calc(100% - 15px);
        }

        .info-label {
            color: rgba(255, 255, 255, .65);
            font-size: 13px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .info-value {
            color: #fff;
            font-weight: 600;
            font-size: 15px;
        }

        .section-title {
            color: #d4a05d;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.15);
            padding-bottom: 8px;
        }

        .finance-card {
            background: rgba(212, 160, 93, 0.08);
            border: 1px solid rgba(212, 160, 93, 0.25);
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 15px;
        }

        .finance-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
            margin-bottom: 4px;
        }

        .finance-val {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
        }

        .table-dark-custom {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
        }

        .table-dark-custom th {
            background: rgba(255, 255, 255, 0.07);
            color: #d4a05d;
            border-color: rgba(255, 255, 255, 0.1);
            font-weight: 600;
            font-size: 13px;
        }

        .table-dark-custom td {
            border-color: rgba(255, 255, 255, 0.06);
            color: #fff;
            vertical-align: middle;
            font-size: 14px;
        }
    </style>
@endsection

@section('content')
    @php
        $package = $booking->package;
        $packageType = $package->package_type ?? 'travel_package';
        $checkoutDetails = is_array($booking->checkout_details) ? $booking->checkout_details : [];

        // Main booking items vs Add-on items
        $addonItems = $booking->items->where('pricing_source', 'package_addon');
        $mainItems = $booking->items->reject(fn($item) => $item->pricing_source === 'package_addon');

        // Fallback for add-ons from checkout_details if items table had none
        $selectedAddons = $checkoutDetails['selected_addons'] ?? [];

        // Financial calculations
        $baseTotal =
            (float) ($checkoutDetails['base_total'] ?? ($mainItems->sum('total_amount') ?: $booking->total_amount));
        $addonsTotal = (float) ($checkoutDetails['addons_total'] ?? $addonItems->sum('total_amount'));
        $grandTotal = (float) ($booking->total_amount ?: $checkoutDetails['grand_total'] ?? $baseTotal + $addonsTotal);
        $paidAmount = (float) ($booking->paid_amount ?? 0);
        $remainingAmount = (float) ($booking->remaining_amount ?? max(0, $grandTotal - $paidAmount));
        $depositAmount = isset($checkoutDetails['deposit_amount']) ? (float) $checkoutDetails['deposit_amount'] : null;
        $paymentProvider = $checkoutDetails['payment_provider'] ?? ($booking->payments->first()?->provider ?? null);

        // Package Type Label and Badge
        $packageTypeInfo = match ($packageType) {
            'day_tour' => ['label' => 'جولة يومية (Day Tour)', 'badge' => 'bg-primary', 'icon' => 'fas fa-sun'],
            'shore_excursion' => [
                'label' => 'رحلة شاطئية (Shore Excursion)',
                'badge' => 'bg-info text-dark',
                'icon' => 'fas fa-anchor',
            ],
            'nile_cruise' => [
                'label' => 'نايل كروز (Nile Cruise)',
                'badge' => 'bg-warning text-dark',
                'icon' => 'fas fa-ship',
            ],
            default => [
                'label' => 'باقة سفر (Travel Package)',
                'badge' => 'bg-success',
                'icon' => 'fas fa-suitcase-rolling',
            ],
        };

        // Booking Status Badge
        $statusBadge = match ($booking->status) {
            'confirmed' => 'bg-success',
            'cancelled' => 'bg-danger',
            'completed' => 'bg-info text-dark',
            default => 'bg-warning text-dark',
        };

        // Payment Status Badge
        $paymentBadge = match ($booking->payment_status) {
            'paid' => 'bg-success',
            'partially_paid' => 'bg-warning text-dark',
            'cancelled', 'failed' => 'bg-danger',
            default => 'bg-secondary',
        };

        // Room breakdown for travel packages
        $roomBreakdown = $checkoutDetails['room_breakdown'] ?? ($mainItems->first()?->meta['room_breakdown'] ?? []);
    @endphp

    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}">الحجوزات</a></li>
                <li class="breadcrumb-item active">عرض الحجز</li>
            </ol>
        </nav>

        <div class="profile-card mb-4">
            {{-- Header --}}
            <div class="profile-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                        <h4 class="mb-0 text-white font-monospace">
                            {{ $booking->booking_reference ?? ($booking->booking_number ?? 'بدون مرجع') }}</h4>
                        <span class="badge {{ $packageTypeInfo['badge'] }} px-3 py-2">
                            <i class="{{ $packageTypeInfo['icon'] }} me-1"></i> {{ $packageTypeInfo['label'] }}
                        </span>
                        <span class="badge {{ $statusBadge }} px-3 py-2">
                            {{ $booking->status ?? 'pending' }}
                        </span>
                    </div>
                    <small class="opacity-75">
                        <i class="fas fa-user me-1"></i> {{ $booking->client->name ?? ($booking->client_name ?? '-') }}
                        · <i class="fas fa-calendar-alt ms-2 me-1"></i>
                        {{ optional($booking->created_at)->translatedFormat('d M Y - h:i A') }}
                    </small>
                </div>
                <div class="d-flex gap-2">
                    @if ($packageType === 'shore_excursion' && $booking->package_id)
                        <a href="{{ route('admin.shore-excursions.edit', $booking->package_id) }}"
                            class="btn btn-info text-white">
                            <i class="fas fa-anchor me-1"></i> الرحلة الشاطئية
                        </a>
                    @endif
                    <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-light">
                        <i class="fas fa-edit me-1"></i> تعديل
                    </a>
                    <a href="{{ route('admin.bookings.print', $booking) }}" target="_blank" class="btn btn-light">
                        <i class="fas fa-print me-1"></i> طباعة
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-light">
                        <i class="fas fa-arrow-right me-1"></i> رجوع
                    </a>
                </div>
            </div>

            <div class="profile-body">
                {{-- 1. General Booking & Client Overview --}}
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="section-title">
                            <i class="fas fa-info-circle"></i> معلومات الحجز والعميل
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="info-box">
                            <div class="info-label"><i class="fas fa-user"></i> العميل</div>
                            <div class="info-value">{{ $booking->client->name ?? ($booking->client_name ?? '-') }}</div>
                            @if (!empty($booking->client?->nationality))
                                <div class="small opacity-75 mt-1"><i class="fas fa-globe me-1"></i>
                                    {{ $booking->client->nationality }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="info-box">
                            <div class="info-label"><i class="fas fa-map-marked-alt"></i> الرحلة / الباقة</div>
                            <div class="info-value">
                                @if ($packageType === 'shore_excursion' && $booking->package_id)
                                    <a href="{{ route('admin.shore-excursions.edit', $booking->package_id) }}"
                                        class="text-info text-decoration-none" title="تعديل الرحلة الشاطئية">
                                        {{ $booking->package->name ?? '-' }} <i
                                            class="fas fa-external-link-alt fs-7 ms-1"></i>
                                    </a>
                                @else
                                    {{ $booking->package->name ?? '-' }}
                                @endif
                            </div>
                            @if (!empty($booking->package?->city?->name) || !empty($booking->package?->destination))
                                <div class="small opacity-75 mt-1">
                                    <i class="fas fa-map-pin me-1"></i>
                                    {{ $booking->package?->city?->name ?? $booking->package?->destination }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="info-box">
                            <div class="info-label"><i class="fas fa-calendar-day"></i> تاريخ السفر</div>
                            <div class="info-value text-warning">
                                {{ optional($booking->travel_date)->translatedFormat('l، d F Y') ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="info-box">
                            <div class="info-label"><i class="fas fa-users"></i> عدد الأفراد</div>
                            <div class="info-value">
                                {{ $booking->travellers_count ?? '-' }} فرد
                                <div class="small opacity-75 mt-1" style="font-size: 13px;">{{ $booking->adults ?? 0 }}
                                    بالغين · {{ $booking->children ?? 0 }} أطفال · {{ $booking->infants ?? 0 }} رضع</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="info-box">
                            <div class="info-label"><i class="fas fa-phone"></i> الهاتف والواتساب</div>
                            <div class="info-value">
                                @if (!empty($booking->phone))
                                    @php $cleanBKPhone = preg_replace('/[^0-9]/', '', $booking->phone); @endphp
                                    <span class="dir-ltr d-inline-block font-monospace me-2">{{ $booking->phone }}</span>
                                    <a href="https://wa.me/{{ $cleanBKPhone }}" target="_blank"
                                        class="btn btn-sm btn-success rounded-circle px-2 py-1 me-1"
                                        title="مراسلة عبر واتساب">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="tel:{{ $booking->phone }}"
                                        class="btn btn-sm btn-info rounded-circle px-2 py-1" title="اتصال هاتفي">
                                        <i class="fas fa-phone-alt"></i>
                                    </a>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="info-box">
                            <div class="info-label"><i class="fas fa-envelope"></i> البريد الإلكتروني</div>
                            <div class="info-value">
                                @if (!empty($booking->email))
                                    <a href="mailto:{{ $booking->email }}" class="text-white text-decoration-none me-2">
                                        {{ $booking->email }}
                                    </a>
                                    <a href="mailto:{{ $booking->email }}"
                                        class="btn btn-sm btn-primary rounded-circle px-2 py-1" title="مراسلة عبر البريد">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Pickup Location (Very important for Day Tours & Shore Excursions) --}}
                    @if (!empty($booking->pickup_location))
                        <div class="col-md-6">
                            <div class="info-box border-warning" style="border-style: dashed;">
                                <div class="info-label text-warning"><i class="fas fa-map-marker-alt"></i> نقطة الالتقاء /
                                    التوصيل (Pickup Location)</div>
                                <div class="info-value text-white">{{ $booking->pickup_location }}</div>
                            </div>
                        </div>
                    @endif

                    {{-- Special Requests / Notes --}}
                    @if (!empty($booking->special_requests) || !empty($booking->notes))
                        <div class="{{ !empty($booking->pickup_location) ? 'col-md-6' : 'col-12' }}">
                            <div class="info-box">
                                <div class="info-label"><i class="fas fa-sticky-note"></i> الطلبات الخاصة وملاحظات العميل
                                </div>
                                <div class="info-value font-monospace" style="font-weight: normal; font-size: 14px;">
                                    {{ $booking->special_requests ?: $booking->notes }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- 2. Package-Type-Specific Details --}}
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="section-title">
                            <i class="{{ $packageTypeInfo['icon'] }}"></i> تفاصيل باقة الرحلة
                            ({{ $packageTypeInfo['label'] }})
                        </div>
                    </div>

                    @if (in_array($packageType, ['day_tour', 'shore_excursion']))
                        {{-- Day Tour & Shore Excursion details --}}
                        @php
                            $mainItem = $mainItems->first();
                            $tierLabel =
                                $mainItem?->option_label ?: $checkoutDetails['option_label'] ?? 'الخطة الأساسية للرحلة';
                            $tierDesc = $mainItem?->meta['description'] ?? '';
                            $tierUnitPrice =
                                (float) ($mainItem?->unit_price ?: $baseTotal / max(1, $booking->travellers_count));
                            $tierQty = $mainItem?->quantity ?: $booking->travellers_count;
                        @endphp
                        <div class="col-md-5">
                            <div class="info-box">
                                <div class="info-label">فئة وخطة الرحلة المحجوزة</div>
                                <div class="info-value text-info">{{ $tierLabel }}</div>
                                @if ($tierDesc)
                                    <div class="small opacity-75 mt-1">{{ $tierDesc }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <div class="info-label">سعر الفرد / الوحدة</div>
                                <div class="info-value">{{ number_format($tierUnitPrice, 2) }}
                                    {{ $booking->currency_code }}</div>
                                <div class="small opacity-75 mt-1">الكمية: {{ $tierQty }} فرد</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="info-box">
                                <div class="info-label">إجمالي تكلفة الرحلة الأساسية</div>
                                <div class="info-value text-warning fs-5">{{ number_format($baseTotal, 2) }}
                                    {{ $booking->currency_code }}</div>
                            </div>
                        </div>
                    @elseif ($packageType === 'travel_package')
                        {{-- Travel Package accommodation & rooms breakdown --}}
                        @php
                            $mainItem = $mainItems->first();
                            $accLabel = $mainItem?->option_label ?: $checkoutDetails['option_label'] ?? 'Standard';
                            $roomCount =
                                $mainItem?->room_count ?: ($checkoutDetails['rooms'] ?? count($roomBreakdown) ?: 1);
                        @endphp
                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-label">فئة الإقامة والفنادق</div>
                                <div class="info-value text-info">{{ $accLabel }}</div>
                                @if ($package && $package->tourPackageAccommodations->isNotEmpty())
                                    @php
                                        $selectedAccModel =
                                            $package->tourPackageAccommodations->first(function ($a) use ($accLabel) {
                                                return \Illuminate\Support\Str::contains(
                                                    strtolower($accLabel),
                                                    strtolower($a->name),
                                                );
                                            }) ?:
                                            $package->tourPackageAccommodations->first();
                                    @endphp
                                    @if ($selectedAccModel && $selectedAccModel->hotels->isNotEmpty())
                                        <div class="small opacity-75 mt-2">
                                            <i class="fas fa-hotel me-1 text-warning"></i> الفنادق المشمولة:
                                            {{ $selectedAccModel->hotels->pluck('name')->implode(' · ') }}
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <div class="info-label">عدد الغرف المحجوزة</div>
                                <div class="info-value">{{ $roomCount }} غرفة</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <div class="info-label">إجمالي سعر الإقامة الأساسي</div>
                                <div class="info-value text-warning fs-5">{{ number_format($baseTotal, 2) }}
                                    {{ $booking->currency_code }}</div>
                            </div>
                        </div>

                        {{-- Room Breakdown Table if available --}}
                        @if (!empty($roomBreakdown))
                            <div class="col-12">
                                <div class="info-box">
                                    <div class="info-label mb-2"><i class="fas fa-bed"></i> تفاصيل توزيع الغرف والنزلاء
                                    </div>
                                    <div class="table-responsive table-dark-custom">
                                        <table class="table table-dark table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>الغرفة</th>
                                                    <th>فئة الإقامة</th>
                                                    <th>البالغين</th>
                                                    <th>الأطفال</th>
                                                    <th class="text-end">تكلفة الغرفة</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($roomBreakdown as $rb)
                                                    <tr>
                                                        <td><strong>غرفة
                                                                {{ $rb['room_number'] ?? $loop->iteration }}</strong></td>
                                                        <td>{{ $rb['accommodation'] ?? $accLabel }}</td>
                                                        <td>{{ $rb['adults'] ?? 1 }} بالغين</td>
                                                        <td>{{ $rb['children'] ?? 0 }} أطفال</td>
                                                        <td class="text-end text-warning font-monospace">
                                                            {{ number_format((float) ($rb['price'] ?? 0), 2) }}
                                                            {{ $booking->currency_code }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @elseif ($packageType === 'nile_cruise')
                        {{-- Nile Cruise ship & cabin details --}}
                        @php
                            $mainItem = $mainItems->first();
                            $shipName =
                                $package?->nileCruiseDetail?->ship_name ?: $package?->name ?? 'Nile Cruise Ship';
                            $cabinLabel = $mainItem?->option_label ?: $mainItem?->cabin?->name ?? 'Cabin';
                            $deckName = $mainItem?->cabin?->deck ?? '-';
                            $occupancy = $mainItem?->occupancy_type ?? '-';
                            $cabinCount = $mainItem?->room_count ?: 1;
                            $boardBasis = $package?->nileCruiseDetail?->board_basis ?: 'Full Board';
                        @endphp
                        <div class="col-md-4">
                            <div class="info-box">
                                <div class="info-label">اسم الباخرة النيلية</div>
                                <div class="info-value text-info"><i class="fas fa-ship me-1"></i> {{ $shipName }}
                                </div>
                                <div class="small opacity-75 mt-1">نظام الوجبات: {{ $boardBasis }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <div class="info-label">الكابينة المحجوزة</div>
                                <div class="info-value">{{ $cabinLabel }}</div>
                                <div class="small opacity-75 mt-1">
                                    الطابق / السطح: {{ $deckName }} · الإشغال: {{ $occupancy }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <div class="info-label">عدد الكابينات والإجمالي</div>
                                <div class="info-value">{{ $cabinCount }} كابينة</div>
                                <div class="small text-warning mt-1 fs-6 font-monospace">
                                    {{ number_format($baseTotal, 2) }} {{ $booking->currency_code }}
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Generic Package items --}}
                        @foreach ($mainItems as $item)
                            <div class="col-md-6">
                                <div class="info-box">
                                    <div class="info-label">خيار التسعير / الباقة</div>
                                    <div class="info-value">{{ $item->option_label }}</div>
                                    @if ($item->occupancy_type)
                                        <small class="opacity-75">{{ $item->occupancy_type }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="info-box">
                                    <div class="info-label">الكمية / الغرف</div>
                                    <div class="info-value">{{ $item->room_count ?: $item->quantity }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="info-box">
                                    <div class="info-label">السعر</div>
                                    <div class="info-value text-warning">
                                        {{ number_format((float) $item->total_amount, 2) }} {{ $booking->currency_code }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- 3. Optional Add-ons Table --}}
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="section-title">
                            <i class="fas fa-plus-circle"></i> الإضافات والخيارات المختارة (Optional Add-ons)
                        </div>
                    </div>

                    @if ($addonItems->isNotEmpty())
                        <div class="col-12">
                            <div class="info-box">
                                <div class="table-responsive table-dark-custom">
                                    <table class="table table-dark table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>الإضافة</th>
                                                <th>نوع التسعير</th>
                                                <th>سعر الوحدة</th>
                                                <th>الكمية</th>
                                                <th class="text-end">الإجمالي</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($addonItems as $item)
                                                @php
                                                    $unitLabel = match ($item->meta['price_unit'] ?? '') {
                                                        'per_person' => 'لكل فرد',
                                                        'per_adult' => 'لكل بالغ',
                                                        'per_room' => 'لكل غرفة',
                                                        default => 'لكل حجز',
                                                    };
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <strong class="text-white">{{ $item->option_label }}</strong>
                                                        @if (!empty($item->meta['description']))
                                                            <div class="small opacity-75 mt-1">
                                                                {{ $item->meta['description'] }}</div>
                                                        @endif
                                                    </td>
                                                    <td><span class="badge bg-secondary">{{ $unitLabel }}</span></td>
                                                    <td class="font-monospace">
                                                        {{ number_format((float) $item->unit_price, 2) }}
                                                        {{ $item->meta['currency_code'] ?? $booking->currency_code }}
                                                    </td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td class="text-end text-warning font-monospace fw-bold">
                                                        {{ number_format((float) $item->total_amount, 2) }}
                                                        {{ $item->meta['currency_code'] ?? $booking->currency_code }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="5" class="text-end text-white">إجمالي الإضافات المختارة:
                                                </th>
                                                <th class="text-end text-warning font-monospace fs-6">
                                                    {{ number_format($addonsTotal, 2) }} {{ $booking->currency_code }}
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @elseif (!empty($selectedAddons))
                        {{-- Fallback from checkout_details if items not populated --}}
                        <div class="col-12">
                            <div class="info-box">
                                <div class="table-responsive table-dark-custom">
                                    <table class="table table-dark table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>الإضافة</th>
                                                <th>نوع التسعير</th>
                                                <th>سعر الوحدة</th>
                                                <th>الكمية</th>
                                                <th class="text-end">الإجمالي</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($selectedAddons as $addon)
                                                @php
                                                    $unitLabel = match ($addon['price_unit'] ?? '') {
                                                        'per_person' => 'لكل فرد',
                                                        'per_adult' => 'لكل بالغ',
                                                        'per_room' => 'لكل غرفة',
                                                        default => 'لكل حجز',
                                                    };
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <strong class="text-white">{{ $addon['title'] ?? '-' }}</strong>
                                                        @if (!empty($addon['description']))
                                                            <div class="small opacity-75 mt-1">{{ $addon['description'] }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td><span class="badge bg-secondary">{{ $unitLabel }}</span></td>
                                                    <td class="font-monospace">
                                                        {{ number_format((float) ($addon['amount'] ?? 0), 2) }}
                                                        {{ $addon['currency_code'] ?? $booking->currency_code }}
                                                    </td>
                                                    <td>{{ $addon['quantity'] ?? 1 }}</td>
                                                    <td class="text-end text-warning font-monospace fw-bold">
                                                        {{ number_format((float) ($addon['total'] ?? 0), 2) }}
                                                        {{ $addon['currency_code'] ?? $booking->currency_code }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="5" class="text-end text-white">إجمالي الإضافات المختارة:
                                                </th>
                                                <th class="text-end text-warning font-monospace fs-6">
                                                    {{ number_format($addonsTotal, 2) }} {{ $booking->currency_code }}
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-12">
                            <div class="info-box text-center py-4">
                                <span class="opacity-75"><i class="fas fa-info-circle me-1"></i> لا توجد إضافات أو خيارات
                                    إضافية مختارة لهذا الحجز.</span>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- 4. Financial & Cost Summary --}}
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="section-title">
                            <i class="fas fa-receipt"></i> ملخص التكلفة والحسابات المالية
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="finance-card">
                            <div class="finance-label">تكلفة الرحلة الأساسية</div>
                            <div class="finance-val font-monospace">{{ number_format($baseTotal, 2) }}
                                {{ $booking->currency_code }}</div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="finance-card">
                            <div class="finance-label">تكلفة الإضافات المختارة</div>
                            <div class="finance-val font-monospace">{{ number_format($addonsTotal, 2) }}
                                {{ $booking->currency_code }}</div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="finance-card"
                            style="background: rgba(40, 167, 69, 0.15); border-color: rgba(40, 167, 69, 0.4);">
                            <div class="finance-label text-success">المجموع الكلي للحجز</div>
                            <div class="finance-val font-monospace text-success">{{ number_format($grandTotal, 2) }}
                                {{ $booking->currency_code }}</div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="finance-card">
                            <div class="finance-label">الدفعة المقدمة المطلوبة</div>
                            <div class="finance-val font-monospace">
                                @if ($depositAmount !== null && $depositAmount > 0)
                                    {{ number_format($depositAmount, 2) }} {{ $booking->currency_code }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="finance-card"
                            style="background: rgba(23, 162, 184, 0.15); border-color: rgba(23, 162, 184, 0.4);">
                            <div class="finance-label text-info">المبلغ المدفوع</div>
                            <div class="finance-val font-monospace text-info">{{ number_format($paidAmount, 2) }}
                                {{ $booking->currency_code }}</div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="finance-card"
                            style="background: rgba(220, 53, 69, 0.15); border-color: rgba(220, 53, 69, 0.4);">
                            <div class="finance-label text-danger">المبلغ المتبقي</div>
                            <div class="finance-val font-monospace text-danger">{{ number_format($remainingAmount, 2) }}
                                {{ $booking->currency_code }}</div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="finance-card">
                            <div class="finance-label">حالة السداد</div>
                            <div class="finance-val">
                                <span
                                    class="badge {{ $paymentBadge }} fs-6">{{ $booking->payment_status ?: 'غير مدفوع' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="finance-card">
                            <div class="finance-label">بوابة الدفع</div>
                            <div class="finance-val">
                                @if ($paymentProvider === 'paypal')
                                    <span class="badge bg-primary fs-6"><i class="fab fa-paypal me-1"></i> PayPal</span>
                                @elseif ($paymentProvider === 'paymob')
                                    <span class="badge bg-info text-dark fs-6"><i class="fas fa-credit-card me-1"></i>
                                        Paymob (بطاقة بنكية)</span>
                                @elseif (!empty($paymentProvider))
                                    <span class="badge bg-secondary fs-6">{{ ucfirst($paymentProvider) }}</span>
                                @else
                                    <span class="opacity-75 fs-6">-</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Payments history records if any --}}
                    @if ($booking->payments->isNotEmpty())
                        <div class="col-12 mt-2">
                            <div class="info-box">
                                <div class="info-label mb-2"><i class="fas fa-history"></i> سجل المعاملات والمدفوعات
                                    الإلكترونية</div>
                                <div class="table-responsive table-dark-custom">
                                    <table class="table table-dark table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>رقم المعاملة / المرجع</th>
                                                <th>وسيلة الدفع</th>
                                                <th>المبلغ</th>
                                                <th>الحالة</th>
                                                <th>التاريخ والوقت</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($booking->payments as $payment)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td class="font-monospace">
                                                        {{ $payment->transaction_id ?: $payment->payment_reference ?? '-' }}
                                                    </td>
                                                    <td>{{ ucfirst($payment->provider ?: $payment->payment_method) }}</td>
                                                    <td class="font-monospace text-warning">
                                                        {{ number_format((float) $payment->amount, 2) }}
                                                        {{ $payment->currency_code }}</td>
                                                    <td>
                                                        @if ($payment->status === 'paid' || $payment->status === 'completed')
                                                            <span class="badge bg-success">ناجحة (Paid)</span>
                                                        @elseif ($payment->status === 'failed' || $payment->status === 'cancelled')
                                                            <span class="badge bg-danger">فشلت (Failed)</span>
                                                        @else
                                                            <span
                                                                class="badge bg-warning text-dark">{{ $payment->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="small opacity-75">
                                                        {{ optional($payment->created_at)->translatedFormat('d M Y - h:i A') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- 5. Travelers List --}}
                @if ($booking->travelers->isNotEmpty())
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="section-title">
                                <i class="fas fa-users"></i> بيانات المسافرين ({{ $booking->travelers->count() }} مسافر)
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="info-box">
                                <div class="table-responsive table-dark-custom">
                                    <table class="table table-dark table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>الفئة</th>
                                                <th>اللقب</th>
                                                <th>الاسم كما في جواز السفر</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($booking->travelers as $traveler)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        @if ($traveler->traveler_type === 'infant')
                                                            <span class="badge bg-info text-dark">رضيع (Infant)</span>
                                                        @elseif($traveler->traveler_type === 'child')
                                                            <span class="badge bg-warning text-dark">طفل (Child)</span>
                                                        @else
                                                            <span class="badge bg-primary">بالغ (Adult)</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $traveler->title }}</td>
                                                    <td class="fw-bold">{{ $traveler->first_name }}
                                                        {{ $traveler->last_name }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
