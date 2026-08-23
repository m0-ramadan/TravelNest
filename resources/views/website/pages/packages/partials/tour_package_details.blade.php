@if($package->package_type === 'travel_package')
    @php
        $tourPackageCities = ($packageCities ?? collect())->map(fn($city) => $city->display_name)->filter()->values();
        $packageDetail = $tourPackageDetail ?? null;
        $mealLabels = collect((array) ($packageDetail?->meals_included ?? []))->filter()->values();
        $groupText = null;
        if ($package->min_participants && $package->max_participants) {
            $groupText = $package->min_participants . '–' . $package->max_participants . ' ' . __('Pax');
        } elseif ($package->max_participants) {
            $groupText = __('Up to') . ' ' . $package->max_participants . ' ' . __('Pax');
        }
    @endphp

    <section class="content-section" id="tour-package-overview">
        <h2 class="section-header">{{ __('Tour Package Details') }}</h2>
        <div class="cruise-details">
            @if($durationText)
                <div class="detail-item"><i class="la la-calendar"></i><div class="detail-text"><strong class="detail-label">{{ __('Duration:') }}</strong><span class="detail-value">{{ $durationText }}</span></div></div>
            @endif
            @if($tourPackageCities->isNotEmpty())
                <div class="detail-item"><i class="la la-route"></i><div class="detail-text"><strong class="detail-label">{{ __('Cities / Route:') }}</strong><span class="detail-value">{{ $tourPackageCities->implode(' / ') }}</span></div></div>
                <div class="detail-item"><i class="la la-map-marker"></i><div class="detail-text"><strong class="detail-label">{{ __('Primary Start City:') }}</strong><span class="detail-value">{{ $tourPackageCities->first() }}</span></div></div>
            @elseif($destinations)
                <div class="detail-item"><i class="la la-route"></i><div class="detail-text"><strong class="detail-label">{{ __('Cities / Route:') }}</strong><span class="detail-value">{{ $destinations }}</span></div></div>
            @endif
            @if($groupText)
                <div class="detail-item"><i class="la la-users"></i><div class="detail-text"><strong class="detail-label">{{ __('Group Size:') }}</strong><span class="detail-value">{{ $groupText }}</span></div></div>
            @endif
            @if($package->difficulty)
                <div class="detail-item"><i class="la la-mountain"></i><div class="detail-text"><strong class="detail-label">{{ __('Difficulty:') }}</strong><span class="detail-value">{{ __(\Illuminate\Support\Str::headline((string)$package->difficulty)) }}</span></div></div>
            @endif
            @if(($onTourLanguages ?? collect())->isNotEmpty())
                <div class="detail-item"><i class="la la-language"></i><div class="detail-text"><strong class="detail-label">{{ __('Languages:') }}</strong><span class="detail-value">{{ $onTourLanguages->implode(' · ') }}</span></div></div>
            @endif
            @if($packageDetail?->accommodation_standard)
                <div class="detail-item"><i class="la la-hotel"></i><div class="detail-text"><strong class="detail-label">{{ __('Accommodation:') }}</strong><span class="detail-value">{{ $packageDetail->accommodation_standard }}</span></div></div>
            @endif
            @if($mealLabels->isNotEmpty())
                <div class="detail-item"><i class="la la-utensils"></i><div class="detail-text"><strong class="detail-label">{{ __('Meals Included:') }}</strong><span class="detail-value">{{ $mealLabels->implode(' · ') }}</span></div></div>
            @endif
            @if($packageDetail)
                <div class="detail-item"><i class="la la-sliders-h"></i><div class="detail-text"><strong class="detail-label">{{ __('Flexible Itinerary:') }}</strong><span class="detail-value">{{ $packageDetail->flexible_itinerary ? __('Available on request') : __('Fixed itinerary') }}</span></div></div>
            @endif
        </div>

        @if(($operatingDays ?? collect())->isNotEmpty())
            <div class="mt-4">
                <h3>{{ __('Departure Days') }}</h3>
                <p>{{ $operatingDays->implode(' · ') }}</p>
                @if(!empty($sharedTimezone))<small class="price-meta">{{ __('Timezone:') }} {{ $sharedTimezone }}</small>@endif
            </div>
        @endif

        @if($packageDetail?->additional_notes)
            <div class="about-content mt-3">{!! nl2br(e($packageDetail->additional_notes)) !!}</div>
        @endif
    </section>
@endif
