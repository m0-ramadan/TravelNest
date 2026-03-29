<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>طباعة الحجز</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            direction: rtl;
            margin: 30px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .box {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        .row {
            margin-bottom: 10px;
        }

        .label {
            font-weight: bold;
            display: inline-block;
            min-width: 160px;
        }

        .title {
            font-size: 26px;
            margin-bottom: 10px;
        }

        .print-btn {
            margin-bottom: 20px;
        }

        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>

<body>

    <button onclick="window.print()" class="print-btn">طباعة</button>

    <div class="header">
        <div class="title">تفاصيل الحجز</div>
        <div>مرجع الحجز: {{ $booking->booking_reference ?? '-' }}</div>
    </div>

    <div class="box">
        <div class="row"><span class="label">اسم العميل:</span>
            {{ $booking->client->name ?? ($booking->client_name ?? '-') }}</div>
        <div class="row"><span class="label">البريد الإلكتروني:</span> {{ $booking->email ?? '-' }}</div>
        <div class="row"><span class="label">الهاتف:</span> {{ $booking->phone ?? '-' }}</div>
    </div>

    <div class="box">
        <div class="row"><span class="label">الباقة:</span> {{ $booking->package->name ?? '-' }}</div>
        <div class="row"><span class="label">الحالة:</span> {{ $booking->status ?? '-' }}</div>
        <div class="row"><span class="label">عدد الأفراد:</span> {{ $booking->travellers_count ?? '-' }}</div>
        <div class="row"><span class="label">تاريخ السفر:</span>
            {{ optional($booking->travel_date)->translatedFormat('d M Y') ?? '-' }}</div>
    </div>

    <div class="box">
        <div class="row"><span class="label">إجمالي السعر:</span>
            {{ number_format($booking->total_amount ?? 0, 2) }} {{ $booking->currency_code ?? '' }}</div>
        <div class="row"><span class="label">تاريخ الإنشاء:</span>
            {{ optional($booking->created_at)->translatedFormat('d M Y - h:i A') ?? '-' }}</div>
    </div>

    <div class="box">
        <div class="row"><span class="label">ملاحظات:</span></div>
        <div>{{ $booking->notes ?: 'لا توجد ملاحظات' }}</div>
    </div>

</body>

</html>
