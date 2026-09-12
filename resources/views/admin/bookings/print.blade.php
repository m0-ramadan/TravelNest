@include('admin.i18n.locale')
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>طباعة تفاصيل الحجز - {{ $booking->booking_reference ?? ($booking->booking_number ?? '') }}</title>
    <style>
        body {
            font-family: DejaVu Sans, "Cairo", Tahoma, sans-serif;
            direction: rtl;
            margin: 25px;
            color: #222;
            background: #fff;
            font-size: 13px;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 6px;
            color: #111;
        }

        .subtitle {
            font-size: 14px;
            color: #555;
        }

        .box {
            border: 1px solid #ddd;
            padding: 14px;
            margin-bottom: 15px;
            border-radius: 6px;
            background: #fafafa;
        }

        .box-title {
            font-weight: bold;
            font-size: 14px;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 6px;
            margin-bottom: 10px;
            color: #333;
        }

        .grid-row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -10px;
            margin-left: -10px;
        }

        .grid-col {
            flex: 1;
            padding: 4px 10px;
            min-width: 200px;
        }

        .label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            min-width: 130px;
        }

        .val {
            font-weight: 600;
            color: #111;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 8px;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: right;
            font-size: 12px;
        }

        table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .text-left {
            text-align: left;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 4px;
            border: 1px solid #999;
        }

        .print-btn {
            background: #2b3b4c;
            color: #fff;
            padding: 8px 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .print-btn:hover {
            background: #1e2833;
        }

        @media print {
            .print-btn {
                display: none;
            }

            body {
                margin: 0;
                background: #fff;
            }

            .box {
                border-color: #bbb;
                background: #fff;
            }
        }
    </style>
</head>

<body>

    <button onclick="window.print()" class="print-btn">طباعة المستند / Print</button>

    @php
        $package = $booking->package;
        $packageType = $package->package_type ?? 'travel_package';
        $checkoutDetails = is_array($booking->checkout_details) ? $booking->checkout_details : [];

        // Main booking items vs Add-on items
        $addonItems = $booking->items->where('pricing_source', 'package_addon');
        $mainItems = $booking->items->reject(fn($item) => $item->pricing_source === 'package_addon');
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

        $packageTypeLabel = match ($packageType) {
            'day_tour' => 'جولة يومية (Day Tour)',
            'shore_excursion' => 'رحلة شاطئية (Shore Excursion)',
            'nile_cruise' => 'نايل كروز (Nile Cruise)',
            default => 'باقة سفر (Travel Package)',
        };

        $roomBreakdown = $checkoutDetails['room_breakdown'] ?? ($mainItems->first()?->meta['room_breakdown'] ?? []);
    @endphp

    <div class="header">
        <div class="title">تفاصيل وسند الحجز (Booking Voucher)</div>
        <div class="subtitle">
            رقم المرجع: <strong>{{ $booking->booking_reference ?? ($booking->booking_number ?? '-') }}</strong>
            · نوع الرحلة: <strong>{{ $packageTypeLabel }}</strong>
            · تاريخ الإنشاء: {{ optional($booking->created_at)->translatedFormat('d M Y - h:i A') ?? '-' }}
        </div>
    </div>

    {{-- Client & Basic Info --}}
    <div class="box">
        <div class="box-title">بيانات العميل والحجز الأساسية</div>
        <div class="grid-row">
            <div class="grid-col"><span class="label">اسم العميل:</span> <span
                    class="val">{{ $booking->client->name ?? ($booking->client_name ?? '-') }}</span></div>
            <div class="grid-col"><span class="label">البريد الإلكتروني:</span> <span
                    class="val">{{ $booking->email ?? '-' }}</span></div>
        </div>
        <div class="grid-row">
            <div class="grid-col"><span class="label">رقم الهاتف:</span> <span class="val"
                    dir="ltr">{{ $booking->phone ?? '-' }}</span></div>
            <div class="grid-col"><span class="label">الجنسية:</span> <span
                    class="val">{{ $booking->client->nationality ?? '-' }}</span></div>
        </div>
        <div class="grid-row">
            <div class="grid-col"><span class="label">الباقة / الرحلة:</span> <span
                    class="val">{{ $booking->package->name ?? '-' }}</span></div>
            <div class="grid-col"><span class="label">تاريخ السفر:</span> <span
                    class="val">{{ optional($booking->travel_date)->translatedFormat('l، d F Y') ?? '-' }}</span>
            </div>
        </div>
        <div class="grid-row">
            <div class="grid-col">
                <span class="label">عدد المسافرين:</span>
                <span class="val">{{ $booking->travellers_count ?? '-' }} ({{ $booking->adults ?? 0 }} بالغين ·
                    {{ $booking->children ?? 0 }} أطفال · {{ $booking->infants ?? 0 }} رضع)</span>
            </div>
            <div class="grid-col">
                <span class="label">حالة الحجز:</span>
                <span class="badge">{{ $booking->status ?? '-' }}</span>
            </div>
        </div>
        @if (!empty($booking->pickup_location))
            <div class="grid-row" style="margin-top: 6px;">
                <div class="grid-col" style="flex: 100%;">
                    <span class="label" style="color: #b85d19;">مكان الالتقاء / التوصيل:</span>
                    <span class="val" style="color: #b85d19;">{{ $booking->pickup_location }}</span>
                </div>
            </div>
        @endif
    </div>

    {{-- Package-Type-Specific Details --}}
    <div class="box">
        <div class="box-title">تفاصيل الباقة المحجوزة ({{ $packageTypeLabel }})</div>

        @if (in_array($packageType, ['day_tour', 'shore_excursion']))
            @php
                $mainItem = $mainItems->first();
                $tierLabel = $mainItem?->option_label ?: $checkoutDetails['option_label'] ?? 'الخطة الأساسية للرحلة';
                $tierDesc = $mainItem?->meta['description'] ?? '';
                $tierUnitPrice = (float) ($mainItem?->unit_price ?: $baseTotal / max(1, $booking->travellers_count));
                $tierQty = $mainItem?->quantity ?: $booking->travellers_count;
            @endphp
            <div class="grid-row">
                <div class="grid-col"><span class="label">فئة / خطة الرحلة:</span> <span
                        class="val">{{ $tierLabel }} {{ $tierDesc ? "($tierDesc)" : '' }}</span></div>
                <div class="grid-col"><span class="label">سعر الفرد:</span> <span
                        class="val">{{ number_format($tierUnitPrice, 2) }} {{ $booking->currency_code }}</span>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-col"><span class="label">عدد المشتركين:</span> <span
                        class="val">{{ $tierQty }} فرد</span></div>
                <div class="grid-col"><span class="label">تكلفة الجولة الأساسية:</span> <span
                        class="val">{{ number_format($baseTotal, 2) }} {{ $booking->currency_code }}</span></div>
            </div>
        @elseif ($packageType === 'travel_package')
            @php
                $mainItem = $mainItems->first();
                $accLabel = $mainItem?->option_label ?: $checkoutDetails['option_label'] ?? 'Standard';
                $roomCount = $mainItem?->room_count ?: ($checkoutDetails['rooms'] ?? count($roomBreakdown) ?: 1);
            @endphp
            <div class="grid-row">
                <div class="grid-col"><span class="label">فئة الإقامة والفنادق:</span> <span
                        class="val">{{ $accLabel }}</span></div>
                <div class="grid-col"><span class="label">عدد الغرف:</span> <span class="val">{{ $roomCount }}
                        غرفة</span></div>
                <div class="grid-col"><span class="label">سعر الإقامة الأساسي:</span> <span
                        class="val">{{ number_format($baseTotal, 2) }} {{ $booking->currency_code }}</span></div>
            </div>

            @if (!empty($roomBreakdown))
                <table style="margin-top: 10px;">
                    <thead>
                        <tr>
                            <th>الغرفة</th>
                            <th>فئة الإقامة</th>
                            <th>البالغين</th>
                            <th>الأطفال</th>
                            <th class="text-left">سعر الغرفة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roomBreakdown as $rb)
                            <tr>
                                <td>غرفة {{ $rb['room_number'] ?? $loop->iteration }}</td>
                                <td>{{ $rb['accommodation'] ?? $accLabel }}</td>
                                <td>{{ $rb['adults'] ?? 1 }}</td>
                                <td>{{ $rb['children'] ?? 0 }}</td>
                                <td class="text-left">{{ number_format((float) ($rb['price'] ?? 0), 2) }}
                                    {{ $booking->currency_code }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @elseif ($packageType === 'nile_cruise')
            @php
                $mainItem = $mainItems->first();
                $shipName = $package?->nileCruiseDetail?->ship_name ?: $package?->name ?? 'Nile Cruise Ship';
                $cabinLabel = $mainItem?->option_label ?: $mainItem?->cabin?->name ?? 'Cabin';
                $deckName = $mainItem?->cabin?->deck ?? '-';
                $occupancy = $mainItem?->occupancy_type ?? '-';
                $cabinCount = $mainItem?->room_count ?: 1;
                $boardBasis = $package?->nileCruiseDetail?->board_basis ?: 'Full Board';
            @endphp
            <div class="grid-row">
                <div class="grid-col"><span class="label">اسم الباخرة:</span> <span
                        class="val">{{ $shipName }}</span></div>
                <div class="grid-col"><span class="label">الكابينة:</span> <span
                        class="val">{{ $cabinLabel }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-col"><span class="label">الطابق / السطح:</span> <span
                        class="val">{{ $deckName }}</span></div>
                <div class="grid-col"><span class="label">نوع الإشغال:</span> <span
                        class="val">{{ $occupancy }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-col"><span class="label">نظام الوجبات:</span> <span
                        class="val">{{ $boardBasis }}</span></div>
                <div class="grid-col"><span class="label">عدد الكابينات والإجمالي:</span> <span
                        class="val">{{ $cabinCount }} كابينة - {{ number_format($baseTotal, 2) }}
                        {{ $booking->currency_code }}</span></div>
            </div>
        @else
            @foreach ($mainItems as $item)
                <div class="grid-row">
                    <div class="grid-col"><span class="label">خيار التسعير:</span> <span
                            class="val">{{ $item->option_label }}</span></div>
                    <div class="grid-col"><span class="label">الكمية:</span> <span
                            class="val">{{ $item->room_count ?: $item->quantity }}</span></div>
                    <div class="grid-col"><span class="label">السعر:</span> <span
                            class="val">{{ number_format((float) $item->total_amount, 2) }}
                            {{ $booking->currency_code }}</span></div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Add-ons Table --}}
    @if ($addonItems->isNotEmpty() || !empty($selectedAddons))
        <div class="box">
            <div class="box-title">الإضافات والخيارات المختارة (Optional Add-ons)</div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الإضافة</th>
                        <th>نوع التسعير</th>
                        <th>سعر الوحدة</th>
                        <th>الكمية</th>
                        <th class="text-left">الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($addonItems->isNotEmpty())
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
                                <td><strong>{{ $item->option_label }}</strong></td>
                                <td>{{ $unitLabel }}</td>
                                <td>{{ number_format((float) $item->unit_price, 2) }}
                                    {{ $item->meta['currency_code'] ?? $booking->currency_code }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-left font-monospace">
                                    {{ number_format((float) $item->total_amount, 2) }}
                                    {{ $item->meta['currency_code'] ?? $booking->currency_code }}</td>
                            </tr>
                        @endforeach
                    @else
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
                                <td><strong>{{ $addon['title'] ?? '-' }}</strong></td>
                                <td>{{ $unitLabel }}</td>
                                <td>{{ number_format((float) ($addon['amount'] ?? 0), 2) }}
                                    {{ $addon['currency_code'] ?? $booking->currency_code }}</td>
                                <td>{{ $addon['quantity'] ?? 1 }}</td>
                                <td class="text-left font-monospace">
                                    {{ number_format((float) ($addon['total'] ?? 0), 2) }}
                                    {{ $addon['currency_code'] ?? $booking->currency_code }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" style="text-align: left;">إجمالي الإضافات:</th>
                        <th class="text-left">{{ number_format($addonsTotal, 2) }} {{ $booking->currency_code }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

    {{-- Financial Summary --}}
    <div class="box">
        <div class="box-title">ملخص التكلفة والحسابات المالية</div>
        <div class="grid-row">
            <div class="grid-col"><span class="label">تكلفة الرحلة الأساسية:</span> <span
                    class="val">{{ number_format($baseTotal, 2) }} {{ $booking->currency_code }}</span></div>
            <div class="grid-col"><span class="label">تكلفة الإضافات:</span> <span
                    class="val">{{ number_format($addonsTotal, 2) }} {{ $booking->currency_code }}</span></div>
        </div>
        <div class="grid-row">
            <div class="grid-col"><span class="label">المجموع الكلي:</span> <span class="val"
                    style="font-size: 15px; color: #0d6efd;">{{ number_format($grandTotal, 2) }}
                    {{ $booking->currency_code }}</span></div>
            @if ($depositAmount !== null && $depositAmount > 0)
                <div class="grid-col"><span class="label">الدفعة المقدمة المطلوبة:</span> <span
                        class="val">{{ number_format($depositAmount, 2) }} {{ $booking->currency_code }}</span>
                </div>
            @else
                <div class="grid-col"></div>
            @endif
        </div>
        <div class="grid-row">
            <div class="grid-col"><span class="label">المبلغ المدفوع:</span> <span class="val"
                    style="color: #198754;">{{ number_format($paidAmount, 2) }} {{ $booking->currency_code }}</span>
            </div>
            <div class="grid-col"><span class="label">المبلغ المتبقي:</span> <span class="val"
                    style="color: #dc3545;">{{ number_format($remainingAmount, 2) }}
                    {{ $booking->currency_code }}</span></div>
        </div>
        <div class="grid-row">
            <div class="grid-col"><span class="label">حالة الدفع:</span> <span
                    class="badge">{{ $booking->payment_status ?: 'unpaid' }}</span></div>
            <div class="grid-col"><span class="label">وسيلة الدفع:</span> <span
                    class="val">{{ ucfirst($paymentProvider ?: 'غير محدد') }}</span></div>
        </div>
    </div>

    {{-- Travelers --}}
    @if ($booking->travelers->isNotEmpty())
        <div class="box">
            <div class="box-title">بيانات المسافرين ({{ $booking->travelers->count() }})</div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الفئة</th>
                        <th>اللقب</th>
                        <th>الاسم بالكامل</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($booking->travelers as $traveler)
                        @php
                            $typeLabel = match ($traveler->traveler_type) {
                                'infant' => 'رضيع (Infant)',
                                'child' => 'طفل (Child)',
                                default => 'بالغ (Adult)',
                            };
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $typeLabel }}</td>
                            <td>{{ $traveler->title }}</td>
                            <td><strong>{{ $traveler->first_name }} {{ $traveler->last_name }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Notes --}}
    @if (!empty($booking->special_requests) || !empty($booking->notes))
        <div class="box">
            <div class="box-title">ملاحظات وطلبات خاصة</div>
            <div>{{ $booking->special_requests ?: $booking->notes }}</div>
        </div>
    @endif

</body>

</html>
