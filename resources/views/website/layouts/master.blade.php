<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="dark">

<head>
    @php
        $siteName = 'Etro Tours';
        $siteUrl = rtrim(config('app.url') ?: request()->root(), '/');
        $logoUrl = asset('website/logo/logo-lat.png');
        $faviconUrl = $logoUrl;
        $defaultTitle = "Etro Tours | Luxury Egypt Tours, Nile Cruises & Tailor-Made Travel";
        $defaultDescription = "Plan luxury Egypt tours, Nile cruises, private day trips, and tailor-made holidays with Etro Tours. Explore Cairo, Luxor, Aswan, and beyond with expert local travel specialists.";
        $defaultKeywords = 'Etro Tours, Egypt tours, luxury Egypt tours, Nile cruises, Egypt travel packages, Cairo tours, Luxor tours, Aswan tours, tailor made Egypt holidays';
        $rawTitle = trim($__env->yieldContent('title'));
        $rawDescription = trim(preg_replace('/\s+/', ' ', strip_tags($__env->yieldContent('description'))));
        $rawKeywords = trim(preg_replace('/\s+/', ' ', strip_tags($__env->yieldContent('keywords'))));
        $rawCanonical = trim($__env->yieldContent('canonical'));
        $rawRobots = trim($__env->yieldContent('robots'));
        $rawOgType = trim($__env->yieldContent('og_type'));
        $rawTwitterCard = trim($__env->yieldContent('twitter_card'));
        $pageTitle = $rawTitle !== '' ? $rawTitle : $defaultTitle;
        $pageDescription = $rawDescription !== '' ? \Illuminate\Support\Str::limit($rawDescription, 170, '...') : $defaultDescription;
        $pageKeywords = $rawKeywords !== '' ? $rawKeywords : $defaultKeywords;
        $pageCanonical = $rawCanonical !== '' ? $rawCanonical : url()->current();
        $pageImage = $logoUrl;
        $pageRobots = $rawRobots !== '' ? $rawRobots : 'index, follow, max-image-preview:large';
        $pageOgType = $rawOgType !== '' ? $rawOgType : (request()->routeIs('website.blogs.show*') ? 'article' : 'website');
        $twitterCard = $rawTwitterCard !== '' ? $rawTwitterCard : 'summary_large_image';
        $ogLocale = app()->getLocale() === 'ar' ? 'ar_AR' : 'en_US';
        $alternateLocale = app()->getLocale() === 'ar' ? 'en_US' : 'ar_AR';
        $organizationSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'TravelAgency',
            'name' => $siteName,
            'url' => $siteUrl,
            'logo' => $logoUrl,
            'image' => $logoUrl,
            'telephone' => '+1-917-267-8628',
            'email' => 'hello@etrotours.com',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Luxor',
                'addressCountry' => 'EG',
            ],
        ];
        $websiteSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $siteName,
            'url' => $siteUrl,
            'inLanguage' => app()->getLocale(),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => route('website.search.index') . '?keyword={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#0b1220" data-theme-color-meta>
    <title>{{ $pageTitle }}</title>
    <link rel="canonical" href="{{ $pageCanonical }}">
    <meta name="robots" content="{{ $pageRobots }}">
    <meta name="author" content="{{ $siteName }}">
    <meta name="application-name" content="{{ $siteName }}">
    <meta name="keywords" content="{{ $pageKeywords }}">
    <meta name="description" content="{{ $pageDescription }}">
    <meta property="og:type" content="{{ $pageOgType }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="{{ $ogLocale }}">
    <meta property="og:locale:alternate" content="{{ $alternateLocale }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:image:alt" content="{{ $pageTitle }}">
    <meta property="og:url" content="{{ $pageCanonical }}">
    <meta name="twitter:card" content="{{ $twitterCard }}">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $pageImage }}">
    @hasSection('published_time')
        <meta property="article:published_time" content="@yield('published_time')">
    @endif
    @hasSection('modified_time')
        <meta property="article:modified_time" content="@yield('modified_time')">
    @endif
    <script type="application/ld+json">
        {!! json_encode($organizationSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode($websiteSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    @hasSection('schema')
        @yield('schema')
    @endif
    
    <script>
        (function() {
            const storageKey = 'website-theme';
            let theme = 'dark';

            try {
                const storedTheme = localStorage.getItem(storageKey);
                if (storedTheme === 'dark' || storedTheme === 'light') {
                    theme = storedTheme;
                }
            } catch (e) {}

            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.style.colorScheme = theme;
        })();
    </script>

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="manifest" href="{{ asset('website/favicon/manifest.json') }}">
    <link rel="shortcut icon" href="{{ $faviconUrl }}">


    <!-- Modern CSS -->
    <link rel="stylesheet" href="{{ request()->root() }}/website/css/new/bootstrap.min.css">
    <link rel="stylesheet" href="{{ request()->root() }}/website/css/flag-icon.min.css">
    <link rel="stylesheet" href="{{ request()->root() }}/website/css/line-awesome.css">
    <link rel="stylesheet" href="{{ request()->root() }}/website/css/new/style.css">



    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel=preload href="{{ request()->root() }}/website/fonts/la-regular-400.woff2" as=font type=font/woff2
        crossorigin>
    <link rel=preload href="{{ request()->root() }}/website/fonts/la-brands-400.woff2" as=font type=font/woff2
        crossorigin>
    <link rel=preload href="{{ request()->root() }}/website/fonts/la-solid-900.woff2" as=font type=font/woff2
        crossorigin>

    @yield('css')
    <link rel="stylesheet" href="{{ request()->root() }}/website/css/style_new.css">
    <style>
        html {
            color-scheme: dark;
        }

        html[data-theme='dark'] {
            color-scheme: dark;
            --primary-navy: #e5edf9;
            --deep-teal: #7dd3fc;
            --rich-gold: #f4c36a;
            --warm-bronze: #e7b85a;
            --sage-green: #94a3b8;
            --cream-elegant: #0b1220;
            --pearl-luxury: #111827;
            --charcoal-deep: #f8fafc;
            --warm-gray: #cbd5e1;
            --light-sand: #1f2937;
            --gradient-hero: linear-gradient(135deg, #081120 0%, #0f1b33 55%, #152745 100%);
            --gradient-elegant: linear-gradient(135deg, #0b1220 0%, #111827 100%);
            --shadow-subtle: 0 4px 20px rgba(0, 0, 0, 0.28);
            --shadow-medium: 0 8px 32px rgba(0, 0, 0, 0.34);
            --shadow-dramatic: 0 16px 48px rgba(0, 0, 0, 0.42);
            --shadow-gold: 0 6px 25px rgba(244, 195, 106, 0.22);
            --dark-surface: #111827;
            --dark-surface-soft: #172033;
            --dark-surface-muted: #0f172a;
            --dark-border: rgba(148, 163, 184, 0.16);
        }

        html[data-theme='dark'] body,
        html[data-theme='dark'] .results-section,
        html[data-theme='dark'] .offers-summary,
        html[data-theme='dark'] .offers-section,
        html[data-theme='dark'] .luxury-cta-section,
        html[data-theme='dark'] .listing-overview,
        html[data-theme='dark'] .listing-results,
        html[data-theme='dark'] .overview-section,
        html[data-theme='dark'] .filters-section,
        html[data-theme='dark'] .tours-section,
        html[data-theme='dark'] .card-area,
        html[data-theme='dark'] .search-hero,
        html[data-theme='dark'] .search-form-container,
        html[data-theme='dark'] .offers-breadcrumb,
        html[data-theme='dark'] .breadcrumb-top-bar,
        html[data-theme='dark'] .why-choose-section {
            background-color: var(--pearl-luxury) !important;
            color: var(--charcoal-deep) !important;
        }

        html[data-theme='dark'] body {
            background: var(--cream-elegant) !important;
        }

        html[data-theme='dark'] .light-section,
        html[data-theme='dark'] .cream-section,
        html[data-theme='dark'] .main-container,
        html[data-theme='dark'] .offers-summary,
        html[data-theme='dark'] .offers-section,
        html[data-theme='dark'] .results-section {
            background: linear-gradient(180deg, #0b1220 0%, #111827 100%) !important;
        }

        html[data-theme='dark'] .navbar,
        html[data-theme='dark'] .modern-mobile-menu,
        html[data-theme='dark'] .mobile-menu-header {
            background: var(--gradient-hero) !important;
            border-color: rgba(244, 195, 106, 0.14) !important;
        }

        html[data-theme='dark'] .dropdown-menu,
        html[data-theme='dark'] .overview-card,
        html[data-theme='dark'] .filters-card,
        html[data-theme='dark'] .offers-summary-card,
        html[data-theme='dark'] .offer-card,
        html[data-theme='dark'] .journey-card,
        html[data-theme='dark'] .result-card,
        html[data-theme='dark'] .tour-card,
        html[data-theme='dark'] .trust-item,
        html[data-theme='dark'] .luxury-cta-content,
        html[data-theme='dark'] .tripadvisor-award,
        html[data-theme='dark'] .feature-card,
        html[data-theme='dark'] .deal-card,
        html[data-theme='dark'] .destination-card,
        html[data-theme='dark'] .article-card,
        html[data-theme='dark'] .testimonial-card,
        html[data-theme='dark'] .newsletter-box,
        html[data-theme='dark'] .modern-blog-card,
        html[data-theme='dark'] .luxury-sidebar,
        html[data-theme='dark'] .cruise-card,
        html[data-theme='dark'] .empty-state,
        html[data-theme='dark'] .sidebar,
        html[data-theme='dark'] .related-card,
        html[data-theme='dark'] .content-section,
        html[data-theme='dark'] .detail-item,
        html[data-theme='dark'] .day-card,
        html[data-theme='dark'] .quantity-control,
        html[data-theme='dark'] .review-card,
        html[data-theme='dark'] .trust-section,
        html[data-theme='dark'] .search-suggestions-dropdown,
        html[data-theme='dark'] .offer-price-panel,
        html[data-theme='dark'] .offers-empty,
        html[data-theme='dark'] .modal-content,
        html[data-theme='dark'] .overview-content,
        html[data-theme='dark'] .filters-container,
        html[data-theme='dark'] .journey-empty,
        html[data-theme='dark'] .no-results,
        html[data-theme='dark'] .empty-tours-box,
        html[data-theme='dark'] .choose-card,
        html[data-theme='dark'] .main-form,
        html[data-theme='dark'] .sidebar-card,
        html[data-theme='dark'] .contact-card,
        html[data-theme='dark'] .form-container {
            background: var(--dark-surface) !important;
            color: var(--charcoal-deep) !important;
            border-color: var(--dark-border) !important;
            box-shadow: var(--shadow-medium) !important;
        }

        html[data-theme='dark'] .trust-item {
            background: var(--dark-surface) !important;
            border-color: var(--dark-border) !important;
            box-shadow: var(--shadow-subtle) !important;
        }

        html[data-theme='dark'] .trust-item::before {
            background: linear-gradient(to bottom, var(--rich-gold), var(--deep-teal)) !important;
        }

        html[data-theme='dark'] .offer-price-panel,
        html[data-theme='dark'] .detail-item,
        html[data-theme='dark'] .day-header,
        html[data-theme='dark'] .quantity-control,
        html[data-theme='dark'] .review-card,
        html[data-theme='dark'] .empty-state,
        html[data-theme='dark'] .search-suggestions-dropdown,
        html[data-theme='dark'] .offers-empty {
            background: var(--dark-surface-soft) !important;
        }

        html[data-theme='dark'] .luxury-cta-section::before {
            background: radial-gradient(circle, rgba(244, 195, 106, 0.12) 0%, transparent 70%) !important;
        }

        html[data-theme='dark'] .luxury-cta-section::after {
            background: radial-gradient(circle, rgba(125, 211, 252, 0.1) 0%, transparent 70%) !important;
        }

        html[data-theme='dark'] .tripadvisor-award {
            background: linear-gradient(135deg, #111827 0%, #172033 100%) !important;
            border-color: rgba(244, 195, 106, 0.22) !important;
            box-shadow: var(--shadow-medium) !important;
        }

        html[data-theme='dark'] .tripadvisor-award::before {
            background: linear-gradient(90deg, transparent, rgba(244, 195, 106, 0.12), transparent) !important;
        }

        html[data-theme='dark'] .award-image {
            background: #0f172a !important;
            border: 1px solid rgba(52, 224, 161, 0.28) !important;
        }

        html[data-theme='dark'] .award-glow {
            background: radial-gradient(circle, rgba(52, 224, 161, 0.18) 0%, transparent 70%) !important;
        }

        html[data-theme='dark'] .price-table tr,
        html[data-theme='dark'] .deal-price {
            background: var(--dark-surface-soft) !important;
            color: var(--charcoal-deep) !important;
        }

        html[data-theme='dark'] .navbar .dropdown-item,
        html[data-theme='dark'] .dropdown-item,
        html[data-theme='dark'] .card-title,
        html[data-theme='dark'] .tour-title a,
        html[data-theme='dark'] .offer-title a,
        html[data-theme='dark'] .deal-title a,
        html[data-theme='dark'] .feature-title,
        html[data-theme='dark'] .author-name,
        html[data-theme='dark'] .related-card-title,
        html[data-theme='dark'] .label-text,
        html[data-theme='dark'] .detail-label,
        html[data-theme='dark'] .award-content .award-title,
        html[data-theme='dark'] .award-title a,
        html[data-theme='dark'] .stat-item,
        html[data-theme='dark'] .cta-title,
        html[data-theme='dark'] .trust-item,
        html[data-theme='dark'] .trust-item-small,
        html[data-theme='dark'] .trust-feature,
        html[data-theme='dark'] .trust-feature span,
        html[data-theme='dark'] .offer-price-current,
        html[data-theme='dark'] .suggestion-item,
        html[data-theme='dark'] .empty-state h3,
        html[data-theme='dark'] .journey-title a,
        html[data-theme='dark'] .section-title,
        html[data-theme='dark'] .overview-card h2,
        html[data-theme='dark'] .results-head h3,
        html[data-theme='dark'] .form-title,
        html[data-theme='dark'] .sidebar-title,
        html[data-theme='dark'] .contact-title,
        html[data-theme='dark'] .choose-title,
        html[data-theme='dark'] .section-header,
        html[data-theme='dark'] .nav-link,
        html[data-theme='dark'] .mobile-nav-link,
        html[data-theme='dark'] .mobile-submenu-link,
        html[data-theme='dark'] .mobile-language-link,
        html[data-theme='dark'] .language-toggle,
        html[data-theme='dark'] .tour-country,
        html[data-theme='dark'] .journey-country {
            color: var(--charcoal-deep) !important;
        }

        html[data-theme='dark'] p,
        html[data-theme='dark'] span,
        html[data-theme='dark'] li,
        html[data-theme='dark'] .section-subtitle,
        html[data-theme='dark'] .tour-description,
        html[data-theme='dark'] .journey-description,
        html[data-theme='dark'] .offer-description,
        html[data-theme='dark'] .card-description,
        html[data-theme='dark'] .search-stats,
        html[data-theme='dark'] .overview-card p,
        html[data-theme='dark'] .results-head p,
        html[data-theme='dark'] .contact-info p,
        html[data-theme='dark'] .deal-meta,
        html[data-theme='dark'] .deal-description,
        html[data-theme='dark'] .feature-description,
        html[data-theme='dark'] .offer-price-label,
        html[data-theme='dark'] .offer-price-regular,
        html[data-theme='dark'] .suggestion-type,
        html[data-theme='dark'] .detail-value,
        html[data-theme='dark'] .award-subtitle,
        html[data-theme='dark'] .cta-subtitle,
        html[data-theme='dark'] .empty-state p,
        html[data-theme='dark'] .breadcrumb-list li,
        html[data-theme='dark'] .feature-item {
            color: var(--warm-gray) !important;
        }

        html[data-theme='dark'] .offer-image-wrap {
            background: #243246 !important;
        }

        html[data-theme='dark'] .feature-tag,
        html[data-theme='dark'] .result-type {
            background: rgba(244, 195, 106, 0.12) !important;
            color: #f7d488 !important;
        }

        html[data-theme='dark'] .dropdown-item:hover,
        html[data-theme='dark'] .suggestion-item:hover,
        html[data-theme='dark'] .suggestion-item.active,
        html[data-theme='dark'] .mobile-language-link.active,
        html[data-theme='dark'] .mobile-submenu-link:hover,
        html[data-theme='dark'] .mobile-nav-link:hover {
            background: rgba(244, 195, 106, 0.12) !important;
            color: var(--charcoal-deep) !important;
        }

        html[data-theme='dark'] .trust-item span,
        html[data-theme='dark'] .trust-item-small span {
            color: var(--charcoal-deep) !important;
        }

        html[data-theme='dark'] .cta-icon-container {
            background: linear-gradient(135deg, #f4c36a 0%, #7dd3fc 100%) !important;
            border-color: rgba(229, 237, 249, 0.7) !important;
            box-shadow: 0 10px 28px rgba(244, 195, 106, 0.22) !important;
        }

        html[data-theme='dark'] .cta-icon-container i,
        html[data-theme='dark'] .trust-feature i {
            color: #0f172a !important;
        }

        html[data-theme='dark'] .luxury-cta-btn {
            background: linear-gradient(135deg, #f4c36a 0%, #7dd3fc 100%) !important;
            color: #0f172a !important;
            box-shadow: 0 10px 28px rgba(244, 195, 106, 0.2) !important;
        }

        html[data-theme='dark'] .luxury-cta-btn:hover {
            color: #0f172a !important;
            box-shadow: 0 14px 34px rgba(244, 195, 106, 0.26) !important;
        }

        html[data-theme='dark'] .award-title a:hover,
        html[data-theme='dark'] .award-title a:focus,
        html[data-theme='dark'] .award-title a:active {
            color: #34e0a1 !important;
        }

        html[data-theme='dark'] .form-control,
        html[data-theme='dark'] .form-select,
        html[data-theme='dark'] input,
        html[data-theme='dark'] textarea,
        html[data-theme='dark'] select {
            background-color: var(--dark-surface-muted) !important;
            color: var(--charcoal-deep) !important;
            border-color: rgba(148, 163, 184, 0.24) !important;
        }

        html[data-theme='dark'] .form-control::placeholder,
        html[data-theme='dark'] input::placeholder,
        html[data-theme='dark'] textarea::placeholder {
            color: #94a3b8 !important;
        }

        html[data-theme='dark'] .action-btn,
        html[data-theme='dark'] .mobile-action-btn,
        html[data-theme='dark'] .mobile-action-card,
        html[data-theme='dark'] .language-toggle {
            background: rgba(255, 255, 255, 0.08) !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
            color: var(--charcoal-deep) !important;
        }

        .theme-toggle-btn {
            transition: background-color 0.25s ease, border-color 0.25s ease, color 0.25s ease, box-shadow 0.25s ease;
        }

        .theme-toggle-btn[aria-pressed='true'] {
            background: rgba(244, 195, 106, 0.18) !important;
            border-color: rgba(244, 195, 106, 0.35) !important;
            color: #f8fafc !important;
            box-shadow: var(--shadow-gold);
        }

        .cta-content-wrapper {
            width: 100%;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 30px;
        }

        .cta-text-content {
            min-width: 0;
        }

        .cta-actions {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            flex-shrink: 0;
            justify-self: end;
        }

        @media (max-width: 768px) {
            .cta-content-wrapper {
                grid-template-columns: 1fr;
                justify-items: center;
            }

            .cta-actions {
                justify-self: center;
            }
        }

        .theme-toggle-btn i {
            transition: transform 0.25s ease;
        }

        .theme-toggle-btn:hover i {
            transform: rotate(14deg) scale(1.08);
        }

        .theme-toggle-btn:focus-visible {
            outline: 2px solid rgba(244, 195, 106, 0.65);
            outline-offset: 2px;
        }

        /* Fix Destinations Dropdown */
        .navbar .dropdown {
            position: relative;
        }

        .navbar .dropdown-menu {
            display: block;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.25s ease;
            pointer-events: none;
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 9999;
            min-width: 220px;
            background: #fff;
            border: 1px solid rgba(197, 149, 91, 0.18);
            border-radius: 14px;
            padding: 10px;
            margin-top: 10px;
            box-shadow: 0 12px 35px rgba(28, 50, 92, 0.18);
        }

        .navbar .dropdown-menu.show,
        .navbar .dropdown:hover>.dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }

        .navbar .dropdown-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 10px;
            color: var(--primary-navy, #1c325c);
            font-size: 0.92rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.25s ease;
            white-space: nowrap;
        }

        .navbar .dropdown-item:hover {
            background: rgba(197, 149, 91, 0.14);
            color: var(--primary-navy, #1c325c);
            transform: translateX(4px);
        }

        .navbar .dropdown-item i {
            color: var(--rich-gold, #c5955b);
            font-size: 1rem;
            width: 18px;
        }

        .navbar .dropdown-toggle::after {
            display: none;
        }

        .navbar .dropdown-toggle i.la-angle-down,
        .navbar .dropdown-toggle i.la-chevron-down {
            margin-left: 5px;
            transition: transform 0.25s ease;
        }

        .navbar .dropdown.show .dropdown-toggle i.la-angle-down,
        .navbar .dropdown:hover .dropdown-toggle i.la-angle-down {
            transform: rotate(180deg);
        }

        @media (max-width: 991px) {
            .navbar .dropdown-menu {
                position: static;
                display: none;
                opacity: 1;
                visibility: visible;
                transform: none;
                pointer-events: auto;
                box-shadow: none;
                margin-top: 8px;
            }

            .navbar .dropdown-menu.show {
                display: block;
            }
        }
    </style>
    @if (app()->getLocale() === 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap"
            rel="stylesheet">
        <style>
            html[dir="rtl"] body {
                direction: rtl;
                text-align: right;
            }

            body,
            body div,
            body span,
            body p,
            body a,
            body li,
            body label,
            body small,
            body strong,
            body button,
            body input,
            body select,
            body textarea,
            body h1,
            body h2,
            body h3,
            body h4,
            body h5,
            body h6 {
                font-family: "Cairo", sans-serif !important;
            }

            html[dir="rtl"] .navbar .dropdown-menu {
                left: auto;
                right: 0;
                text-align: right;
            }

            html[dir="rtl"] .navbar .dropdown-item:hover {
                transform: translateX(-4px);
            }

            html[dir="rtl"] .navbar .dropdown-toggle i.la-angle-down,
            html[dir="rtl"] .navbar .dropdown-toggle i.la-chevron-down {
                margin-left: 0;
                margin-right: 5px;
            }

            html[dir="rtl"] .navbar .dropdown-item,
            html[dir="rtl"] .nav-link,
            html[dir="rtl"] .mobile-nav-link,
            html[dir="rtl"] .mobile-submenu-link,
            html[dir="rtl"] .mobile-action-card,
            html[dir="rtl"] .mobile-destinations-toggle,
            html[dir="rtl"] .mobile-language-toggle,
            html[dir="rtl"] .footer-section,
            html[dir="rtl"] .deal-title,
            html[dir="rtl"] .deal-description,
            html[dir="rtl"] .feature-title,
            html[dir="rtl"] .feature-description,
            html[dir="rtl"] .section-subtitle,
            html[dir="rtl"] .article-title,
            html[dir="rtl"] .article-excerpt,
            html[dir="rtl"] .testimonial-text,
            html[dir="rtl"] .sidebar-widget,
            html[dir="rtl"] .content-section {
                text-align: right;
            }

            html[dir="rtl"] .mobile-destinations-toggle > div,
            html[dir="rtl"] .mobile-language-toggle > div,
            html[dir="rtl"] .mobile-menu-brand,
            html[dir="rtl"] .footer-contact-list li,
            html[dir="rtl"] .deal-meta span,
            html[dir="rtl"] .trust-item,
            html[dir="rtl"] .quote-feature {
                flex-direction: row-reverse;
            }

            html[dir="rtl"] .hero-cta i,
            html[dir="rtl"] .trust-item i,
            html[dir="rtl"] .quote-feature i,
            html[dir="rtl"] .deal-meta i,
            html[dir="rtl"] .article-date i,
            html[dir="rtl"] .mobile-nav-link i,
            html[dir="rtl"] .mobile-submenu-link i,
            html[dir="rtl"] .mobile-action-card i,
            html[dir="rtl"] .footer-contact-list i {
                margin-right: 0 !important;
                margin-left: 10px !important;
            }

            html[dir="rtl"] .mobile-destinations-toggle > div i,
            html[dir="rtl"] .mobile-language-toggle > div i {
                margin-right: 0 !important;
                margin-left: 12px !important;
            }

            html[dir="rtl"] .cta-text-content,
            html[dir="rtl"] .cta-title,
            html[dir="rtl"] .cta-subtitle,
            html[dir="rtl"] .trust-features,
            html[dir="rtl"] .trust-feature {
                text-align: right;
            }

            html[dir="rtl"] .luxury-cta-content {
                text-align: right;
            }

            html[dir="rtl"] .cta-actions,
            html[dir="rtl"] .luxury-cta-btn {
                flex-direction: row-reverse;
            }

            html[dir="rtl"] .trust-features {
                justify-content: flex-start;
                direction: rtl;
            }

            html[dir="rtl"] .trust-feature {
                flex-direction: row-reverse;
            }

            @media (max-width: 768px) {
                html[dir="rtl"] .luxury-cta-content,
                html[dir="rtl"] .cta-content-wrapper {
                    text-align: center;
                }

                html[dir="rtl"] .cta-actions {
                    justify-content: center;
                }

                html[dir="rtl"] .trust-features {
                    justify-content: center;
                }
            }

            html[dir="rtl"] .badge-top {
                left: auto;
                right: 15px;
            }

            html[dir="rtl"] .deal-price {
                right: auto;
                left: 15px;
            }

            html[dir="rtl"] .author-section,
            html[dir="rtl"] .rating-stars,
            html[dir="rtl"] .newsletter-form,
            html[dir="rtl"] .tag-list,
            html[dir="rtl"] .footer-links ul,
            html[dir="rtl"] .social-links ul {
                direction: rtl;
            }
        </style>
    @endif
    <meta name="google-site-verification" content="OKwZFMPi1pE0RpnHtt6lJnyE_qPXCNqW8E7-U4BHPRw" />
</head>

<body class="{{ app()->getLocale() === 'ar' ? 'website-rtl' : 'website-ltr' }}">

    @include('website.layouts.header')

    @yield('content')

    <!-- Fixed WhatsApp Button -->
    <a href="https://wa.me/201553383000" target="_blank" class="whatsapp-fixed">
        <i class="lab la-whatsapp"></i>
    </a>

    <!-- Include Footer -->
    <!-- Why Travel With Us Section -->
    <section class="why-choose-section" style="background: var(--pearl-luxury); padding: 80px 0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading text-center mb-0">
                        <h2 class="section-header"
                            style="font-family: 'Playfair Display', serif; color: var(--primary-navy); font-size: clamp(1.5rem, 3vw, 2.2rem); margin-bottom: 20px;">
                            {{ __('Why travel with Etro Tours?') }}
                        </h2>
                        <p class="section-subtitle"
                            style="color: var(--warm-gray); font-size: 1.2rem; max-width: 700px; margin: 0 auto 60px; line-height: 1.6;">
                            {{ __('Your entire vacation is designed around your requirements with expert guidance every step of the way.') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="choose-card"
                        style="background: white; border-radius: 25px; padding: 40px 30px; text-align: center; box-shadow: var(--shadow-medium); border: 2px solid transparent; transition: all 0.4s ease; height: 100%; position: relative; overflow: hidden;"
                        onmouseover="if (!window.__cfRLUnblockHandlers) return false; this.style.borderColor='var(--rich-gold)'; this.style.transform='translateY(-8px)'; this.style.boxShadow='var(--shadow-dramatic)'"
                        onmouseout="if (!window.__cfRLUnblockHandlers) return false; this.style.borderColor='transparent'; this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-medium)'"
                        data-cf-modified-bbfb53b5999c6c3f61fbade4-="">
                        <div class="choose-icon"
                            style="width: 80px; height: 80px;  border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; font-size: 2.2rem; color: white; box-shadow: var(--shadow-gold); transition: all 0.3s ease;">
                            <i class="la la-cog"></i>
                        </div>
                        <h3 class="choose-title"
                            style="font-family: 'Playfair Display', serif; color: var(--primary-navy); font-size: 1.4rem; font-weight: 600; margin-bottom: 20px;">
                            {{ __('100% Tailor made') }}</h3>
                        <div class="choose-features">
                            <div class="feature-item"
                                style="padding: 12px 0; border-bottom: 1px solid rgba(197, 149, 91, 0.2); color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                {{ __('Your entire vacation is designed around your requirements') }}
                            </div>
                            <div class="feature-item"
                                style="padding: 12px 0; border-bottom: 1px solid rgba(197, 149, 91, 0.2); color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                {{ __('Explore your interests at your own speed') }}
                            </div>
                            <div class="feature-item"
                                style="padding: 12px 0; border-bottom: 1px solid rgba(197, 149, 91, 0.2); color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                {{ __('Select your preferred style of accommodations') }}
                            </div>
                            <div class="feature-item"
                                style="padding: 12px 0; color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                {{ __('Create the perfect trip with the help of our specialists') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="choose-card"
                        style="background: white; border-radius: 25px; padding: 40px 30px; text-align: center; box-shadow: var(--shadow-medium); border: 2px solid transparent; transition: all 0.4s ease; height: 100%; position: relative; overflow: hidden;"
                        onmouseover="if (!window.__cfRLUnblockHandlers) return false; this.style.borderColor='var(--rich-gold)'; this.style.transform='translateY(-8px)'; this.style.boxShadow='var(--shadow-dramatic)'"
                        onmouseout="if (!window.__cfRLUnblockHandlers) return false; this.style.borderColor='transparent'; this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-medium)'"
                        data-cf-modified-bbfb53b5999c6c3f61fbade4-="">
                        <div class="choose-icon"
                            style="width: 80px; height: 80px;  border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; font-size: 2.2rem; color: white; box-shadow: var(--shadow-gold); transition: all 0.3s ease;">
                            <i class="la la-lightbulb"></i>
                        </div>
                        <h3 class="choose-title"
                            style="font-family: 'Playfair Display', serif; color: var(--primary-navy); font-size: 1.4rem; font-weight: 600; margin-bottom: 20px;">
                            {{ __('Expert knowledge') }}</h3>
                        <div class="choose-features">
                            <div class="feature-item"
                                style="padding: 12px 0; border-bottom: 1px solid rgba(197, 149, 91, 0.2); color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                {{ __('All our specialists have traveled extensively or lived in their specialist regions, We\'re with you every step of the way') }}
                            </div>
                            <div class="feature-item"
                                style="padding: 12px 0; border-bottom: 1px solid rgba(197, 149, 91, 0.2); color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                {{ __('The same specialist will handle your trip from start to finish') }}
                            </div>
                            <div class="feature-item"
                                style="padding: 12px 0; color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                {{ __('Make the most of your time and budget') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="choose-card"
                        style="background: white; border-radius: 25px; padding: 40px 30px; text-align: center; box-shadow: var(--shadow-medium); border: 2px solid transparent; transition: all 0.4s ease; height: 100%; position: relative; overflow: hidden;"
                        onmouseover="if (!window.__cfRLUnblockHandlers) return false; this.style.borderColor='var(--rich-gold)'; this.style.transform='translateY(-8px)'; this.style.boxShadow='var(--shadow-dramatic)'"
                        onmouseout="if (!window.__cfRLUnblockHandlers) return false; this.style.borderColor='transparent'; this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-medium)'"
                        data-cf-modified-bbfb53b5999c6c3f61fbade4-="">
                        <div class="choose-icon"
                            style="width: 80px; height: 80px;  border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; font-size: 2.2rem; color: white; box-shadow: var(--shadow-gold); transition: all 0.3s ease;">
                            <i class="la la-user-graduate"></i>
                        </div>
                        <h3 class="choose-title"
                            style="font-family: 'Playfair Display', serif; color: var(--primary-navy); font-size: 1.4rem; font-weight: 600; margin-bottom: 20px;">
                            {{ __('The best guides') }}</h3>
                        <div class="choose-features">
                            <div class="feature-item"
                                style="padding: 12px 0; border-bottom: 1px solid rgba(197, 149, 91, 0.2); color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                {{ __('Make the difference between a good trip and an outstanding one') }}
                            </div>
                            <div class="feature-item"
                                style="padding: 12px 0; border-bottom: 1px solid rgba(197, 149, 91, 0.2); color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                {{ __('Our leaders will be there to ensure your safety and wellbeing is the number one priority') }}
                            </div>
                            <div class="feature-item"
                                style="padding: 12px 0; color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                {{ __('Offering more than just dates and names, they strive to offer real insight into their country') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="choose-card"
                        style="background: white; border-radius: 25px; padding: 40px 30px; text-align: center; box-shadow: var(--shadow-medium); border: 2px solid transparent; transition: all 0.4s ease; height: 100%; position: relative; overflow: hidden;"
                        onmouseover="if (!window.__cfRLUnblockHandlers) return false; this.style.borderColor='var(--rich-gold)'; this.style.transform='translateY(-8px)'; this.style.boxShadow='var(--shadow-dramatic)'"
                        onmouseout="if (!window.__cfRLUnblockHandlers) return false; this.style.borderColor='transparent'; this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-medium)'"
                        data-cf-modified-bbfb53b5999c6c3f61fbade4-="">
                        <div class="choose-icon"
                            style="width: 80px; height: 80px;  border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; font-size: 2.2rem; color: white; box-shadow: var(--shadow-gold); transition: all 0.3s ease;">
                            <i class="la la-shield-alt"></i>
                        </div>
                        <h3 class="choose-title"
                            style="font-family: 'Playfair Display', serif; color: var(--primary-navy); font-size: 1.4rem; font-weight: 600; margin-bottom: 20px;">
                            {{ __('Fully protected') }}</h3>
                        <div class="choose-features">
                            <div class="feature-item"
                                style="padding: 12px 0; border-bottom: 1px solid rgba(197, 149, 91, 0.2); color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                {{ __('Secure Payment - Use your debit card or credit card. Your transactions are protected by 3D Secure and SecureCode.') }}
                            </div>
                            <div class="feature-item" style="padding: 12px 0; text-align: center;">
                                <img loading="lazy" src="{{ asset('website/flags/cybersource.png') }}"
                                    height="100" width="150" alt="{{ __('Cybersource Security') }}" style="opacity: 0.8;">
                                <img loading="lazy" src="{{ asset('website/flags/mpgs.webp') }}" height="100"
                                    width="150" alt="{{ __('Cybersource Security') }}" style="opacity: 0.8;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Minimal Enhanced Luxury CTA Section -->
    <section class="luxury-cta-section">
        <div class="container">
            <div class="luxury-cta-content">
                <div class="cta-content-wrapper">
                    <div class="cta-text-content">
                        <h2 class="cta-title">{{ __('Ready to Plan Your Dream Cruise?') }}</h2>
                        <p class="cta-subtitle">{{ __('Speak with our Egypt specialists for your perfect luxury journey.') }}</p>

                        <div class="trust-features">
                            <div class="trust-feature">
                                <i class="la la-shield-alt"></i>
                                <span>{{ __('Free Consultation') }}</span>
                            </div>
                            <div class="trust-feature">
                                <i class="la la-clock"></i>
                                <span>{{ __('24/7 Support') }}</span>
                            </div>
                            <div class="trust-feature">
                                <i class="la la-award"></i>
                                <span>{{ __('Best Price Guarantee') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="cta-actions">
                        <div class="cta-icon-container">
                            <i class="la la-phone"></i>
                        </div>

                        <a href="{{ route('website.contact.index') }}" class="luxury-cta-btn">
                            <i class="la la-calendar-check"></i>
                            {{ __('Start Planning') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('website.layouts.footer')


    @yield('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeStorageKey = 'website-theme';
            const themeColorMeta = document.querySelector('[data-theme-color-meta]');

            function applyTheme(theme) {
                document.documentElement.setAttribute('data-theme', theme);
                document.documentElement.style.colorScheme = theme;
                if (themeColorMeta) {
                    themeColorMeta.setAttribute('content', theme === 'dark' ? '#0b1220' : '#f7fafc');
                }
                updateThemeButtons(theme);
            }

            function updateThemeButtons(theme) {
                document.querySelectorAll('[data-theme-toggle]').forEach(function(button) {
                    const icon = button.querySelector('i');
                    const isDark = theme === 'dark';
                    const nextLabel = isDark ? button.dataset.lightLabel : button.dataset.darkLabel;

                    button.setAttribute('aria-label', nextLabel);
                    button.setAttribute('title', nextLabel);
                    button.setAttribute('aria-pressed', isDark ? 'true' : 'false');

                    if (icon) {
                        icon.className = 'la ' + (isDark ? 'la-sun' : 'la-moon');
                    }
                });
            }

            function toggleTheme() {
                const currentTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
                const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';

                try {
                    localStorage.setItem(themeStorageKey, nextTheme);
                } catch (e) {}

                applyTheme(nextTheme);
            }

            document.querySelectorAll('[data-theme-toggle]').forEach(function(button) {
                button.addEventListener('click', function() {
                    toggleTheme();
                });
            });

            const savedTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
            updateThemeButtons(savedTheme);

            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            if (typeof mediaQuery.addEventListener === 'function') {
                mediaQuery.addEventListener('change', function(event) {
                    try {
                        if (localStorage.getItem(themeStorageKey)) {
                            return;
                        }
                    } catch (e) {}

                    applyTheme(event.matches ? 'dark' : 'light');
                });
            }

            /*
            |--------------------------------------------------------------------------
            | Bootstrap Dropdown Fix
            |--------------------------------------------------------------------------
            | This fixes the Destinations dropdown in case Bootstrap dropdown JS
            | is not initializing correctly or CSS is hiding the menu.
            */

            document.querySelectorAll('.navbar .dropdown-toggle').forEach(function(toggle) {
                toggle.addEventListener('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();

                    const dropdown = this.closest('.dropdown');
                    const menu = dropdown ? dropdown.querySelector('.dropdown-menu') : null;

                    if (!dropdown || !menu) {
                        return;
                    }

                    document.querySelectorAll('.navbar .dropdown').forEach(function(item) {
                        if (item !== dropdown) {
                            item.classList.remove('show');

                            const otherMenu = item.querySelector('.dropdown-menu');
                            const otherToggle = item.querySelector('.dropdown-toggle');

                            if (otherMenu) {
                                otherMenu.classList.remove('show');
                            }

                            if (otherToggle) {
                                otherToggle.setAttribute('aria-expanded', 'false');
                            }
                        }
                    });

                    dropdown.classList.toggle('show');
                    menu.classList.toggle('show');

                    const isOpen = menu.classList.contains('show');
                    this.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
            });

            document.addEventListener('click', function(event) {
                if (event.target.closest('.navbar .dropdown')) {
                    return;
                }

                document.querySelectorAll('.navbar .dropdown').forEach(function(dropdown) {
                    dropdown.classList.remove('show');

                    const menu = dropdown.querySelector('.dropdown-menu');
                    const toggle = dropdown.querySelector('.dropdown-toggle');

                    if (menu) {
                        menu.classList.remove('show');
                    }

                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'false');
                    }
                });
            });
        });
    </script>
</body>

</html>
