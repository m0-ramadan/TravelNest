<style>
    .nc-fare-card {
        margin-bottom: 18px;
        border: 1px solid #e5e9ed;
        border-radius: 14px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 4px 14px rgba(20, 40, 60, .05);
    }

    .nc-fare-card summary {
        display: grid;
        grid-template-columns: 1fr 1fr auto 18px;
        align-items: center;
        gap: 16px;
        padding: 20px;
        cursor: pointer;
        list-style: none;
        background: linear-gradient(110deg, #1c355c, #204d59);
        color: #fff;
    }

    .nc-fare-card summary::-webkit-details-marker {
        display: none;
    }

    .nc-fare-duration {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
    }

    .nc-fare-period {
        font-size: .85rem;
        color: #e1ecf1;
        text-align: center;
    }

    .nc-fare-from {
        color: #d9a35d;
        font-weight: 700;
        white-space: nowrap;
    }

    .nc-fare-chevron {
        color: #d9a35d;
        transition: transform .2s;
    }

    .nc-fare-card[open] .nc-fare-chevron {
        transform: rotate(180deg);
    }

    .nc-fare-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 24px 20px;
        border-top: 1px solid #f0f1f4;
    }

    .nc-fare-label {
        font-weight: 600;
        color: #29436a;
    }

    .nc-fare-amount {
        text-align: end;
    }

    .nc-fare-amount strong {
        color: #bd8b48;
        font: 700 1.35rem 'Playfair Display', serif;
    }

    .nc-fare-currency {
        color: #7b8798;
        font-size: .7rem;
        margin-inline-end: 8px;
    }

    .nc-fare-note {
        display: block;
        color: #7b8798;
        font-size: .75rem;
        margin-top: 5px;
    }

    html[data-theme='dark'] .nc-fare-card {
        background: #142238;
        border-color: #33445a;
    }

    html[data-theme='dark'] .nc-fare-row {
        border-color: #33445a;
    }

    html[data-theme='dark'] .nc-fare-label {
        color: #e0e8f4;
    }

    @media(max-width: 575px) {
        .nc-fare-card summary {
            gap: 8px;
            padding: 16px 12px;
            grid-template-columns: 1fr auto 14px;
        }

        .nc-fare-period {
            grid-column: 1;
            grid-row: 2;
            text-align: start;
        }

        .nc-fare-from {
            grid-column: 2;
            grid-row: 1 / span 2;
            font-size: .85rem;
        }

        .nc-fare-chevron {
            grid-column: 3;
            grid-row: 1 / span 2;
        }

        .nc-fare-row {
            padding: 20px 12px;
        }
    }
</style>
@php $fareAccommodations = $package->tourPackageAccommodations->where('is_active', true); @endphp
@foreach ($fareAccommodations as $accommodation)
    @if ($fareAccommodations->count() > 1)
        <h3 class="mb-3">{{ $accommodation->name }}</h3>
    @endif
    @foreach ($accommodation->seasons->where('is_active', true)->values() as $seasonIndex => $season)
        @php
            $fareItems = $season->items->where('is_active', true)->filter(fn($item) => (float) $item->price > 0);
            $fareSymbol = $season->currency?->symbol ?: $currencySymbol ?? ($package->currency?->symbol ?? '$');
            $fareCode = $season->currency?->code ?: ($package->currency?->code ?: 'USD');
        @endphp
        @if ($fareItems->isNotEmpty())
            <details class="nc-fare-card" {{ $seasonIndex < 2 ? 'open' : '' }}>
                <summary>
                    <span class="nc-fare-duration">{{ $season->display_season_name }}</span>
                    <span
                        class="nc-fare-period">{{ $season->period ?: trim(($season->date_from?->format('M d') ?? '') . ' – ' . ($season->date_to?->format('M d') ?? ''), ' –') }}</span>
                    <span class="nc-fare-from">{{ __('From') }}: {{ $fareSymbol }}{{ number_format((float) $fareItems->min('price'), 0) }}</span>
                    <i class="la la-chevron-down nc-fare-chevron" aria-hidden="true"></i>
                </summary>
                @foreach ($fareItems as $item)
                    @php
                        $cabinLabels = [
                            'triple' => __('Triple Cabin'),
                            'double' => __('Double Cabin'),
                            'single' => __('Single Cabin'),
                        ];
                        $cabinNotes = [
                            'triple' => __('per adult in a triple share cabin'),
                            'double' => __('per adult in a double share cabin'),
                            'single' => __('per adult in a single share cabin'),
                        ];
                    @endphp
                    <div class="nc-fare-row">
                        <span
                            class="nc-fare-label">{{ $cabinLabels[$item->occupancy_type] ?? $item->display_label }}</span>
                        <div class="nc-fare-amount">
                            <span class="nc-fare-currency">{{ $fareCode }}</span>
                            <strong>{{ $fareSymbol }}{{ number_format((float) $item->price, 0) }}</strong>
                            <small
                                class="nc-fare-note">{{ $item->price_unit === 'per_person' ? $cabinNotes[$item->occupancy_type] ?? __('per person') : __($item->price_unit) }}</small>
                        </div>
                    </div>
                @endforeach
            </details>
        @endif
    @endforeach
@endforeach
