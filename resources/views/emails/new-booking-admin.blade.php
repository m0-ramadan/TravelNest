<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Booking</title>
</head>
<body style="margin:0;background:#f1f5f9;font-family:Arial,sans-serif;color:#172033">
    <div style="max-width:680px;margin:0 auto;padding:28px 16px">
        <div style="background:#172033;color:#fff;padding:22px 26px;border-radius:14px 14px 0 0">
            <div style="font-size:13px;color:#7dd3fc">ETRO TOURS</div>
            <h1 style="font-size:24px;margin:8px 0 0">New website booking</h1>
        </div>
        <div style="background:#fff;padding:26px;border-radius:0 0 14px 14px">
            <p style="margin-top:0">A new booking has been submitted through the website.</p>
            <table style="width:100%;border-collapse:collapse;font-size:14px">
                <tr><td style="padding:9px 0;color:#64748b">Booking reference</td><td style="padding:9px 0;font-weight:700">{{ $booking->booking_number }}</td></tr>
                <tr><td style="padding:9px 0;color:#64748b">Tour</td><td style="padding:9px 0;font-weight:700">{{ $booking->package?->display_title ?? '—' }}</td></tr>
                <tr><td style="padding:9px 0;color:#64748b">Customer</td><td style="padding:9px 0">{{ $booking->client?->full_name ?? '—' }}</td></tr>
                <tr><td style="padding:9px 0;color:#64748b">Email</td><td style="padding:9px 0"><a href="mailto:{{ $booking->client?->email }}">{{ $booking->client?->email ?? '—' }}</a></td></tr>
                <tr><td style="padding:9px 0;color:#64748b">Phone</td><td style="padding:9px 0">{{ $booking->client?->phone ?? '—' }}</td></tr>
                <tr><td style="padding:9px 0;color:#64748b">Travel date</td><td style="padding:9px 0">{{ $booking->travel_date?->format('d M Y') ?? '—' }}</td></tr>
                <tr><td style="padding:9px 0;color:#64748b">Travelers</td><td style="padding:9px 0">{{ $booking->adults }} adults, {{ $booking->children }} children, {{ $booking->infants }} infants</td></tr>
                <tr><td style="padding:9px 0;color:#64748b">Total</td><td style="padding:9px 0;font-weight:700">{{ number_format((float) $booking->total_amount, 2) }} {{ $booking->currency_code }}</td></tr>
                <tr><td style="padding:9px 0;color:#64748b">Payment</td><td style="padding:9px 0">{{ ucfirst($booking->payment_status) }}</td></tr>
            </table>
            @if ($booking->pickup_location)
                <p><strong>Pickup:</strong> {{ $booking->pickup_location }}</p>
            @endif
            @if ($booking->special_requests)
                <p><strong>Special requests:</strong><br>{{ $booking->special_requests }}</p>
            @endif
        </div>
    </div>
</body>
</html>
