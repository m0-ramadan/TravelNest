@php
    $ncPackage = $package ?? null;
    $ncDetail = $ncPackage?->nileCruiseDetail;
    $ncCruise = $ncPackage?->cruise;

    $ncSchedules = old('nile_cruise.schedules');
    if (!is_array($ncSchedules)) {
        $ncSchedules = $ncPackage?->nileCruiseSchedules?->map(fn($row) => [
            'departure_day' => $row->departure_day,
            'departure_city_id' => $row->departure_city_id,
            'arrival_city_id' => $row->arrival_city_id,
            'direction' => $row->direction,
            'notes' => $row->notes,
            'is_active' => $row->is_active ? 1 : 0,
        ])->values()->all() ?? [];
    }

    $ncAddons = old('nile_cruise.addons');
    if (!is_array($ncAddons)) {
        $ncAddons = $ncPackage?->nileCruiseAddons?->map(fn($row) => [
            'name' => $row->name,
            'description' => $row->description,
            'price' => $row->price,
            'currency_id' => $row->currency_id,
            'is_active' => $row->is_active ? 1 : 0,
        ])->values()->all() ?? [];
    }

    $ncCabins = old('nile_cruise.cabins');
    if (!is_array($ncCabins)) {
        $ncCabins = $ncPackage?->nileCruiseCabins?->map(fn($row) => [
            'id' => $row->id,
            'client_key' => (string) $row->id,
            'name' => $row->name,
            'quantity' => $row->quantity,
            'bed_type' => $row->bed_type,
            'size_sqm' => $row->size_sqm,
            'max_adults' => $row->max_adults,
            'max_children' => $row->max_children,
            'has_private_bathroom' => $row->has_private_bathroom ? 1 : 0,
            'has_private_terrace' => $row->has_private_terrace ? 1 : 0,
            'amenities' => implode("\n", (array) $row->amenities),
            'description' => $row->description,
            'existing_image' => $row->featured_image,
        ])->values()->all() ?? [];
    }

    $ncDurations = old('nile_cruise.durations');
    if (!is_array($ncDurations)) {
        $ncDurations = $ncPackage?->nileCruiseDurations?->map(function($duration) {
            return [
                'title' => $duration->title,
                'days' => $duration->days,
                'nights' => $duration->nights,
                'direction' => $duration->direction,
                'departure_city_id' => $duration->departure_city_id,
                'arrival_city_id' => $duration->arrival_city_id,
                'departure_day' => $duration->departure_day,
                'currency_id' => $duration->currency_id,
                'is_default' => $duration->is_default ? 1 : 0,
                'is_active' => $duration->is_active ? 1 : 0,
                'itinerary' => $duration->itineraryDays->map(fn($day) => [
                    'day_number' => $day->day_number,
                    'title' => $day->display_title,
                    'description' => $day->display_description,
                    'meals' => implode("\n", (array) $day->meals),
                    'overnight' => $day->display_overnight,
                    'activities' => $day->activities->map(fn($activity) => [
                        'title' => $activity->display_title,
                        'description' => $activity->display_description,
                        'attraction_id' => $activity->attraction_id,
                    ])->values()->all(),
                ])->values()->all(),
                'seasons' => $duration->seasonPrices->map(fn($season) => [
                    'season_name' => $season->display_season_name,
                    'date_from' => optional($season->date_from)->format('Y-m-d'),
                    'date_to' => optional($season->date_to)->format('Y-m-d'),
                    'currency_id' => $season->currency_id,
                    'notes' => $season->display_notes,
                    'is_active' => $season->is_active ? 1 : 0,
                    'items' => $season->items->map(fn($item) => [
                        'cabin_key' => $item->nile_cruise_cabin_id ? (string) $item->nile_cruise_cabin_id : '',
                        'occupancy_type' => $item->occupancy_type,
                        'label' => $item->display_label,
                        'price' => $item->price,
                    ])->values()->all(),
                ])->values()->all(),
            ];
        })->values()->all() ?? [];
    }

    $ncRouteCities = old('nile_cruise.route_city_ids');
    if (!is_array($ncRouteCities)) {
        $ncRouteCities = $ncPackage?->cities?->sortBy(fn($city) => $city->pivot?->stop_order ?? 0)->pluck('id')->all() ?? [];
    }

    $ncCabinOptions = collect($ncCabins)->mapWithKeys(function($cabin, $index) {
        $key = (string) ($cabin['client_key'] ?? $cabin['id'] ?? ('new-'.$index));
        return [$key => $cabin['name'] ?? ('Cabin '.($index + 1))];
    });
    $oldNcFacilityTitles = old('nile_cruise.facility_titles');
    $ncSelectedFacilities = is_array($oldNcFacilityTitles)
        ? collect($oldNcFacilityTitles)->filter()->map(fn($v)=>trim((string)$v))->all()
        : ($ncPackage?->facilities?->pluck('title')->filter()->map(fn($v)=>trim((string)$v))->all() ?? []);
    $ncFacilityPresets = \App\Services\NileCruisePackageService::facilityPresets();
    $ncOperatingDays = old('nile_cruise.operating_days', (array)($ncDetail?->operating_days ?? []));
    $ncAllowedPaymentMethodIds = collect(old('nile_cruise.allowed_payment_method_ids', (array)($ncDetail?->allowed_payment_method_ids ?? [])))->map(fn($id)=>(int)$id)->all();
    $ncPaymentMethods = $paymentMethods ?? collect();
@endphp

<div id="nileCruiseExtendedSection" class="nile-cruise-extended-section" style="display:none;">
    <input type="hidden" name="nile_cruise[_present]" value="1">
    <style>
        .nile-cruise-extended-section{margin-top:22px}.nile-cruise-extended-section .nc-card{border:1px solid rgba(124,92,255,.22);border-radius:18px;padding:20px;margin-bottom:18px;background:rgba(124,92,255,.045)}
        .nile-cruise-extended-section .nc-head{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:16px}.nile-cruise-extended-section .nc-head h4{margin:0;font-size:18px}.nile-cruise-extended-section .nc-head p{margin:5px 0 0;opacity:.72}
        .nile-cruise-extended-section .nc-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.nile-cruise-extended-section .nc-grid-3{grid-template-columns:repeat(3,minmax(0,1fr))}.nile-cruise-extended-section .nc-full{grid-column:1/-1}
        .nile-cruise-extended-section .nc-repeat{border:1px solid rgba(255,255,255,.09);border-radius:14px;padding:15px;margin-bottom:12px;background:rgba(255,255,255,.025)}
        .nile-cruise-extended-section .nc-repeat-head{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:12px}.nile-cruise-extended-section .nc-sub{border-left:3px solid rgba(124,92,255,.5);padding-left:12px;margin-top:14px}
        .nile-cruise-extended-section .nc-actions{display:flex;gap:8px;flex-wrap:wrap}.nile-cruise-extended-section .nc-muted{opacity:.7;font-size:12px}.nile-cruise-extended-section .nc-image{max-width:120px;border-radius:10px;margin-top:8px}
        @media(max-width:900px){.nile-cruise-extended-section .nc-grid,.nile-cruise-extended-section .nc-grid-3{grid-template-columns:1fr}}
    </style>

    <div class="nc-card">
        <div class="nc-head">
            <div>
                <h4>{{ admin_t('تفاصيل النايل كروز') }}</h4>
                <p>{{ admin_t('هذه البيانات تظهر فقط عندما يكون نوع الرحلة Nile Cruise، وتكمل بيانات الرحلة العادية بدون تغيير باقي الأنواع.') }}</p>
            </div>
        </div>
        <div class="nc-grid nc-grid-3">
            <div><label class="form-label">{{ admin_t('اسم السفينة / الدهبية') }}</label><input type="text" class="form-control" name="nile_cruise[ship_name]" value="{{ old('nile_cruise.ship_name', $ncCruise?->ship_name) }}" placeholder="Princess Farida"></div>
            <div><label class="form-label">{{ admin_t('فئة الكروز / السفينة') }}</label><input type="text" class="form-control" name="nile_cruise[cruise_class]" value="{{ old('nile_cruise.cruise_class', $ncCruise?->cruise_class) }}" placeholder="Dahabiya / Luxury / Motor Ship"></div>
            <div><label class="form-label">{{ admin_t('التقييم بالنجوم') }}</label><select class="form-select" name="nile_cruise[star_rating]"><option value="">-</option>@for($star=1;$star<=5;$star++)<option value="{{ $star }}" {{ (int)old('nile_cruise.star_rating', $ncCruise?->star_rating)===$star?'selected':'' }}>{{ $star }} {{ admin_t('نجوم') }}</option>@endfor</select></div>
            <div class="nc-full"><input type="hidden" name="nile_cruise[all_inclusive]" value="0"><label class="nc-repeat mb-0"><input type="checkbox" name="nile_cruise[all_inclusive]" value="1" {{ old('nile_cruise.all_inclusive', $ncDetail?->all_inclusive) ? 'checked' : '' }}> <strong class="ms-2">{{ admin_t('All Inclusive') }}</strong><div class="nc-muted mt-1">{{ admin_t('جميع الوجبات والمشروبات مشمولة حسب سياسة الرحلة.') }}</div></label></div>
            <div><label class="form-label">{{ admin_t('عدد الطوابق') }}</label><input type="number" min="0" class="form-control" name="nile_cruise[decks]" value="{{ old('nile_cruise.decks', $ncDetail?->decks) }}"></div>
            <div><label class="form-label">{{ admin_t('عدد Sun Beds') }}</label><input type="number" min="0" class="form-control" name="nile_cruise[sun_beds]" value="{{ old('nile_cruise.sun_beds', $ncDetail?->sun_beds) }}"></div>
            <div><label class="form-label">{{ admin_t('عدد Sun Deck Pergolas') }}</label><input type="number" min="0" class="form-control" name="nile_cruise[sun_deck_pergolas]" value="{{ old('nile_cruise.sun_deck_pergolas', $ncDetail?->sun_deck_pergolas) }}"></div>
            <div><label class="form-label">{{ admin_t('أسلوب الجولة') }}</label><input type="text" class="form-control" name="nile_cruise[tour_style]" value="{{ old('nile_cruise.tour_style', $ncDetail?->tour_style) }}" placeholder="Small Group Tour"></div>
            <div class="nc-full"><label class="form-label">{{ admin_t('ملخص المسار') }}</label><input type="text" class="form-control" name="nile_cruise[route_summary]" value="{{ old('nile_cruise.route_summary', $ncDetail?->route_summary) }}" placeholder="Luxor / Edfu / Kom Ombo / Aswan"></div>
            <div class="nc-full"><label class="form-label">{{ admin_t('ملاحظات الاستلام') }}</label><textarea class="form-control" rows="2" name="nile_cruise[pickup_notes]">{{ old('nile_cruise.pickup_notes', $ncDetail?->pickup_notes) }}</textarea></div>
            <div class="nc-full"><label class="form-label">{{ admin_t('ملاحظات التوصيل') }}</label><textarea class="form-control" rows="2" name="nile_cruise[dropoff_notes]">{{ old('nile_cruise.dropoff_notes', $ncDetail?->dropoff_notes) }}</textarea></div>
            {{-- Languages, What to Bring, operating days, promotional videos, deposit and payment methods are shared across all three Tour Types and are edited in the common Package sections. --}}
            <div class="nc-full"><label class="form-label">{{ admin_t('ملاحظات إضافية للكروز') }}</label><textarea class="form-control" rows="3" name="nile_cruise[additional_notes]">{{ old('nile_cruise.additional_notes', $ncDetail?->additional_notes) }}</textarea></div>
            <div class="nc-full">
                <label class="form-label">{{ admin_t('Fact Sheet PDF') }}</label>
                <input type="file" class="form-control" name="nile_cruise[fact_sheet]" accept="application/pdf">
                @if($ncDetail?->fact_sheet_path)
                    <div class="mt-2"><a href="{{ asset('storage/'.$ncDetail->fact_sheet_path) }}" target="_blank">{{ admin_t('فتح الملف الحالي') }}</a> · <label><input type="checkbox" name="nile_cruise[remove_fact_sheet]" value="1"> {{ admin_t('حذف الملف الحالي') }}</label></div>
                @endif
            </div>
        </div>
    </div>

    <div class="nc-card">
        <div class="nc-head"><div><h4>{{ admin_t('مرافق إضافية خاصة بالكروز') }}</h4><p>{{ admin_t('تُحفظ داخل نفس مرافق الرحلة الموجودة بالفعل، وليست نظام مرافق منفصل.') }}</p></div></div>
        <div class="nc-grid nc-grid-3">
            @foreach($ncFacilityPresets as $facilityTitle)
                <label class="nc-repeat mb-0">
                    <input type="checkbox" name="nile_cruise[facility_titles][]" value="{{ $facilityTitle }}" {{ in_array($facilityTitle, $ncSelectedFacilities, true) ? 'checked' : '' }}>
                    <span class="ms-2">{{ $facilityTitle }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="nc-card">
        <div class="nc-head"><div><h4>{{ admin_t('مسار الكروز') }}</h4><p>{{ admin_t('رتّب المدن حسب خط سير الرحلة. أول مدينة تعتبر نقطة البداية الأساسية.') }}</p></div><button type="button" class="btn btn-outline-primary btn-sm" data-nc-add-route>{{ admin_t('+ إضافة محطة') }}</button></div>
        <div id="ncRouteList">
            @foreach($ncRouteCities as $i => $cityId)
                <div class="nc-repeat" data-nc-route-row><div class="nc-grid"><div><label class="form-label">{{ admin_t('المحطة') }} {{ $i+1 }}</label><select class="form-select" name="nile_cruise[route_city_ids][]"><option value="">{{ admin_t('اختر مدينة') }}</option>@foreach($destinations ?? collect() as $city)<option value="{{ $city->id }}" {{ (int)$cityId===(int)$city->id?'selected':'' }}>{{ adminTrans($city->name) }}</option>@endforeach</select></div><div class="d-flex align-items-end"><button type="button" class="btn btn-outline-danger" data-nc-remove>{{ admin_t('حذف') }}</button></div></div></div>
            @endforeach
        </div>
    </div>

    <div class="nc-card">
        <div class="nc-head"><div><h4>{{ admin_t('مواعيد الإبحار') }}</h4><p>{{ admin_t('مثال: كل اثنين من الأقصر، وكل جمعة من أسوان.') }}</p></div><button type="button" class="btn btn-outline-primary btn-sm" data-nc-add-schedule>{{ admin_t('+ إضافة موعد') }}</button></div>
        <div id="ncSchedulesList">
            @foreach($ncSchedules as $i => $row)
                <div class="nc-repeat" data-nc-schedule><div class="nc-repeat-head"><strong>{{ admin_t('موعد') }} {{ $i+1 }}</strong><button type="button" class="btn btn-outline-danger btn-sm" data-nc-remove>{{ admin_t('حذف') }}</button></div><div class="nc-grid nc-grid-3">
                    <div><label class="form-label">{{ admin_t('اليوم') }}</label><input class="form-control" name="nile_cruise[schedules][{{ $i }}][departure_day]" value="{{ $row['departure_day'] ?? '' }}" placeholder="Monday"></div>
                    <div><label class="form-label">{{ admin_t('من') }}</label><select class="form-select" name="nile_cruise[schedules][{{ $i }}][departure_city_id]"><option value="">-</option>@foreach($destinations ?? collect() as $city)<option value="{{ $city->id }}" {{ (int)($row['departure_city_id']??0)===(int)$city->id?'selected':'' }}>{{ adminTrans($city->name) }}</option>@endforeach</select></div>
                    <div><label class="form-label">{{ admin_t('إلى') }}</label><select class="form-select" name="nile_cruise[schedules][{{ $i }}][arrival_city_id]"><option value="">-</option>@foreach($destinations ?? collect() as $city)<option value="{{ $city->id }}" {{ (int)($row['arrival_city_id']??0)===(int)$city->id?'selected':'' }}>{{ adminTrans($city->name) }}</option>@endforeach</select></div>
                    <div><label class="form-label">{{ admin_t('الاتجاه') }}</label><input class="form-control" name="nile_cruise[schedules][{{ $i }}][direction]" value="{{ $row['direction'] ?? '' }}" placeholder="Luxor → Aswan"></div>
                    <div class="nc-full"><label class="form-label">{{ admin_t('ملاحظات') }}</label><input class="form-control" name="nile_cruise[schedules][{{ $i }}][notes]" value="{{ $row['notes'] ?? '' }}"></div>
                    <input type="hidden" name="nile_cruise[schedules][{{ $i }}][is_active]" value="1">
                </div></div>
            @endforeach
        </div>
    </div>

    <div class="nc-card">
        <div class="nc-head"><div><h4>{{ admin_t('الكبائن والأجنحة') }}</h4><p>{{ admin_t('أضف أنواع الكبائن والأجنحة وأبعادها ومميزاتها. تستخدم نفس الأنواع لاحقًا في التسعير الموسمي.') }}</p></div><button type="button" class="btn btn-outline-primary btn-sm" data-nc-add-cabin>{{ admin_t('+ إضافة كابينة / جناح') }}</button></div>
        <div id="ncCabinsList">
            @foreach($ncCabins as $i => $row)
                @php $clientKey=(string)($row['client_key']??$row['id']??('new-'.$i)); @endphp
                <div class="nc-repeat" data-nc-cabin><div class="nc-repeat-head"><strong>{{ $row['name'] ?? admin_t('Cabin') }}</strong><button type="button" class="btn btn-outline-danger btn-sm" data-nc-remove>{{ admin_t('حذف') }}</button></div><div class="nc-grid nc-grid-3">
                    <input type="hidden" name="nile_cruise[cabins][{{ $i }}][id]" value="{{ $row['id'] ?? '' }}"><input type="hidden" data-cabin-key name="nile_cruise[cabins][{{ $i }}][client_key]" value="{{ $clientKey }}"><input type="hidden" name="nile_cruise[cabins][{{ $i }}][existing_image]" value="{{ $row['existing_image'] ?? '' }}">
                    <div><label class="form-label">{{ admin_t('الاسم') }}</label><input class="form-control" data-cabin-name name="nile_cruise[cabins][{{ $i }}][name]" value="{{ $row['name'] ?? '' }}" placeholder="Royal Suite"></div>
                    <div><label class="form-label">{{ admin_t('العدد') }}</label><input type="number" min="0" class="form-control" name="nile_cruise[cabins][{{ $i }}][quantity]" value="{{ $row['quantity'] ?? '' }}"></div>
                    <div><label class="form-label">{{ admin_t('نوع السرير') }}</label><input class="form-control" name="nile_cruise[cabins][{{ $i }}][bed_type]" value="{{ $row['bed_type'] ?? '' }}" placeholder="King Size Bed"></div>
                    <div><label class="form-label">{{ admin_t('المساحة م²') }}</label><input type="number" step="0.01" min="0" class="form-control" name="nile_cruise[cabins][{{ $i }}][size_sqm]" value="{{ $row['size_sqm'] ?? '' }}"></div>
                    <div><label class="form-label">{{ admin_t('أقصى عدد بالغين') }}</label><input type="number" min="0" class="form-control" name="nile_cruise[cabins][{{ $i }}][max_adults]" value="{{ $row['max_adults'] ?? '' }}"></div>
                    <div><label class="form-label">{{ admin_t('أقصى عدد أطفال') }}</label><input type="number" min="0" class="form-control" name="nile_cruise[cabins][{{ $i }}][max_children]" value="{{ $row['max_children'] ?? '' }}"></div>
                    <div><input type="hidden" name="nile_cruise[cabins][{{ $i }}][has_private_bathroom]" value="0"><label><input type="checkbox" name="nile_cruise[cabins][{{ $i }}][has_private_bathroom]" value="1" {{ !empty($row['has_private_bathroom'])?'checked':'' }}> {{ admin_t('حمام خاص') }}</label></div>
                    <div><input type="hidden" name="nile_cruise[cabins][{{ $i }}][has_private_terrace]" value="0"><label><input type="checkbox" name="nile_cruise[cabins][{{ $i }}][has_private_terrace]" value="1" {{ !empty($row['has_private_terrace'])?'checked':'' }}> {{ admin_t('تراس خاص') }}</label></div>
                    <div class="nc-full"><label class="form-label">{{ admin_t('المرافق داخل الكابينة') }}</label><textarea class="form-control" rows="2" name="nile_cruise[cabins][{{ $i }}][amenities]" placeholder="WiFi&#10;TV&#10;Mini Bar">{{ is_array($row['amenities']??null)?implode("\n",$row['amenities']):($row['amenities']??'') }}</textarea></div>
                    <div class="nc-full"><label class="form-label">{{ admin_t('الوصف') }}</label><textarea class="form-control" rows="2" name="nile_cruise[cabins][{{ $i }}][description]">{{ $row['description'] ?? '' }}</textarea></div>
                    <div class="nc-full"><label class="form-label">{{ admin_t('صورة الكابينة') }}</label><input type="file" class="form-control" name="nile_cruise[cabins][{{ $i }}][image]" accept="image/jpeg,image/png,image/webp">@if(!empty($row['existing_image']))<img class="nc-image" src="{{ asset('storage/'.$row['existing_image']) }}" alt="">@endif</div>
                </div></div>
            @endforeach
        </div>
    </div>

    {{-- Optional add-ons are edited once in the common Package operations section. --}}

    <div class="nc-card">
        <div class="nc-head"><div><h4>{{ admin_t('المدد والبرامج والأسعار الموسمية') }}</h4><p>{{ admin_t('كل مدة لها برنامج يومي وأسعار مواسم مستقلة، مثل 4 أيام من أسوان و5 أيام من الأقصر.') }}</p></div><button type="button" class="btn btn-primary btn-sm" data-nc-add-duration>{{ admin_t('+ إضافة مدة') }}</button></div>
        <div id="ncDurationsList">
            @foreach($ncDurations as $d => $duration)
                <div class="nc-repeat" data-nc-duration data-duration-index="{{ $d }}"><div class="nc-repeat-head"><strong>{{ $duration['title'] ?? admin_t('مدة الكروز') }}</strong><button type="button" class="btn btn-outline-danger btn-sm" data-nc-remove>{{ admin_t('حذف المدة') }}</button></div>
                    <div class="nc-grid nc-grid-3">
                        <div><label class="form-label">{{ admin_t('العنوان') }}</label><input class="form-control" name="nile_cruise[durations][{{ $d }}][title]" value="{{ $duration['title'] ?? '' }}" placeholder="3 Nights / 4 Days"></div>
                        <div><label class="form-label">{{ admin_t('الأيام') }}</label><input type="number" min="1" class="form-control" name="nile_cruise[durations][{{ $d }}][days]" value="{{ $duration['days'] ?? '' }}"></div>
                        <div><label class="form-label">{{ admin_t('الليالي') }}</label><input type="number" min="0" class="form-control" name="nile_cruise[durations][{{ $d }}][nights]" value="{{ $duration['nights'] ?? '' }}"></div>
                        <div><label class="form-label">{{ admin_t('الاتجاه') }}</label><input class="form-control" name="nile_cruise[durations][{{ $d }}][direction]" value="{{ $duration['direction'] ?? '' }}" placeholder="Aswan → Luxor"></div>
                        <div><label class="form-label">{{ admin_t('يوم المغادرة') }}</label><input class="form-control" name="nile_cruise[durations][{{ $d }}][departure_day]" value="{{ $duration['departure_day'] ?? '' }}" placeholder="Friday"></div>
                        <div><label class="form-label">{{ admin_t('العملة') }}</label><select class="form-select" name="nile_cruise[durations][{{ $d }}][currency_id]"><option value="">-</option>@foreach($currencies ?? collect() as $currency)<option value="{{ $currency->id }}" {{ (int)($duration['currency_id']??0)===(int)$currency->id?'selected':'' }}>{{ $currency->code }}</option>@endforeach</select></div>
                        <div><label class="form-label">{{ admin_t('من') }}</label><select class="form-select" name="nile_cruise[durations][{{ $d }}][departure_city_id]"><option value="">-</option>@foreach($destinations ?? collect() as $city)<option value="{{ $city->id }}" {{ (int)($duration['departure_city_id']??0)===(int)$city->id?'selected':'' }}>{{ adminTrans($city->name) }}</option>@endforeach</select></div>
                        <div><label class="form-label">{{ admin_t('إلى') }}</label><select class="form-select" name="nile_cruise[durations][{{ $d }}][arrival_city_id]"><option value="">-</option>@foreach($destinations ?? collect() as $city)<option value="{{ $city->id }}" {{ (int)($duration['arrival_city_id']??0)===(int)$city->id?'selected':'' }}>{{ adminTrans($city->name) }}</option>@endforeach</select></div>
                        <div><input type="hidden" name="nile_cruise[durations][{{ $d }}][is_active]" value="0"><label><input type="checkbox" name="nile_cruise[durations][{{ $d }}][is_active]" value="1" {{ !array_key_exists('is_active',$duration)||!empty($duration['is_active'])?'checked':'' }}> {{ admin_t('نشطة') }}</label><br><input type="hidden" name="nile_cruise[durations][{{ $d }}][is_default]" value="0"><label><input type="checkbox" name="nile_cruise[durations][{{ $d }}][is_default]" value="1" {{ !empty($duration['is_default'])?'checked':'' }}> {{ admin_t('المدة الافتراضية') }}</label></div>
                    </div>

                    <div class="nc-sub"><div class="nc-head"><div><strong>{{ admin_t('البرنامج اليومي لهذه المدة') }}</strong></div><button type="button" class="btn btn-outline-primary btn-sm" data-nc-add-day>{{ admin_t('+ إضافة يوم') }}</button></div><div data-nc-days>
                        @foreach((array)($duration['itinerary']??[]) as $dayIndex => $day)
                            <div class="nc-repeat" data-nc-day data-day-index="{{ $dayIndex }}"><div class="nc-repeat-head"><strong>{{ admin_t('اليوم') }} {{ $day['day_number'] ?? ($dayIndex+1) }}</strong><button type="button" class="btn btn-outline-danger btn-sm" data-nc-remove>{{ admin_t('حذف') }}</button></div><div class="nc-grid">
                                <input type="hidden" name="nile_cruise[durations][{{ $d }}][itinerary][{{ $dayIndex }}][day_number]" value="{{ $day['day_number'] ?? ($dayIndex+1) }}">
                                <div><label class="form-label">{{ admin_t('عنوان اليوم') }}</label><input class="form-control" name="nile_cruise[durations][{{ $d }}][itinerary][{{ $dayIndex }}][title]" value="{{ $day['title'] ?? '' }}"></div>
                                <div><label class="form-label">{{ admin_t('المبيت') }}</label><input class="form-control" name="nile_cruise[durations][{{ $d }}][itinerary][{{ $dayIndex }}][overnight]" value="{{ $day['overnight'] ?? '' }}"></div>
                                <div class="nc-full"><label class="form-label">{{ admin_t('وصف اليوم') }}</label><textarea class="form-control" rows="4" name="nile_cruise[durations][{{ $d }}][itinerary][{{ $dayIndex }}][description]">{{ $day['description'] ?? '' }}</textarea></div>
                                <div class="nc-full"><label class="form-label">{{ admin_t('الوجبات - كل وجبة في سطر') }}</label><textarea class="form-control" rows="2" name="nile_cruise[durations][{{ $d }}][itinerary][{{ $dayIndex }}][meals]">{{ is_array($day['meals']??null)?implode("\n",$day['meals']):($day['meals']??'') }}</textarea></div>
                            </div><div class="nc-sub"><div class="nc-head"><strong>{{ admin_t('الأنشطة / المحطات') }}</strong><button type="button" class="btn btn-outline-secondary btn-sm" data-nc-add-activity>{{ admin_t('+ نشاط') }}</button></div><div data-nc-activities>
                                @foreach((array)($day['activities']??[]) as $a => $activity)
                                    <div class="nc-repeat" data-nc-activity><div class="nc-grid nc-grid-3"><div><label class="form-label">{{ admin_t('العنوان') }}</label><input class="form-control" name="nile_cruise[durations][{{ $d }}][itinerary][{{ $dayIndex }}][activities][{{ $a }}][title]" value="{{ $activity['title'] ?? '' }}"></div><div><label class="form-label">{{ admin_t('ربط بمعلم') }}</label><select class="form-select" name="nile_cruise[durations][{{ $d }}][itinerary][{{ $dayIndex }}][activities][{{ $a }}][attraction_id]"><option value="">-</option>@foreach($attractions ?? collect() as $att)<option value="{{ $att->id }}" {{ (int)($activity['attraction_id']??0)===(int)$att->id?'selected':'' }}>{{ adminTrans($att->name) }}</option>@endforeach</select></div><div><button type="button" class="btn btn-outline-danger mt-4" data-nc-remove>{{ admin_t('حذف') }}</button></div><div class="nc-full"><textarea class="form-control" rows="2" name="nile_cruise[durations][{{ $d }}][itinerary][{{ $dayIndex }}][activities][{{ $a }}][description]" placeholder="{{ admin_t('التفاصيل') }}">{{ $activity['description'] ?? '' }}</textarea></div></div></div>
                                @endforeach
                            </div></div></div>
                        @endforeach
                    </div></div>

                    <div class="nc-sub"><div class="nc-head"><div><strong>{{ admin_t('المواسم والأسعار لهذه المدة') }}</strong><div class="nc-muted">{{ admin_t('أضف Summer / Winter / Easter / Xmas ثم أسعار Single / Double / Suite أو أي Label مخصص.') }}</div></div><button type="button" class="btn btn-outline-primary btn-sm" data-nc-add-season>{{ admin_t('+ إضافة موسم') }}</button></div><div data-nc-seasons>
                        @foreach((array)($duration['seasons']??[]) as $s => $season)
                            <div class="nc-repeat" data-nc-season data-season-index="{{ $s }}"><div class="nc-repeat-head"><strong>{{ $season['season_name'] ?? admin_t('Season') }}</strong><button type="button" class="btn btn-outline-danger btn-sm" data-nc-remove>{{ admin_t('حذف') }}</button></div><div class="nc-grid nc-grid-3">
                                <div><label class="form-label">{{ admin_t('الموسم') }}</label><input class="form-control" name="nile_cruise[durations][{{ $d }}][seasons][{{ $s }}][season_name]" value="{{ $season['season_name'] ?? '' }}"></div>
                                <div><label class="form-label">{{ admin_t('من') }}</label><input type="date" class="form-control" name="nile_cruise[durations][{{ $d }}][seasons][{{ $s }}][date_from]" value="{{ $season['date_from'] ?? '' }}"></div>
                                <div><label class="form-label">{{ admin_t('إلى') }}</label><input type="date" class="form-control" name="nile_cruise[durations][{{ $d }}][seasons][{{ $s }}][date_to]" value="{{ $season['date_to'] ?? '' }}"></div>
                                <div><label class="form-label">{{ admin_t('العملة') }}</label><select class="form-select" name="nile_cruise[durations][{{ $d }}][seasons][{{ $s }}][currency_id]"><option value="">-</option>@foreach($currencies ?? collect() as $currency)<option value="{{ $currency->id }}" {{ (int)($season['currency_id']??0)===(int)$currency->id?'selected':'' }}>{{ $currency->code }}</option>@endforeach</select></div>
                                <div class="nc-full"><label class="form-label">{{ admin_t('ملاحظات') }}</label><input class="form-control" name="nile_cruise[durations][{{ $d }}][seasons][{{ $s }}][notes]" value="{{ $season['notes'] ?? '' }}"></div><input type="hidden" name="nile_cruise[durations][{{ $d }}][seasons][{{ $s }}][is_active]" value="1">
                            </div><div class="nc-sub"><div class="nc-head"><strong>{{ admin_t('بنود السعر') }}</strong><button type="button" class="btn btn-outline-secondary btn-sm" data-nc-add-price-item>{{ admin_t('+ سعر') }}</button></div><div data-nc-price-items>
                                @foreach((array)($season['items']??[]) as $pi => $item)
                                    <div class="nc-repeat" data-nc-price-item><div class="nc-grid nc-grid-3"><div><label class="form-label">{{ admin_t('الكابينة') }}</label><select data-nc-cabin-select class="form-select" name="nile_cruise[durations][{{ $d }}][seasons][{{ $s }}][items][{{ $pi }}][cabin_key]"><option value="">{{ admin_t('بدون كابينة محددة') }}</option>@foreach($ncCabinOptions as $key=>$label)<option value="{{ $key }}" {{ (string)($item['cabin_key']??'')===(string)$key?'selected':'' }}>{{ $label }}</option>@endforeach</select></div><div><label class="form-label">{{ admin_t('نوع الإشغال') }}</label><select class="form-select" name="nile_cruise[durations][{{ $d }}][seasons][{{ $s }}][items][{{ $pi }}][occupancy_type]"><option value="single" {{ ($item['occupancy_type']??'')==='single'?'selected':'' }}>Single</option><option value="double" {{ ($item['occupancy_type']??'')==='double'?'selected':'' }}>Double</option><option value="triple" {{ ($item['occupancy_type']??'')==='triple'?'selected':'' }}>Triple</option><option value="suite" {{ ($item['occupancy_type']??'')==='suite'?'selected':'' }}>Suite</option><option value="custom" {{ ($item['occupancy_type']??'')==='custom'?'selected':'' }}>Custom</option></select></div><div><label class="form-label">{{ admin_t('السعر') }}</label><input type="number" step="0.01" min="0" class="form-control" name="nile_cruise[durations][{{ $d }}][seasons][{{ $s }}][items][{{ $pi }}][price]" value="{{ $item['price'] ?? '' }}"></div><div class="nc-full"><label class="form-label">{{ admin_t('Label اختياري') }}</label><input class="form-control" name="nile_cruise[durations][{{ $d }}][seasons][{{ $s }}][items][{{ $pi }}][label]" value="{{ $item['label'] ?? '' }}" placeholder="Royal Suite Price"></div><div><button type="button" class="btn btn-outline-danger" data-nc-remove>{{ admin_t('حذف') }}</button></div></div></div>
                                @endforeach
                            </div></div></div>
                        @endforeach
                    </div></div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@once
<script>
document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('nileCruiseExtendedSection');
    if (!root) return;
    const packageType = document.querySelector('[name="package_type"]');
    const cityOptions = @json(($destinations ?? collect())->map(fn($c)=>['id'=>$c->id,'name'=>adminTrans($c->name)])->values());
    const currencyOptions = @json(($currencies ?? collect())->map(fn($c)=>['id'=>$c->id,'code'=>$c->code])->values());
    const attractionOptions = @json(($attractions ?? collect())->map(fn($a)=>['id'=>$a->id,'name'=>adminTrans($a->name)])->values());
    let scheduleIndex = root.querySelectorAll('[data-nc-schedule]').length;
    let addonIndex = root.querySelectorAll('[data-nc-addon]').length;
    let cabinIndex = root.querySelectorAll('[data-nc-cabin]').length;
    let durationIndex = root.querySelectorAll('[data-nc-duration]').length;

    const esc = v => String(v ?? '').replace(/[&<>'"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[c]));
    const citySelect = (name) => `<select class="form-select" name="${name}"><option value="">-</option>${cityOptions.map(c=>`<option value="${c.id}">${esc(c.name)}</option>`).join('')}</select>`;
    const currencySelect = (name) => `<select class="form-select" name="${name}"><option value="">-</option>${currencyOptions.map(c=>`<option value="${c.id}">${esc(c.code)}</option>`).join('')}</select>`;
    const attractionSelect = (name) => `<select class="form-select" name="${name}"><option value="">-</option>${attractionOptions.map(a=>`<option value="${a.id}">${esc(a.name)}</option>`).join('')}</select>`;

    function toggle(){
        const active = packageType?.value === 'nile_cruise';
        root.style.display = active ? '' : 'none';
        root.querySelectorAll('input, select, textarea, button').forEach(control => {
            control.disabled = !active;
        });
    }
    packageType?.addEventListener('change', toggle); toggle();

    function refreshCabinSelectors(){
        const cabins=[...root.querySelectorAll('[data-nc-cabin]')].map((row,i)=>({key:row.querySelector('[data-cabin-key]')?.value||`new-${i}`,name:row.querySelector('[data-cabin-name]')?.value||`Cabin ${i+1}`}));
        root.querySelectorAll('[data-nc-cabin-select]').forEach(sel=>{const current=sel.value;sel.innerHTML='<option value="">{{ admin_t("بدون كابينة محددة") }}</option>'+cabins.map(c=>`<option value="${esc(c.key)}">${esc(c.name)}</option>`).join('');sel.value=current;});
    }
    root.addEventListener('input',e=>{if(e.target.matches('[data-cabin-name]')) refreshCabinSelectors();});
    root.addEventListener('click',e=>{
        const remove=e.target.closest('[data-nc-remove]'); if(remove){remove.closest('.nc-repeat')?.remove();refreshCabinSelectors();return;}
        if(e.target.closest('[data-nc-add-route]')){document.getElementById('ncRouteList').insertAdjacentHTML('beforeend',`<div class="nc-repeat" data-nc-route-row><div class="nc-grid"><div><label class="form-label">{{ admin_t('المحطة') }}</label>${citySelect('nile_cruise[route_city_ids][]')}</div><div class="d-flex align-items-end"><button type="button" class="btn btn-outline-danger" data-nc-remove>{{ admin_t('حذف') }}</button></div></div></div>`);return;}
        if(e.target.closest('[data-nc-add-schedule]')){const i=scheduleIndex++;document.getElementById('ncSchedulesList').insertAdjacentHTML('beforeend',`<div class="nc-repeat" data-nc-schedule><div class="nc-repeat-head"><strong>{{ admin_t('موعد') }}</strong><button type="button" class="btn btn-outline-danger btn-sm" data-nc-remove>{{ admin_t('حذف') }}</button></div><div class="nc-grid nc-grid-3"><div><label class="form-label">{{ admin_t('اليوم') }}</label><input class="form-control" name="nile_cruise[schedules][${i}][departure_day]"></div><div><label class="form-label">{{ admin_t('من') }}</label>${citySelect(`nile_cruise[schedules][${i}][departure_city_id]`)}</div><div><label class="form-label">{{ admin_t('إلى') }}</label>${citySelect(`nile_cruise[schedules][${i}][arrival_city_id]`)}</div><div><label class="form-label">{{ admin_t('الاتجاه') }}</label><input class="form-control" name="nile_cruise[schedules][${i}][direction]"></div><div class="nc-full"><label class="form-label">{{ admin_t('ملاحظات') }}</label><input class="form-control" name="nile_cruise[schedules][${i}][notes]"></div><input type="hidden" name="nile_cruise[schedules][${i}][is_active]" value="1"></div></div>`);return;}
        if(e.target.closest('[data-nc-add-addon]')){const i=addonIndex++;document.getElementById('ncAddonsList').insertAdjacentHTML('beforeend',`<div class="nc-repeat" data-nc-addon><div class="nc-repeat-head"><strong>{{ admin_t('Add-on') }}</strong><button type="button" class="btn btn-outline-danger btn-sm" data-nc-remove>{{ admin_t('حذف') }}</button></div><div class="nc-grid nc-grid-3"><div><label class="form-label">{{ admin_t('الاسم') }}</label><input class="form-control" name="nile_cruise[addons][${i}][name]"></div><div><label class="form-label">{{ admin_t('السعر') }}</label><input type="number" min="0" step="0.01" class="form-control" name="nile_cruise[addons][${i}][price]" value="0"></div><div><label class="form-label">{{ admin_t('العملة') }}</label>${currencySelect(`nile_cruise[addons][${i}][currency_id]`)}</div><div class="nc-full"><label class="form-label">{{ admin_t('الوصف') }}</label><input class="form-control" name="nile_cruise[addons][${i}][description]"></div><input type="hidden" name="nile_cruise[addons][${i}][is_active]" value="1"></div></div>`);return;}
        if(e.target.closest('[data-nc-add-cabin]')){const i=cabinIndex++, key=`new-${Date.now()}-${i}`;document.getElementById('ncCabinsList').insertAdjacentHTML('beforeend',`<div class="nc-repeat" data-nc-cabin><div class="nc-repeat-head"><strong>{{ admin_t('Cabin / Suite') }}</strong><button type="button" class="btn btn-outline-danger btn-sm" data-nc-remove>{{ admin_t('حذف') }}</button></div><div class="nc-grid nc-grid-3"><input type="hidden" data-cabin-key name="nile_cruise[cabins][${i}][client_key]" value="${key}"><div><label class="form-label">{{ admin_t('الاسم') }}</label><input class="form-control" data-cabin-name name="nile_cruise[cabins][${i}][name]"></div><div><label class="form-label">{{ admin_t('العدد') }}</label><input type="number" min="0" class="form-control" name="nile_cruise[cabins][${i}][quantity]"></div><div><label class="form-label">{{ admin_t('نوع السرير') }}</label><input class="form-control" name="nile_cruise[cabins][${i}][bed_type]"></div><div><label class="form-label">{{ admin_t('المساحة م²') }}</label><input type="number" step="0.01" min="0" class="form-control" name="nile_cruise[cabins][${i}][size_sqm]"></div><div><label class="form-label">{{ admin_t('أقصى بالغين') }}</label><input type="number" min="0" class="form-control" name="nile_cruise[cabins][${i}][max_adults]"></div><div><label class="form-label">{{ admin_t('أقصى أطفال') }}</label><input type="number" min="0" class="form-control" name="nile_cruise[cabins][${i}][max_children]"></div><div><input type="hidden" name="nile_cruise[cabins][${i}][has_private_bathroom]" value="0"><label><input type="checkbox" name="nile_cruise[cabins][${i}][has_private_bathroom]" value="1"> {{ admin_t('حمام خاص') }}</label></div><div><input type="hidden" name="nile_cruise[cabins][${i}][has_private_terrace]" value="0"><label><input type="checkbox" name="nile_cruise[cabins][${i}][has_private_terrace]" value="1"> {{ admin_t('تراس خاص') }}</label></div><div class="nc-full"><label class="form-label">{{ admin_t('المرافق') }}</label><textarea class="form-control" rows="2" name="nile_cruise[cabins][${i}][amenities]"></textarea></div><div class="nc-full"><label class="form-label">{{ admin_t('الوصف') }}</label><textarea class="form-control" rows="2" name="nile_cruise[cabins][${i}][description]"></textarea></div><div class="nc-full"><label class="form-label">{{ admin_t('الصورة') }}</label><input type="file" class="form-control" name="nile_cruise[cabins][${i}][image]" accept="image/jpeg,image/png,image/webp"></div></div></div>`);refreshCabinSelectors();return;}
        if(e.target.closest('[data-nc-add-duration]')){const d=durationIndex++;document.getElementById('ncDurationsList').insertAdjacentHTML('beforeend',durationTemplate(d));return;}
        const duration=e.target.closest('[data-nc-duration]'); if(!duration)return; const d=duration.dataset.durationIndex;
        if(e.target.closest('[data-nc-add-day]')){const wrap=duration.querySelector('[data-nc-days]'), day=wrap.querySelectorAll('[data-nc-day]').length;wrap.insertAdjacentHTML('beforeend',dayTemplate(d,day));return;}
        const day=e.target.closest('[data-nc-day]');
        if(day&&e.target.closest('[data-nc-add-activity]')){const dayIndex=day.dataset.dayIndex,wrap=day.querySelector('[data-nc-activities]'),a=wrap.querySelectorAll('[data-nc-activity]').length;wrap.insertAdjacentHTML('beforeend',activityTemplate(d,dayIndex,a));return;}
        if(e.target.closest('[data-nc-add-season]')){const wrap=duration.querySelector('[data-nc-seasons]'),s=wrap.querySelectorAll('[data-nc-season]').length;wrap.insertAdjacentHTML('beforeend',seasonTemplate(d,s));refreshCabinSelectors();return;}
        const season=e.target.closest('[data-nc-season]');
        if(season&&e.target.closest('[data-nc-add-price-item]')){const s=season.dataset.seasonIndex,wrap=season.querySelector('[data-nc-price-items]'),p=wrap.querySelectorAll('[data-nc-price-item]').length;wrap.insertAdjacentHTML('beforeend',priceItemTemplate(d,s,p));refreshCabinSelectors();return;}
    });

    function durationTemplate(d){return `<div class="nc-repeat" data-nc-duration data-duration-index="${d}"><div class="nc-repeat-head"><strong>{{ admin_t('مدة الكروز') }}</strong><button type="button" class="btn btn-outline-danger btn-sm" data-nc-remove>{{ admin_t('حذف المدة') }}</button></div><div class="nc-grid nc-grid-3"><div><label class="form-label">{{ admin_t('العنوان') }}</label><input class="form-control" name="nile_cruise[durations][${d}][title]" placeholder="3 Nights / 4 Days"></div><div><label class="form-label">{{ admin_t('الأيام') }}</label><input type="number" min="1" class="form-control" name="nile_cruise[durations][${d}][days]"></div><div><label class="form-label">{{ admin_t('الليالي') }}</label><input type="number" min="0" class="form-control" name="nile_cruise[durations][${d}][nights]"></div><div><label class="form-label">{{ admin_t('الاتجاه') }}</label><input class="form-control" name="nile_cruise[durations][${d}][direction]"></div><div><label class="form-label">{{ admin_t('يوم المغادرة') }}</label><input class="form-control" name="nile_cruise[durations][${d}][departure_day]"></div><div><label class="form-label">{{ admin_t('العملة') }}</label>${currencySelect(`nile_cruise[durations][${d}][currency_id]`)}</div><div><label class="form-label">{{ admin_t('من') }}</label>${citySelect(`nile_cruise[durations][${d}][departure_city_id]`)}</div><div><label class="form-label">{{ admin_t('إلى') }}</label>${citySelect(`nile_cruise[durations][${d}][arrival_city_id]`)}</div><div><input type="hidden" name="nile_cruise[durations][${d}][is_active]" value="0"><label><input type="checkbox" checked name="nile_cruise[durations][${d}][is_active]" value="1"> {{ admin_t('نشطة') }}</label><br><input type="hidden" name="nile_cruise[durations][${d}][is_default]" value="0"><label><input type="checkbox" name="nile_cruise[durations][${d}][is_default]" value="1"> {{ admin_t('الافتراضية') }}</label></div></div><div class="nc-sub"><div class="nc-head"><strong>{{ admin_t('البرنامج اليومي') }}</strong><button type="button" class="btn btn-outline-primary btn-sm" data-nc-add-day>{{ admin_t('+ يوم') }}</button></div><div data-nc-days></div></div><div class="nc-sub"><div class="nc-head"><strong>{{ admin_t('المواسم والأسعار') }}</strong><button type="button" class="btn btn-outline-primary btn-sm" data-nc-add-season>{{ admin_t('+ موسم') }}</button></div><div data-nc-seasons></div></div></div>`;}
    function dayTemplate(d,day){return `<div class="nc-repeat" data-nc-day data-day-index="${day}"><div class="nc-repeat-head"><strong>{{ admin_t('اليوم') }} ${day+1}</strong><button type="button" class="btn btn-outline-danger btn-sm" data-nc-remove>{{ admin_t('حذف') }}</button></div><div class="nc-grid"><input type="hidden" name="nile_cruise[durations][${d}][itinerary][${day}][day_number]" value="${day+1}"><div><label class="form-label">{{ admin_t('العنوان') }}</label><input class="form-control" name="nile_cruise[durations][${d}][itinerary][${day}][title]"></div><div><label class="form-label">{{ admin_t('المبيت') }}</label><input class="form-control" name="nile_cruise[durations][${d}][itinerary][${day}][overnight]"></div><div class="nc-full"><label class="form-label">{{ admin_t('الوصف') }}</label><textarea class="form-control" rows="4" name="nile_cruise[durations][${d}][itinerary][${day}][description]"></textarea></div><div class="nc-full"><label class="form-label">{{ admin_t('الوجبات') }}</label><textarea class="form-control" rows="2" name="nile_cruise[durations][${d}][itinerary][${day}][meals]"></textarea></div></div><div class="nc-sub"><div class="nc-head"><strong>{{ admin_t('الأنشطة') }}</strong><button type="button" class="btn btn-outline-secondary btn-sm" data-nc-add-activity>{{ admin_t('+ نشاط') }}</button></div><div data-nc-activities></div></div></div>`;}
    function activityTemplate(d,day,a){return `<div class="nc-repeat" data-nc-activity><div class="nc-grid nc-grid-3"><div><label class="form-label">{{ admin_t('العنوان') }}</label><input class="form-control" name="nile_cruise[durations][${d}][itinerary][${day}][activities][${a}][title]"></div><div><label class="form-label">{{ admin_t('المعلم') }}</label>${attractionSelect(`nile_cruise[durations][${d}][itinerary][${day}][activities][${a}][attraction_id]`)}</div><div><button type="button" class="btn btn-outline-danger mt-4" data-nc-remove>{{ admin_t('حذف') }}</button></div><div class="nc-full"><textarea class="form-control" rows="2" name="nile_cruise[durations][${d}][itinerary][${day}][activities][${a}][description]"></textarea></div></div></div>`;}
    function seasonTemplate(d,s){return `<div class="nc-repeat" data-nc-season data-season-index="${s}"><div class="nc-repeat-head"><strong>{{ admin_t('Season') }}</strong><button type="button" class="btn btn-outline-danger btn-sm" data-nc-remove>{{ admin_t('حذف') }}</button></div><div class="nc-grid nc-grid-3"><div><label class="form-label">{{ admin_t('الموسم') }}</label><input class="form-control" name="nile_cruise[durations][${d}][seasons][${s}][season_name]"></div><div><label class="form-label">{{ admin_t('من') }}</label><input type="date" class="form-control" name="nile_cruise[durations][${d}][seasons][${s}][date_from]"></div><div><label class="form-label">{{ admin_t('إلى') }}</label><input type="date" class="form-control" name="nile_cruise[durations][${d}][seasons][${s}][date_to]"></div><div><label class="form-label">{{ admin_t('العملة') }}</label>${currencySelect(`nile_cruise[durations][${d}][seasons][${s}][currency_id]`)}</div><div class="nc-full"><input class="form-control" name="nile_cruise[durations][${d}][seasons][${s}][notes]" placeholder="{{ admin_t('ملاحظات') }}"></div><input type="hidden" name="nile_cruise[durations][${d}][seasons][${s}][is_active]" value="1"></div><div class="nc-sub"><div class="nc-head"><strong>{{ admin_t('بنود السعر') }}</strong><button type="button" class="btn btn-outline-secondary btn-sm" data-nc-add-price-item>{{ admin_t('+ سعر') }}</button></div><div data-nc-price-items></div></div></div>`;}
    function priceItemTemplate(d,s,p){return `<div class="nc-repeat" data-nc-price-item><div class="nc-grid nc-grid-3"><div><label class="form-label">{{ admin_t('الكابينة') }}</label><select data-nc-cabin-select class="form-select" name="nile_cruise[durations][${d}][seasons][${s}][items][${p}][cabin_key]"></select></div><div><label class="form-label">{{ admin_t('الإشغال') }}</label><select class="form-select" name="nile_cruise[durations][${d}][seasons][${s}][items][${p}][occupancy_type]"><option value="single">Single</option><option value="double">Double</option><option value="triple">Triple</option><option value="suite">Suite</option><option value="custom">Custom</option></select></div><div><label class="form-label">{{ admin_t('السعر') }}</label><input type="number" step="0.01" min="0" class="form-control" name="nile_cruise[durations][${d}][seasons][${s}][items][${p}][price]"></div><div class="nc-full"><input class="form-control" name="nile_cruise[durations][${d}][seasons][${s}][items][${p}][label]" placeholder="Royal Suite Price"></div><div><button type="button" class="btn btn-outline-danger" data-nc-remove>{{ admin_t('حذف') }}</button></div></div></div>`;}
    refreshCabinSelectors();
});
</script>
@endonce
