@extends('website.layouts.master')

@section('title', $package->getTranslation('seo_title') ?: $title . ' - Etro Tours')
@section('description', $package->getTranslation('seo_description') ?: $shortDescription)
@section('keywords', trim(collect([$title, $tourTypeText ?? null, $package->primaryCountry?->display_name ?? null, 'Etro Tours'])->filter()->implode(', '), ', '))
@section('image', $heroImage)

@section('css')
    <style>
        .package-hero {
            height: 72vh;
            min-height: 520px;
            background: linear-gradient(rgba(28, 50, 92, .38), rgba(26, 75, 102, .48)), var(--hero-bg);
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden
        }

        .package-hero:after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .18);
            pointer-events: none
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: #fff;
            max-width: 1000px;
            margin: auto;
            padding: 120px 20px 0
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.4rem, 6vw, 5rem);
            font-weight: 700;
            margin-bottom: 18px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, .35)
        }

        .hero-subtitle {
            font-size: 1.2rem;
            opacity: .95;
            max-width: 780px;
            margin: 0 auto 25px;
            line-height: 1.7
        }

        .hero-actions {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap
        }

        .gold-btn,
        .outline-btn,
        .submit-btn {
            background: var(--gradient-gold, #c5955b);
            color: var(--primary-navy, #1c325c);
            padding: 13px 28px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            border: 0;
            transition: .3s;
            cursor: pointer
        }

        .outline-btn {
            background: rgba(255, 255, 255, .13);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .45);
            backdrop-filter: blur(8px)
        }

        .gold-btn:hover,
        .submit-btn:hover {
            transform: translateY(-3px);
            color: var(--primary-navy, #1c325c);
            box-shadow: 0 12px 28px rgba(197, 149, 91, .35)
        }

        .outline-btn:hover {
            background: #fff;
            color: var(--primary-navy, #1c325c)
        }

        .breadcrumb-top-bar {
            background: var(--pearl-luxury, #faf8f3);
            padding: 15px 0;
            border-bottom: 1px solid rgba(197, 149, 91, .18)
        }

        .breadcrumb-list ul {
            list-style: none;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 0;
            padding: 0
        }

        .breadcrumb-list li {
            color: #777
        }

        .breadcrumb-list li:not(:last-child):after {
            content: '›';
            margin-left: 10px;
            color: var(--rich-gold, #c5955b);
            font-size: 1.2rem
        }

        .breadcrumb-list a {
            color: var(--primary-navy, #1c325c);
            text-decoration: none
        }

        .main-container {
            background: linear-gradient(135deg, var(--cream-elegant, #f8f2e8), var(--light-sand, #efe4d3));
            padding: 70px 0
        }

        .content-wrapper {
            position: relative
        }

        .content-section {
            background: #fff;
            border-radius: 24px;
            padding: 34px;
            margin-bottom: 30px;
            box-shadow: 0 10px 35px rgba(28, 50, 92, .08);
            border: 1px solid rgba(197, 149, 91, .14)
        }

        .section-header {
            font-family: 'Playfair Display', serif;
            color: var(--primary-navy, #1c325c);
            font-size: clamp(1.45rem, 3vw, 2.15rem);
            font-weight: 700;
            margin-bottom: 18px;
            position: relative
        }

        .section-header:after {
            content: '';
            display: block;
            width: 78px;
            height: 4px;
            background: var(--gradient-gold, #c5955b);
            border-radius: 4px;
            margin-top: 12px
        }

        .section-subtitle {
            color: #777;
            line-height: 1.7;
            margin-bottom: 25px
        }

        .about-content {
            color: #555;
            line-height: 1.85
        }

        .cruise-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 28px
        }

        .detail-item {
            background: var(--pearl-luxury, #faf8f3);
            border: 1px solid rgba(197, 149, 91, .16);
            border-radius: 16px;
            padding: 16px;
            display: flex;
            gap: 12px;
            align-items: flex-start
        }

        .detail-item i {
            width: 38px;
            height: 38px;
            min-width: 38px;
            border-radius: 50%;
            background: var(--gradient-gold, #c5955b);
            color: var(--primary-navy, #1c325c);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem
        }

        .detail-text {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px 6px;
            line-height: 1.65
        }

        .detail-label {
            color: var(--primary-navy, #1c325c);
            white-space: nowrap
        }

        .detail-value {
            color: #555
        }

        .day-card {
            border: 1px solid rgba(197, 149, 91, .18);
            border-radius: 18px;
            margin-bottom: 16px;
            overflow: hidden;
            background: #fff
        }

        .day-header {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 18px;
            cursor: pointer;
            background: var(--pearl-luxury, #faf8f3)
        }

        .day-number {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: var(--gradient-gold, #c5955b);
            color: var(--primary-navy, #1c325c);
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 46px
        }

        .day-title {
            font-family: 'Playfair Display', serif;
            color: var(--primary-navy, #1c325c);
            font-size: 1.15rem;
            margin: 0
        }

        .collapsible-content {
            display: none
        }

        .collapsible-content.open {
            display: block
        }

        .day-content {
            padding: 22px;
            color: #555;
            line-height: 1.85
        }

        .meals-row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 14px
        }

        .meal-badge {
            background: rgba(197, 149, 91, .13);
            color: var(--primary-navy, #1c325c);
            border-radius: 20px;
            padding: 6px 12px;
            font-weight: 600;
            font-size: .85rem
        }

        .styled-list ul {
            padding-left: 20px;
            margin: 0
        }

        .styled-list li {
            padding: 9px 0;
            color: #555;
            line-height: 1.65;
            border-bottom: 1px solid rgba(197, 149, 91, .12)
        }

        .styled-list li:last-child {
            border-bottom: 0
        }

        .included-box,
        .excluded-box,
        .price-box {
            background: var(--pearl-luxury, #faf8f3);
            border-radius: 20px;
            padding: 26px;
            border: 1px solid rgba(197, 149, 91, .16);
            height: 100%
        }

        .box-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--primary-navy, #1c325c);
            margin-bottom: 16px
        }

        .price-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px
        }

        .price-table tr {
            background: #fff
        }

        .price-table td,
        .price-table th {
            padding: 13px 15px
        }

        .price-table th {
            color: var(--primary-navy, #1c325c)
        }

        .price-table td:first-child,
        .price-table th:first-child {
            border-radius: 12px 0 0 12px
        }

        .price-table td:last-child,
        .price-table th:last-child {
            border-radius: 0 12px 12px 0;
            text-align: right;
            font-weight: 800;
            color: var(--rich-gold, #c5955b)
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px
        }

        .gallery-item {
            height: 170px;
            border-radius: 18px;
            overflow: hidden;
            display: block;
            background: #eee;
            position: relative
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: .4s
        }

        .gallery-item:hover img {
            transform: scale(1.06)
        }

        .gallery-item::after {
            content: '\f00e';
            font-family: 'Line Awesome Free';
            font-weight: 900;
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 2rem;
            background: rgba(28, 50, 92, .26);
            opacity: 0;
            transition: .3s
        }

        .gallery-item:hover::after {
            opacity: 1
        }

        .gallery-lightbox {
            position: fixed;
            inset: 0;
            background: rgba(8, 14, 27, .88);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
            z-index: 10050;
            opacity: 0;
            visibility: hidden;
            transition: .25s ease
        }

        .gallery-lightbox.open {
            opacity: 1;
            visibility: visible
        }

        .gallery-lightbox-dialog {
            position: relative;
            width: min(1100px, 100%);
            max-height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center
        }

        .gallery-lightbox-img {
            max-width: 100%;
            max-height: 88vh;
            border-radius: 18px;
            box-shadow: 0 18px 45px rgba(0, 0, 0, .35);
            object-fit: contain;
            background: #fff
        }

        .gallery-lightbox-close,
        .gallery-lightbox-nav {
            border: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: .25s ease
        }

        .gallery-lightbox-close {
            position: absolute;
            top: -18px;
            right: -18px;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #fff;
            color: var(--primary-navy, #1c325c);
            font-size: 2rem;
            line-height: 1;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .2)
        }

        .gallery-lightbox-close:hover,
        .gallery-lightbox-nav:hover {
            transform: scale(1.06)
        }

        .gallery-lightbox-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .16);
            color: #fff;
            font-size: 1.6rem;
            backdrop-filter: blur(6px)
        }

        .gallery-lightbox-nav.prev {
            left: 18px
        }

        .gallery-lightbox-nav.next {
            right: 18px
        }

        .gallery-lightbox-counter {
            position: absolute;
            bottom: 18px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(12, 20, 36, .72);
            color: #fff;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: .9rem
        }

        .review-card {
            background: var(--pearl-luxury, #faf8f3);
            border-radius: 18px;
            padding: 22px;
            margin-bottom: 16px;
            border: 1px solid rgba(197, 149, 91, .15)
        }

        .rating-stars {
            color: #FFD700;
            margin-bottom: 8px
        }

        .verified-badge {
            display: inline-flex;
            background: #2358e6;
            color: #fff;
            font-size: .75rem;
            padding: 5px 10px;
            border-radius: 15px;
            margin-left: 8px
        }

        .sidebar {
            position: sticky;
            top: 100px;
            background: #fff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(28, 50, 92, .14);
            border: 1px solid rgba(197, 149, 91, .18)
        }

        .sidebar-header {
            background: linear-gradient(135deg, var(--primary-navy, #1c325c), #1a4b66);
            color: #fff;
            padding: 25px;
            text-align: center
        }

        .sidebar-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.55rem;
            margin: 0 0 12px
        }

        .sidebar-price span.item {
            font-size: 2.1rem;
            font-weight: 900;
            color: var(--rich-gold, #c5955b)
        }

        .sidebar-content {
            padding: 25px
        }

        .input-box {
            margin-bottom: 18px
        }

        .label-text {
            display: block;
            color: var(--primary-navy, #1c325c);
            font-weight: 700;
            margin-bottom: 8px
        }

        .form-group {
            position: relative
        }

        .form-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--rich-gold, #c5955b);
            z-index: 2
        }

        .form-control,
        .select-contain-select {
            width: 100%;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 14px 18px 14px 44px;
            background: #fff;
            transition: .3s
        }

        .select-contain-select {
            padding-left: 18px
        }

        .message-control {
            min-height: 100px;
            padding-top: 14px
        }

        .form-control:focus,
        .select-contain-select:focus {
            border-color: var(--rich-gold, #c5955b);
            box-shadow: 0 0 0 .25rem rgba(197, 149, 91, .22);
            outline: 0
        }

        .quantity-control {
            background: var(--pearl-luxury, #faf8f3);
            border-radius: 15px;
            padding: 14px;
            margin-bottom: 12px
        }

        .qty-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 8px
        }

        .qty-btn {
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 50%;
            background: var(--gradient-gold, #c5955b);
            color: var(--primary-navy, #1c325c);
            font-weight: 900
        }

        .qty-input {
            width: 55px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 7px
        }

        .trust-indicators {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
            margin-top: 18px
        }

        .trust-item-small {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--primary-navy, #1c325c);
            font-weight: 700
        }

        .trust-item-small i {
            color: var(--rich-gold, #c5955b)
        }

        .fixed-mobile-btn {
            position: fixed;
            bottom: 18px;
            left: 15px;
            right: 15px;
            z-index: 999
        }

        .mobile-enquiry-btn {
            width: 100%;
            background: var(--gradient-gold, #c5955b);
            color: var(--primary-navy, #1c325c);
            border-radius: 50px;
            padding: 14px 20px;
            text-decoration: none;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .2)
        }

        .alert-success {
            background: #e8f8ee;
            color: #146c2e;
            border-radius: 15px;
            padding: 14px 18px;
            margin-bottom: 20px
        }

        .alert-danger {
            background: #fff0f0;
            color: #b42318;
            border-radius: 15px;
            padding: 14px 18px;
            margin-bottom: 20px
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px
        }

        .related-card {
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid rgba(197, 149, 91, .15);
            box-shadow: 0 8px 24px rgba(28, 50, 92, .08)
        }

        .related-card img {
            width: 100%;
            height: 150px;
            object-fit: cover
        }

        .related-card-body {
            padding: 16px
        }

        .related-card-title {
            font-family: 'Playfair Display', serif;
            color: var(--primary-navy, #1c325c);
            font-weight: 800
        }

        .empty-state {
            background: var(--pearl-luxury, #faf8f3);
            padding: 22px;
            border-radius: 16px;
            color: #777;
            text-align: center
        }

        .modal-content {
            border-radius: 24px;
            overflow: hidden
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-navy, #1c325c), #1a4b66);
            color: #fff
        }

        .btn-close {
            filter: invert(1)
        }

        @media(max-width:991px) {
            .package-hero {
                height: 62vh;
                background-attachment: scroll
            }

            .cruise-details,
            .related-grid {
                grid-template-columns: 1fr
            }

            .sidebar {
                position: static;
                margin-top: 25px
            }

            .gallery-grid {
                grid-template-columns: repeat(2, 1fr)
            }

            .gallery-lightbox {
                padding: 18px
            }

            .gallery-lightbox-close {
                top: 10px;
                right: 10px;
                width: 42px;
                height: 42px;
                font-size: 1.8rem
            }

            .gallery-lightbox-nav.prev {
                left: 10px
            }

            .gallery-lightbox-nav.next {
                right: 10px
            }
        }

        @media(max-width:575px) {
            .package-hero {
                min-height: 430px
            }

            .hero-content {
                padding-top: 80px
            }

            .content-section {
                padding: 23px
            }

            .gallery-grid {
                grid-template-columns: 1fr
            }

            .gallery-item {
                height: 210px
            }

            .gallery-lightbox-nav {
                width: 44px;
                height: 44px;
                font-size: 1.35rem
            }

            .price-table {
                font-size: .9rem
            }
        }
    </style>
@endsection

@section('schema')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'TouristTrip',
            'name' => $title,
            'description' => trim(preg_replace('/\s+/', ' ', strip_tags($shortDescription ?: $title))),
            'image' => $heroImage,
            'url' => request()->fullUrl(),
            'provider' => [
                '@type' => 'TravelAgency',
                'name' => 'Etro Tours',
                'url' => url('/'),
            ],
            'touristType' => $tourTypeText ?? __('Private'),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endsection

@section('content')
    @php
        $currencySymbol = $package->currency?->symbol ?? '$';
        $priceFrom = (float) ($package->price_from ?? $package->start_from_price ?? 0);
        $priceTo = (float) ($package->price_to ?? 0);
        $priceText = null;

        if ($priceFrom > 0 || $priceTo > 0) {
            if ($priceTo > $priceFrom && $priceFrom > 0) {
                $priceText = __('trips.from_to_price', [
                    'currency' => $currencySymbol,
                    'from' => number_format($priceFrom, 2),
                    'to' => number_format($priceTo, 2),
                ]);
            } else {
                $priceText = __('trips.from_price', [
                    'currency' => $currencySymbol,
                    'amount' => number_format(max($priceFrom, $priceTo), 2),
                ]);
            }
        }
    @endphp

    <section class="breadcrumb-top-bar">
        <div class="container">
            <div class="breadcrumb-list">
                <ul>
                    <li><a href="{{ route('website.home') }}">{{ __('Home') }}</a></li>
                    <li><a href="{{ route('website.multi_country') }}">{{ __('Tours') }}</a></li>
                    <li>{{ $title }}</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="package-hero" style="--hero-bg:url('{{ $heroImage }}')">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">{{ $title }}</h1>
                @if ($subtitle || $shortDescription)
                    <p class="hero-subtitle">{{ $subtitle ?: $shortDescription }}</p>
                @endif
                <div class="hero-actions">
                    @if (!empty($gallery))
                        <a class="outline-btn js-gallery-trigger" href="{{ $gallery[0] }}" data-gallery-index="0">
                            <i class="la la-image"></i> {{ __('View Gallery') }}
                        </a>
                    @endif
                    <a href="#reserve" class="gold-btn"><i class="la la-envelope"></i> {{ __('Enquire Now') }}</a>
                </div>
            </div>
        </div>
    </section>

    <div class="main-container">
        <div class="container content-wrapper">
            @if (session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <section id="about" class="content-section">
                        <h2 class="section-header">{{ __('About') }} {{ $title }}</h2>
                        @if ($shortDescription)
                            <p class="section-subtitle">{{ $shortDescription }}</p>
                        @endif

                        <div class="about-content">
                            @if ($description)
                                {!! $description !!}
                            @else
                                <p class="empty-state">{{ __('No description added for this package yet.') }}</p>
                            @endif

                            <div class="cruise-details">
                                @if ($durationText)
                                    <div class="detail-item"><i class="la la-calendar"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Duration:') }}</strong>
                                            <span class="detail-value">{{ $durationText }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if ($schedule)
                                    <div class="detail-item"><i class="la la-clock"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Schedule:') }}</strong>
                                            <span class="detail-value">{{ $schedule }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if ($destinations || $locationSummary)
                                    <div class="detail-item"><i class="la la-map-marker"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Destinations:') }}</strong>
                                            <span class="detail-value">{{ $destinations ?: $locationSummary }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if ($pickup)
                                    <div class="detail-item"><i class="la la-map-pin"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Pickup Location:') }}</strong>
                                            <span class="detail-value">{{ $pickup }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if ($dropoff)
                                    <div class="detail-item"><i class="la la-location-arrow"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Dropoff Location:') }}</strong>
                                            <span class="detail-value">{{ $dropoff }}</span>
                                        </div>
                                    </div>
                                @endif
                                <div class="detail-item"><i class="la la-users"></i>
                                    <div class="detail-text">
                                        <strong class="detail-label">{{ __('Tour Type:') }}</strong>
                                        <span class="detail-value">{{ $tourTypeText }}</span>
                                    </div>
                                </div>
                                @if ($package->category)
                                    <div class="detail-item"><i class="la la-tag"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Category:') }}</strong>
                                            <span class="detail-value">{{ $package->category->display_name }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if ($package->difficulty_level)
                                    <div class="detail-item"><i class="la la-hiking"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Difficulty:') }}</strong>
                                            <span class="detail-value">{{ __(ucfirst($package->difficulty_level)) }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if ($package->min_participants || $package->max_participants)
                                    <div class="detail-item"><i class="la la-user-friends"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Group Size:') }}</strong>
                                            <span class="detail-value">
                                                @if($package->min_participants && $package->max_participants)
                                                    {{ $package->min_participants }} - {{ $package->max_participants }} {{ __('Pax') }}
                                                @elseif($package->max_participants)
                                                    {{ __('Up to') }} {{ $package->max_participants }} {{ __('Pax') }}
                                                @else
                                                    {{ __('Min') }} {{ $package->min_participants }} {{ __('Pax') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @endif
                                @if ($package->booking_lead_days)
                                    <div class="detail-item"><i class="la la-hourglass-half"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Booking Window:') }}</strong>
                                            <span class="detail-value">{{ __('Min. :days days before', ['days' => $package->booking_lead_days]) }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    @if ($package->highlights && $package->highlights->count())
                        <section class="content-section">
                            <h2 class="section-header">{{ __('Tour Highlights') }}</h2>
                            <div class="styled-list">
                                <ul style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 10px;">
                                    @foreach ($package->highlights as $highlight)
                                        <li style="border: none; padding: 5px 0;"><i class="la la-check-circle" style="color:var(--rich-gold, #c5955b); margin-right:8px; font-size: 1.2rem; vertical-align: middle;"></i> {{ $highlight->display_title }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </section>
                    @endif

                    @if ($package->packageAttractions && $package->packageAttractions->count())
                        <section class="content-section">
                            <h2 class="section-header">{{ __('Places You\'ll Visit') }}</h2>
                            <div class="row g-4 mt-2">
                                @foreach ($package->packageAttractions as $attraction)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="related-card h-100">
                                            @if($attraction->image)
                                                <img src="{{ asset(ltrim($attraction->image, '/')) }}" alt="{{ $attraction->display_title }}" loading="lazy">
                                            @elseif($attraction->attraction && $attraction->attraction->image)
                                                <img src="{{ asset(ltrim($attraction->attraction->image, '/')) }}" alt="{{ $attraction->display_title }}" loading="lazy">
                                            @else
                                                <img src="{{ asset('website/photos/home2.webp') }}" alt="{{ $attraction->display_title }}" loading="lazy">
                                            @endif
                                            <div class="related-card-body">
                                                <h5 class="related-card-title mb-2">{{ $attraction->display_title }}</h5>
                                                @if($teaser = $attraction->getTranslation('teaser'))
                                                    <p class="mb-0 text-muted" style="font-size: 0.9rem;">{{ \Illuminate\Support\Str::limit($teaser, 100) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if ($package->video_url)
                        <section class="content-section">
                            <h2 class="section-header">{{ __('Video Tour') }}</h2>
                            <div style="border-radius:18px; overflow:hidden; position:relative; padding-bottom:56.25%; height:0; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                                @php
                                    $videoUrl = $package->video_url;
                                    if (str_contains($videoUrl, 'youtube.com/watch?v=')) {
                                        $videoUrl = str_replace('watch?v=', 'embed/', $videoUrl);
                                    } elseif (str_contains($videoUrl, 'youtu.be/')) {
                                        $videoUrl = str_replace('youtu.be/', 'youtube.com/embed/', $videoUrl);
                                    }
                                @endphp
                                <iframe src="{{ $videoUrl }}" style="position:absolute; top:0; left:0; width:100%; height:100%; border:0;" allowfullscreen></iframe>
                            </div>
                        </section>
                    @endif

                    <section id="itinerary" class="content-section">
                        <h2 class="section-header">{{ $title }} {{ __('Itinerary') }}</h2>
                        @if ($itineraries->count())
                            <div class="itinerary-section">
                                @foreach ($itineraries as $day)
                                    <div class="day-card">
                                        <div class="day-header" onclick="toggleDay('day-{{ $day->id }}')">
                                            <div class="day-number">{{ $day->day_number }}</div>
                                            <div>
                                                <h3 class="day-title">{{ __('Day') }} {{ $day->day_number }}:
                                                    {{ $day->display_title }}</h3>
                                                @if ($day->duration)
                                                    <small>{{ $day->duration }}</small>
                                                @endif
                                            </div>
                                            <i class="la la-chevron-down collapse-icon" style="margin-left:auto"></i>
                                        </div>
                                        <div class="collapsible-content {{ $loop->first ? 'open' : '' }}"
                                            id="day-{{ $day->id }}">
                                            <div class="day-content">
                                                {!! $day->display_description ?: '<p>' . __('No itinerary description added yet.') . '</p>' !!}
                                                @if ($day->display_overnight)
                                                    <p><strong>{{ __('Overnight:') }}</strong>
                                                        {{ $day->display_overnight }}</p>
                                                @endif
                                                @if ($day->meals_breakfast || $day->meals_lunch || $day->meals_dinner)
                                                    <div class="meals-row">
                                                        @if ($day->meals_breakfast)
                                                            <span class="meal-badge">{{ __('Breakfast') }}</span>
                                                        @endif
                                                        @if ($day->meals_lunch)
                                                            <span class="meal-badge">{{ __('Lunch') }}</span>
                                                        @endif
                                                        @if ($day->meals_dinner)
                                                            <span class="meal-badge">{{ __('Dinner') }}</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                {{ __('Itinerary is not linked yet because there are no records for this package in the itineraries table.') }}
                            </div>
                        @endif
                    </section>

                    <section class="content-section">
                        <h2 class="section-header">{{ __('What\'s Included') }}</h2>
                        <div class="row g-4">
                            <div class="col-md-12">
                                <div class="included-box">
                                    <h4 class="box-title">{{ __('Included in Your Journey') }}</h4>
                                    @if ($included->count())
                                        <div class="styled-list">
                                            <ul>
                                                @foreach ($included as $item)
                                                    <li>{!! $item->display_content !!}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <div class="empty-state">
                                            {{ __('Included items are not linked yet because there are no valid included records in package_inclusions.') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="excluded-box">
                                    <h4 class="box-title">{{ __('Not Included') }}</h4>
                                    @if ($excluded->count())
                                        <div class="styled-list">
                                            <ul>
                                                @foreach ($excluded as $item)
                                                    <li>{!! $item->display_content !!}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <div class="empty-state">
                                            {{ __('Not included items are not linked yet because there are no valid excluded records in package_inclusions.') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>

                    @if ($prices->count())
                        <section class="content-section">
                            <h2 class="section-header">{{ __('Pricing & Packages') }}</h2>
                            <div class="price-box">
                                <table class="price-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Option') }}</th>
                                            <th>{{ __('Guests') }}</th>
                                            <th>{{ __('Price') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($prices as $price)
                                            <tr>
                                                <td>
                                                    {{ $price->display_label }}
                                                    @if ($price->display_notes)
                                                        <br><small>{{ $price->display_notes }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($price->pax_min || $price->pax_max)
                                                        {{ $price->pax_min ?? 1 }} - {{ $price->pax_max ?? '+' }} {{ __('Pax') }}
                                                    @else
                                                        {{ __('Per Person') }}
                                                    @endif
                                                </td>
                                                <td>{{ $price->formatted_amount }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    @endif

                    @if (count($gallery) > 1)
                        <section class="content-section">
                            <h2 class="section-header">{{ __('Gallery') }}</h2>
                            <div class="gallery-grid">
                                @foreach ($gallery as $img)
                                    <a class="gallery-item js-gallery-trigger" href="{{ $img }}"
                                        data-gallery-index="{{ $loop->index }}">
                                        <img src="{{ $img }}" alt="{{ $title }}" loading="lazy">
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if ($package->getTranslation('cancellation_policy') || $package->getTranslation('terms_conditions'))
                        <section class="content-section">
                            <h2 class="section-header">{{ __('Important Information') }}</h2>
                            
                            @if ($cancellationPolicy = $package->getTranslation('cancellation_policy'))
                                <div class="mb-4">
                                    <h4 class="mb-3" style="color: var(--primary-navy, #1c325c); font-family: 'Playfair Display', serif;"><i class="la la-info-circle" style="color: var(--rich-gold, #c5955b);"></i> {{ __('Cancellation Policy') }}</h4>
                                    <div class="about-content">
                                        {!! $cancellationPolicy !!}
                                    </div>
                                </div>
                            @endif

                            @if ($termsConditions = $package->getTranslation('terms_conditions'))
                                <div>
                                    <h4 class="mb-3" style="color: var(--primary-navy, #1c325c); font-family: 'Playfair Display', serif;"><i class="la la-file-alt" style="color: var(--rich-gold, #c5955b);"></i> {{ __('Terms & Conditions') }}</h4>
                                    <div class="about-content">
                                        {!! $termsConditions !!}
                                    </div>
                                </div>
                            @endif
                        </section>
                    @endif

                    @if ($package->faq_json && is_array($package->faq_json) && count($package->faq_json) > 0)
                        <section class="content-section">
                            <h2 class="section-header">{{ __('Frequently Asked Questions') }}</h2>
                            <div class="faq-accordion">
                                @foreach ($package->faq_json as $index => $faq)
                                    <div class="day-card mb-3">
                                        <div class="day-header" onclick="toggleDay('faq-{{ $index }}')">
                                            <div class="day-number" style="width: 36px; height: 36px; min-width: 36px; font-size: 1.2rem;"><i class="la la-question"></i></div>
                                            <div>
                                                <h3 class="day-title" style="font-size: 1.05rem; font-family: inherit; font-weight: 700;">
                                                    {{ is_array($faq['question'] ?? '') ? ($faq['question'][app()->getLocale()] ?? $faq['question']['en'] ?? '') : ($faq['question'] ?? '') }}
                                                </h3>
                                            </div>
                                            <i class="la la-chevron-down collapse-icon" style="margin-left:auto"></i>
                                        </div>
                                        <div class="collapsible-content" id="faq-{{ $index }}">
                                            <div class="day-content" style="padding: 15px 22px;">
                                                <p class="mb-0">
                                                    {!! is_array($faq['answer'] ?? '') ? ($faq['answer'][app()->getLocale()] ?? $faq['answer']['en'] ?? '') : ($faq['answer'] ?? '') !!}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    <section id="reviews" class="content-section">
                        <h2 class="section-header">{{ __('Guest Reviews') }}</h2>
                        @if ($reviews->count())
                            @foreach ($reviews as $review)
                                <div class="review-card">
                                    <div class="rating-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="la {{ $i <= round($review->rating) ? 'la-star' : 'la-star-o' }}"></i>
                                        @endfor
                                    </div>
                                    @if ($review->title)
                                        <h5>{{ $review->title }}</h5>
                                    @endif
                                    <p>{{ $review->content }}</p>
                                </div>
                            @endforeach
                        @elseif($testimonials->count())
                            @foreach ($testimonials as $testimonial)
                                <div class="review-card">
                                    <div class="rating-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="la {{ $i <= (int) $testimonial->rating ? 'la-star' : 'la-star-o' }}"></i>
                                        @endfor
                                        @if ($testimonial->is_verified)
                                            <span class="verified-badge">{{ __('Verified') }}</span>
                                        @endif
                                    </div>
                                    <p>"{{ $testimonial->content }}"</p>
                                    <strong>{{ $testimonial->customer_name }}</strong>
                                    @if ($testimonial->source)
                                        <small> - {{ $testimonial->source }}</small>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                {{ __('Reviews are not linked yet. No approved reviews or active testimonials found for this package.') }}
                            </div>
                        @endif

                        {{-- غير مربوط: TripAdvisor live widget لأنه محتاج كود رسمي/API من TripAdvisor --}}
                    </section>

                    @if ($relatedPackages->count())
                        <section class="content-section">
                            <h2 class="section-header">{{ __('Related Tours') }}</h2>
                            <div class="related-grid">
                                @foreach ($relatedPackages as $related)
                                    <div class="related-card">
                                        <img src="{{ $related['image'] }}" alt="{{ $related['title'] }}" loading="lazy">
                                        <div class="related-card-body">
                                            <div class="related-card-title">{{ $related['title'] }}</div>
                                            <a class="gold-btn mt-3"
                                                href="{{ $related['url'] }}">{{ $related['button_text'] }}</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif
                </div>

                <div class="col-lg-4 d-none d-lg-block">
                    <div class="sidebar" id="reserve">
                        <div class="sidebar-header">
                            <h3 class="sidebar-title">{{ __('Reserve Your Journey') }}</h3>
                            @if ($priceText)
                                <div class="sidebar-price"><span class="item">{{ $priceText }}</span></div>
                            @else
                                <div class="sidebar-price"><span class="item">{{ __('Ask for Price') }}</span></div>
                            @endif
                        </div>
                        <div class="sidebar-content">
                            @include('website.pages.packages.partials.enquiry-form', [
                                'formSuffix' => 'desktop',
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (!empty($gallery))
        <div class="gallery-lightbox" id="galleryLightbox" aria-hidden="true">
            <div class="gallery-lightbox-dialog">
                <button type="button" class="gallery-lightbox-close" id="galleryLightboxClose"
                    aria-label="{{ __('Close') }}">×</button>
                <button type="button" class="gallery-lightbox-nav prev" id="galleryLightboxPrev"
                    aria-label="{{ __('Previous') }}">
                    <i class="la la-angle-left"></i>
                </button>
                <img src="" alt="{{ $title }}" class="gallery-lightbox-img" id="galleryLightboxImage">
                <button type="button" class="gallery-lightbox-nav next" id="galleryLightboxNext"
                    aria-label="{{ __('Next') }}">
                    <i class="la la-angle-right"></i>
                </button>
                <div class="gallery-lightbox-counter" id="galleryLightboxCounter"></div>
            </div>
        </div>
    @endif

    <div class="fixed-mobile-btn d-lg-none">
        <a href="#" class="mobile-enquiry-btn" data-bs-toggle="modal" data-bs-target="#simpleEnquiryModal">
            <i class="la la-envelope"></i> {{ __('Enquire Now') }}
        </a>
    </div>

    <div class="modal fade" id="simpleEnquiryModal" tabindex="-1" aria-labelledby="simpleEnquiryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h3 class="modal-title">{{ __('Enquire About This Tour') }}</h3>
                        <p class="mb-0">{{ __('Get a personalized quote') }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    @include('website.pages.packages.partials.enquiry-form', [
                        'formSuffix' => 'mobile',
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function toggleDay(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.toggle('open');
            }
        }

        function changeQty(id, amount) {
            const input = document.getElementById(id);
            if (!input) return;
            const min = parseInt(input.getAttribute('min') || '0');
            const current = parseInt(input.value || min);
            input.value = Math.max(min, current + amount);
        }

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (!target) return;
                e.preventDefault();
                window.scrollTo({
                    top: target.offsetTop - 90,
                    behavior: 'smooth'
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const galleryImages = @json(array_values($gallery ?? []));
            const lightbox = document.getElementById('galleryLightbox');

            if (!lightbox || !galleryImages.length) {
                return;
            }

            const lightboxImage = document.getElementById('galleryLightboxImage');
            const lightboxCounter = document.getElementById('galleryLightboxCounter');
            const closeButton = document.getElementById('galleryLightboxClose');
            const prevButton = document.getElementById('galleryLightboxPrev');
            const nextButton = document.getElementById('galleryLightboxNext');
            const triggers = document.querySelectorAll('.js-gallery-trigger');
            let currentIndex = 0;

            const updateLightbox = () => {
                lightboxImage.src = galleryImages[currentIndex];
                lightboxCounter.textContent = `${currentIndex + 1} / ${galleryImages.length}`;
                prevButton.style.display = galleryImages.length > 1 ? 'inline-flex' : 'none';
                nextButton.style.display = galleryImages.length > 1 ? 'inline-flex' : 'none';
            };

            const openLightbox = (index) => {
                currentIndex = index;
                updateLightbox();
                lightbox.classList.add('open');
                lightbox.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            };

            const closeLightbox = () => {
                lightbox.classList.remove('open');
                lightbox.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            };

            const showNext = () => {
                currentIndex = (currentIndex + 1) % galleryImages.length;
                updateLightbox();
            };

            const showPrev = () => {
                currentIndex = (currentIndex - 1 + galleryImages.length) % galleryImages.length;
                updateLightbox();
            };

            triggers.forEach((trigger) => {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    openLightbox(Number(this.dataset.galleryIndex || 0));
                });
            });

            closeButton.addEventListener('click', closeLightbox);
            nextButton.addEventListener('click', showNext);
            prevButton.addEventListener('click', showPrev);

            lightbox.addEventListener('click', function(e) {
                if (e.target === lightbox) {
                    closeLightbox();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (!lightbox.classList.contains('open')) {
                    return;
                }

                if (e.key === 'Escape') {
                    closeLightbox();
                } else if (e.key === 'ArrowRight') {
                    showNext();
                } else if (e.key === 'ArrowLeft') {
                    showPrev();
                }
            });
        });
    </script>
@endsection
