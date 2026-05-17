<form method="post" action="{{ route('website.inquiries.store') }}">
    @csrf
    <input type="hidden" name="package_id" value="{{ $package->id }}">
    <input type="hidden" name="title" value="{{ $title }}">

    <div class="input-box">
        <label class="label-text">{{ __('Your Name *') }}</label>
        <div class="form-group">
            <span class="la la-user form-icon"></span>
            <input class="form-control" type="text" name="name" required placeholder="{{ __('Your name') }}"
                value="{{ old('name') }}">
        </div>
    </div>

    <div class="input-box">
        <label class="label-text">{{ __('Your Email *') }}</label>
        <div class="form-group">
            <span class="la la-envelope-o form-icon"></span>
            <input class="form-control" type="email" name="email" required placeholder="{{ __('Email address') }}"
                value="{{ old('email') }}">
        </div>
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
    </div>

    <div class="input-box">
        <label class="label-text">{{ __('Phone Number') }}</label>
        <div class="form-group">
            <span class="la la-phone form-icon"></span>
            <input class="form-control" type="text" name="phone" placeholder="{{ __('Phone Number') }}"
                value="{{ old('phone') }}">
        </div>
    </div>

    <div class="input-box">
        <label class="label-text">{{ __('Date *') }}</label>
        <div class="form-group">
            <span class="la la-calendar form-icon"></span>
            <input name="travel_date" class="form-control" type="date" required value="{{ old('travel_date') }}">
        </div>
    </div>

    <div class="sidebar-widget-item">
        <div class="quantity-control">
            <label for="adults_book_{{ $formSuffix ?? 'form' }}">{{ __('Adults (12+ years)') }}</label>
            <div class="qty-buttons">
                <button type="button" class="qty-btn"
                    onclick="changeQty('adults_book_{{ $formSuffix ?? 'form' }}', -1)">-</button>
                <input type="number" id="adults_book_{{ $formSuffix ?? 'form' }}" name="adults" class="qty-input"
                    value="{{ old('adults', 2) }}" min="1" readonly>
                <button type="button" class="qty-btn"
                    onclick="changeQty('adults_book_{{ $formSuffix ?? 'form' }}', 1)">+</button>
            </div>
        </div>

        <div class="quantity-control">
            <label for="children_book_{{ $formSuffix ?? 'form' }}">{{ __('Children (2–11 years)') }}</label>
            <div class="qty-buttons">
                <button type="button" class="qty-btn"
                    onclick="changeQty('children_book_{{ $formSuffix ?? 'form' }}', -1)">-</button>
                <input type="number" id="children_book_{{ $formSuffix ?? 'form' }}" name="children" class="qty-input"
                    value="{{ old('children', 0) }}" min="0" readonly>
                <button type="button" class="qty-btn"
                    onclick="changeQty('children_book_{{ $formSuffix ?? 'form' }}', 1)">+</button>
            </div>
        </div>
    </div>

    <div class="input-box">
        <label class="label-text">{{ __('Message') }}</label>
        <div class="form-group">
            <span class="la la-pencil form-icon" style="top:24px"></span>
            <textarea class="message-control form-control" name="comment" placeholder="{{ __('Please advise your tour requirements') }}">{{ old('comment') }}</textarea>
        </div>
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
