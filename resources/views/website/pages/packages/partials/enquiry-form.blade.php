<form method="post" action="{{ route('website.inquiries.store') }}">
    @csrf
    @php
        $suffix = $formSuffix ?? 'form';
        $adultMinAge = (int) ($package->adult_min_age ?? 12);
        $childMinAge = (int) ($package->child_min_age ?? 2);
        $childMaxAge = (int) ($package->child_max_age ?? 11);
        $infantMinAge = (int) ($package->infant_min_age ?? 0);
        $infantMaxAge = (int) ($package->infant_max_age ?? 1);
        $currencySymbol = $package->currency?->symbol ?? '$';
    @endphp
    <input type="hidden" name="package_id" value="{{ $package->id }}">
    <input type="hidden" name="title" value="{{ $title }}">

    <div class="input-box">
        <label class="label-text">{{ __('Your Name *') }}</label>
        <div class="form-group">
            <span class="la la-user form-icon"></span>
            <input class="form-control" type="text" name="name" required placeholder="{{ __('Your name') }}"
                value="{{ old('name') }}">
        </div>
        @error('name')
            <small class="text-danger d-block mt-1">{{ $message }}</small>
        @enderror
    </div>

    <div class="input-box">
        <label class="label-text">{{ __('Your Email *') }}</label>
        <div class="form-group">
            <span class="la la-envelope-o form-icon"></span>
            <input class="form-control" type="email" name="email" required placeholder="{{ __('Email address') }}"
                value="{{ old('email') }}">
        </div>
        @error('email')
            <small class="text-danger d-block mt-1">{{ $message }}</small>
        @enderror
    </div>

    <div class="input-box">
        <label class="label-text">{{ __('Country *') }}</label>
        <div class="select-contain w-auto">
            <select class="select-contain-select" required name="nationality">
                <option value="">{{ __('Select your country') }}</option>
                @foreach ($countries as $countryName)
                    <option value="{{ $countryName }}" @selected(old('nationality') == $countryName)>{{ $countryName }}</option>
                @endforeach
            </select>
        </div>
        @error('nationality')
            <small class="text-danger d-block mt-1">{{ $message }}</small>
        @enderror
    </div>

    <div class="input-box">
        <label class="label-text">{{ __('Phone Number') }}</label>
        <div class="form-group">
            <span class="la la-phone form-icon"></span>
            <input class="form-control" type="text" name="phone" placeholder="{{ __('Phone Number') }}"
                value="{{ old('phone') }}">
        </div>
        @error('phone')
            <small class="text-danger d-block mt-1">{{ $message }}</small>
        @enderror
    </div>

    <div class="input-box">
        <label class="label-text">{{ __('Date *') }}</label>
        <div class="form-group">
            <span class="la la-calendar form-icon"></span>
            <input name="travel_date" class="form-control" type="date" required value="{{ old('travel_date') }}">
        </div>
        @error('travel_date')
            <small class="text-danger d-block mt-1">{{ $message }}</small>
        @enderror
    </div>

    <div class="sidebar-widget-item">
        <div class="quantity-control">
            <label for="adults_book_{{ $suffix }}">
                {{ __('trips.adults') }}
                {{ __('trips.adults_age', ['age' => $adultMinAge]) }}
            </label>
            <div class="qty-buttons">
                <button type="button" class="qty-btn" data-qty-target="adults_book_{{ $suffix }}"
                    data-qty-step="-1">-</button>
                <input type="number" id="adults_book_{{ $suffix }}" name="adults" class="qty-input"
                    value="{{ old('adults', 1) }}" min="1" readonly>
                <button type="button" class="qty-btn" data-qty-target="adults_book_{{ $suffix }}"
                    data-qty-step="1">+</button>
            </div>
            @error('adults')
                <small class="text-danger d-block mt-1">{{ $message }}</small>
            @enderror
        </div>

        <div class="quantity-control">
            <label for="children_book_{{ $suffix }}">
                {{ __('trips.children') }}
                {{ __('trips.children_age', ['from' => $childMinAge, 'to' => $childMaxAge]) }}
            </label>
            <div class="qty-buttons">
                <button type="button" class="qty-btn" data-qty-target="children_book_{{ $suffix }}"
                    data-qty-step="-1">-</button>
                <input type="number" id="children_book_{{ $suffix }}" name="children" class="qty-input"
                    value="{{ old('children', 0) }}" min="0" readonly>
                <button type="button" class="qty-btn" data-qty-target="children_book_{{ $suffix }}"
                    data-qty-step="1">+</button>
            </div>
            @error('children')
                <small class="text-danger d-block mt-1">{{ $message }}</small>
            @enderror
        </div>

        <div class="quantity-control">
            <label for="infants_book_{{ $suffix }}">
                {{ __('trips.infants') }}
                {{ __('trips.infants_age', ['from' => $infantMinAge, 'to' => $infantMaxAge]) }}
            </label>
            <div class="qty-buttons">
                <button type="button" class="qty-btn" data-qty-target="infants_book_{{ $suffix }}"
                    data-qty-step="-1">-</button>
                <input type="number" id="infants_book_{{ $suffix }}" name="infants" class="qty-input"
                    value="{{ old('infants', 0) }}" min="0" readonly>
                <button type="button" class="qty-btn" data-qty-target="infants_book_{{ $suffix }}"
                    data-qty-step="1">+</button>
            </div>
            @error('infants')
                <small class="text-danger d-block mt-1">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="input-box">
        <label class="label-text">{{ __('trips.total') }}</label>
        <div class="form-group">
            <span class="la la-calculator form-icon"></span>
            <input class="form-control" type="text" id="booking_total_{{ $suffix }}"
                value="{{ $currencySymbol }}0.00" readonly>
        </div>
    </div>

    <div class="input-box">
        <label class="label-text">{{ __('Message') }}</label>
        <div class="form-group">
            <span class="la la-pencil form-icon" style="top:24px"></span>
            <textarea class="message-control form-control" name="comment" placeholder="{{ __('Please advise your tour requirements') }}">{{ old('comment') }}</textarea>
        </div>
        @error('comment')
            <small class="text-danger d-block mt-1">{{ $message }}</small>
        @enderror
    </div>

    {{-- غير مربوط: recaptcha-holder متساب لأنك محتاج تضيف site key + secret key لو هتشغل Google reCAPTCHA --}}
    <div class="input-box">
        <div class="form-group">
            <div class="recaptcha-holder"></div>
        </div>
    </div>

    <div class="btn-box">
        <button type="submit" class="submit-btn" style="width:100%">{{ __('Submit Enquiry') }}</button>
    </div>

    <div class="trust-indicators">
        <div class="trust-item-small"><i class="la la-shield-alt"></i><span>{{ __('Secure Enquiry') }}</span></div>
        <div class="trust-item-small"><i class="la la-clock"></i><span>{{ __('24/7 Support') }}</span></div>
        <div class="trust-item-small"><i class="la la-award"></i><span>{{ __('Best Price Guarantee') }}</span></div>
    </div>
</form>

<script>
    (function() {
        const adultPrice = @json((float) ($package->adult_price ?? 0));
        const childPrice = @json((float) ($package->child_price ?? 0));
        const infantPrice = @json((float) ($package->infant_price ?? 0));
        const currencySymbol = @json($currencySymbol);
        const suffix = @json($suffix);
        const adultsInput = document.getElementById(`adults_book_${suffix}`);
        const childrenInput = document.getElementById(`children_book_${suffix}`);
        const infantsInput = document.getElementById(`infants_book_${suffix}`);
        const totalInput = document.getElementById(`booking_total_${suffix}`);

        function recalculateTotal() {
            if (!totalInput) {
                return;
            }

            const adults = parseInt(adultsInput?.value || '0', 10);
            const children = parseInt(childrenInput?.value || '0', 10);
            const infants = parseInt(infantsInput?.value || '0', 10);
            const total = (adults * adultPrice) + (children * childPrice) + (infants * infantPrice);

            totalInput.value = `${currencySymbol}${total.toFixed(2)}`;
        }

        document.querySelectorAll(`[data-qty-target$="${suffix}"]`).forEach((button) => {
            button.addEventListener('click', function() {
                const input = document.getElementById(this.dataset.qtyTarget);
                if (!input) {
                    return;
                }

                const min = parseInt(input.getAttribute('min') || '0', 10);
                const current = parseInt(input.value || String(min), 10);
                const next = Math.max(min, current + parseInt(this.dataset.qtyStep || '0', 10));
                input.value = next;
                recalculateTotal();
            });
        });

        recalculateTotal();
    })();
</script>
