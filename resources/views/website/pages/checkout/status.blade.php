@extends('website.layouts.master')
@section('title', __('Payment Status'))
@section('robots', 'noindex, nofollow')
@section('preferred_theme', 'light')
@section('body_class', 'checkout-page')

@section('css')
    <style>
        .checkout-page .why-choose-section,
        .checkout-page .luxury-cta-section {
            display: none !important;
        }

        .payment-result {
            background: #f8f6f1;
            padding: 60px 15px;
            min-height: 70vh;
        }

        .result-card {
            max-width: 720px;
            margin: auto;
            background: #fff;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 18px 45px rgba(21, 39, 67, .08);
        }

        .result-icon {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            margin: 0 auto 18px;
            font-size: 2.4rem;
            background: #eaf8f1;
            color: #1ca36e;
        }

        .result-icon.pending {
            background: #fff6e7;
            color: #c98a32;
        }

        .result-icon.failed {
            background: #fff0f0;
            color: #c84444;
        }

        .result-card h1 {
            font-family: 'Playfair Display', serif;
            color: #1c325c;
            font-size: 1.8rem;
            margin-bottom: 10px;
            text-align: center;
        }

        .result-subtitle {
            text-align: center;
            color: #667085;
            font-size: 0.95rem;
            margin-bottom: 25px;
        }

        .booking-summary-box {
            background: #fbfbfd;
            border: 1px solid #e9ecef;
            border-radius: 16px;
            padding: 24px;
            margin: 25px 0;
            text-align: start;
        }

        .summary-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed #d0d5dd;
            padding-bottom: 14px;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .summary-header h3 {
            font-size: 1.15rem;
            color: #1c325c;
            margin: 0;
            font-weight: 700;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.92rem;
            color: #475467;
        }

        .detail-label {
            font-weight: 600;
            color: #344054;
        }

        .detail-value {
            font-weight: 500;
            text-align: end;
        }

        .addons-list {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #eaecf0;
            padding: 12px 16px;
            margin: 14px 0;
        }

        .addon-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid #f2f4f7;
            font-size: 0.88rem;
        }

        .addon-item:last-child {
            border-bottom: none;
        }

        .cost-divider {
            border-top: 1px solid #d0d5dd;
            margin: 14px 0 10px;
        }

        .cost-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.05rem;
            font-weight: 700;
            color: #1c325c;
        }

        .cost-paid {
            color: #16a34a;
        }

        .cost-remain {
            color: #dc2626;
        }

        .result-card .btn-home {
            display: inline-flex;
            background: #d4a05d;
            color: #173763;
            padding: 12px 28px;
            border-radius: 999px;
            font-weight: 800;
            text-decoration: none;
            transition: 0.2s;
            margin-top: 10px;
        }

        .result-card .btn-home:hover {
            background: #c5914c;
        }

        .text-center {
            text-align: center;
        }
    </style>
@endsection

@section('content')
    @php
        $isPaid = $payment->status === \App\Models\Payment::STATUS_PAID;
        $isFailed =
            in_array($payment->status, [\App\Models\Payment::STATUS_FAILED, 'cancelled'], true) ||
            request('result') === 'cancelled';
        $booking = $payment->booking;
        $package = $booking?->package;
        $checkoutDetails = is_array($booking?->checkout_details) ? $booking->checkout_details : [];

        $addonItems = $booking?->items ? $booking->items->where('pricing_source', 'package_addon') : collect();
        $mainItems = $booking?->items
            ? $booking->items->reject(fn($item) => $item->pricing_source === 'package_addon')
            : collect();
        $selectedAddons = $checkoutDetails['selected_addons'] ?? [];

        $packageType = $package?->package_type ?? 'travel_package';
        $packageTypeLabel = match ($packageType) {
            'day_tour' => __('Day Tour'),
            'shore_excursion' => __('Shore Excursion'),
            'nile_cruise' => __('Nile Cruise'),
            default => __('Travel Package'),
        };

        $optionLabel = $mainItems->first()?->option_label ?: $checkoutDetails['option_label'] ?? '';
        $baseTotal =
            (float) ($checkoutDetails['base_total'] ?? ($mainItems->sum('total_amount') ?: $booking?->total_amount));
        $addonsTotal = (float) ($checkoutDetails['addons_total'] ?? $addonItems->sum('total_amount'));
        $grandTotal = (float) ($booking?->total_amount ?: $checkoutDetails['grand_total'] ?? $baseTotal + $addonsTotal);
        $paidAmount = (float) ($booking?->paid_amount ?: $payment->amount);
        $remainingAmount = (float) ($booking?->remaining_amount ?? max(0, $grandTotal - $paidAmount));
    @endphp

    <section class="payment-result">
        <div class="result-card">
            <div class="result-icon {{ $isPaid ? '' : ($isFailed ? 'failed' : 'pending') }}">
                <i class="la {{ $isPaid ? 'la-check' : ($isFailed ? 'la-times' : 'la-clock') }}"></i>
            </div>

            <h1>{{ $isPaid ? __('Payment Successful') : ($isFailed ? __('Payment Not Completed') : __('Payment Is Processing')) }}
            </h1>
            <p class="result-subtitle">
                {{ $isPaid ? __('Your booking has been confirmed and paid successfully. A confirmation email has been sent.') : ($isFailed ? __('Your booking is saved, but the payment was not completed. Please contact us to retry.') : __('We are confirming the payment with the provider. Your booking will update automatically once confirmed.')) }}
            </p>

            @if ($booking)
                <div class="booking-summary-box">
                    <div class="summary-header">
                        <div>
                            <h3>{{ $package?->name ?? __('Booking Details') }}</h3>
                            <small class="badge bg-light text-dark border">{{ $packageTypeLabel }}</small>
                        </div>
                        <div class="text-end">
                            <small class="text-muted d-block">{{ __('Reference') }}</small>
                            <strong class="font-monospace text-primary">{{ $booking->booking_number }}</strong>
                        </div>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">{{ __('Travel Date') }}:</span>
                        <span
                            class="detail-value">{{ optional($booking->travel_date)->translatedFormat('d M Y') ?? '-' }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">{{ __('Travelers') }}:</span>
                        <span class="detail-value">{{ $booking->travellers_count ?? 1 }} ({{ $booking->adults ?? 0 }}
                            {{ __('Adults') }} · {{ $booking->children ?? 0 }} {{ __('Children') }})</span>
                    </div>

                    @if ($optionLabel)
                        <div class="detail-row">
                            <span class="detail-label">{{ __('Selected Option / Tier') }}:</span>
                            <span class="detail-value text-dark fw-bold">{{ $optionLabel }}</span>
                        </div>
                    @endif

                    @if (!empty($booking->pickup_location))
                        <div class="detail-row">
                            <span class="detail-label">{{ __('Pickup Location') }}:</span>
                            <span class="detail-value text-warning fw-bold">{{ $booking->pickup_location }}</span>
                        </div>
                    @endif

                    {{-- Add-ons if any --}}
                    @if ($addonItems->isNotEmpty() || !empty($selectedAddons))
                        <div class="addons-list">
                            <div class="detail-label mb-2" style="font-size: 0.85rem; color: #667085;">
                                {{ __('Selected Add-ons & Extras') }}:</div>
                            @if ($addonItems->isNotEmpty())
                                @foreach ($addonItems as $addon)
                                    <div class="addon-item">
                                        <span>{{ $addon->option_label }} (×{{ $addon->quantity }})</span>
                                        <span class="font-monospace fw-bold">{{ $booking->currency_code }}
                                            {{ number_format((float) $addon->total_amount, 2) }}</span>
                                    </div>
                                @endforeach
                            @else
                                @foreach ($selectedAddons as $addon)
                                    <div class="addon-item">
                                        <span>{{ $addon['title'] ?? '-' }} (×{{ $addon['quantity'] ?? 1 }})</span>
                                        <span class="font-monospace fw-bold">{{ $booking->currency_code }}
                                            {{ number_format((float) ($addon['total'] ?? 0), 2) }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endif

                    <div class="cost-divider"></div>

                    <div class="detail-row">
                        <span class="detail-label">{{ __('Base Price') }}:</span>
                        <span class="detail-value">{{ $booking->currency_code }} {{ number_format($baseTotal, 2) }}</span>
                    </div>

                    @if ($addonsTotal > 0)
                        <div class="detail-row">
                            <span class="detail-label">{{ __('Add-ons Total') }}:</span>
                            <span class="detail-value">{{ $booking->currency_code }}
                                {{ number_format($addonsTotal, 2) }}</span>
                        </div>
                    @endif

                    <div class="cost-total my-2">
                        <span>{{ __('Total Amount') }}:</span>
                        <span>{{ $booking->currency_code }} {{ number_format($grandTotal, 2) }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">{{ __('Amount Paid') }}:</span>
                        <span class="detail-value cost-paid">{{ $booking->currency_code }}
                            {{ number_format($paidAmount, 2) }}</span>
                    </div>

                    @if ($remainingAmount > 0)
                        <div class="detail-row">
                            <span class="detail-label">{{ __('Remaining Balance') }}:</span>
                            <span class="detail-value cost-remain">{{ $booking->currency_code }}
                                {{ number_format($remainingAmount, 2) }}</span>
                        </div>
                    @endif
                </div>
            @else
                <div class="result-ref">
                    <strong>{{ __('Payment Reference') }}:</strong> {{ $payment->transaction_reference }}<br>
                    <strong>{{ __('Amount') }}:</strong> {{ $payment->currency_code }}
                    {{ number_format((float) $payment->amount, 2) }}
                </div>
            @endif

            <div class="text-center">
                <a href="{{ route('website.home') }}" class="btn-home">{{ __('Back to Home') }}</a>
            </div>
        </div>
    </section>
@endsection
