@php
    $ncDetail = $package->nileCruiseDetail;
    $ncCruise = $package->cruise;
    $ncDurations = $package->nileCruiseDurations?->where('is_active', true)->values() ?? collect();
    $ncRoute = $package->cities?->sortBy(fn($city) => $city->pivot?->stop_order ?? 0)->values() ?? collect();
    $ncSchedules = $package->nileCruiseSchedules?->where('is_active', true) ?? collect();
    $ncOperatingDays = isset($operatingDays) && $operatingDays->isNotEmpty() ? $operatingDays : collect((array) ($ncDetail?->operating_days ?? []));
    $ncLanguages = isset($onTourLanguages) && $onTourLanguages->isNotEmpty() ? $onTourLanguages : collect((array) ($ncDetail?->on_tour_languages ?? []));
@endphp

<section class="content-section" id="nile-cruise-details">
    <h2 class="section-header">{{ __('Nile Cruise Details') }}</h2>
    <div class="cruise-details">
        @if($ncCruise?->ship_name)
            <div class="detail-item">
                <i class="la la-ship"></i>
                <div class="detail-text">
                    <strong class="detail-label">{{ __('Cruise / Boat:') }}</strong>
                    <span class="detail-value">{{ $ncCruise->ship_name }}</span>
                </div>
            </div>
        @endif

        @if($ncCruise?->cruise_class)
            <div class="detail-item">
                <i class="la la-award"></i>
                <div class="detail-text">
                    <strong class="detail-label">{{ __('Cruise Class:') }}</strong>
                    <span class="detail-value">{{ $ncCruise->cruise_class }}</span>
                </div>
            </div>
        @endif

        @if($ncCruise?->star_rating)
            <div class="detail-item">
                <i class="la la-star"></i>
                <div class="detail-text">
                    <strong class="detail-label">{{ __('Rating:') }}</strong>
                    <span class="detail-value text-warning fw-bold">{{ $ncCruise->star_rating }} ★</span>
                </div>
            </div>
        @endif

        @if($ncDurations->isNotEmpty())
            <div class="detail-item">
                <i class="la la-calendar"></i>
                <div class="detail-text">
                    <strong class="detail-label">{{ __('Duration(s):') }}</strong>
                    <span class="detail-value">{{ $ncDurations->pluck('title')->filter()->implode(' / ') }}</span>
                </div>
            </div>
        @elseif($durationText)
            <div class="detail-item">
                <i class="la la-calendar"></i>
                <div class="detail-text">
                    <strong class="detail-label">{{ __('Duration:') }}</strong>
                    <span class="detail-value">{{ $durationText }}</span>
                </div>
            </div>
        @endif

        @if($ncRoute->isNotEmpty())
            <div class="detail-item">
                <i class="la la-route"></i>
                <div class="detail-text">
                    <strong class="detail-label">{{ __('Route:') }}</strong>
                    <span class="detail-value">{{ $ncRoute->map(fn($city)=>$city->display_name)->filter()->implode(' / ') }}</span>
                </div>
            </div>
        @elseif($ncDetail?->route_summary)
            <div class="detail-item">
                <i class="la la-route"></i>
                <div class="detail-text">
                    <strong class="detail-label">{{ __('Route:') }}</strong>
                    <span class="detail-value">{{ $ncDetail->route_summary }}</span>
                </div>
            </div>
        @endif

        @if($ncSchedules->isNotEmpty())
            <div class="detail-item">
                <i class="la la-clock"></i>
                <div class="detail-text">
                    <strong class="detail-label">{{ __('Sailing Schedule:') }}</strong>
                    <span class="detail-value">{{ $ncSchedules->map(fn($s)=>trim(($s->departure_day ?: '').' '.($s->departureCity?->display_name ? __('from').' '.$s->departureCity->display_name : '')))->filter()->implode(' · ') }}</span>
                </div>
            </div>
        @elseif($ncOperatingDays->isNotEmpty())
            <div class="detail-item">
                <i class="la la-clock"></i>
                <div class="detail-text">
                    <strong class="detail-label">{{ __('Operating Days:') }}</strong>
                    <span class="detail-value">{{ $ncOperatingDays->implode(' · ') }}</span>
                </div>
            </div>
        @endif

        @if($ncLanguages->isNotEmpty())
            <div class="detail-item">
                <i class="la la-language"></i>
                <div class="detail-text">
                    <strong class="detail-label">{{ __('Languages:') }}</strong>
                    <span class="detail-value">{{ $ncLanguages->implode(' · ') }}</span>
                </div>
            </div>
        @endif

        @if($ncDetail)
            <div class="detail-item">
                <i class="la la-gem"></i>
                <div class="detail-text">
                    <strong class="detail-label">{{ __('All Inclusive:') }}</strong>
                    <span class="detail-value">{{ $ncDetail->all_inclusive ? __('Yes') : __('No') }}</span>
                </div>
            </div>
        @endif

        @if($ncDetail?->tour_style || $tourTypeText)
            <div class="detail-item">
                <i class="la la-compass"></i>
                <div class="detail-text">
                    <strong class="detail-label">{{ __('Tour Type:') }}</strong>
                    <span class="detail-value">{{ $ncDetail?->tour_style ?: $tourTypeText }}</span>
                </div>
            </div>
        @endif
    </div>
</section>
