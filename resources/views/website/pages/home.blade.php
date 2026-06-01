@extends('website.layouts.master')

@section('title', __('Home - Etro Tours'))
@section('description', __('Luxury Egypt tours, Nile cruises, private day trips, and tailor-made travel experiences
    curated by Etro Tours across Cairo, Luxor, Aswan, and beyond.'))
@section('keywords', 'Etro Tours, luxury Egypt tours, Nile cruises, Egypt holidays, Cairo tours, Luxor tours, Aswan
    tours, tailor made travel')
@section('image', asset('website/logo/logo-lat.png'))

@php($isRtl = app()->getLocale() === 'ar')

@section('css')
    <style>
        :root {
            --tour-navy: #082f49;
            --tour-blue: #0f5f8f;
            --tour-sky: #38bdf8;
            --tour-gold: #d6a354;
            --tour-sand: #fff7ed;
            --tour-cream: #f8efe2;
            --tour-dark: #071923;
            --tour-muted: #64748b;
            --tour-white: #ffffff;
            --tour-border: rgba(15, 95, 143, 0.14);
            --tour-shadow: 0 22px 60px rgba(8, 47, 73, 0.12);
            --tour-shadow-hover: 0 28px 80px rgba(8, 47, 73, 0.2);
            --tour-radius: 28px;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: #fff;
            color: var(--tour-dark);
        }

        a {
            transition: all .3s ease;
        }

        img {
            max-width: 100%;
        }

        .tour-page {
            overflow: hidden;
            background:
                radial-gradient(circle at top left, rgba(56, 189, 248, .10), transparent 35%),
                radial-gradient(circle at 90% 10%, rgba(214, 163, 84, .12), transparent 30%),
                #fff;
        }

        .container {
            position: relative;
            z-index: 2;
        }

        .section-pad {
            padding: 110px 0;
            position: relative;
        }

        .light-section {
            background:
                linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
        }

        .cream-section {
            background:
                radial-gradient(circle at 10% 10%, rgba(214, 163, 84, .14), transparent 34%),
                linear-gradient(180deg, #fffaf2 0%, #ffffff 100%);
        }

        .section-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 999px;
            background: rgba(56, 189, 248, .12);
            color: var(--tour-blue);
            font-weight: 800;
            font-size: .82rem;
            letter-spacing: .6px;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .section-kicker i {
            color: var(--tour-gold);
            font-size: 1.1rem;
        }

        .section-heading {
            text-align: center;
            max-width: 850px;
            margin: 0 auto 55px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            color: var(--tour-navy);
            font-size: clamp(2rem, 4vw, 3.45rem);
            line-height: 1.08;
            font-weight: 800;
            margin-bottom: 18px;
        }

        .section-subtitle {
            color: var(--tour-muted);
            font-size: 1.05rem;
            line-height: 1.8;
            max-width: 690px;
            margin: 0 auto;
        }

        .gold-btn,
        .outline-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 13px 24px;
            border-radius: 999px;
            font-weight: 800;
            text-decoration: none;
            border: 0;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            isolation: isolate;
            white-space: nowrap;
        }

        .gold-btn {
            color: #fff;
            background: linear-gradient(135deg, #e7b762, #bd7f32);
            box-shadow: 0 16px 34px rgba(214, 163, 84, .34);
        }

        .gold-btn::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #bd7f32, #e7b762);
            opacity: 0;
            z-index: -1;
            transition: opacity .3s ease;
        }

        .gold-btn:hover {
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 20px 46px rgba(214, 163, 84, .42);
        }

        .gold-btn:hover::before {
            opacity: 1;
        }

        .outline-btn {
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .38);
            background: rgba(255, 255, 255, .12);
            backdrop-filter: blur(14px);
        }

        .outline-btn:hover {
            color: var(--tour-navy);
            background: #fff;
            transform: translateY(-3px);
        }

        .reveal-up {
            opacity: 0;
            transform: translateY(35px);
            transition: opacity .75s ease, transform .75s ease;
        }

        .reveal-up.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            margin-top: -85px;
            padding: 150px 0 90px;
            color: #fff;
            overflow: hidden;
            background:
                linear-gradient(110deg, rgba(4, 25, 43, .92) 0%, rgba(8, 47, 73, .72) 48%, rgba(8, 47, 73, .24) 100%),
                url('{{ asset('website/photos/home2.webp') }}');
            background-size: cover;
            background-position: center;
        }

        .hero-section::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 25%, rgba(56, 189, 248, .26), transparent 32%),
                radial-gradient(circle at 75% 28%, rgba(214, 163, 84, .22), transparent 30%),
                linear-gradient(180deg, transparent 68%, rgba(255, 255, 255, 1) 100%);
            pointer-events: none;
        }

        .hero-section::after {
            content: "";
            position: absolute;
            left: -7%;
            bottom: -80px;
            width: 115%;
            height: 155px;
            background: #fff;
            border-radius: 50% 50% 0 0;
            transform: rotate(-2deg);
            z-index: 1;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(320px, .72fr);
            gap: 45px;
            align-items: center;
            padding-top: 70px;
        }

        .hero-content {
            position: relative;
            z-index: 3;
            max-width: 820px;
            text-align: start;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .22);
            backdrop-filter: blur(16px);
            margin-bottom: 24px;
            font-weight: 800;
            animation: fadeInUp .8s ease both;
        }

        .hero-badge i {
            color: var(--tour-gold);
            font-size: 1.15rem;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(3rem, 7vw, 6.2rem);
            line-height: .95;
            letter-spacing: -2.5px;
            font-weight: 900;
            margin-bottom: 26px;
            animation: fadeInUp .9s ease .12s both;
        }

        .hero-title span {
            display: block;
            background: linear-gradient(135deg, #fff 0%, #f8d28a 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero-subtitle {
            max-width: 670px;
            font-size: 1.16rem;
            line-height: 1.85;
            color: rgba(255, 255, 255, .86);
            margin-bottom: 34px;
            animation: fadeInUp .9s ease .24s both;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            animation: fadeInUp .9s ease .36s both;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-top: 42px;
            max-width: 680px;
            animation: fadeInUp .9s ease .48s both;
        }

        .hero-stat {
            padding: 18px;
            border-radius: 22px;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .18);
            backdrop-filter: blur(14px);
        }

        .hero-stat strong {
            display: block;
            font-size: 1.55rem;
            font-weight: 900;
            color: #fff;
            margin-bottom: 2px;
        }

        .hero-stat span {
            font-size: .86rem;
            color: rgba(255, 255, 255, .78);
        }

        .hero-floating-card {
            position: relative;
            z-index: 3;
            padding: 18px;
            border-radius: 34px;
            background: rgba(255, 255, 255, .16);
            border: 1px solid rgba(255, 255, 255, .22);
            backdrop-filter: blur(18px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, .22);
            animation: floatY 5s ease-in-out infinite;
        }

        .hero-floating-card-inner {
            border-radius: 26px;
            overflow: hidden;
            background: #fff;
            color: var(--tour-dark);
        }

        .hero-floating-card img {
            width: 100%;
            height: 280px;
            object-fit: cover;
        }

        .floating-info {
            padding: 22px;
            text-align: start;
        }

        .floating-info h3 {
            color: var(--tour-navy);
            font-size: 1.15rem;
            font-weight: 900;
            margin-bottom: 10px;
        }

        .floating-info p {
            color: var(--tour-muted);
            margin-bottom: 16px;
            line-height: 1.7;
        }

        .mini-route {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            color: var(--tour-blue);
            font-weight: 800;
            font-size: .9rem;
            direction: ltr;
        }

        .mini-route i {
            color: var(--tour-gold);
        }

        .trust-section {
            position: relative;
            z-index: 4;
            margin-top: -70px;
            padding: 0 0 35px;
        }

        .trust-box {
            background: rgba(255, 255, 255, .88);
            border: 1px solid rgba(8, 47, 73, .08);
            box-shadow: var(--tour-shadow);
            border-radius: 30px;
            padding: 22px;
            backdrop-filter: blur(18px);
        }

        .trust-content {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .trust-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 18px;
            border-radius: 22px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border: 1px solid rgba(8, 47, 73, .07);
            color: var(--tour-navy);
            font-weight: 900;
            min-height: 86px;
        }

        .trust-item i {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            color: #fff;
            background: linear-gradient(135deg, var(--tour-blue), var(--tour-sky));
            font-size: 1.45rem;
            flex: 0 0 auto;
        }

        .tripadvisor-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 22px;
            margin-top: 35px;
        }

        .certificate-card {
            width: 145px;
            height: 145px;
            border-radius: 26px;
            background: #fff;
            border: 1px solid rgba(8, 47, 73, .08);
            box-shadow: 0 15px 40px rgba(8, 47, 73, .09);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px;
            transition: all .35s ease;
        }

        .certificate-card:hover {
            transform: translateY(-10px) rotate(-2deg);
            box-shadow: var(--tour-shadow-hover);
        }

        .certificate-img {
            max-height: 100%;
            object-fit: contain;
        }

        .features-grid,
        .cards-grid,
        .destinations-grid,
        .articles-grid,
        .testimonials-grid {
            display: grid;
            gap: 28px;
        }

        .features-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .cards-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .destinations-grid,
        .articles-grid,
        .testimonials-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .feature-card,
        .deal-card,
        .destination-card,
        .article-card,
        .testimonial-card {
            background: rgba(255, 255, 255, .94);
            border: 1px solid rgba(8, 47, 73, .08);
            border-radius: var(--tour-radius);
            box-shadow: 0 18px 45px rgba(8, 47, 73, .08);
            transition: transform .35s ease, box-shadow .35s ease, border-color .35s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before,
        .deal-card::before,
        .destination-card::before,
        .article-card::before,
        .testimonial-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(56, 189, 248, .12), transparent 40%, rgba(214, 163, 84, .13));
            opacity: 0;
            transition: opacity .35s ease;
            pointer-events: none;
        }

        .feature-card:hover,
        .deal-card:hover,
        .destination-card:hover,
        .article-card:hover,
        .testimonial-card:hover {
            transform: translateY(-12px);
            box-shadow: var(--tour-shadow-hover);
            border-color: rgba(56, 189, 248, .24);
        }

        .feature-card:hover::before,
        .deal-card:hover::before,
        .destination-card:hover::before,
        .article-card:hover::before,
        .testimonial-card:hover::before {
            opacity: 1;
        }

        .feature-card {
            padding: 34px 24px;
            text-align: center;
        }

        .feature-icon {
            width: 76px;
            height: 76px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 26px;
            color: #fff;
            font-size: 2.1rem;
            background: linear-gradient(135deg, var(--tour-blue), var(--tour-sky));
            margin-bottom: 24px;
            box-shadow: 0 16px 34px rgba(15, 95, 143, .24);
            transition: all .35s ease;
        }

        .feature-card:hover .feature-icon {
            transform: rotate(-5deg) scale(1.08);
            background: linear-gradient(135deg, var(--tour-gold), #bd7f32);
        }

        .feature-title {
            color: var(--tour-navy);
            font-size: 1.18rem;
            font-weight: 900;
            margin-bottom: 13px;
        }

        .feature-description {
            color: var(--tour-muted);
            line-height: 1.75;
            margin: 0;
            font-size: .96rem;
        }

        .deal-card,
        .destination-card,
        .article-card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card-image {
            position: relative;
            height: 255px;
            overflow: hidden;
            background: #e2e8f0;
        }

        .card-image::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, .02), rgba(0, 0, 0, .48));
            opacity: .68;
            transition: opacity .35s ease;
        }

        .deal-card:hover .card-image::after,
        .destination-card:hover .card-image::after,
        .article-card:hover .card-image::after {
            opacity: .4;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .75s ease;
        }

        .deal-card:hover .card-image img,
        .destination-card:hover .card-image img,
        .article-card:hover .card-image img {
            transform: scale(1.12);
        }

        .badge-top,
        .deal-price {
            position: absolute;
            z-index: 3;
            border-radius: 999px;
            font-weight: 900;
            box-shadow: 0 12px 28px rgba(0, 0, 0, .14);
        }

        .badge-top {
            top: 18px;
            inset-inline-start: 18px;
            padding: 8px 14px;
            background: rgba(255, 255, 255, .92);
            color: var(--tour-navy);
            backdrop-filter: blur(12px);
            font-size: .78rem;
        }

        .deal-price {
            inset-inline-end: 18px;
            bottom: 18px;
            padding: 10px 16px;
            background: linear-gradient(135deg, var(--tour-gold), #bd7f32);
            color: #fff;
            font-size: .92rem;
        }

        .card-body {
            padding: 26px;
            flex: 1;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 2;
            text-align: start;
        }

        .deal-title,
        .destination-title,
        .article-title {
            font-size: 1.24rem;
            font-weight: 900;
            line-height: 1.35;
            margin-bottom: 14px;
        }

        .deal-title a,
        .destination-title a,
        .article-title a {
            color: var(--tour-navy);
            text-decoration: none;
        }

        .deal-title a:hover,
        .destination-title a:hover,
        .article-title a:hover {
            color: var(--tour-blue);
        }

        .deal-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
        }

        .deal-meta span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--tour-muted);
            font-size: .86rem;
            font-weight: 700;
            padding: 7px 10px;
            background: #f8fafc;
            border-radius: 999px;
        }

        .deal-meta i,
        .destination-meta i,
        .article-date i {
            color: var(--tour-gold);
        }

        .deal-description,
        .destination-description,
        .article-excerpt {
            color: var(--tour-muted);
            line-height: 1.75;
            font-size: .96rem;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .deal-description,
        .article-excerpt {
            -webkit-line-clamp: 3;
        }

        .destination-description {
            -webkit-line-clamp: 2;
        }

        .tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 24px;
        }

        .feature-tag {
            display: inline-flex;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(56, 189, 248, .1);
            color: var(--tour-blue);
            font-size: .78rem;
            font-weight: 900;
        }

        .deal-btn,
        .destination-btn {
            width: 100%;
            margin-top: auto;
        }

        .destination-meta {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            padding-top: 18px;
            border-top: 1px solid rgba(8, 47, 73, .08);
            margin-bottom: 22px;
            color: var(--tour-muted);
            font-size: .9rem;
            font-weight: 800;
        }

        .destination-meta span,
        .article-date {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .article-date {
            color: var(--tour-blue);
            font-size: .9rem;
            font-weight: 900;
            margin-bottom: 12px;
        }

        .hero-subtitle,
        .deal-meta span,
        .destination-meta span,
        .deal-description,
        .destination-description,
        .article-date,
        .article-excerpt,
        .testimonial-text,
        .mini-route span {
            unicode-bidi: plaintext;
        }

        .quote-section {
            position: relative;
            padding: 120px 0;
            color: #fff;
            overflow: hidden;
            background:
                linear-gradient(135deg, rgba(4, 25, 43, .96), rgba(15, 95, 143, .9)),
                url('{{ asset('website/photos/bg-pattern.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .quote-section::before {
            content: "";
            position: absolute;
            width: 520px;
            height: 520px;
            border-radius: 50%;
            right: -160px;
            top: -180px;
            background: rgba(56, 189, 248, .18);
            filter: blur(4px);
        }

        .quote-section::after {
            content: "";
            position: absolute;
            width: 440px;
            height: 440px;
            border-radius: 50%;
            left: -160px;
            bottom: -180px;
            background: rgba(214, 163, 84, .16);
            filter: blur(4px);
        }

        .quote-card {
            max-width: 980px;
            margin: 0 auto;
            padding: 52px;
            text-align: center;
            border-radius: 38px;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .22);
            backdrop-filter: blur(18px);
            box-shadow: 0 30px 90px rgba(0, 0, 0, .18);
            position: relative;
            z-index: 2;
        }

        .quote-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.2rem, 4vw, 4rem);
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 16px;
            color: #fff;
        }

        .quote-card>p {
            max-width: 700px;
            margin: 0 auto;
            color: rgba(255, 255, 255, .84);
            line-height: 1.8;
            font-size: 1.05rem;
        }

        .quote-features {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin: 38px 0;
        }

        .quote-feature {
            padding: 18px 14px;
            border-radius: 22px;
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .16);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 9px;
            font-weight: 800;
        }

        .quote-feature i {
            width: 46px;
            height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            background: rgba(214, 163, 84, .2);
            color: #f8d28a;
            font-size: 1.55rem;
        }

        .testimonial-card {
            padding: 30px;
        }

        .rating-stars {
            color: #fbbc04;
            font-size: 1.18rem;
            margin-bottom: 18px;
        }

        .verified-badge {
            display: inline-flex;
            margin-left: 8px;
            padding: 5px 10px;
            border-radius: 999px;
            background: #dcfce7;
            color: #15803d;
            font-size: .75rem;
            font-weight: 900;
            vertical-align: middle;
        }

        .testimonial-text {
            color: #334155;
            font-style: italic;
            font-size: 1.03rem;
            line-height: 1.85;
            margin-bottom: 24px;
        }

        .author-section {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-top: 20px;
            border-top: 1px solid rgba(8, 47, 73, .08);
        }

        .author-avatar {
            width: 56px;
            height: 56px;
            border-radius: 20px;
            overflow: hidden;
            background: linear-gradient(135deg, var(--tour-blue), var(--tour-sky));
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 1.15rem;
            flex: 0 0 auto;
        }

        .author-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .author-name {
            color: var(--tour-navy);
            font-weight: 900;
            margin: 0 0 4px;
        }

        .newsletter-box {
            max-width: 820px;
            margin: 0 auto;
            padding: 52px;
            border-radius: 38px;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, .96), rgba(255, 255, 255, .9));
            border: 1px solid rgba(8, 47, 73, .08);
            box-shadow: var(--tour-shadow);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .newsletter-box::before {
            content: "";
            position: absolute;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: rgba(56, 189, 248, .14);
            right: -80px;
            top: -100px;
        }

        .newsletter-form {
            display: flex;
            gap: 12px;
            margin-top: 30px;
            position: relative;
            z-index: 2;
        }

        .newsletter-form input {
            flex: 1;
            min-height: 56px;
            border: 1px solid rgba(8, 47, 73, .12);
            background: #fff;
            border-radius: 999px;
            padding: 0 22px;
            outline: none;
            color: var(--tour-dark);
            box-shadow: 0 12px 30px rgba(8, 47, 73, .06);
        }

        .newsletter-form input:focus {
            border-color: var(--tour-sky);
            box-shadow: 0 0 0 4px rgba(56, 189, 248, .14);
        }

        .empty-state {
            grid-column: 1 / -1;
            padding: 34px;
            border-radius: 24px;
            background: #fff;
            border: 1px dashed rgba(15, 95, 143, .28);
            color: var(--tour-muted);
            text-align: center;
            font-weight: 800;
        }

        .modal-content {
            border: 0;
            border-radius: 30px !important;
            overflow: hidden;
            box-shadow: var(--tour-shadow-hover);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--tour-navy), var(--tour-blue));
            color: #fff;
            border: 0;
            padding: 22px 28px;
        }

        .modal-title {
            font-weight: 900;
        }

        .modal-body {
            padding: 28px;
        }

        .modal-footer {
            padding: 20px 28px 28px;
            border: 0;
        }

        .form-control {
            min-height: 52px;
            border-radius: 16px;
            border-color: rgba(8, 47, 73, .12);
            box-shadow: none !important;
            text-align: start;
        }

        textarea.form-control {
            min-height: 130px;
        }

        .form-control:focus {
            border-color: var(--tour-sky);
            box-shadow: 0 0 0 4px rgba(56, 189, 248, .12) !important;
        }

        .btn-close {
            filter: invert(1);
            opacity: .9;
        }

        .text-muted {
            color: var(--tour-muted) !important;
        }

        .newsletter-form input[type="email"],
        .form-control[type="email"],
        .form-control[type="number"],
        .form-control[type="date"],
        .form-control[name="phone"] {
            direction: ltr;
            text-align: left;
        }

        html[dir="rtl"] .section-kicker {
            text-transform: none;
            letter-spacing: 0;
        }

        html[dir="rtl"] .hero-content,
        html[dir="rtl"] .floating-info,
        html[dir="rtl"] .card-body,
        html[dir="rtl"] .article-date,
        html[dir="rtl"] .rating-stars {
            text-align: right;
        }

        html[dir="rtl"] .tour-page .gold-btn,
        html[dir="rtl"] .tour-page .outline-btn,
        html[dir="rtl"] .hero-badge,
        html[dir="rtl"] .deal-meta span,
        html[dir="rtl"] .destination-meta span,
        html[dir="rtl"] .article-date,
        html[dir="rtl"] .author-section {
            flex-direction: row-reverse;
        }

        html[dir="rtl"] .hero-grid {
            direction: rtl;
        }

        html[dir="rtl"] .hero-content,
        html[dir="rtl"] .hero-floating-card {
            direction: rtl;
        }

        html[dir="rtl"] .mini-route {
            direction: rtl;
        }

        html[dir="rtl"] .destination-meta {
            direction: rtl;
        }

        html[data-theme='dark'] .tour-page {
            background:
                radial-gradient(circle at top left, rgba(56, 189, 248, .16), transparent 34%),
                radial-gradient(circle at 88% 12%, rgba(214, 163, 84, .14), transparent 28%),
                linear-gradient(180deg, #081120 0%, #0b1220 46%, #111827 100%);
        }

        html[data-theme='dark'] .hero-section {
            background:
                linear-gradient(110deg, rgba(2, 6, 23, .95) 0%, rgba(8, 47, 73, .86) 46%, rgba(15, 95, 143, .48) 100%),
                url('{{ asset('website/photos/home2.webp') }}');
            background-size: cover;
            background-position: center;
            box-shadow: inset 0 -90px 120px rgba(2, 6, 23, .3);
        }

        html[data-theme='dark'] .hero-section::before {
            background:
                radial-gradient(circle at 20% 25%, rgba(56, 189, 248, .18), transparent 32%),
                radial-gradient(circle at 75% 28%, rgba(214, 163, 84, .16), transparent 30%),
                linear-gradient(180deg, transparent 60%, rgba(11, 18, 32, .97) 100%);
        }

        html[data-theme='dark'] .hero-section::after {
            background: #0b1220;
        }

        html[data-theme='dark'] .hero-title span {
            color: transparent !important;
            -webkit-text-fill-color: transparent;
        }

        html[data-theme='dark'] .hero-subtitle {
            color: rgba(226, 232, 240, .82) !important;
        }

        html[data-theme='dark'] .hero-badge,
        html[data-theme='dark'] .hero-stat,
        html[data-theme='dark'] .hero-floating-card {
            background: rgba(15, 23, 42, .42);
            border-color: rgba(148, 163, 184, .22);
            box-shadow: 0 20px 55px rgba(0, 0, 0, .24);
        }

        html[data-theme='dark'] .hero-badge span {
            color: rgba(248, 250, 252, .92) !important;
        }

        html[data-theme='dark'] .hero-stat span {
            color: rgba(226, 232, 240, .76) !important;
        }

        html[data-theme='dark'] .hero-floating-card-inner {
            background: #0f172a;
            color: #e2e8f0;
        }

        html[data-theme='dark'] .floating-info h3 {
            color: #f8fafc !important;
        }

        html[data-theme='dark'] .floating-info p {
            color: #94a3b8 !important;
        }

        html[data-theme='dark'] .mini-route {
            color: #7dd3fc;
        }

        html[data-theme='dark'] .mini-route span {
            color: inherit !important;
        }

        html[data-theme='dark'] .outline-btn {
            color: #fff;
            border-color: rgba(255, 255, 255, .18);
            background: rgba(15, 23, 42, .28);
        }

        html[data-theme='dark'] .outline-btn:hover {
            color: #f8fafc;
            background: rgba(255, 255, 255, .12);
        }

        html[data-theme='dark'] .trust-section {
            background: transparent !important;
        }

        html[data-theme='dark'] .trust-box {
            background: linear-gradient(135deg, rgba(15, 23, 42, .92), rgba(23, 32, 51, .84));
            border-color: rgba(148, 163, 184, .16);
            box-shadow: 0 24px 60px rgba(0, 0, 0, .32);
        }

        html[data-theme='dark'] .trust-item {
            background: linear-gradient(180deg, rgba(15, 23, 42, .94) 0%, rgba(17, 24, 39, .88) 100%) !important;
            border-color: rgba(148, 163, 184, .12) !important;
            color: #f8fafc !important;
        }

        html[data-theme='dark'] .light-section .tripadvisor-row {
            gap: 18px;
        }

        html[data-theme='dark'] .certificate-card {
            background: linear-gradient(180deg, #0f172a 0%, #172033 100%);
            border-color: rgba(244, 195, 106, .18);
            box-shadow: 0 18px 42px rgba(0, 0, 0, .32);
        }

        html[data-theme='dark'] .certificate-card:hover {
            box-shadow: 0 22px 54px rgba(0, 0, 0, .4);
        }

        html[data-theme='dark'] .certificate-img {
            filter: drop-shadow(0 8px 24px rgba(244, 195, 106, .14));
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(26px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes floatY {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-16px);
            }
        }

        @media (max-width: 1199px) {

            .features-grid,
            .cards-grid,
            .destinations-grid,
            .articles-grid,
            .testimonials-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .hero-grid {
                grid-template-columns: 1fr;
            }

            .hero-floating-card {
                max-width: 520px;
            }

            .quote-features {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 991px) {
            .hero-section {
                min-height: auto;
                padding: 135px 0 120px;
                margin-top: -70px;
                background-attachment: scroll;
            }

            .hero-grid {
                gap: 32px;
                padding-top: 34px;
            }

            .hero-content {
                max-width: 100%;
            }

            .hero-floating-card {
                width: min(100%, 520px);
                margin-inline: auto;
            }

            .trust-content {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .section-pad {
                padding: 80px 0;
            }
        }

        @media (max-width: 767px) {
            .hero-section {
                padding: 118px 0 92px;
                margin-top: -58px;
                background-position: center top;
            }

            .hero-section::after {
                height: 118px;
                bottom: -72px;
            }

            .hero-grid {
                gap: 24px;
                padding-top: 8px;
            }

            .hero-badge {
                padding: 9px 14px;
                font-size: .76rem;
                margin-bottom: 18px;
            }

            .hero-title {
                font-size: clamp(2.35rem, 12vw, 3.6rem);
                line-height: 1.02;
                letter-spacing: -1px;
                margin-bottom: 18px;
            }

            .hero-subtitle {
                font-size: 1rem;
                line-height: 1.72;
                margin-bottom: 24px;
            }

            .gold-btn,
            .outline-btn {
                min-height: 52px;
                padding: 12px 18px;
            }

            .hero-stats,
            .features-grid,
            .cards-grid,
            .destinations-grid,
            .articles-grid,
            .testimonials-grid,
            .trust-content,
            .quote-features {
                grid-template-columns: 1fr;
            }

            .hero-actions {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }

            .hero-actions a {
                width: 100%;
            }

            .hero-stats {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 10px;
                margin-top: 28px;
            }

            .hero-stat {
                padding: 14px 10px;
                border-radius: 18px;
                text-align: center;
            }

            .hero-stat strong {
                font-size: 1.2rem;
            }

            .hero-stat span {
                display: block;
                font-size: .76rem;
                line-height: 1.45;
            }

            .hero-floating-card {
                display: none;
            }

            .trust-section {
                margin-top: -34px;
                padding-bottom: 20px;
            }

            .trust-box {
                padding: 12px;
                border-radius: 22px;
            }

            .trust-item {
                padding: 15px;
                gap: 12px;
                border-radius: 18px;
                min-height: auto;
            }

            .trust-item i {
                width: 40px;
                height: 40px;
                border-radius: 14px;
                font-size: 1.2rem;
            }

            .tripadvisor-row {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 14px;
            }

            .card-image {
                height: 230px;
            }

            .quote-card,
            .newsletter-box {
                padding: 32px 22px;
                border-radius: 28px;
            }

            .newsletter-form {
                flex-direction: column;
            }

            .newsletter-form button {
                width: 100%;
            }

            .certificate-card {
                width: 100%;
                max-width: none;
                height: 118px;
                border-radius: 20px;
                padding: 14px;
            }
        }

        @media (max-width: 575px) {
            .hero-section {
                padding: 110px 0 86px;
            }

            .hero-section::after {
                height: 104px;
                bottom: -64px;
            }

            .hero-badge {
                width: 100%;
                justify-content: center;
                text-align: center;
            }

            .hero-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .hero-stat:last-child {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 480px) {
            .section-pad {
                padding: 68px 0;
            }

            .section-heading {
                margin-bottom: 32px;
            }

            .card-body {
                padding: 20px;
            }

            .hero-section {
                padding-bottom: 82px;
            }

            .hero-title {
                font-size: clamp(2.1rem, 11.5vw, 3rem);
            }

            .hero-subtitle {
                font-size: .95rem;
            }

            .quote-card,
            .newsletter-box {
                padding: 28px 18px;
            }

            .tripadvisor-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="tour-page">

        <section class="hero-section" id="home">
            <div class="container">
                <div class="hero-grid">
                    <div class="hero-content" dir="{{ $isRtl ? 'rtl' : 'ltr' }}"
                        style="text-align: {{ $isRtl ? 'right' : 'left' }} !important;">
                        <div class="hero-badge">
                            <i class="la la-map-marked"></i>
                            <span>{{ __('Luxury Egypt Travel Experiences') }}</span>
                        </div>

                        <h1 class="hero-title">
                            {{ __('Explore Egypt') }}
                            <span>{{ __('With Etro Tours') }}</span>
                        </h1>

                        <p class="hero-subtitle">
                            {{ __('Discover timeless monuments, Nile cruises, desert escapes, and private journeys designed with comfort, style, and local expertise from arrival to departure.') }}
                        </p>

                        <div class="hero-actions">
                            <a href="#deals" class="gold-btn">
                                <i class="la la-compass"></i>
                                {{ __('Discover Experiences') }}
                            </a>
                            <a href="#quote" class="outline-btn">
                                <i class="la la-paper-plane"></i>
                                {{ __('Plan My Trip') }}
                            </a>
                        </div>

                        <div class="hero-stats">
                            <div class="hero-stat">
                                <strong>10+</strong>
                                <span>{{ __('Years Experience') }}</span>
                            </div>
                            <div class="hero-stat">
                                <strong>24/7</strong>
                                <span>{{ __('Local Support') }}</span>
                            </div>
                            <div class="hero-stat">
                                <strong>5★</strong>
                                <span>{{ __('Guest Reviews') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="hero-floating-card">
                        <div class="hero-floating-card-inner">
                            <img src="{{ asset('website/photos/home2.webp') }}" alt="{{ __('Egypt Tour') }}">
                            <div class="floating-info">
                                <h3>{{ __('Private Egypt Journey') }}</h3>
                                <p>{{ __('Premium tours, hand-picked guides, comfortable transfers, and carefully curated routes.') }}
                                </p>
                                <div class="mini-route">
                                    @if ($isRtl)
                                        <span>{{ __('Aswan') }}</span>
                                        <span><i class="la la-long-arrow-left"></i></span>
                                        <span>{{ __('Luxor') }}</span>
                                        <span><i class="la la-long-arrow-left"></i></span>
                                        <span><i class="la la-map-marker"></i> {{ __('Cairo') }}</span>
                                    @else
                                        <span><i class="la la-map-marker"></i> {{ __('Cairo') }}</span>
                                        <span><i class="la la-long-arrow-right"></i></span>
                                        <span>{{ __('Luxor') }}</span>
                                        <span><i class="la la-long-arrow-right"></i></span>
                                        <span>{{ __('Aswan') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="trust-section">
            <div class="container">
                <div class="trust-box reveal-up">
                    <div class="trust-content">
                        <div class="trust-item">
                            <i class="la la-trophy"></i>
                            <span>{{ __('Award-Winning Service') }}</span>
                        </div>
                        <div class="trust-item">
                            <i class="la la-certificate"></i>
                            <span>{{ __('Licensed & Certified') }}</span>
                        </div>
                        <div class="trust-item">
                            <i class="la la-clock"></i>
                            <span>{{ __('24/7 Travel Support') }}</span>
                        </div>
                        <div class="trust-item">
                            <i class="la la-credit-card"></i>
                            <span>{{ __('Secure Payment') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-pad light-section">
            <div class="container">
                <div class="section-heading reveal-up">
                    <div class="section-kicker">
                        <i class="la la-tripadvisor"></i>
                        {{ __('Trusted Excellence') }}
                    </div>
                    <h2 class="section-title">{{ __('TripAdvisor Hall of Fame') }}</h2>
                    <p class="section-subtitle">
                        {{ __('Consistently recognized for excellence in travel experiences and unforgettable journeys across Egypt.') }}
                    </p>
                </div>

                <div class="tripadvisor-row">
                    @foreach (['Travellers-Choice-2019-.png', 'Travellers-Choice-2020.png', 'Travellers-Choice-2021.png', 'Travellers-Choice-2025.png', 'Travellers-Choice-2022.png', 'Travellers-Choice-2023.png', 'Travellers-Choice-2024-.png'] as $award)
                        <div class="certificate-card reveal-up">
                            <img loading="lazy" src="{{ asset('website/photos/tripadvisor/' . $award) }}"
                                alt="{{ __('TripAdvisor Award') }}" class="certificate-img">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="section-pad">
            <div class="container">
                <div class="section-heading reveal-up">
                    <div class="section-kicker">
                        <i class="la la-star"></i>
                        {{ __('Why Etro Tours') }}
                    </div>
                    <h2 class="section-title">{{ __('Travel Egypt With Confidence') }}</h2>
                    <p class="section-subtitle">
                        {{ __('A modern tourism experience combining expert planning, premium service, authentic culture, and smooth operations.') }}
                    </p>
                </div>

                <div class="features-grid">
                    <div class="feature-card reveal-up">
                        <div class="feature-icon"><i class="la la-user-graduate"></i></div>
                        <h3 class="feature-title">{{ __('Expert Egyptologists') }}</h3>
                        <p class="feature-description">
                            {{ __('Certified guides bring temples, tombs, museums, and ancient stories to life with rich knowledge.') }}
                        </p>
                    </div>

                    <div class="feature-card reveal-up">
                        <div class="feature-icon"><i class="la la-shield-alt"></i></div>
                        <h3 class="feature-title">{{ __('Safe Operations') }}</h3>
                        <p class="feature-description">
                            {{ __('Trusted transport, organized itineraries, and reliable local support for a comfortable journey.') }}
                        </p>
                    </div>

                    <div class="feature-card reveal-up">
                        <div class="feature-icon"><i class="la la-gem"></i></div>
                        <h3 class="feature-title">{{ __('Luxury Touch') }}</h3>
                        <p class="feature-description">
                            {{ __('Premium experiences, carefully selected services, and details designed for a refined holiday.') }}
                        </p>
                    </div>

                    <div class="feature-card reveal-up">
                        <div class="feature-icon"><i class="la la-headset"></i></div>
                        <h3 class="feature-title">{{ __('Tailor-Made Service') }}</h3>
                        <p class="feature-description">
                            {{ __('Every trip can be customized around your schedule, budget, interests, and travel style.') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="deals" class="section-pad cream-section">
            <div class="container">
                <div class="section-heading reveal-up">
                    <div class="section-kicker">
                        <i class="la la-suitcase"></i>
                        {{ __('Featured Tours') }}
                    </div>
                    <h2 class="section-title">{{ __('Signature Egypt Experiences') }}</h2>
                    <p class="section-subtitle">
                        {{ __('Discover our most requested journeys, from iconic landmarks to luxurious Nile adventures.') }}
                    </p>
                </div>

                <div class="cards-grid">
                    @forelse ($featuredPackages as $package)
                        <div class="deal-card reveal-up">
                            <div class="card-image">
                                @if ($package['is_ultra_luxury'])
                                    <div class="badge-top">{{ __('Ultra Luxury') }}</div>
                                @elseif ($package['is_best_seller'])
                                    <div class="badge-top">{{ __('Best Seller') }}</div>
                                @endif

                                <div class="deal-price">{{ $package['price'] }}</div>

                                <a href="{{ $package['url'] }}">
                                    <img src="{{ $package['image'] }}" alt="{{ $package['title'] }}" loading="lazy">
                                </a>
                            </div>

                            <div class="card-body">
                                <h3 class="deal-title">
                                    <a href="{{ $package['url'] }}">{{ $package['title'] }}</a>
                                </h3>

                                <div class="deal-meta">
                                    <span><i class="la la-clock"></i>{{ $package['duration'] }}</span>
                                    <span><i class="la la-users"></i>{{ $package['tour_type'] }}</span>
                                    @if ($package['route_text'])
                                        <span><i class="la la-map-marker"></i>{{ $package['route_text'] }}</span>
                                    @endif
                                </div>

                                <p class="deal-description">{{ $package['description'] }}</p>

                                @if (!empty($package['tags']))
                                    <div class="tag-list">
                                        @foreach ($package['tags'] as $tag)
                                            <span class="feature-tag">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                <a href="{{ $package['url'] }}" class="gold-btn deal-btn">
                                    {{ __('Explore Journey') }}
                                    <i class="la la-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            {{ __('No featured packages found. Add active packages from the admin panel.') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="quote-section" id="quote">
            <div class="container">
                <div class="quote-card reveal-up">
                    <h2 class="quote-title">{{ __('Need Help Planning Your Trip?') }}</h2>
                    <p>
                        {{ __('Tell us your travel dates, interests, number of guests, and preferred style. Our travel experts will create a personalized Egypt experience for you.') }}
                    </p>

                    <div class="quote-features">
                        <div class="quote-feature">
                            <i class="la la-check-circle"></i>
                            <span>{{ __('Custom Itineraries') }}</span>
                        </div>
                        <div class="quote-feature">
                            <i class="la la-user-graduate"></i>
                            <span>{{ __('Expert Guides') }}</span>
                        </div>
                        <div class="quote-feature">
                            <i class="la la-headset"></i>
                            <span>{{ __('24/7 Support') }}</span>
                        </div>
                        <div class="quote-feature">
                            <i class="la la-dollar"></i>
                            <span>{{ __('Best Value') }}</span>
                        </div>
                    </div>

                    <button class="gold-btn" data-bs-toggle="modal" data-bs-target="#quoteModal">
                        <i class="la la-paper-plane"></i>
                        {{ __('Get Custom Quote') }}
                    </button>
                </div>
            </div>
        </section>

        <section class="section-pad light-section">
            <div class="container">
                <div class="section-heading reveal-up">
                    <div class="section-kicker">
                        <i class="la la-map"></i>
                        {{ __('Destinations') }}
                    </div>
                    <h2 class="section-title">{{ __('Explore Extraordinary Places') }}</h2>
                    <p class="section-subtitle">
                        {{ __('From Cairo and Giza to Luxor, Aswan, the Red Sea, and hidden gems across Egypt.') }}
                    </p>
                </div>

                <div class="destinations-grid">
                    @forelse ($destinations as $destination)
                        <div class="destination-card reveal-up">
                            <div class="card-image">
                                <div class="badge-top">{{ $destination['country'] ?: __('Destination') }}</div>
                                <a href="{{ $destination['url'] }}">
                                    <img src="{{ $destination['image'] }}" alt="{{ $destination['title'] }}"
                                        loading="lazy">
                                </a>
                            </div>

                            <div class="card-body">
                                <h3 class="destination-title">
                                    <a href="{{ $destination['url'] }}">{{ $destination['title'] }}</a>
                                </h3>

                                <p class="destination-description">{{ $destination['description'] }}</p>

                                <div class="destination-meta">
                                    <span>
                                        <i class="la la-map-marker"></i>
                                        {{ $destination['sites_count'] }} {{ __('Sites') }}
                                    </span>
                                    <span>
                                        <i class="la la-suitcase"></i>
                                        {{ $destination['packages_count'] }} {{ __('Trips') }}
                                    </span>
                                </div>

                                <a href="{{ $destination['url'] }}" class="gold-btn destination-btn">
                                    {{ __('Discover') }}
                                    <i class="la la-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            {{ __('No active destinations found. Add active cities from the admin panel.') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="section-pad">
            <div class="container">
                <div class="section-heading reveal-up">
                    <div class="section-kicker">
                        <i class="la la-newspaper"></i>
                        {{ __('Travel Guides') }}
                    </div>
                    <h2 class="section-title">{{ __('Latest Egypt Travel Stories') }}</h2>
                    <p class="section-subtitle">
                        {{ __('Useful tips, destination insights, and inspiring stories for planning your Egypt journey.') }}
                    </p>
                </div>

                <div class="articles-grid">
                    @forelse ($latestArticles as $article)
                        <div class="article-card reveal-up">
                            <div class="card-image">
                                <a href="{{ $article['url'] }}">
                                    <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" loading="lazy">
                                </a>
                            </div>

                            <div class="card-body">
                                <div class="article-date">
                                    <i class="la la-calendar"></i>
                                    {{ $article['date'] }}
                                </div>

                                <h3 class="article-title">
                                    <a href="{{ $article['url'] }}">{{ $article['title'] }}</a>
                                </h3>

                                <p class="article-excerpt">{{ $article['excerpt'] }}</p>

                                <a href="{{ $article['url'] }}" class="gold-btn">
                                    {{ __('Read More') }}
                                    <i class="la la-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">{{ __('No active articles found.') }}</div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="section-pad light-section">
            <div class="container">
                <div class="section-heading reveal-up">
                    <div class="section-kicker">
                        <i class="la la-comments"></i>
                        {{ __('Guest Reviews') }}
                    </div>
                    <h2 class="section-title">{{ __('Travelers Love Etro Tours') }}</h2>
                    <p class="section-subtitle">
                        {{ __('Real experiences from guests who discovered the magic of Egypt with our team.') }}
                    </p>
                </div>

                <div class="testimonials-grid">
                    @forelse ($testimonials as $testimonial)
                        <div class="testimonial-card reveal-up">
                            <div class="rating-stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="la {{ $i <= $testimonial['rating'] ? 'la-star' : 'la-star-o' }}"></i>
                                @endfor

                                @if ($testimonial['is_verified'])
                                    <span class="verified-badge">{{ __('Verified') }}</span>
                                @endif
                            </div>

                            <p class="testimonial-text">“{{ $testimonial['content'] }}”</p>

                            <div class="author-section">
                                <div class="author-avatar">
                                    @if ($testimonial['avatar'])
                                        <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] }}">
                                    @else
                                        {{ $testimonial['initials'] }}
                                    @endif
                                </div>

                                <div>
                                    <h5 class="author-name">{{ $testimonial['name'] }}</h5>
                                    <p class="mb-0 text-muted">
                                        <i class="la la-check-circle"></i>
                                        {{ __('Guest Review') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            {{ __('No testimonials found. Add active testimonials from the admin panel.') }}
                        </div>
                    @endforelse
                </div>

                <div class="text-center mt-5 reveal-up">
                    <a href="https://www.tripadvisor.com/Attraction_Review-g294205-d12148903-Reviews-Luxor_and_Aswan_Travel-Luxor_Nile_River_Valley.html"
                        target="_blank" class="gold-btn">
                        <i class="la la-external-link"></i>
                        {{ __('Read All Reviews on TripAdvisor') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="section-pad cream-section">
            <div class="container">
                <div class="newsletter-box reveal-up">
                    <div class="section-kicker">
                        <i class="la la-envelope"></i>
                        {{ __('Newsletter') }}
                    </div>

                    <h2 class="section-title">{{ __('Get Our Latest Travel Deals') }}</h2>

                    <p class="section-subtitle">
                        {{ __('Subscribe to receive updates, new packages, seasonal offers, and useful Egypt travel tips.') }}
                    </p>

                    <form action="{{ route('website.newsletter.store') }}" method="POST" class="newsletter-form">
                        @csrf
                        <input type="email" name="email" placeholder="{{ __('Enter your email address') }}"
                            required>
                        <button type="submit" class="gold-btn">
                            {{ __('Subscribe') }}
                            <i class="la la-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <div class="modal fade" id="quoteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Get Custom Quote') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('Close') }}"></button>
                    </div>

                    <form action="{{ route('website.inquiries.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="inquiry_type" value="custom_quote">

                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input class="form-control" name="full_name" placeholder="{{ __('Full name') }}"
                                        required>
                                </div>

                                <div class="col-md-6">
                                    <input class="form-control" type="email" name="email"
                                        placeholder="{{ __('Email address') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <input class="form-control" name="phone"
                                        placeholder="{{ __('Phone / WhatsApp') }}">
                                </div>

                                <div class="col-md-6">
                                    <input class="form-control" name="country_name" placeholder="{{ __('Country') }}">
                                </div>

                                <div class="col-md-4">
                                    <input class="form-control" type="date" name="travel_date">
                                </div>

                                <div class="col-md-4">
                                    <input class="form-control" type="number" min="1" name="adults"
                                        placeholder="{{ __('Adults') }}">
                                </div>

                                <div class="col-md-4">
                                    <input class="form-control" type="number" min="0" name="children"
                                        placeholder="{{ __('Children') }}">
                                </div>

                                <div class="col-12">
                                    <textarea class="form-control" name="message" rows="4"
                                        placeholder="{{ __('Tell us about your preferred trip') }}"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                {{ __('Close') }}
                            </button>

                            <button type="submit" class="gold-btn">
                                {{ __('Send Request') }}
                                <i class="la la-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const revealItems = document.querySelectorAll('.reveal-up');

            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.12
                });

                revealItems.forEach(function(item, index) {
                    item.style.transitionDelay = (index % 4) * 80 + 'ms';
                    observer.observe(item);
                });
            } else {
                revealItems.forEach(function(item) {
                    item.classList.add('is-visible');
                });
            }

            const hero = document.querySelector('.hero-section');

            if (hero && window.innerWidth > 991) {
                window.addEventListener('scroll', function() {
                    const offset = window.pageYOffset || document.documentElement.scrollTop;
                    hero.style.backgroundPositionY = (offset * 0.25) + 'px';
                }, {
                    passive: true
                });
            }
        });
    </script>
@endsection
