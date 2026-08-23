@if($package->package_type === 'nile_cruise')
    @php
        $ncDetail = $package->nileCruiseDetail;
        $ncSchedules = $package->nileCruiseSchedules?->where('is_active', true) ?? collect();
        $ncCabins = $package->nileCruiseCabins ?? collect();
        $ncLegacyAddons = $package->nileCruiseAddons?->where('is_active', true) ?? collect();
        $ncAddons = isset($addons) && $addons->isNotEmpty() ? $addons : $ncLegacyAddons;
        $ncOperatingDays = isset($operatingDays) && $operatingDays->isNotEmpty() ? $operatingDays : collect((array) ($ncDetail?->operating_days ?? []));
        $ncLanguages = isset($onTourLanguages) && $onTourLanguages->isNotEmpty() ? $onTourLanguages : collect((array) ($ncDetail?->on_tour_languages ?? []));
        $ncWhatToBring = isset($whatToBring) && $whatToBring->isNotEmpty() ? $whatToBring : collect((array) ($ncDetail?->what_to_bring ?? []));
        $ncVideos = isset($promotionalVideos) && $promotionalVideos->isNotEmpty() ? $promotionalVideos : collect((array) ($ncDetail?->promotional_videos ?? []));
        $ncDepositPolicy = $package->deposit_policy ?: ($ncDetail?->deposit_policy ?? null);
        $ncDepositType = $package->deposit_type ?: ($ncDetail?->deposit_type ?? null);
        $ncDepositValue = $package->deposit_value ?? ($ncDetail?->deposit_value ?? null);
        $ncCruise = $package->cruise;
        $ncDurations = $package->nileCruiseDurations?->where('is_active', true)->values() ?? collect();
        $ncRoute = $package->cities?->sortBy(fn($city) => $city->pivot?->stop_order ?? 0)->values() ?? collect();
        $hasNcExtendedData = $ncDetail || $ncCruise || $ncSchedules->isNotEmpty() || $ncCabins->isNotEmpty() || $ncAddons->isNotEmpty() || $ncDurations->isNotEmpty();
    @endphp

    @if($hasNcExtendedData)
        <style>
            .nc-premium{margin-bottom:34px}.nc-premium-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px}.nc-premium-fact{border:1px solid #e7e2d8;border-radius:14px;padding:16px;background:#fff}.nc-premium-fact small{display:block;color:#7c746a;margin-bottom:6px}.nc-premium-fact strong{display:block;color:#29231d}.nc-premium-block{margin-top:24px}.nc-premium-block h3{font-size:21px;margin-bottom:14px}.nc-schedule-grid,.nc-cabin-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.nc-schedule,.nc-cabin{border:1px solid #ebe6dc;border-radius:14px;padding:16px;background:#fff}.nc-cabin-meta{display:flex;flex-wrap:wrap;gap:8px;margin-top:9px}.nc-pill{display:inline-flex;padding:6px 10px;border-radius:999px;background:#f5f1e8;font-size:12px}.nc-duration-tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:15px}.nc-duration-tab{border:1px solid #d6cbb9;background:#fff;padding:9px 14px;border-radius:999px;cursor:pointer}.nc-duration-tab.active{background:#8b6b3f;color:#fff;border-color:#8b6b3f}.nc-duration-panel{display:none}.nc-duration-panel.active{display:block}.nc-day{border:1px solid #ebe6dc;border-radius:14px;margin-bottom:12px;background:#fff;overflow:hidden}.nc-day summary{cursor:pointer;padding:15px 17px;font-weight:700}.nc-day-body{padding:0 17px 17px}.nc-activity{padding:12px 0;border-top:1px solid #f0ece4}.nc-price-table{width:100%;border-collapse:collapse;min-width:720px}.nc-price-table th,.nc-price-table td{padding:11px;border-bottom:1px solid #ece7dd;text-align:left}.nc-table-wrap{overflow:auto;border:1px solid #ebe6dc;border-radius:14px}.nc-fact-sheet{display:inline-flex;align-items:center;gap:8px;margin-top:15px}.nc-info-list{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.nc-info-card{border:1px solid #ebe6dc;border-radius:14px;padding:15px;background:#fff}.nc-addon-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}.nc-addon{border:1px solid #ebe6dc;border-radius:14px;padding:15px;background:#fff}.nc-video-list{display:flex;gap:10px;flex-wrap:wrap}.nc-cabin img{width:100%;height:180px;object-fit:cover;border-radius:10px;margin-bottom:12px}
            @media(max-width:900px){.nc-premium-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.nc-schedule-grid,.nc-cabin-grid,.nc-info-list{grid-template-columns:1fr}.nc-addon-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:520px){.nc-premium-grid,.nc-addon-grid{grid-template-columns:1fr}}
        </style>

        <section class="content-section nc-premium" id="cruise-overview">
            <h2 class="section-header">{{ __('Nile Cruise Details') }}</h2>

            <div class="nc-premium-grid">
                @if($ncCruise?->ship_name)<div class="nc-premium-fact"><small>{{ __('Cruise / Boat') }}</small><strong>{{ $ncCruise->ship_name }}</strong></div>@endif
                @if($ncCruise?->cruise_class)<div class="nc-premium-fact"><small>{{ __('Cruise Class') }}</small><strong>{{ $ncCruise->cruise_class }}</strong></div>@endif
                @if($ncCruise?->star_rating)<div class="nc-premium-fact"><small>{{ __('Rating') }}</small><strong>{{ $ncCruise->star_rating }} ★</strong></div>@endif
                @if($ncDetail)<div class="nc-premium-fact"><small>{{ __('All Inclusive') }}</small><strong>{{ $ncDetail->all_inclusive ? __('Yes') : __('No') }}</strong></div>@endif
                @if($ncDurations->isNotEmpty())
                    <div class="nc-premium-fact"><small>{{ __('Duration') }}</small><strong>{{ $ncDurations->pluck('title')->filter()->implode(' / ') }}</strong></div>
                @endif
                @if($ncOperatingDays->isNotEmpty() || $ncLanguages->isNotEmpty())
                <div class="nc-premium-block"><div class="nc-info-list">
                    @if($ncOperatingDays->isNotEmpty())<div class="nc-info-card"><strong>{{ __('Operating Days') }}</strong><div class="mt-2">{{ $ncOperatingDays->implode(' · ') }}</div></div>@endif
                    @if($ncLanguages->isNotEmpty())<div class="nc-info-card"><strong>{{ __('On-Tour Languages') }}</strong><div class="mt-2">{{ $ncLanguages->implode(' · ') }}</div></div>@endif
                </div></div>
            @endif

            @if($ncSchedules->isNotEmpty())
                    <div class="nc-premium-fact"><small>{{ __('Schedule') }}</small><strong>{{ $ncSchedules->map(fn($s)=>trim(($s->departure_day ?: '').' '.($s->departureCity?->display_name ? __('from').' '.$s->departureCity->display_name : '')))->filter()->implode(' · ') }}</strong></div>
                @endif
                @if($ncRoute->isNotEmpty())
                    <div class="nc-premium-fact"><small>{{ __('Route') }}</small><strong>{{ $ncRoute->map(fn($city)=>$city->display_name)->filter()->implode(' / ') }}</strong></div>
                @elseif($ncDetail?->route_summary)
                    <div class="nc-premium-fact"><small>{{ __('Route') }}</small><strong>{{ $ncDetail->route_summary }}</strong></div>
                @endif
                @if($ncDetail?->tour_style || $tourTypeText)
                    <div class="nc-premium-fact"><small>{{ __('Tour Type') }}</small><strong>{{ $ncDetail?->tour_style ?: $tourTypeText }}</strong></div>
                @endif
            </div>

            @if($ncSchedules->isNotEmpty())
                <div class="nc-premium-block">
                    <h3>{{ __('Cruise Schedule') }}</h3>
                    <div class="nc-schedule-grid">
                        @foreach($ncSchedules as $scheduleItem)
                            <div class="nc-schedule">
                                <strong>{{ $scheduleItem->departure_day ?: __('Scheduled Sailing') }}</strong>
                                @if($scheduleItem->direction)<div>{{ $scheduleItem->direction }}</div>@elseif($scheduleItem->departureCity || $scheduleItem->arrivalCity)<div>{{ $scheduleItem->departureCity?->display_name }} @if($scheduleItem->arrivalCity) → {{ $scheduleItem->arrivalCity->display_name }} @endif</div>@endif
                                @if($scheduleItem->notes)<small>{{ $scheduleItem->notes }}</small>@endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($ncCabins->isNotEmpty())
                <div class="nc-premium-block" id="cabins-suites">
                    <h3>{{ __('Cabins & Suites') }}</h3>
                    <div class="nc-cabin-grid">
                        @foreach($ncCabins as $cabin)
                            <div class="nc-cabin">
                                @if($cabin->featured_image)<img src="{{ asset('storage/'.$cabin->featured_image) }}" alt="{{ $cabin->name }}">@endif
                                <h4>{{ $cabin->name }}</h4>
                                <div class="nc-cabin-meta">
                                    @if($cabin->quantity)<span class="nc-pill">{{ $cabin->quantity }} {{ __('Cabins') }}</span>@endif
                                    @if($cabin->bed_type)<span class="nc-pill">{{ $cabin->bed_type }}</span>@endif
                                    @if($cabin->size_sqm)<span class="nc-pill">{{ rtrim(rtrim(number_format((float)$cabin->size_sqm,2), '0'), '.') }} m²</span>@endif
                                    @if($cabin->has_private_bathroom)<span class="nc-pill">{{ __('Private Bathroom') }}</span>@endif
                                    @if($cabin->has_private_terrace)<span class="nc-pill">{{ __('Private Terrace') }}</span>@endif
                                </div>
                                @if($cabin->description)<p class="mt-3 mb-2">{{ $cabin->description }}</p>@endif
                                @if(!empty($cabin->amenities))<ul class="mb-0">@foreach($cabin->amenities as $amenity)<li>{{ $amenity }}</li>@endforeach</ul>@endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($ncDurations->isNotEmpty())
                <div class="nc-premium-block" id="cruise-itineraries">
                    <h3>{{ __('Itinerary & Pricing Packages') }}</h3>
                    <div class="nc-duration-tabs" role="tablist">
                        @foreach($ncDurations as $durationIndex => $duration)
                            <button type="button" class="nc-duration-tab {{ ($duration->is_default || (!$ncDurations->contains('is_default', true) && $durationIndex===0)) ? 'active' : '' }}" data-nc-duration-target="nc-duration-{{ $duration->id }}">{{ $duration->title }}</button>
                        @endforeach
                    </div>

                    @foreach($ncDurations as $durationIndex => $duration)
                        @php $isActiveDuration = $duration->is_default || (!$ncDurations->contains('is_default', true) && $durationIndex===0); @endphp
                        <div class="nc-duration-panel {{ $isActiveDuration ? 'active' : '' }}" id="nc-duration-{{ $duration->id }}">
                            <div class="nc-premium-grid mb-3">
                                <div class="nc-premium-fact"><small>{{ __('Duration') }}</small><strong>{{ $duration->title }}</strong></div>
                                @if($duration->direction)<div class="nc-premium-fact"><small>{{ __('Direction') }}</small><strong>{{ $duration->direction }}</strong></div>@endif
                                @if($duration->departure_day)<div class="nc-premium-fact"><small>{{ __('Departure') }}</small><strong>{{ $duration->departure_day }}</strong></div>@endif
                                @if($duration->start_from_price)<div class="nc-premium-fact"><small>{{ __('From') }}</small><strong>{{ $duration->currency?->symbol ?: ($package->currency?->symbol ?: '$') }}{{ number_format((float)$duration->start_from_price, 0) }}</strong></div>@endif
                            </div>

                            @if($duration->itineraryDays->isNotEmpty())
                                <div class="mb-4">
                                    @foreach($duration->itineraryDays as $day)
                                        <details class="nc-day" {{ $loop->first ? 'open' : '' }}>
                                            <summary>{{ __('Day') }} {{ $day->day_number }}@if($day->display_title): {{ $day->display_title }}@endif</summary>
                                            <div class="nc-day-body">
                                                @if($day->display_description)<div>{!! nl2br(e($day->display_description)) !!}</div>@endif
                                                @foreach($day->activities as $activity)
                                                    @php $activityHeading = $activity->display_title ?: $activity->attraction?->display_name; @endphp
                                                    <div class="nc-activity">
                                                        @if($activityHeading)
                                                            @if($activity->attraction?->slug)
                                                                <strong><a href="{{ route('website.attractions.show', $activity->attraction->slug) }}">{{ $activityHeading }}</a></strong>
                                                            @else
                                                                <strong>{{ $activityHeading }}</strong>
                                                            @endif
                                                        @endif
                                                        @if($activity->display_description)<div>{!! nl2br(e($activity->display_description)) !!}</div>@endif
                                                    </div>
                                                @endforeach
                                                @php
                                                    $dayMeals = [];
                                                    if (!empty($day->meals) && (is_array($day->meals) || $day->meals instanceof \Illuminate\Support\Collection)) {
                                                        $dayMeals = is_array($day->meals) ? $day->meals : $day->meals->toArray();
                                                    }
                                                    if (!empty($day->meals_breakfast) && !in_array('breakfast', $dayMeals)) $dayMeals[] = 'breakfast';
                                                    if (!empty($day->meals_lunch) && !in_array('lunch', $dayMeals)) $dayMeals[] = 'lunch';
                                                    if (!empty($day->meals_dinner) && !in_array('dinner', $dayMeals)) $dayMeals[] = 'dinner';
                                                @endphp
                                                @if (!empty($dayMeals))
                                                    <div class="meals-included-card mt-3 p-3 rounded-3" style="background-color: #f8f6f0; border-left: 4px solid #c9974c;">
                                                        <div class="fw-bold mb-2" style="color: #1e293b; font-size: 0.9rem;">{{ __('Meals Included') }}</div>
                                                        <div class="d-flex flex-wrap gap-2">
                                                            @foreach ($dayMeals as $m)
                                                                @php
                                                                    $mLower = strtolower((string)$m);
                                                                    if (in_array($mLower, ['breakfast', 'إفطار', 'افطار'])) {
                                                                        $mealText = __('Breakfast');
                                                                    } elseif (in_array($mLower, ['lunch', 'غداء'])) {
                                                                        $mealText = __('Lunch');
                                                                    } elseif (in_array($mLower, ['dinner', 'عشاء'])) {
                                                                        $mealText = __('Dinner');
                                                                    } else {
                                                                        $mealText = __(ucfirst($mLower));
                                                                    }
                                                                @endphp
                                                                <span class="badge px-3 py-2 rounded-pill fw-medium" style="background-color: #c9974c; color: #ffffff; font-size: 0.85rem; border: none;">
                                                                    {{ $mealText }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($day->display_overnight)<div class="mt-2"><strong>{{ __('Overnight') }}:</strong> {{ $day->display_overnight }}</div>@endif
                                            </div>
                                        </details>
                                    @endforeach
                                </div>
                            @endif

                            @if($duration->seasonPrices->isNotEmpty())
                                <div class="nc-table-wrap">
                                    <table class="nc-price-table">
                                        <thead><tr><th>{{ __('Season') }}</th><th>{{ __('From') }}</th><th>{{ __('To') }}</th><th>{{ __('Cabin / Occupancy') }}</th><th>{{ __('Price') }}</th></tr></thead>
                                        <tbody>
                                        @foreach($duration->seasonPrices->where('is_active', true) as $season)
                                            @foreach($season->items as $item)
                                                <tr>
                                                    <td>{{ $season->display_season_name ?: __('Season') }}</td>
                                                    <td>{{ $season->date_from?->format('d M Y') ?: '—' }}</td>
                                                    <td>{{ $season->date_to?->format('d M Y') ?: '—' }}</td>
                                                    <td>{{ $item->cabin?->name ?: ($item->display_label ?: ucfirst((string)$item->occupancy_type)) }} @if($item->cabin && $item->occupancy_type)<small>· {{ ucfirst($item->occupancy_type) }}</small>@endif</td>
                                                    <td><strong>{{ $season->currency?->symbol ?: ($package->currency?->symbol ?: '$') }}{{ number_format((float)$item->price, 0) }}</strong></td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            @if($ncWhatToBring->isNotEmpty())
                <div class="nc-premium-block"><h3>{{ __('What to Bring') }}</h3><ul>@foreach($ncWhatToBring as $item)<li>{{ $item }}</li>@endforeach</ul></div>
            @endif

            @if($ncAddons->isNotEmpty())
                <div class="nc-premium-block"><h3>{{ __('Optional Add-ons') }}</h3><div class="nc-addon-grid">@foreach($ncAddons as $addon)<div class="nc-addon"><strong>{{ $addon->title ?? $addon->name ?? __('Add-on') }}</strong>@if($addon->description)<p class="mb-2 mt-2">{{ $addon->description }}</p>@endif<div><strong>{{ $addon->currency?->symbol ?: ($package->currency?->symbol ?: '$') }}{{ number_format((float)$addon->price, 2) }}</strong></div></div>@endforeach</div></div>
            @endif

            @if($ncVideos->isNotEmpty())
                <div class="nc-premium-block"><h3>{{ __('Promotional Videos') }}</h3><div class="nc-video-list">@foreach($ncVideos as $video)<a class="gold-btn" href="{{ $video }}" target="_blank" rel="noopener noreferrer"><i class="la la-play-circle"></i> {{ __('Watch Video') }}</a>@endforeach</div></div>
            @endif

            @if($ncDepositPolicy && $ncDepositPolicy !== 'inherit')
                <div class="nc-premium-block"><h3>{{ __('Booking Deposit') }}</h3><p>@if($ncDepositPolicy === 'not_required'){{ __('No deposit required for this cruise.') }}@else{{ __('Deposit required') }}@if($ncDepositValue !== null): {{ rtrim(rtrim(number_format((float)$ncDepositValue,2), '0'), '.') }}{{ $ncDepositType === 'percent' ? '%' : ' '.($package->currency?->code ?? '') }}@endif.@endif</p></div>
            @endif

            @if($ncDetail?->pickup_notes || $ncDetail?->dropoff_notes)
                <div class="nc-premium-block"><h3>{{ __('Pickup & Drop-off') }}</h3>@if($ncDetail->pickup_notes)<p>{{ $ncDetail->pickup_notes }}</p>@endif @if($ncDetail->dropoff_notes)<p>{{ $ncDetail->dropoff_notes }}</p>@endif</div>
            @endif

            @if($ncDetail?->fact_sheet_path || !empty($brochureUrl))
                <div class="d-flex flex-wrap gap-2 mt-3">
                    @if($ncDetail?->fact_sheet_path)
                        <a class="gold-btn nc-fact-sheet" href="{{ asset('storage/'.$ncDetail->fact_sheet_path) }}" target="_blank" rel="noopener"><i class="la la-file-pdf"></i> {{ __('Cruise Fact Sheet') }}</a>
                    @endif
                    @if(!empty($brochureUrl))
                        <a class="gold-btn nc-fact-sheet" href="{{ $brochureUrl }}" target="_blank" rel="noopener"><i class="la la-file-pdf"></i> {{ __('Trip Brochure') }}</a>
                    @endif
                </div>
            @endif
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function(){document.querySelectorAll('.nc-duration-tab').forEach(function(btn){btn.addEventListener('click',function(){const scope=btn.closest('#cruise-itineraries');scope.querySelectorAll('.nc-duration-tab').forEach(x=>x.classList.remove('active'));scope.querySelectorAll('.nc-duration-panel').forEach(x=>x.classList.remove('active'));btn.classList.add('active');document.getElementById(btn.dataset.ncDurationTarget)?.classList.add('active');});});});
        </script>
    @endif
@endif
