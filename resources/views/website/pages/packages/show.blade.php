@extends('website.layouts.master')

@section('title', $package->getTranslation('seo_title') ?: $title . ' - Etro Tours')
@section('description', $package->getTranslation('seo_description') ?: $shortDescription)
@section('keywords',
    trim(
    collect([
    $title,
    $tourTypeText ?? null,
    $package->primaryCountry?->display_name ?? null,
    'Etro
    Tours',
    ])->filter()->implode(', '),
    ', ',
    ))
@section('image', $heroImage)
@section('canonical', $canonicalUrl)

@section('css')
    <style>
        .package-hero {
            min-height: clamp(560px, 72vh, 760px);
            background: linear-gradient(rgba(28, 50, 92, .38), rgba(26, 75, 102, .48)), var(--hero-bg);
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
            padding: clamp(120px, 14vh, 150px) 0 clamp(45px, 7vh, 70px)
        }

        .package-hero:after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .18);
            pointer-events: none
        }

        .package-hero .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: #fff;
            max-width: 1000px;
            margin: auto;
            padding: 0 20px
        }

        .package-hero .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.85rem, 4.2vw, 3.6rem);
            font-weight: 700;
            line-height: 1.12;
            margin-bottom: 14px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, .35)
        }

        .package-hero .hero-subtitle {
            font-size: clamp(.9rem, 1.35vw, 1.05rem);
            opacity: .95;
            max-width: 780px;
            margin: 0 auto 20px;
            line-height: 1.55
        }

        .package-hero .hero-actions {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
            position: relative;
            z-index: 3
        }

        .package-hero .hero-badges {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 14px
        }

        .package-hero .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 11px;
            border: 1px solid rgba(255, 255, 255, .36);
            border-radius: 999px;
            background: rgba(11, 18, 32, .36);
            color: #fff;
            font-size: .78rem;
            font-weight: 700;
            backdrop-filter: blur(7px)
        }

        .gold-btn,
        .outline-btn,
        .submit-btn {
            background: var(--gradient-gold, #c5955b);
            color: var(--primary-navy, #1c325c);
            padding: 11px 22px;
            border-radius: 50px;
            text-decoration: none;
            font-size: .9rem;
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
            background: var(--pearl-luxury, #faf8f3);
            border: 0;
            width: 100%;
            color: inherit;
            font: inherit;
            text-align: start
        }

        .day-header:focus-visible {
            outline: 3px solid rgba(197, 149, 91, .45);
            outline-offset: -3px
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
            display: block;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transition: max-height .4s ease, opacity .25s ease, visibility .25s ease
        }

        .collapsible-content.open,
        .collapsible-content.active {
            max-height: 3200px;
            opacity: 1;
            visibility: visible
        }

        .day-header[aria-expanded='true'] .collapse-icon {
            transform: rotate(180deg)
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

        .price-table-wrap {
            overflow-x: auto;
            border-radius: 14px
        }

        .price-table {
            min-width: 660px
        }

        .price-meta {
            display: block;
            color: #777;
            font-size: .82rem;
            font-weight: 500;
            margin-top: 4px
        }

        .compare-price {
            display: block;
            margin-top: 6px;
            color: rgba(255, 255, 255, .72);
            font-size: .95rem;
            text-decoration: line-through
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

        .pricing-showcase {
            padding: clamp(32px, 4vw, 48px);
            overflow: hidden;
            background:
                radial-gradient(circle at 50% 0, rgba(255, 255, 255, .98), rgba(255, 253, 248, .94) 74%),
                #fffdf9;
            border: 1px solid rgba(28, 50, 92, .11);
            border-radius: 28px;
            box-shadow: 0 12px 38px rgba(28, 50, 92, .08)
        }

        .pricing-showcase .section-header {
            margin-bottom: 20px;
            text-align: center;
            font-size: clamp(2rem, 4.2vw, 3.25rem);
            line-height: 1.15
        }

        .pricing-showcase .section-header:after {
            width: 90px;
            height: 4px;
            margin: 16px auto 0;
            background: #2897ee
        }

        .pricing-showcase .section-subtitle {
            margin: 0 0 27px;
            color: #476181;
            font-size: clamp(1rem, 2vw, 1.25rem);
            line-height: 1.5;
            text-align: center
        }

        .pricing-cards {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: clamp(14px, 2.2vw, 24px)
        }

        .pricing-card {
            position: relative;
            min-width: 0;
            min-height: 280px;
            padding: 28px 18px 32px;
            overflow: hidden;
            text-align: center;
            background: #ffffff;
            border: 1px solid rgba(28, 50, 92, .12);
            border-radius: 22px;
            box-shadow: 0 9px 22px rgba(28, 50, 92, .08);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        .pricing-card:after {
            content: '';
            position: absolute;
            z-index: 1;
            right: -12%;
            bottom: -50px;
            left: -12%;
            height: 90px;
            background: linear-gradient(135deg, rgba(243, 248, 254, 0.6), rgba(237, 244, 252, 0.6));
            border-radius: 48% 55% 0 0 / 34% 52% 0 0;
            transform: rotate(-3deg);
            pointer-events: none;
        }

        .pricing-card>* {
            position: relative;
            z-index: 3;
        }

        .pricing-card-icon,
        .pricing-info-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0966bd;
            background: linear-gradient(145deg, #f1f7fd, #e9f2fb);
            border-radius: 50%
        }

        .pricing-card-icon {
            width: 78px;
            height: 78px;
            margin: 0 auto 18px
        }

        .pricing-card-icon svg {
            width: 50px;
            height: 50px
        }

        .pricing-card-title {
            margin: 0 0 13px;
            color: #062c56;
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.65rem, 3vw, 2.15rem);
            font-weight: 700;
            line-height: 1.1
        }

        .pricing-card-age {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            margin: 0;
            padding: 7px 16px;
            color: #064e9d;
            font-size: .94rem;
            line-height: 1.2;
            background: linear-gradient(110deg, #f1f7fd, #eaf3fb);
            border-radius: 999px
        }

        .pricing-card-divider {
            display: block;
            width: 58%;
            height: 2px;
            margin: 21px auto 18px;
            background: #dbe5ef;
            border-radius: 2px
        }

        .pricing-card-price {
            margin: auto 0 0;
            color: #0870cf;
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.7rem, 3.1vw, 2.2rem);
            font-weight: 700;
            line-height: 1.1;
            white-space: nowrap;
            position: relative;
            z-index: 5;
        }

        .pricing-options {
            margin-top: 25px
        }

        /* Dynamic Pricing Calculator & Tier Cards */
        .price-calculator-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(197, 149, 91, 0.25);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .counter-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            border: 1px solid rgba(197, 149, 91, 0.4);
            background: #ffffff;
            color: #1c325c;
            font-size: 1.25rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .counter-btn:hover:not(:disabled) {
            background: #c5955b;
            color: #ffffff;
            border-color: #c5955b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(197, 149, 91, 0.3);
        }

        .counter-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .counter-value {
            font-size: 1.45rem;
            font-weight: 800;
            color: #1c325c;
            min-width: 44px;
            text-align: center;
        }

        .pax-tier-card {
            background: #ffffff;
            border: 2px solid rgba(28, 50, 92, 0.1);
            border-radius: 18px;
            padding: 18px 14px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .pax-tier-card:hover {
            border-color: #c5955b;
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(197, 149, 91, 0.15);
        }

        .pax-tier-card.active {
            border-color: #c5955b;
            background: linear-gradient(145deg, rgba(197, 149, 91, 0.1), rgba(197, 149, 91, 0.02));
            box-shadow: 0 8px 24px rgba(197, 149, 91, 0.22);
        }

        .pax-tier-card.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #c5955b;
        }

        .pax-tier-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1c325c;
            margin-bottom: 6px;
        }

        .pax-tier-price {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0870cf;
            margin-bottom: 4px;
            font-family: 'Playfair Display', serif;
        }

        .pax-tier-sub {
            font-size: 0.82rem;
            color: #64748b;
            font-weight: 500;
        }

        .pricing-information {
            display: flex;
            align-items: center;
            gap: 25px;
            margin-top: 34px;
            padding-top: 27px;
            border-top: 1px solid #dce4eb
        }

        .pricing-info-icon {
            width: 72px;
            height: 72px;
            min-width: 72px
        }

        .pricing-info-icon svg {
            width: 47px;
            height: 47px
        }

        .pricing-info-content {
            min-width: 0
        }

        .pricing-info-title {
            margin: 0 0 7px;
            color: #1689e6;
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.35rem, 2.6vw, 1.75rem);
            font-weight: 700
        }

        .pricing-info-text,
        .pricing-info-text>*:last-child {
            margin-bottom: 0
        }

        .pricing-info-text {
            color: #293648;
            line-height: 1.72
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
                min-height: auto;
                padding: 105px 0 46px;
                background-attachment: scroll
            }

            .package-hero .hero-title {
                font-size: clamp(1.75rem, 6vw, 3rem)
            }

            .package-hero .hero-subtitle {
                max-width: 680px
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
                min-height: auto;
                padding: 92px 0 38px
            }

            .package-hero .hero-content {
                padding: 0 12px
            }

            .package-hero .hero-title {
                font-size: clamp(1.55rem, 7.5vw, 2.15rem);
                line-height: 1.15;
                margin-bottom: 12px
            }

            .package-hero .hero-subtitle {
                font-size: .88rem;
                line-height: 1.45;
                margin-bottom: 18px
            }

            .package-hero .hero-badges {
                gap: 6px;
                margin-bottom: 12px
            }

            .package-hero .hero-badge {
                padding: 5px 9px;
                font-size: .7rem
            }

            .package-hero .hero-actions {
                gap: 10px
            }

            .package-hero .hero-actions .gold-btn,
            .package-hero .hero-actions .outline-btn {
                min-height: 42px;
                padding: 10px 16px;
                font-size: .82rem
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

            .pricing-showcase {
                padding: 28px 18px
            }

            .pricing-cards {
                grid-template-columns: 1fr
            }

            .pricing-card {
                min-height: 290px
            }

            .pricing-information {
                align-items: flex-start;
                gap: 15px
            }

            .pricing-info-icon {
                width: 54px;
                height: 54px;
                min-width: 54px
            }

            .pricing-info-icon svg {
                width: 35px;
                height: 35px
            }
        }

        @media(min-width:576px) and (max-width:767px) {
            .pricing-cards {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media(max-width:420px) {
            .package-hero .hero-actions {
                flex-direction: column;
                align-items: center
            }

            .package-hero .hero-actions .gold-btn,
            .package-hero .hero-actions .outline-btn {
                width: min(100%, 240px)
            }
        }

        @media(max-height:650px) {
            .package-hero {
                min-height: auto;
                padding: 52px 0 24px
            }

            .package-hero .hero-title {
                font-size: clamp(1.55rem, 4vw, 2.5rem);
                margin-bottom: 10px
            }

            .package-hero .hero-subtitle {
                font-size: .84rem;
                line-height: 1.4;
                margin-bottom: 14px
            }

            .package-hero .hero-badges {
                margin-bottom: 10px
            }
        }

        /* Attractions Highlight Section & Cards */
        .attractions-highlight-section {
            background: #faf7f2;
            border: 1px solid rgba(197, 149, 91, 0.22);
            border-radius: 28px;
            padding: 36px 32px;
            box-shadow: 0 10px 30px rgba(28, 50, 92, 0.04);
        }

        .attractions-highlight-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.6rem, 3.5vw, 2.2rem);
            font-weight: 700;
            color: #1c325c;
            text-align: center;
            margin-bottom: 0;
        }

        .attractions-highlight-divider {
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #c5955b 0%, #b8860b 100%);
            border-radius: 4px;
            margin: 10px auto 28px auto;
        }

        .attractions-highlight-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .attraction-highlight-card {
            display: flex;
            align-items: center;
            gap: 18px;
            background: #ffffff;
            border: 1.5px solid rgba(28, 50, 92, 0.08);
            border-radius: 20px;
            padding: 16px 22px;
            text-decoration: none !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
            position: relative;
        }

        .attraction-highlight-card:hover,
        .attraction-highlight-card:focus {
            border-color: #c5955b;
            box-shadow: 0 8px 25px rgba(197, 149, 91, 0.22);
            transform: translateY(-2px);
        }

        .attraction-highlight-img {
            width: 64px;
            height: 64px;
            min-width: 64px;
            border-radius: 14px;
            object-fit: cover;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .attraction-highlight-content {
            flex: 1;
            min-width: 0;
        }

        .attraction-highlight-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.12rem;
            font-weight: 700;
            color: #1c325c;
            margin-bottom: 4px;
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .attraction-highlight-card:hover .attraction-highlight-name {
            color: #c5955b;
        }

        .attraction-highlight-sub {
            font-size: 0.88rem;
            font-style: italic;
            color: #718096;
            margin-bottom: 0;
            line-height: 1.4;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .attraction-highlight-arrow {
            color: #c5955b;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease;
            margin-inline-start: auto;
        }

        .attraction-highlight-card:hover .attraction-highlight-arrow {
            transform: translateX(4px);
        }

        html[dir="rtl"] .attraction-highlight-card:hover .attraction-highlight-arrow,
        body.rtl .attraction-highlight-card:hover .attraction-highlight-arrow {
            transform: translateX(-4px);
        }

        html[data-theme='dark'] .attractions-highlight-section {
            background: #111827;
            border-color: rgba(197, 149, 91, 0.3);
        }

        html[data-theme='dark'] .attractions-highlight-title {
            color: #ffffff;
        }

        html[data-theme='dark'] .attraction-highlight-card {
            background: #1a233a;
            border-color: rgba(255, 255, 255, 0.08);
        }

        html[data-theme='dark'] .attraction-highlight-name {
            color: #ffffff;
        }

        html[data-theme='dark'] .attraction-highlight-sub {
            color: #a0aec0;
        }

        html[data-theme='dark'] .pricing-showcase {
            background: #111827;
            border-color: rgba(197, 149, 91, 0.3);
        }

        html[data-theme='dark'] .pricing-showcase .section-header {
            color: #ffffff;
        }

        html[data-theme='dark'] .pricing-card {
            background: #1a233a;
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 9px 22px rgba(0, 0, 0, 0.3);
        }

        html[data-theme='dark'] .pricing-card:after {
            background: linear-gradient(135deg, #151d30, #101625);
        }

        html[data-theme='dark'] .pricing-card-icon {
            background: rgba(197, 149, 91, 0.18);
            color: #c5955b;
        }

        html[data-theme='dark'] .pricing-card-title {
            color: #ffffff;
        }

        html[data-theme='dark'] .pricing-card-age {
            color: #38bdf8;
            background: rgba(56, 189, 248, 0.15);
        }

        html[data-theme='dark'] .pricing-card-price {
            color: #c5955b;
        }

        html[data-theme='dark'] .pricing-info-title {
            color: #c5955b;
        }

        html[data-theme='dark'] .pricing-info-text {
            color: #e2e8f0;
        }

        html[data-theme='dark'] .price-table {
            color: #e2e8f0;
        }

        html[data-theme='dark'] .price-table th {
            background: #151d30;
            color: #c5955b;
            border-color: rgba(255, 255, 255, 0.1);
        }

        html[data-theme='dark'] .price-table td {
            border-color: rgba(255, 255, 255, 0.08);
            color: #e2e8f0;
        }

        .free-price-badge {
            display: inline-block;
            padding: 6px 22px;
            border-radius: 20px;
            font-size: 1.1rem;
            font-weight: 800;
            font-family: inherit;
            background-color: #10b981 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
            line-height: 1.2;
            letter-spacing: 0.5px;
        }

        html[data-theme='dark'] .price-badge-item {
            background: rgba(255, 255, 255, 0.08) !important;
            color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
        }

        html[data-theme='dark'] .price-calculator-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-color: rgba(197, 149, 91, 0.35);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        html[data-theme='dark'] .counter-btn {
            background: #1e293b;
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.2);
        }

        html[data-theme='dark'] .counter-btn:hover:not(:disabled) {
            background: #c5955b;
            color: #ffffff;
            border-color: #c5955b;
        }

        html[data-theme='dark'] .counter-value {
            color: #ffffff;
        }

        html[data-theme='dark'] .pax-tier-card {
            background: #1a233a;
            border-color: rgba(255, 255, 255, 0.12);
        }

        html[data-theme='dark'] .pax-tier-card.active {
            background: linear-gradient(145deg, rgba(197, 149, 91, 0.22), rgba(15, 23, 42, 0.85));
            border-color: #c5955b;
            box-shadow: 0 8px 24px rgba(197, 149, 91, 0.3);
        }

        html[data-theme='dark'] .pax-tier-title {
            color: #ffffff;
        }

        html[data-theme='dark'] .pax-tier-price {
            color: #c5955b;
        }

        html[data-theme='dark'] .pax-tier-sub {
            color: #94a3b8;
        }

        html[data-theme='dark'] .price-table tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        @media (max-width: 576px) {
            .attractions-highlight-section {
                padding: 24px 16px;
                border-radius: 20px;
            }

            .attraction-highlight-card {
                padding: 14px 16px;
                gap: 14px;
            }

            .attraction-highlight-img {
                width: 54px;
                height: 54px;
                min-width: 54px;
                border-radius: 12px;
            }

            .attraction-highlight-name {
                font-size: 1rem;
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
            'url' => $canonicalUrl,
            'provider' => [
                '@type' => 'TravelAgency',
                'name' => 'Etro Tours',
                'url' => url('/'),
            ],
            'touristType' => $tourTypeText ?? __('Private'),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    @if ($faqs->count())
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $faqs->map(fn ($faq, $index) => [
                    '@type' => 'Question',
                    'name' => $faq['question'] ?: __('Question') . ' ' . ($index + 1),
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => strip_tags($faq['answer']),
                    ],
                ])->all(),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
        </script>
    @endif
@endsection

@section('content')
    @php
        $currencySymbol = $package->currency?->symbol ?? '$';
        $priceFrom = (float) ($package->price_from ?? ($package->start_from_price ?? 0));
        $priceTo = (float) ($package->price_to ?? 0);
        $comparePrice = (float) ($package->compare_price ?? 0);
        $offerPrice = (float) ($package->offer_price ?? 0);
        $priceText = null;
        $hasCategoryPricing =
            ($package->adult_price !== null && (float) $package->adult_price > 0) ||
            $package->child_price !== null ||
            $package->infant_price !== null;
        $pricingInformation = $package->getTranslation('pricing_information');

        if ($priceFrom > 0 || $priceTo > 0) {
            $effectivePrice = $priceFrom > 0 ? $priceFrom : $priceTo;
            $priceText = __('trips.from_price', [
                'currency' => $currencySymbol,
                'amount' => number_format($effectivePrice, 2),
            ]);
        }
    @endphp

    <section class="breadcrumb-top-bar">
        <div class="container">
            <div class="breadcrumb-list">
                <ul>
                    <li><a href="{{ route('website.home') }}">{{ __('Home') }}</a></li>
                    <li><a href="{{ $listingUrl }}">{{ $listingLabel }}</a></li>
                    <li>{{ $breadcrumbTitle }}</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="package-hero" style="--hero-bg:url('{{ $heroImage }}')">
        <div class="container">
            <div class="hero-content">
                <div class="hero-badges" aria-label="{{ __('Trip badges') }}">
                    <span class="hero-badge"><i class="la la-compass"></i> {{ $packageTypeText }}</span>
                    @if ($package->is_best_seller)
                        <span class="hero-badge"><i class="la la-fire"></i> {{ __('Best Seller') }}</span>
                    @endif
                    @if ($package->is_ultra_luxury)
                        <span class="hero-badge"><i class="la la-gem"></i> {{ __('Ultra Luxury') }}</span>
                    @endif
                    @if ($package->is_featured)
                        <span class="hero-badge"><i class="la la-star"></i> {{ __('Featured') }}</span>
                    @endif
                </div>
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
                    <a href="#reserve" class="gold-btn d-none d-lg-inline-flex">
                        <i class="la la-envelope"></i> {{ __('Enquire Now') }}
                    </a>
                    <a href="#" class="gold-btn d-inline-flex d-lg-none" data-bs-toggle="modal"
                        data-bs-target="#simpleEnquiryModal">
                        <i class="la la-envelope"></i> {{ __('Enquire Now') }}
                    </a>
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
                                {{-- @if ($packageTypeText)
                                    <div class="detail-item"><i class="la la-suitcase"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Package Type:') }}</strong>
                                            <span class="detail-value">{{ $packageTypeText }}</span>
                                        </div>
                                    </div>
                                @endif --}}
                                {{-- @if ($countryText)
                                    <div class="detail-item"><i class="la la-globe"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Country:') }}</strong>
                                            <span class="detail-value">{{ $countryText }}</span>
                                        </div>
                                    </div>
                                @endif --}}
                                @if ($destinations)
                                    <div class="detail-item"><i class="la la-map-marker"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Destinations:') }}</strong>
                                            <span class="detail-value">{{ $destinations }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if ($routeText)
                                    <div class="detail-item"><i class="la la-route"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Route:') }}</strong>
                                            <span class="detail-value">{{ $routeText }}</span>
                                        </div>
                                    </div>
                                @endif
                                {{-- @if ($locationSummary)
                                    <div class="detail-item"><i class="la la-map"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Location:') }}</strong>
                                            <span class="detail-value">{{ $locationSummary }}</span>
                                        </div>
                                    </div>
                                @endif --}}
                                @if ($pickup)
                                    <div class="detail-item"><i class="la la-map-pin"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Pickup Location:') }}</strong>
                                            <span class="detail-value">{{ $pickup }}</span>
                                        </div>
                                    </div>
                                @endif
                                {{-- @if ($dropoff)
                                    <div class="detail-item"><i class="la la-location-arrow"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Dropoff Location:') }}</strong>
                                            <span class="detail-value">{{ $dropoff }}</span>
                                        </div>
                                    </div>
                                @endif --}}
                                @if ($tourTypeText)
                                    <div class="detail-item"><i class="la la-users"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Tour Type:') }}</strong>
                                            <span class="detail-value">{{ $tourTypeText }}</span>
                                        </div>
                                    </div>
                                @endif
                                {{-- @if ($package->category)
                                    <div class="detail-item"><i class="la la-tag"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Category:') }}</strong>
                                            <span class="detail-value">{{ $package->category->display_name }}</span>
                                        </div>
                                    </div>
                                @endif --}}
                                {{-- @if ($package->difficulty_level)
                                    <div class="detail-item"><i class="la la-hiking"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Difficulty:') }}</strong>
                                            <span class="detail-value">{{ __(ucfirst($package->difficulty_level)) }}</span>
                                        </div>
                                    </div>
                                @endif --}}
                                {{-- @if ($package->min_participants || $package->max_participants)
                                    <div class="detail-item"><i class="la la-user-friends"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Group Size:') }}</strong>
                                            <span class="detail-value">
                                                @if ($package->min_participants && $package->max_participants)
                                                    {{ $package->min_participants }} - {{ $package->max_participants }}
                                                    {{ __('Pax') }}
                                                @elseif($package->max_participants)
                                                    {{ __('Up to') }} {{ $package->max_participants }}
                                                    {{ __('Pax') }}
                                                @else
                                                    {{ __('Min') }} {{ $package->min_participants }}
                                                    {{ __('Pax') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @endif --}}
                                {{-- @if ($package->booking_lead_days)
                                    <div class="detail-item"><i class="la la-hourglass-half"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Booking Window:') }}</strong>
                                            <span
                                                class="detail-value">{{ __('Min. :days days before', ['days' => $package->booking_lead_days]) }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if ($bookingModeText)
                                    <div class="detail-item"><i class="la la-calendar-check"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Booking Mode:') }}</strong>
                                            <span class="detail-value">{{ $bookingModeText }}</span>
                                        </div>
                                    </div>
                                @endif --}}
                                {{-- @if ((float) $package->rating_avg > 0 || (int) $package->reviews_count > 0)
                                    <div class="detail-item"><i class="la la-star"></i>
                                        <div class="detail-text">
                                            <strong class="detail-label">{{ __('Rating:') }}</strong>
                                            <span class="detail-value">
                                                {{ number_format((float) $package->rating_avg, 1) }}/5
                                                @if ((int) $package->reviews_count > 0)
                                                    ({{ trans_choice(':count review|:count reviews', (int) $package->reviews_count, ['count' => (int) $package->reviews_count]) }})
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @endif --}}
                            </div>
                        </div>
                    </section>

                    @if ($highlights->count())
                        <section class="content-section">
                            <h2 class="section-header">{{ __('Tour Highlights') }}</h2>
                            <div class="styled-list">
                                <ul
                                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 10px;">
                                    @foreach ($highlights as $highlight)
                                        <li style="border: none; padding: 5px 0;">
                                            <i class="la la-check-circle"
                                                style="color:var(--rich-gold, #c5955b); margin-right:8px; font-size: 1.2rem; vertical-align: middle;"></i>
                                            @if ($highlight->display_title)
                                                <strong>{{ $highlight->display_title }}</strong>
                                            @endif
                                            @if ($highlight->display_description)
                                                <span class="price-meta">{{ $highlight->display_description }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </section>
                    @endif

                    @if ($package->packageAttractions && $package->packageAttractions->count())
                        <section class="content-section attractions-highlight-section">
                            <h2 class="attractions-highlight-title">{{ __('Places You\'ll Visit') }}</h2>
                            <div class="attractions-highlight-divider"></div>
                            <div class="attractions-highlight-list">
                                @foreach ($package->packageAttractions as $attraction)
                                    @php
                                        $attractionModel = $attraction->attraction;
                                        $attractionTitle =
                                            $attraction->display_title ?: $attractionModel?->display_name;
                                        $attractionTeaser =
                                            $attraction->getTranslation('teaser') ?:
                                            $attractionModel?->display_short_description;

                                        $citySlug = $attractionModel?->city?->slug
                                            ?: $package->destination?->city?->slug
                                            ?: $package->destination?->slug;

                                        if ($attractionModel && $attractionModel->slug) {
                                            $attractionUrl = route('website.attractions.show', $attractionModel->slug);
                                            $target = '_self';
                                        } elseif ($attractionModel?->map_url) {
                                            $attractionUrl = $attractionModel->map_url;
                                            $target = '_blank';
                                        } elseif ($citySlug) {
                                            $attractionUrl = route('website.destinations.show', $citySlug);
                                            $target = '_self';
                                        } else {
                                            $attractionUrl = route('website.destinations.index');
                                            $target = '_self';
                                        }

                                        if ($attraction->image) {
                                            $imgSrc = asset('storage/' . ltrim($attraction->image, '/'));
                                        } elseif ($attractionModel && $attractionModel->image) {
                                            $imgSrc = asset('storage/' . ltrim($attractionModel->image, '/'));
                                        } else {
                                            $imgSrc = asset('website/photos/home2.webp');
                                        }
                                    @endphp
                                    <a href="{{ $attractionUrl }}" target="{{ $target }}" class="attraction-highlight-card" @if($target === '_blank') rel="noopener noreferrer" @endif>
                                        <img src="{{ $imgSrc }}"
                                            alt="{{ $attractionTitle }}"
                                            class="attraction-highlight-img"
                                            loading="lazy">
                                        <div class="attraction-highlight-content">
                                            <h3 class="attraction-highlight-name">{{ $attractionTitle }}</h3>
                                            <p class="attraction-highlight-sub">{{ __('Click to explore') }}</p>
                                        </div>
                                        <div class="attraction-highlight-arrow" aria-hidden="true">
                                            <i class="la {{ app()->getLocale() === 'ar' ? 'la-angle-left' : 'la-angle-right' }}"></i>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if ($videoEmbedUrl)
                        <section class="content-section">
                            <h2 class="section-header">{{ __('Video Tour') }}</h2>
                            <div
                                style="border-radius:18px; overflow:hidden; position:relative; padding-bottom:56.25%; height:0; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                                <iframe src="{{ $videoEmbedUrl }}" title="{{ __('Video Tour') }}: {{ $title }}"
                                    style="position:absolute; top:0; left:0; width:100%; height:100%; border:0;"
                                    loading="lazy" referrerpolicy="strict-origin-when-cross-origin"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen></iframe>
                            </div>
                        </section>
                    @endif

                    @if ($itineraries->count())
                        <section id="itinerary" class="content-section">
                            <h2 class="section-header">{{ $title }} {{ __('Itinerary') }}</h2>
                            <div class="itinerary-section">
                                @foreach ($itineraries as $day)
                                    <div class="day-card">
                                        <button type="button" class="day-header"
                                            data-collapse-target="day-{{ $day->id }}"
                                            aria-controls="day-{{ $day->id }}"
                                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                            <div class="day-number" style="color: white !important;">
                                                {{ $day->day_number }}</div>
                                            <div>
                                                <h3 class="day-title">
                                                    {{ $itineraryUnit }} {{ $day->day_number }}@if ($day->display_title)
                                                        : {{ $day->display_title }}
                                                    @endif
                                                </h3>
                                                @if ($day->duration)
                                                    <small>
                                                        <i class="la la-clock"></i>
                                                        {{ $day->duration }}
                                                    </small>
                                                @endif
                                            </div>
                                            <i class="la la-chevron-down collapse-icon" style="margin-left:auto"></i>
                                        </button>
                                        <div class="collapsible-content {{ $loop->first ? 'open active' : '' }}"
                                            id="day-{{ $day->id }}">
                                            <div class="day-content">
                                                {!! nl2br(e($day->display_description ?: __('No itinerary description added yet.'))) !!}
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
                        </section>
                    @endif

                    @if ($included->count() || $excluded->count())
                        <section class="content-section">
                            <h2 class="section-header">{{ __('What\'s Included') }}</h2>
                            <div class="row g-4">
                                @if ($included->count())
                                    <div class="{{ $excluded->count() ? 'col-md-6' : 'col-12' }}">
                                        <div class="included-box">
                                            <h4 class="box-title">{{ __('Included in Your Journey') }}</h4>
                                            <div class="styled-list">
                                                <ul>
                                                    @foreach ($included as $item)
                                                        <li>{{ $item->display_content }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($excluded->count())
                                    <div class="{{ $included->count() ? 'col-md-6' : 'col-12' }}">
                                        <div class="excluded-box">
                                            <h4 class="box-title">{{ __('Not Included') }}</h4>
                                            <div class="styled-list">
                                                <ul>
                                                    @foreach ($excluded as $item)
                                                        <li>{{ $item->display_content }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </section>
                    @endif

                    @if ($prices->count() || $hasCategoryPricing || $pricingInformation)
                        <section class="content-section pricing-showcase">
                            <h2 class="section-header">{{ __('Pricing & Packages') }}</h2>
                            @if ($priceFrom > 0 || $priceTo > 0 || $comparePrice > 0)
                                <div class="section-subtitle d-flex align-items-center justify-content-center flex-wrap gap-2 mb-4">
                                    @if ($priceTo > $priceFrom && $priceFrom > 0)
                                        <span class="price-badge-item" style="background: rgba(197, 149, 91, 0.12); color: #1c325c; border: 1px solid rgba(197, 149, 91, 0.3); padding: 8px 18px; border-radius: 20px; font-weight: 700; font-size: 0.95rem;">
                                            <i class="la la-tag text-gold me-1"></i>
                                            {{ __('Price Range:') }} {{ $currencySymbol }}{{ number_format($priceFrom, 2) }} - {{ $currencySymbol }}{{ number_format($priceTo, 2) }}
                                        </span>
                                    @elseif ($priceFrom > 0)
                                        <span class="price-badge-item" style="background: rgba(197, 149, 91, 0.12); color: #1c325c; border: 1px solid rgba(197, 149, 91, 0.3); padding: 8px 18px; border-radius: 20px; font-weight: 700; font-size: 0.95rem;">
                                            <i class="la la-tag text-gold me-1"></i>
                                            {{ __('Starts From:') }} {{ $currencySymbol }}{{ number_format($priceFrom, 2) }}
                                        </span>
                                    @endif

                                    @if ($comparePrice > max($priceFrom, $priceTo, 0))
                                        <span class="price-badge-item" style="background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; padding: 8px 18px; border-radius: 20px; font-weight: 600; font-size: 0.95rem; text-decoration: line-through;">
                                            {{ __('Was:') }} {{ $currencySymbol }}{{ number_format($comparePrice, 2) }}
                                        </span>
                                        @php
                                            $basePrice = $priceFrom > 0 ? $priceFrom : $priceTo;
                                            $saveAmount = $comparePrice - $basePrice;
                                            $savePercent = round(($saveAmount / $comparePrice) * 100);
                                        @endphp
                                        @if ($savePercent > 0)
                                            <span class="price-badge-item" style="background: #ef4444; color: #ffffff; padding: 8px 18px; border-radius: 20px; font-weight: 700; font-size: 0.95rem;">
                                                <i class="la la-percentage me-1"></i> {{ __('Save') }} {{ $savePercent }}%
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            @endif

                            @php
                                $tierPrices = $prices->filter(function($p) {
                                    return ($p->pax_min !== null || $p->pax_max !== null || $p->group_size_min !== null || $p->group_size_max !== null) && (float)$p->amount > 0;
                                })->values();
                            @endphp

                            <!-- Interactive Person Count Calculator -->
                            <div class="price-calculator-card">
                                <div class="row align-items-center g-3">
                                    <div class="col-lg-5">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="pricing-card-icon m-0 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px; background: rgba(197, 149, 91, 0.15); border-radius: 14px;" aria-hidden="true">
                                                <i class="la la-calculator" style="font-size: 1.8rem; color: #c5955b;"></i>
                                            </div>
                                            <div>
                                                <h4 class="mb-1" style="font-weight: 700; font-size: 1.15rem; color: var(--text-heading, #1c325c);">
                                                    {{ __('Calculate Price by Travelers') }}
                                                </h4>
                                                <p class="mb-0 text-muted small">
                                                    {{ __('Change traveler count to calculate live price') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-7">
                                        <div class="d-flex align-items-center justify-content-lg-end justify-content-start flex-wrap gap-4">
                                            <!-- Counter -->
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-semibold me-1 text-nowrap" style="font-size: 0.95rem;">{{ __('Travelers:') }}</span>
                                                <button type="button" class="counter-btn js-pax-decrement" aria-label="Decrease travelers">-</button>
                                                <span class="counter-value js-pax-count">1</span>
                                                <button type="button" class="counter-btn js-pax-increment" aria-label="Increase travelers">+</button>
                                            </div>

                                            <!-- Price Result Output -->
                                            <div class="text-lg-end text-start border-start ps-4" style="border-color: rgba(197, 149, 91, 0.25) !important;">
                                                <span class="text-muted d-block small" style="font-size: 0.8rem;">{{ __('Estimated Total:') }}</span>
                                                <span class="js-calc-total-price" style="font-size: 1.6rem; font-weight: 800; color: #c5955b; font-family: 'Playfair Display', serif;">
                                                    {{ $currencySymbol }}0.00
                                                </span>
                                                <span class="js-calc-per-person d-block text-muted" style="font-size: 0.8rem;"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($tierPrices->count())
                                <!-- Tiered Pricing Cards -->
                                <div class="mb-4">
                                    <h4 class="mb-3" style="font-weight: 700; font-size: 1.1rem; color: var(--text-heading, #1c325c);">
                                        <i class="la la-users me-1 text-gold"></i> {{ __('Pricing Tiers by Group Size') }}
                                    </h4>
                                    <div class="row g-3">
                                        @foreach ($tierPrices as $tp)
                                            @php
                                                $minP = $tp->pax_min ?: ($tp->group_size_min ?: 1);
                                                $maxP = $tp->pax_max ?: ($tp->group_size_max ?: 0);
                                            @endphp
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <div class="pax-tier-card js-tier-card" 
                                                    data-min="{{ $minP }}" 
                                                    data-max="{{ $maxP }}" 
                                                    data-amount="{{ (float)$tp->amount }}"
                                                    data-type="{{ $tp->price_type }}">
                                                    <div class="pax-tier-title">
                                                        @if ($minP === $maxP)
                                                            {{ $minP }} {{ __('Person') }}
                                                        @elseif ($maxP > 0)
                                                            {{ $minP }} - {{ $maxP }} {{ __('Persons') }}
                                                        @else
                                                            {{ $minP }}+ {{ __('Persons') }}
                                                        @endif
                                                    </div>
                                                    <div class="pax-tier-price">
                                                        {{ $currencySymbol }}{{ number_format((float)$tp->amount, 2) }}
                                                    </div>
                                                    <div class="pax-tier-sub">
                                                        {{ $tp->display_price_type }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($hasCategoryPricing)
                                <div class="pricing-cards">
                                    @if ($package->adult_price !== null && (float) $package->adult_price > 0)
                                        <article class="pricing-card">
                                            <div class="pricing-card-icon" aria-hidden="true">
                                                <svg viewBox="0 0 64 64" fill="none" stroke="currentColor"
                                                    stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="32" cy="20" r="10"></circle>
                                                    <path d="M15 52v-5c0-9.4 7.6-17 17-17s17 7.6 17 17v5H15Z"></path>
                                                </svg>
                                            </div>
                                            <h3 class="pricing-card-title">{{ __('Adult') }}</h3>
                                            <p class="pricing-card-age">{{ $package->adult_min_age ?: 12 }}+ {{ __('years') }}</p>
                                            <span class="pricing-card-divider"></span>
                                            <p class="pricing-card-price">
                                                {{ $currencySymbol }}{{ number_format((float) $package->adult_price, 2) }}
                                            </p>
                                        </article>
                                    @endif

                                    @if ($package->child_price !== null)
                                        <article class="pricing-card">
                                            <div class="pricing-card-icon" aria-hidden="true">
                                                <svg viewBox="0 0 64 64" fill="none" stroke="currentColor"
                                                    stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M14.5 30.5C14.5 18.6 22.3 11 32 11s17.5 7.6 17.5 19.5v7C49.5 48.3 41.7 56 32 56s-17.5-7.7-17.5-18.5v-7Z"></path>
                                                    <path d="M19 20c5 1.2 11.5-1.1 15-6 2.5 5.2 7.3 8.2 14 8"></path>
                                                    <path d="M14.5 29.5c-4 0-5.5 2.8-5.5 6s2.2 6 6 6M49.5 29.5c4 0 5.5 2.8 5.5 6s-2.2 6-6 6"></path>
                                                    <circle cx="25" cy="34" r="1.6" fill="currentColor" stroke="none"></circle>
                                                    <circle cx="39" cy="34" r="1.6" fill="currentColor" stroke="none"></circle>
                                                    <path d="M26 44c1.8 2 3.8 3 6 3s4.2-1 6-3"></path>
                                                </svg>
                                            </div>
                                            <h3 class="pricing-card-title">{{ __('Child') }}</h3>
                                            <p class="pricing-card-age">
                                                {{ $package->child_min_age ?: 2 }} - {{ $package->child_max_age ?: 11 }}
                                                {{ __('years') }}
                                            </p>
                                            <span class="pricing-card-divider"></span>
                                            <p class="pricing-card-price">
                                                @if ((float) $package->child_price > 0)
                                                    {{ $currencySymbol }}{{ number_format((float) $package->child_price, 2) }}
                                                @else
                                                    <span class="free-price-badge">--</span>
                                                @endif
                                            </p>
                                        </article>
                                    @endif

                                    @if ($package->infant_price !== null)
                                        <article class="pricing-card">
                                            <div class="pricing-card-icon" aria-hidden="true">
                                                <svg viewBox="0 0 64 64" fill="none" stroke="currentColor"
                                                    stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M18 29.5C18 19 24.2 12 32 12s14 7 14 17.5V36c0 9.2-6.2 16-14 16s-14-6.8-14-16v-6.5Z"></path>
                                                    <path d="M32 12c-1.7-2.7-.7-5.4 1.8-6.8M18.5 39c7.5 1.7 16.7 6.8 21.5 11.5M45.5 35.5 32 43"></path>
                                                    <path d="M18.2 29c-3.7 0-5.2 2.6-5.2 5.5s2 5.5 5.5 5.5M45.8 29c3.7 0 5.2 2.6 5.2 5.5s-2 5.5-5.5 5.5"></path>
                                                    <circle cx="26" cy="28" r="1.5" fill="currentColor" stroke="none"></circle>
                                                    <circle cx="38" cy="28" r="1.5" fill="currentColor" stroke="none"></circle>
                                                    <path d="M27 35c1.5 1.7 3.2 2.5 5 2.5s3.5-.8 5-2.5"></path>
                                                </svg>
                                            </div>
                                            <h3 class="pricing-card-title">{{ __('Infant') }}</h3>
                                            <p class="pricing-card-age">
                                                {{ $package->infant_min_age !== null ? $package->infant_min_age : 0 }} - {{ $package->infant_max_age ?: 1 }}
                                                {{ __('years') }}
                                            </p>
                                            <span class="pricing-card-divider"></span>
                                            <p class="pricing-card-price">
                                                @if ((float) $package->infant_price > 0)
                                                    {{ $currencySymbol }}{{ number_format((float) $package->infant_price, 2) }}
                                                @else
                                                    <span class="free-price-badge">{{ __('Free') }}</span>
                                                @endif
                                            </p>
                                        </article>
                                    @endif
                                </div>
                            @endif

                            @if ($prices->count())
                                <div class="price-box pricing-options">
                                    <div class="price-table-wrap">
                                        <table class="price-table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Option') }}</th>
                                                    <th>{{ __('Pax / Group') }}</th>
                                                    <th>{{ __('Price Details') }}</th>
                                                    <th>{{ __('Validity') }}</th>
                                                    <th>{{ __('Price') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($prices as $price)
                                                    <tr>
                                                        <td>
                                                            {{ $price->display_label }}
                                                            @if ($price->display_season_name)
                                                                <span class="price-meta"><i class="la la-sun"></i>
                                                                    {{ $price->display_season_name }}</span>
                                                            @endif
                                                            @if ($price->display_notes)
                                                                <span
                                                                    class="price-meta">{{ $price->display_notes }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($price->pax_min && $price->pax_max && $price->pax_min === $price->pax_max)
                                                                {{ $price->pax_min }} {{ __('Pax') }}
                                                            @elseif ($price->pax_min && $price->pax_max)
                                                                {{ $price->pax_min }} - {{ $price->pax_max }} {{ __('Pax') }}
                                                            @elseif ($price->pax_min)
                                                                {{ $price->pax_min }}+ {{ __('Pax') }}
                                                            @elseif ($price->pax_max)
                                                                1 - {{ $price->pax_max }} {{ __('Pax') }}
                                                            @elseif ($price->group_size_min || $price->group_size_max)
                                                                @if ($price->group_size_min && $price->group_size_max && $price->group_size_min === $price->group_size_max)
                                                                    {{ $price->group_size_min }} {{ __('Pax') }}
                                                                @elseif ($price->group_size_min && $price->group_size_max)
                                                                    {{ $price->group_size_min }} - {{ $price->group_size_max }} {{ __('Pax') }}
                                                                @elseif ($price->group_size_min)
                                                                    {{ $price->group_size_min }}+ {{ __('Pax') }}
                                                                @elseif ($price->group_size_max)
                                                                    1 - {{ $price->group_size_max }} {{ __('Pax') }}
                                                                @endif
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $price->display_price_type }}
                                                            @if ($price->display_room_type)
                                                                <span class="price-meta">{{ __('Room:') }}
                                                                    {{ $price->display_room_type }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($price->display_valid_from || $price->display_valid_to)
                                                                @if ($price->display_valid_from)
                                                                    <span class="price-meta">{{ __('From:') }}
                                                                        {{ $price->display_valid_from }}</span>
                                                                @endif
                                                                @if ($price->display_valid_to)
                                                                    <span class="price-meta">{{ __('To:') }}
                                                                        {{ $price->display_valid_to }}</span>
                                                                @endif
                                                            @else
                                                                {{ __('All Year') }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $price->formatted_amount }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if ($pricingInformation)
                                <div class="pricing-information">
                                    <div class="pricing-info-icon" aria-hidden="true">
                                        <svg viewBox="0 0 64 64" fill="none" stroke="currentColor"
                                            stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M32 6c7 5 14 7.5 23 8.5V30c0 13.5-8.4 22.6-23 29-14.6-6.4-23-15.5-23-29V14.5C18 13.5 25 11 32 6Z"></path>
                                            <path d="m23.5 31.5 6 6 12-13"></path>
                                        </svg>
                                    </div>
                                    <div class="pricing-info-content">
                                        <h4 class="pricing-info-title">{{ __('Pricing Information') }}</h4>
                                        <div class="pricing-info-text">{!! $pricingInformation !!}</div>
                                    </div>
                                </div>
                            @endif
                        </section>
                    @endif

                    {{-- @if (count($gallery) > 1)
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
                    @endif --}}

                    @php
                        $cancellationPolicy = $package->getTranslation('cancellation_policy');
                        $termsConditions = $package->getTranslation('terms_conditions');
                        $childrenPolicy = $package->getTranslation('children_policy');
                        $pickupPolicy = $package->getTranslation('pickup_policy');
                    @endphp
                    @if ($cancellationPolicy || $termsConditions || $childrenPolicy || $pickupPolicy)
                        <section class="content-section">
                            <h2 class="section-header">{{ __('Important Information') }}</h2>

                            @if ($childrenPolicy)
                                <div class="mb-4">
                                    <h4 class="mb-3"
                                        style="color: var(--primary-navy, #1c325c); font-family: 'Playfair Display', serif;">
                                        <i class="la la-child" style="color: var(--rich-gold, #c5955b);"></i>
                                        {{ __('Children Policy') }}
                                    </h4>
                                    <div class="about-content">{!! $childrenPolicy !!}</div>
                                </div>
                            @endif

                            @if ($pickupPolicy)
                                <div class="mb-4">
                                    <h4 class="mb-3"
                                        style="color: var(--primary-navy, #1c325c); font-family: 'Playfair Display', serif;">
                                        <i class="la la-shuttle-van" style="color: var(--rich-gold, #c5955b);"></i>
                                        {{ __('Pickup & Drop-off Policy') }}
                                    </h4>
                                    <div class="about-content">{!! $pickupPolicy !!}</div>
                                </div>
                            @endif

                            @if ($cancellationPolicy)
                                <div class="mb-4">
                                    <h4 class="mb-3"
                                        style="color: var(--primary-navy, #1c325c); font-family: 'Playfair Display', serif;">
                                        <i class="la la-info-circle" style="color: var(--rich-gold, #c5955b);"></i>
                                        {{ __('Cancellation Policy') }}
                                    </h4>
                                    <div class="about-content">
                                        {!! $cancellationPolicy !!}
                                    </div>
                                </div>
                            @endif

                            @if ($termsConditions)
                                <div>
                                    <h4 class="mb-3"
                                        style="color: var(--primary-navy, #1c325c); font-family: 'Playfair Display', serif;">
                                        <i class="la la-file-alt" style="color: var(--rich-gold, #c5955b);"></i>
                                        {{ __('Terms & Conditions') }}
                                    </h4>
                                    <div class="about-content">
                                        {!! $termsConditions !!}
                                    </div>
                                </div>
                            @endif
                        </section>
                    @endif

                    @if ($faqs->count())
                        <section class="content-section">
                            <h2 class="section-header">{{ __('Frequently Asked Questions') }}</h2>
                            <div class="faq-accordion">
                                @foreach ($faqs as $index => $faq)
                                    <div class="day-card mb-3">
                                        <button type="button" class="day-header"
                                            data-collapse-target="faq-{{ $package->id }}-{{ $index }}"
                                            aria-controls="faq-{{ $package->id }}-{{ $index }}"
                                            aria-expanded="false">
                                            <div class="day-number"
                                                style="width: 36px; height: 36px; min-width: 36px; font-size: 1.2rem;"><i
                                                    class="las la-question-circle" style="color: #fff !important;"
                                                    aria-hidden="true"></i></div>
                                            <div>
                                                <h3 class="day-title"
                                                    style="font-size: 1.05rem; font-family: inherit; font-weight: 700;">
                                                    {{ $faq['question'] ?: __('Question') . ' ' . ($index + 1) }}
                                                </h3>
                                            </div>
                                            <i class="la la-chevron-down collapse-icon" style="margin-left:auto"></i>
                                        </button>
                                        <div class="collapsible-content"
                                            id="faq-{{ $package->id }}-{{ $index }}">
                                            <div class="day-content" style="padding: 15px 22px;">
                                                <div class="mb-0 about-content">{!! nl2br(e($faq['answer'] ?: __('Answer will be added soon.'))) !!}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if ($reviews->count() || $testimonials->count())
                        <section id="reviews" class="content-section">
                            <h2 class="section-header">{{ __('Guest Reviews') }}</h2>
                            @if ($reviews->count())
                                @foreach ($reviews as $review)
                                    <div class="review-card">
                                        <div class="rating-stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="la {{ $i <= round($review->rating) ? 'la-star' : 'la-star-o' }}"></i>
                                            @endfor
                                        </div>
                                        @if ($review->title)
                                            <h5>{{ $review->title }}</h5>
                                        @endif
                                        <p>{{ $review->content }}</p>
                                    </div>
                                @endforeach
                            @else
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
                            @endif
                        </section>
                    @endif

                    @if ($relatedPackages->count())
                        <section class="content-section">
                            <h2 class="section-header">{{ __('Related Tours') }}</h2>
                            <div class="related-grid">
                                @foreach ($relatedPackages as $related)
                                    <div class="related-card">
                                        <img src="{{ $related['image'] }}" alt="{{ $related['title'] }}"
                                            loading="lazy">
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
                            @if ($comparePrice > max($priceFrom, $priceTo, 0))
                                <span class="compare-price">
                                    {{ __('Was') }} {{ $currencySymbol }}{{ number_format($comparePrice, 2) }}
                                </span>
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
        window.changeQty = function(id, amount) {
            const input = document.getElementById(id);
            if (!input) return;
            const min = parseInt(input.getAttribute('min') || '0');
            const current = parseInt(input.value || min);
            input.value = Math.max(min, current + amount);
        };

        document.addEventListener('DOMContentLoaded', function() {
            const collapseTriggers = document.querySelectorAll('[data-collapse-target]');

            const setCollapseState = (trigger, content, isOpen) => {
                content.classList.toggle('open', isOpen);
                content.classList.toggle('active', isOpen);
                content.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
                trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                content.style.maxHeight = isOpen ? `${content.scrollHeight}px` : '0px';
            };

            collapseTriggers.forEach((trigger) => {
                const content = document.getElementById(trigger.dataset.collapseTarget);

                if (!content) {
                    return;
                }

                setCollapseState(
                    trigger,
                    content,
                    content.classList.contains('open') || content.classList.contains('active')
                );

                trigger.addEventListener('click', function() {
                    setCollapseState(this, content, this.getAttribute('aria-expanded') !== 'true');
                });
            });

            window.addEventListener('resize', function() {
                document.querySelectorAll('.collapsible-content.open').forEach((content) => {
                    content.style.maxHeight = `${content.scrollHeight}px`;
                });
            });

            document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');

                    if (!href || href === '#' || this.hasAttribute('data-bs-toggle')) {
                        return;
                    }

                    let target = null;

                    try {
                        target = document.querySelector(href);
                    } catch (error) {
                        return;
                    }

                    if (!target) return;

                    e.preventDefault();
                    window.scrollTo({
                        top: target.offsetTop - 90,
                        behavior: 'smooth'
                    });
                });
            });

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

        /* Dynamic Pax Pricing Calculator Logic */
        document.addEventListener('DOMContentLoaded', function () {
            const decBtn = document.querySelector('.js-pax-decrement');
            const incBtn = document.querySelector('.js-pax-increment');
            const paxCountEl = document.querySelector('.js-pax-count');
            const totalPriceEl = document.querySelector('.js-calc-total-price');
            const perPersonEl = document.querySelector('.js-calc-per-person');
            const tierCards = document.querySelectorAll('.js-tier-card');

            if (!paxCountEl) return;

            const currencySymbol = "{{ $currencySymbol }}";
            const baseAdultPrice = parseFloat("{{ (float)($package->adult_price ?: ($package->price_from ?: 0)) }}");

            let currentPax = 1;

            function updateCalculator() {
                paxCountEl.textContent = currentPax;
                if (decBtn) decBtn.disabled = currentPax <= 1;

                let matchedAmount = baseAdultPrice;
                let isPerGroup = false;
                let activeCard = null;

                tierCards.forEach(card => {
                    const min = parseInt(card.dataset.min, 10) || 1;
                    const max = parseInt(card.dataset.max, 10) || 0;
                    const amt = parseFloat(card.dataset.amount) || 0;
                    const type = card.dataset.type;

                    card.classList.remove('active');

                    if (currentPax >= min && (max === 0 || currentPax <= max)) {
                        matchedAmount = amt;
                        isPerGroup = (type === 'per_group');
                        activeCard = card;
                    }
                });

                if (activeCard) {
                    activeCard.classList.add('active');
                }

                let total = 0;
                let perPerson = 0;

                if (isPerGroup) {
                    total = matchedAmount;
                    perPerson = currentPax > 0 ? (matchedAmount / currentPax) : matchedAmount;
                } else {
                    perPerson = matchedAmount;
                    total = matchedAmount * currentPax;
                }

                if (totalPriceEl) {
                    totalPriceEl.textContent = currencySymbol + total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }
                if (perPersonEl && perPerson > 0) {
                    perPersonEl.textContent = '(' + currencySymbol + perPerson.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' {{ __('per person') }}' + ')';
                }
            }

            if (decBtn) {
                decBtn.addEventListener('click', function () {
                    if (currentPax > 1) {
                        currentPax--;
                        updateCalculator();
                    }
                });
            }

            if (incBtn) {
                incBtn.addEventListener('click', function () {
                    currentPax++;
                    updateCalculator();
                });
            }

            tierCards.forEach(card => {
                card.addEventListener('click', function () {
                    const min = parseInt(card.dataset.min, 10) || 1;
                    currentPax = min;
                    updateCalculator();
                });
            });

            updateCalculator();
        });
    </script>
@endsection
