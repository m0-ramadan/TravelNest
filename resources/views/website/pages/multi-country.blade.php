@extends('website.layouts.master')

@section('title', 'Multi Country Tours - Luxor and Aswan Travel')

@section('css')
    <style>
        /* Enhanced color input focus states */
        .form-control:focus {
            border-color: var(--rich-gold);
            box-shadow: 0 0 0 0.25rem rgba(197, 149, 91, 0.25);
            outline: none;
            transform: translateY(-2px);
        }

        /* Enhanced Hero Section with Responsive Heights */
        .hero-section {
            height: 60vh;
            min-height: 450px;
            max-height: 600px;
            background: linear-gradient(rgb(28 50 92 / 31%), rgb(26 75 102 / 43%)), url(../../../images/multi-country-hero.jpg);
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        /* Large Desktop (1400px+) */
        @media (min-width: 1400px) {
            .hero-section {
                height: 55vh;
                max-height: 550px;
            }
        }

        /* Desktop (1200px - 1399px) */
        @media (max-width: 1399px) and (min-width: 1200px) {
            .hero-section {
                height: 55vh;
                min-height: 500px;
                max-height: 550px;
            }
        }

        /* Laptop (992px - 1199px) */
        @media (max-width: 1199px) and (min-width: 992px) {
            .hero-section {
                height: 50vh;
                min-height: 450px;
                max-height: 500px;
            }
        }

        /* Tablet Portrait (768px - 991px) */
        @media (max-width: 991px) and (min-width: 768px) {
            .hero-section {
                height: 45vh;
                min-height: 400px;
                max-height: 450px;
                background-attachment: scroll;
            }
        }

        /* Mobile Landscape (576px - 767px) */
        @media (max-width: 767px) and (min-width: 576px) {
            .hero-section {
                height: 40vh;
                min-height: 350px;
                max-height: 400px;
                background-attachment: scroll;
            }
        }

        /* Mobile Portrait (320px - 575px) */
        @media (max-width: 575px) {
            .hero-section {
                height: 35vh;
                min-height: 300px;
                max-height: 350px;
                background-attachment: scroll;
            }
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20" fill="none"><path d="M0 10L10 0L20 10L30 0L40 10L50 0L60 10L70 0L80 10L90 0L100 10V20H0V10Z" fill="rgba(197,149,91,0.1)"/></svg>') repeat-x;
            opacity: 0.4;
            animation: wave 20s ease-in-out infinite;
        }

        @keyframes wave {

            0%,
            100% {
                transform: translateX(0);
            }

            50% {
                transform: translateX(-50px);
            }
        }

        .hero-content {
            position: relative;
            z-index: 3;
            text-align: center;
            color: white;
            max-width: 900px;
            margin: 0 auto;
            padding: 100px 20px 0;
            animation: fadeInUp 1.2s ease-out;
        }

        @media (max-width: 991px) {
            .hero-content {
                padding: 80px 30px 0;
            }
        }

        @media (max-width: 575px) {
            .hero-content {
                padding: 60px 20px 0;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-badge {
            background: rgba(197, 149, 91, 0.9);
            color: var(--primary-navy);
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            backdrop-filter: blur(10px);
            animation: pulse 2s infinite;
            border: 2px solid rgba(197, 149, 91, 0.3);
        }

        @media (max-width: 575px) {
            .hero-badge {
                padding: 8px 20px;
                font-size: 0.85rem;
                margin-bottom: 20px;
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            background: linear-gradient(135deg, #ffffff 0%, var(--cream-elegant) 100%);
            -webkit-background-clip: text;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.95;
            font-weight: 300;
            letter-spacing: 0.5px;
        }

        @media (max-width: 575px) {
            .hero-subtitle {
                font-size: 1rem;
                margin-bottom: 25px;
            }
        }

        /* Breadcrumb Styling */
        .breadcrumb-top-bar {
            background: var(--pearl-luxury);
            padding: 15px 0;
            border-bottom: 1px solid rgba(197, 149, 91, 0.2);
        }

        .breadcrumb-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .breadcrumb-list ul li {
            display: flex;
            align-items: center;
            color: var(--warm-gray);
            font-size: 0.95rem;
        }

        .breadcrumb-list ul li:not(:last-child)::after {
            content: '›';
            margin-left: 10px;
            color: var(--rich-gold);
            font-size: 1.2rem;
            line-height: 1;
        }

        .breadcrumb-list ul li a {
            color: var(--primary-navy);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb-list ul li a:hover {
            color: var(--rich-gold);
        }

        /* Filters Section */
        .filters-section {
            background: white;
            padding: 40px 0;
            border-bottom: 1px solid rgba(197, 149, 91, 0.2);
        }

        .content-description {
            max-width: 1000px;
            margin: 0 auto 30px;
            color: var(--warm-gray);
            line-height: 1.8;
            text-align: center;
        }

        .filters-container {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            justify-content: center;
            align-items: flex-end;
            background: var(--pearl-luxury);
            padding: 30px;
            border-radius: 20px;
            box-shadow: var(--shadow-subtle);
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--primary-navy);
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .filter-label i {
            color: var(--rich-gold);
            font-size: 1.1rem;
        }

        .filter-select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid rgba(197, 149, 91, 0.2);
            border-radius: 12px;
            background: white;
            color: var(--primary-navy);
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23c5955b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px;
        }

        .filter-select:hover,
        .filter-select:focus {
            border-color: var(--rich-gold);
            box-shadow: 0 0 0 0.25rem rgba(197, 149, 91, 0.25);
            outline: none;
            transform: translateY(-2px);
        }

        /* Tours Section */
        .tours-section {
            background: linear-gradient(135deg, var(--cream-elegant) 0%, var(--light-sand) 100%);
            padding: 80px 0;
            position: relative;
        }

        .tours-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 35px;
        }

        @media (max-width: 1200px) {
            .tours-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .tours-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }
        }

        .tour-card {
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: var(--shadow-medium);
            transition: all 0.4s ease;
            position: relative;
            border: 2px solid rgba(197, 149, 91, 0.1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .tour-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-dramatic);
            border-color: var(--rich-gold);
        }

        .tour-image {
            position: relative;
            height: 240px;
            overflow: hidden;
            display: block;
        }

        .tour-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .tour-card:hover .tour-image img {
            transform: scale(1.1);
        }

        .price-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--gradient-gold);
            color: var(--primary-navy);
            padding: 10px 18px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 0.95rem;
            z-index: 2;
            box-shadow: var(--shadow-gold);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .tour-content {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .tour-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--primary-navy);
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .tour-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .tour-title a:hover {
            color: var(--rich-gold);
        }

        .tour-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--warm-gray);
            font-size: 0.9rem;
            background: rgba(197, 149, 91, 0.1);
            padding: 6px 12px;
            border-radius: 20px;
        }

        .meta-item i {
            color: var(--rich-gold);
            font-size: 0.95rem;
        }

        .tour-description {
            color: var(--warm-gray);
            line-height: 1.6;
            margin-bottom: 20px;
            flex: 1;
            font-size: 0.95rem;
        }

        .tour-action {
            margin-top: auto;
        }

        .view-btn {
            background: var(--gradient-gold);
            color: var(--primary-navy);
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            width: 100%;
            justify-content: center;
            border: none;
            cursor: pointer;
        }

        .view-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-gold);
            color: var(--primary-navy);
        }

        .view-btn i {
            transition: transform 0.3s ease;
        }

        .view-btn:hover i {
            transform: translateX(5px);
        }

        /* Fade In Animation */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s ease forwards;
        }

        .fade-in-up:nth-child(1) {
            animation-delay: 0.1s;
        }

        .fade-in-up:nth-child(2) {
            animation-delay: 0.2s;
        }

        .fade-in-up:nth-child(3) {
            animation-delay: 0.3s;
        }

        .fade-in-up:nth-child(4) {
            animation-delay: 0.4s;
        }

        .fade-in-up:nth-child(5) {
            animation-delay: 0.5s;
        }

        .fade-in-up:nth-child(6) {
            animation-delay: 0.6s;
        }

        .fade-in-up:nth-child(7) {
            animation-delay: 0.7s;
        }

        .fade-in-up:nth-child(8) {
            animation-delay: 0.8s;
        }

        .fade-in-up:nth-child(9) {
            animation-delay: 0.9s;
        }

        .fade-in-up:nth-child(10) {
            animation-delay: 1.0s;
        }

        .fade-in-up:nth-child(11) {
            animation-delay: 1.1s;
        }

        .fade-in-up:nth-child(12) {
            animation-delay: 1.2s;
        }

        .fade-in-up:nth-child(13) {
            animation-delay: 1.3s;
        }

        .fade-in-up:nth-child(14) {
            animation-delay: 1.4s;
        }

        .fade-in-up:nth-child(15) {
            animation-delay: 1.5s;
        }

        .fade-in-up:nth-child(16) {
            animation-delay: 1.6s;
        }

        .fade-in-up:nth-child(17) {
            animation-delay: 1.7s;
        }

        .fade-in-up:nth-child(18) {
            animation-delay: 1.8s;
        }

        /* Why Choose Section - Reused from home page */
        .why-choose-section {
            background: var(--pearl-luxury);
            padding: 80px 0;
            position: relative;
            border-top: 1px solid rgba(197, 149, 91, 0.2);
        }

        .section-header {
            font-family: 'Playfair Display', serif;
            color: var(--primary-navy);
            font-size: clamp(1.5rem, 3vw, 2.2rem);
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }

        .section-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--gradient-gold);
            border-radius: 2px;
        }

        .section-subtitle {
            color: var(--warm-gray);
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 60px;
            line-height: 1.6;
        }

        .choose-card {
            background: white;
            border-radius: 25px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: var(--shadow-medium);
            border: 2px solid transparent;
            transition: all 0.4s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .choose-card:hover {
            border-color: var(--rich-gold);
            transform: translateY(-8px);
            box-shadow: var(--shadow-dramatic);
        }

        .choose-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2.2rem;
            color: white;
            box-shadow: var(--shadow-gold);
            transition: all 0.3s ease;
        }

        .choose-card:hover .choose-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .choose-title {
            font-family: 'Playfair Display', serif;
            color: var(--primary-navy);
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .choose-features {
            text-align: left;
        }

        .feature-item {
            padding: 12px 0;
            border-bottom: 1px solid rgba(197, 149, 91, 0.2);
            color: var(--warm-gray);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .feature-item:last-child {
            border-bottom: none;
        }

        /* Luxury CTA Section */
        .luxury-cta-section {
            background: linear-gradient(135deg, var(--primary-navy) 0%, #1a4b66 100%);
            padding: 60px 0;
            position: relative;
            overflow: hidden;
            border-top: 1px solid rgba(197, 149, 91, 0.3);
        }

        .luxury-cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20" fill="none"><path d="M0 10L10 0L20 10L30 0L40 10L50 0L60 10L70 0L80 10L90 0L100 10V20H0V10Z" fill="rgba(197,149,91,0.1)"/></svg>') repeat-x;
            opacity: 0.3;
        }

        .luxury-cta-content {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 30px;
            padding: 50px;
            border: 2px solid rgba(197, 149, 91, 0.3);
            box-shadow: var(--shadow-dramatic);
            position: relative;
            z-index: 2;
        }

        .cta-icon-container {
            width: 80px;
            height: 80px;
            background: var(--gradient-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 2.2rem;
            color: var(--primary-navy);
            box-shadow: var(--shadow-gold);
            animation: pulse 2s infinite;
        }

        .cta-content-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .cta-content-wrapper {
                flex-direction: column;
                text-align: center;
            }
        }

        .cta-text-content {
            flex: 1;
        }

        .cta-title {
            font-family: 'Playfair Display', serif;
            color: white;
            font-size: clamp(1.5rem, 3vw, 2.2rem);
            margin-bottom: 15px;
        }

        .cta-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            margin-bottom: 20px;
        }

        .trust-features {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .trust-features {
                justify-content: center;
            }
        }

        .trust-feature {
            display: flex;
            align-items: center;
            gap: 8px;
            color: white;
            background: rgba(197, 149, 91, 0.2);
            padding: 8px 15px;
            border-radius: 25px;
            font-size: 0.9rem;
            border: 1px solid rgba(197, 149, 91, 0.3);
        }

        .trust-feature i {
            color: var(--rich-gold);
            font-size: 1rem;
        }

        .luxury-cta-btn {
            background: var(--gradient-gold);
            color: var(--primary-navy);
            padding: 18px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.2rem;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-gold);
            white-space: nowrap;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        @media (max-width: 768px) {
            .luxury-cta-btn {
                white-space: normal;
                width: 100%;
                justify-content: center;
            }
        }

        .luxury-cta-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(197, 149, 91, 0.4);
            color: var(--primary-navy);
        }

        .luxury-cta-btn i {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .luxury-cta-btn:hover i {
            transform: translateX(5px);
        }
    </style>
@endsection

@section('content')
    <!-- Breadcrumb Section -->
    <section class="breadcrumb-top-bar">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-list">
                        <ul>
                            <li><a href="/">Home</a></li>
                            <li>Multi Country Tours</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="las la-globe"></i>
                    Multi-Country Adventures
                </div>
                <h1 class="hero-title">Multi Country Tours</h1>
                <p class="hero-subtitle">Discover extraordinary multi-country adventures across magnificent destinations</p>
            </div>
        </div>
    </section>

    <!-- Description & Filters Section -->
    <section class="filters-section">
        <div class="container">
            <div class="content-description fade-in-up">
                <p>Enjoy the diversity of cultures and best attractions in the Middle East region, Europe, Asia, Africa, and
                    more through picking one of our middle east tours. Integrate the exquisite Ancient Egyptian civilization
                    with colorful India, Modern Dubai, Wild Africa, historical Jordan, the blue pearl Morocco, the real
                    Turkey, and beyond to make the best use of Multi-country Tours. Explore the wonders of different
                    countries and witness the diversity of history and cultures all in one package. It's time to explore and
                    embrace diversity. Book your Middle East Tour Now!</p>
            </div>

            <div class="filters-container fade-in-up">
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="las la-dollar-sign"></i>
                        Filter by Price
                    </label>
                    <select onchange="window.location.href=this.value" class="filter-select">
                        <option value="" selected>All Prices</option>
                        <option value="?pricerange=1">Less than $1,500</option>
                        <option value="?pricerange=2">$1,500 - $2,500</option>
                        <option value="?pricerange=3">$2,500+</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">
                        <i class="las la-calendar"></i>
                        Filter by Duration
                    </label>
                    <select onchange="window.location.href=this.value" class="filter-select">
                        <option value="" selected>All Durations</option>
                        <option value="?days=1">Less than 10 Days</option>
                        <option value="?days=2">10 to 20 Days</option>
                        <option value="?days=3">20+ Days</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">
                        <i class="las la-sort"></i>
                        Sort by
                    </label>
                    <select onchange="window.location.href=this.value" class="filter-select">
                        <option value="" selected>Default Order</option>
                        <option value="?sort=price">Sort by Price</option>
                        <option value="?sort=duration">Sort by Duration</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Tours Section -->
    <section class="tours-section">
        <div class="container">
            <div class="tours-grid" id="toursGrid">
                <!-- Uganda Tanzania Wildlife Tour -->
                <div class="tour-card fade-in-up">
                    <a href="/African-Safari/package/uganda-tanzania-wildlife-tour" class="tour-image">
                        <img src="../../../images/15985702561stock-photo-120592579.jpg"
                            alt="Uganda Tanzania Wildlife Tour Package" loading="lazy">
                        <div class="price-badge">from $200</div>
                    </a>
                    <div class="tour-content">
                        <h3 class="tour-title">
                            <a href="/African-Safari/package/uganda-tanzania-wildlife-tour">Uganda Tanzania Wildlife Tour
                                Package</a>
                        </h3>
                        <div class="tour-meta">
                            <div class="meta-item">
                                <i class="las la-calendar"></i>
                                <span>9 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="las la-tag"></i>
                                <span>Private</span>
                            </div>
                        </div>
                        <p class="tour-description">
                            Explore the amazing nature of Africa in two of its must-visit countries: Uganda and Tanzania,
                            with our 9 days safari tour where you will experience the flora and fauna of Serengeti National
                            Park, Bwindi Impenetrable National Park and more.
                        </p>
                        <div class="tour-action">
                            <a href="/African-Safari/package/uganda-tanzania-wildlife-tour" class="view-btn">
                                View Trip <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kenya Uganda Safari Tour -->
                <div class="tour-card fade-in-up">
                    <a href="/African-Safari/package/wonders-of-uganda-safari" class="tour-image">
                        <img src="../../../images/15985688471Kenya.jpg" alt="7 Days Kenya Uganda Safari Tour"
                            loading="lazy">
                        <div class="price-badge">from $200</div>
                    </a>
                    <div class="tour-content">
                        <h3 class="tour-title">
                            <a href="/African-Safari/package/wonders-of-uganda-safari">7 Days Kenya Uganda Safari Tour</a>
                        </h3>
                        <div class="tour-meta">
                            <div class="meta-item">
                                <i class="las la-calendar"></i>
                                <span>7 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="las la-tag"></i>
                                <span>Private</span>
                            </div>
                        </div>
                        <p class="tour-description">
                            With all that Uganda and Kenya has to offer, Kenya Uganda Safari brings them all to you. Enjoy
                            the roaring wildlife of Kenya visiting Maasai Mara National Reserve and trek the national parks
                            for Chimps and Gorillas, like the Bwindi Impenetrable National park.
                        </p>
                        <div class="tour-action">
                            <a href="/African-Safari/package/wonders-of-uganda-safari" class="view-btn">
                                View Trip <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tanzania & Zanzibar -->
                <div class="tour-card fade-in-up">
                    <a href="/African-Safari/package/best-of-tanzania-and-zanzibar" class="tour-image">
                        <img src="../../../images/15985495411229MeliaZanzibar-Jetty-Entrance-_2_.jpeg"
                            alt="Best of Tanzania & Zanzibar Package" loading="lazy">
                        <div class="price-badge">from $200</div>
                    </a>
                    <div class="tour-content">
                        <h3 class="tour-title">
                            <a href="/African-Safari/package/best-of-tanzania-and-zanzibar">Best of Tanzania & Zanzibar
                                Package</a>
                        </h3>
                        <div class="tour-meta">
                            <div class="meta-item">
                                <i class="las la-calendar"></i>
                                <span>11 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="las la-tag"></i>
                                <span>Private</span>
                            </div>
                        </div>
                        <p class="tour-description">
                            Habari and Jambo! Welcome to your Tanzania and Zanzibar Safari. A once in a life time trip with
                            natural diversity like no other. Explore The Serengeti and Selous Reserves, the snow covered
                            Kilimanjaro and the spice island of Zanzibar.
                        </p>
                        <div class="tour-action">
                            <a href="/African-Safari/package/best-of-tanzania-and-zanzibar" class="view-btn">
                                View Trip <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Turkey and Greece Tour -->
                <div class="tour-card fade-in-up">
                    <a href="/Turkey/package/turkey-greece-tour" class="tour-image">
                        <img src="../../../images/15984474711Parthenon.jpg" alt="Taste of Turkey and Greece Tour"
                            loading="lazy">
                        <div class="price-badge">from $1350</div>
                    </a>
                    <div class="tour-content">
                        <h3 class="tour-title">
                            <a href="/Turkey/package/turkey-greece-tour">Taste of Turkey and Greece Tour</a>
                        </h3>
                        <div class="tour-meta">
                            <div class="meta-item">
                                <i class="las la-calendar"></i>
                                <span>7 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="las la-tag"></i>
                                <span>Private</span>
                            </div>
                        </div>
                        <p class="tour-description">
                            Your Dream Trip is Now Real. Enjoy Turkey Greece Tour between Istanbul and Athens. Extra Fun and
                            Excitement in 7 Days in Greece Turkey Travel Packages with Luxor and Aswan Travel.
                        </p>
                        <div class="tour-action">
                            <a href="/Turkey/package/turkey-greece-tour" class="view-btn">
                                View Trip <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Best Tour of Turkey and Greece -->
                <div class="tour-card fade-in-up">
                    <a href="/Turkey/package/turkey-and-greece-itinerary" class="tour-image">
                        <img src="../../../images/15984480920bestgreekislands-hero.jpg"
                            alt="Best Tour of Turkey and Greece" loading="lazy">
                        <div class="price-badge">from $1697</div>
                    </a>
                    <div class="tour-content">
                        <h3 class="tour-title">
                            <a href="/Turkey/package/turkey-and-greece-itinerary">Best Tour of Turkey and Greece</a>
                        </h3>
                        <div class="tour-meta">
                            <div class="meta-item">
                                <i class="las la-calendar"></i>
                                <span>9 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="las la-tag"></i>
                                <span>Private</span>
                            </div>
                        </div>
                        <p class="tour-description">
                            Enjoy an amazing 9 Days in Turkey and Greece Itinerary Visiting Istanbul, Cappadocia, Athens,
                            and Santorini. Exploring the significant attractions including Blue Mosque, Topkapi Palace,
                            Gallipoli Battlefields, and more.
                        </p>
                        <div class="tour-action">
                            <a href="/Turkey/package/turkey-and-greece-itinerary" class="view-btn">
                                View Trip <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Oman & Dubai Tour -->
                <div class="tour-card fade-in-up">
                    <a href="/Oman/package/Oman-and-Dubai-Tour-Package" class="tour-image">
                        <img src="../../../images/159820735611_2994_02.jpg" alt="8 Days Oman & Dubai Tour Package"
                            loading="lazy">
                        <div class="price-badge">from $1750</div>
                    </a>
                    <div class="tour-content">
                        <h3 class="tour-title">
                            <a href="/Oman/package/Oman-and-Dubai-Tour-Package">8 Days Oman & Dubai Tour Package</a>
                        </h3>
                        <div class="tour-meta">
                            <div class="meta-item">
                                <i class="las la-calendar"></i>
                                <span>8 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="las la-tag"></i>
                                <span>Private</span>
                            </div>
                        </div>
                        <p class="tour-description">
                            Discover the beauty of Oman and the marvels of modern Dubai in 8 day Oman Dubai Tour Package.
                            Experience the historical sights of Sultanate of Oman and the most amazing attractions and then
                            fly to Dubai to explore all of it.
                        </p>
                        <div class="tour-action">
                            <a href="/Oman/package/Oman-and-Dubai-Tour-Package" class="view-btn">
                                View Trip <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Morocco and Tunisia Tour -->
                <div class="tour-card fade-in-up">
                    <a href="/Morocco/package/morocco-and-tunisia-tour" class="tour-image">
                        <img src="../../../images/15982261141great-mosque-kairouan-1200.jpg"
                            alt="Morocco and Tunisia Tour Package" loading="lazy">
                        <div class="price-badge">from $2199</div>
                    </a>
                    <div class="tour-content">
                        <h3 class="tour-title">
                            <a href="/Morocco/package/morocco-and-tunisia-tour">Morocco and Tunisia Tour Package</a>
                        </h3>
                        <div class="tour-meta">
                            <div class="meta-item">
                                <i class="las la-calendar"></i>
                                <span>15 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="las la-tag"></i>
                                <span>Private</span>
                            </div>
                        </div>
                        <p class="tour-description">
                            We already created for you a special combined tour package between Morocco and Tunisia, the most
                            magnificent counties in Africa to experience the history and importance of Morocco and the
                            beauty of Tunisia.
                        </p>
                        <div class="tour-action">
                            <a href="/Morocco/package/morocco-and-tunisia-tour" class="view-btn">
                                View Trip <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 16 Days Tour to Turkey and Greece -->
                <div class="tour-card fade-in-up">
                    <a href="/Turkey/package/trip-to-turkey-and-greece" class="tour-image">
                        <img src="../../../images/15984486931istanbul-turkey.jpg" alt="16 Days Tour to Turkey and Greece"
                            loading="lazy">
                        <div class="price-badge">from $2410</div>
                    </a>
                    <div class="tour-content">
                        <h3 class="tour-title">
                            <a href="/Turkey/package/trip-to-turkey-and-greece">16 Days Tour to Turkey and Greece</a>
                        </h3>
                        <div class="tour-meta">
                            <div class="meta-item">
                                <i class="las la-calendar"></i>
                                <span>16 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="las la-tag"></i>
                                <span>Private</span>
                            </div>
                        </div>
                        <p class="tour-description">
                            Discover the Glory of Greece and Turkey Packages and Spend 16 Days exploring the Ancient Cities
                            of Istanbul, Cappadocia, Pamukkale, Kusadasi, Athens, Mykonos and Santorini with best
                            attractions.
                        </p>
                        <div class="tour-action">
                            <a href="/Turkey/package/trip-to-turkey-and-greece" class="view-btn">
                                View Trip <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Road from Petra to Cairo -->
                <div class="tour-card fade-in-up">
                    <a href="/Jordan/package/Petra-Egypt-Travel-Deals-Packages" class="tour-image">
                        <img src="../../../images/159759719405f1aca145bf3c.png" alt="8 Day Road from Petra to Cairo"
                            loading="lazy">
                        <div class="price-badge">from $2449</div>
                    </a>
                    <div class="tour-content">
                        <h3 class="tour-title">
                            <a href="/Jordan/package/Petra-Egypt-Travel-Deals-Packages">8 Day Road from Petra to Cairo</a>
                        </h3>
                        <div class="tour-meta">
                            <div class="meta-item">
                                <i class="las la-calendar"></i>
                                <span>8 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="las la-tag"></i>
                                <span>Private</span>
                            </div>
                        </div>
                        <p class="tour-description">
                            This Egypt and Jordan Tour Packages will make you speechless after visiting the ancient city of
                            Petra and Cairo witness the beauty of middle east. See the amazing Roman monuments in Jordan and
                            the Great Giza Pyramids.
                        </p>
                        <div class="tour-action">
                            <a href="/Jordan/package/Petra-Egypt-Travel-Deals-Packages" class="view-btn">
                                View Trip <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Egypt and Jordan Short Package -->
                <div class="tour-card fade-in-up">
                    <a href="/Egypt/package/Egypt-and-Jordan-Short-Package" class="tour-image">
                        <img src="../../../images/15975028951stock-photo-129637541.jpg"
                            alt="Egypt and Jordan Short Package" loading="lazy">
                        <div class="price-badge">from $2491</div>
                    </a>
                    <div class="tour-content">
                        <h3 class="tour-title">
                            <a href="/Egypt/package/Egypt-and-Jordan-Short-Package">Egypt and Jordan Short Package</a>
                        </h3>
                        <div class="tour-meta">
                            <div class="meta-item">
                                <i class="las la-calendar"></i>
                                <span>7 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="las la-tag"></i>
                                <span>Private</span>
                            </div>
                        </div>
                        <p class="tour-description">
                            Enjoy the beauty of Egypt and Jordan in a unique experience takes you back through history in 7
                            days, starting from Cairo with its ancient and Islamic sightseeings before heading to Jordan to
                            see the legendary 'red-rose city' of Petra.
                        </p>
                        <div class="tour-action">
                            <a href="/Egypt/package/Egypt-and-Jordan-Short-Package" class="view-btn">
                                View Trip <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 10 Day Egypt and Jordan Tours -->
                <div class="tour-card fade-in-up">
                    <a href="/Jordan/package/Travel-Packages-to-Jordan-and-Egypt" class="tour-image">
                        <img src="../../../images/15975965080camel.jpg" alt="10 Day Egypt and Jordan Tours"
                            loading="lazy">
                        <div class="price-badge">from $3292</div>
                    </a>
                    <div class="tour-content">
                        <h3 class="tour-title">
                            <a href="/Jordan/package/Travel-Packages-to-Jordan-and-Egypt">10 Day Egypt and Jordan Tours</a>
                        </h3>
                        <div class="tour-meta">
                            <div class="meta-item">
                                <i class="las la-calendar"></i>
                                <span>10 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="las la-tag"></i>
                                <span>Private</span>
                            </div>
                        </div>
                        <p class="tour-description">
                            Experience a marvelous 9-night Jordan Egypt Travel Package discovering Egypt, enjoying 5-star
                            accommodation with a panoramic view of the river Nile through Luxor and Aswan Nile Cruise,
                            Petra, Dead Sea, and More.
                        </p>
                        <div class="tour-action">
                            <a href="/Jordan/package/Travel-Packages-to-Jordan-and-Egypt" class="view-btn">
                                View Trip <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 12 Day Egypt Nile Cruise and Jordan Tour -->
                <div class="tour-card fade-in-up">
                    <a href="/Egypt/package/12-Day-Nile-Cruise-and-Jordan-Tour" class="tour-image">
                        <img src="../../../images/15975079391stock-photo-234981517.jpg"
                            alt="12 Day Egypt Nile Cruise and Jordan Tour" loading="lazy">
                        <div class="price-badge">from $3514</div>
                    </a>
                    <div class="tour-content">
                        <h3 class="tour-title">
                            <a href="/Egypt/package/12-Day-Nile-Cruise-and-Jordan-Tour">12 Day Egypt Nile Cruise and Jordan
                                Tour</a>
                        </h3>
                        <div class="tour-meta">
                            <div class="meta-item">
                                <i class="las la-calendar"></i>
                                <span>12 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="las la-tag"></i>
                                <span>Private</span>
                            </div>
                        </div>
                        <p class="tour-description">
                            Cairo and Petra Tours: Immerse yourself in the ancient wonders of Cairo; relax with a Nile
                            cruise and explore the historical sites of Jordan. This 12 day Nile Cruise and Jordan tour is
                            ideal for anyone longing to experience the best of both worlds.
                        </p>
                        <div class="tour-action">
                            <a href="/Egypt/package/12-Day-Nile-Cruise-and-Jordan-Tour" class="view-btn">
                                View Trip <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 12 Day Highlights of Egypt Jordan Tour -->
                <div class="tour-card fade-in-up">
                    <a href="/Jordan/package/Jordan-Holidays-Tours-and-Egypt-Nile-Cruise" class="tour-image">
                        <img src="../../../images/159759786014afa23aacb6e4d0337dac29e6557420d.jpg"
                            alt="12 Day Highlights of Egypt Jordan Tour" loading="lazy">
                        <div class="price-badge">from $3585</div>
                    </a>
                    <div class="tour-content">
                        <h3 class="tour-title">
                            <a href="/Jordan/package/Jordan-Holidays-Tours-and-Egypt-Nile-Cruise">12 Day Highlights of
                                Egypt Jordan Tour</a>
                        </h3>
                        <div class="tour-meta">
                            <div class="meta-item">
                                <i class="las la-calendar"></i>
                                <span>12 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="las la-tag"></i>
                                <span>Private</span>
                            </div>
                        </div>
                        <p class="tour-description">
                            Are you looking for Tours of Jordan and Egypt? Explore this Fascinating Journey through a
                            combined Jordan and Egypt tour Package to get the mystery of Jordan and ancient Egypt with Nile
                            Cruise.
                        </p>
                        <div class="tour-action">
                            <a href="/Jordan/package/Jordan-Holidays-Tours-and-Egypt-Nile-Cruise" class="view-btn">
                                View Trip <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 10 Day Egypt and Jordan Travel Package -->
                <div class="tour-card fade-in-up">
                    <a href="/Egypt/package/10-Day-Jordan-Egypt-Travel-Package" class="tour-image">
                        <img src="../../../images/15975073731Queen Hatshepsut.jpg"
                            alt="10 Day Egypt and Jordan Travel Package" loading="lazy">
                        <div class="price-badge">from $3792</div>
                    </a>
                    <div class="tour-content">
                        <h3 class="tour-title">
                            <a href="/Egypt/package/10-Day-Jordan-Egypt-Travel-Package">10 Day Egypt and Jordan Travel
                                Package</a>
                        </h3>
                        <div class="tour-meta">
                            <div class="meta-item">
                                <i class="las la-calendar"></i>
                                <span>10 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="las la-tag"></i>
                                <span>Private</span>
                            </div>
                        </div>
                        <p class="tour-description">
                            Make dreams come true with our epic 10-Day Jordan Egypt Travel Package. Enjoy VIP treatment and
                            unrivaled hospitality from the moment you arrive to the moment we bid you farewell.
                        </p>
                        <div class="tour-action">
                            <a href="/Egypt/package/10-Day-Jordan-Egypt-Travel-Package" class="view-btn">
                                View Trip <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 14 Day Egypt and Jordan Highlights Tour -->
                <div class="tour-card fade-in-up">
                    <a href="/Egypt/package/14-Day-Egypt-and-Jordan-Highlights-Tour" class="tour-image">
                        <img src="../../../images/159750853115f1ad286a1aff.png"
                            alt="14 Day Egypt and Jordan Highlights Tour" loading="lazy">
                        <div class="price-badge">from $4292</div>
                    </a>
                    <div class="tour-content">
                        <h3 class="tour-title">
                            <a href="/Egypt/package/14-Day-Egypt-and-Jordan-Highlights-Tour">14 Day Egypt and Jordan
                                Highlights Tour</a>
                        </h3>
                        <div class="tour-meta">
                            <div class="meta-item">
                                <i class="las la-calendar"></i>
                                <span>14 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="las la-tag"></i>
                                <span>Private</span>
                            </div>
                        </div>
                        <p class="tour-description">
                            A 14 day extravaganza: Egypt and Jordan highlights tour. In Cairo visit the Pyramids of Giza and
                            the Sphinx, and so much more. Transfer to Aswan, enjoy the ancient sites and then cruise to
                            Luxor.
                        </p>
                        <div class="tour-action">
                            <a href="/Egypt/package/14-Day-Egypt-and-Jordan-Highlights-Tour" class="view-btn">
                                View Trip <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 14 Day Pyramids, Petra and Jerusalem Package -->
                <div class="tour-card fade-in-up">
                    <a href="/Jordan/package/Jordan-Jerusalem-and-Egypt-Package-Holidays" class="tour-image">
                        <img src="../../../images/15975997761stock-photo-72715393.jpg"
                            alt="14 Day Pyramids, Petra and Jerusalem Package" loading="lazy">
                        <div class="price-badge">from $4385</div>
                    </a>
                    <div class="tour-content">
                        <h3 class="tour-title">
                            <a href="/Jordan/package/Jordan-Jerusalem-and-Egypt-Package-Holidays">14 Day Pyramids, Petra
                                and Jerusalem Package</a>
                        </h3>
                        <div class="tour-meta">
                            <div class="meta-item">
                                <i class="las la-calendar"></i>
                                <span>14 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="las la-tag"></i>
                                <span>Private</span>
                            </div>
                        </div>
                        <p class="tour-description">
                            Enjoy this tour to Egypt and Jordan including Cairo Petra Jerusalem Tour. Starting with the
                            glory of the ancient Egyptian Monuments and the Great Pyramids of Cheops, Chefren, and
                            Mykerinus.
                        </p>
                        <div class="tour-action">
                            <a href="/Jordan/package/Jordan-Jerusalem-and-Egypt-Package-Holidays" class="view-btn">
                                View Trip <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 15 Day Egypt and Jordan Tours -->
                <div class="tour-card fade-in-up">
                    <a href="/Egypt/package/15-Day-Egypt-and-Jordan-Trip-Cairo-Nile-Cruise-and-Dahab" class="tour-image">
                        <img src="../../../images/15975068441camel at pyramids.jpg" alt="15 Day Egypt and Jordan Tours"
                            loading="lazy">
                        <div class="price-badge">from $4392</div>
                    </a>
                    <div class="tour-content">
                        <h3 class="tour-title">
                            <a href="/Egypt/package/15-Day-Egypt-and-Jordan-Trip-Cairo-Nile-Cruise-and-Dahab">15 Day Egypt
                                and Jordan Tours</a>
                        </h3>
                        <div class="tour-meta">
                            <div class="meta-item">
                                <i class="las la-calendar"></i>
                                <span>15 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="las la-tag"></i>
                                <span>Private</span>
                            </div>
                        </div>
                        <p class="tour-description">
                            This 15 day Egypt and Jordan trip will allow you to experience the magic of Egypt and Jordan in
                            way you've never imagined. Explore the best sites in Cairo, including the Great Pyramids and
                            Sphinx.
                        </p>
                        <div class="tour-action">
                            <a href="/Egypt/package/15-Day-Egypt-and-Jordan-Trip-Cairo-Nile-Cruise-and-Dahab"
                                class="view-btn">
                                View Trip <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 15 Day Jordan Holidays and Egypt Nile Tours -->
                <div class="tour-card fade-in-up">
                    <a href="/Jordan/package/Jordan-Travel-Holidays-and-Egypt-Nile-Tours" class="tour-image">
                        <img src="../../../images/159759888315 Days 4 Nights Short Breaks.jpg"
                            alt="15 Day Jordan Holidays and Egypt Nile Tours" loading="lazy">
                        <div class="price-badge">from $4425</div>
                    </a>
                    <div class="tour-content">
                        <h3 class="tour-title">
                            <a href="/Jordan/package/Jordan-Travel-Holidays-and-Egypt-Nile-Tours">15 Day Jordan Holidays
                                and Egypt Nile Tours</a>
                        </h3>
                        <div class="tour-meta">
                            <div class="meta-item">
                                <i class="las la-calendar"></i>
                                <span>15 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="las la-tag"></i>
                                <span>Private</span>
                            </div>
                        </div>
                        <p class="tour-description">
                            Delight in Petra Egypt Tour and Enjoy the beauty of Egypt and Jordan in extraordinary tours
                            takes you back through history, Starting from Cairo with its ancient and Islamic sightseeings.
                        </p>
                        <div class="tour-action">
                            <a href="/Jordan/package/Jordan-Travel-Holidays-and-Egypt-Nile-Tours" class="view-btn">
                                View Trip <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

@section('js')
    <!-- JavaScript -->
    <script src="{{ request()->root() }}/website/js/new/jquery.min.js"></script>
    <script src="{{ request()->root() }}/website/js/new/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const offsetTop = target.offsetTop - 80;
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Enhanced card interactions
        document.querySelectorAll('.tour-card, .choose-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Loading optimization
        window.addEventListener('load', () => {
            document.body.style.opacity = '1';
            document.body.style.transition = 'opacity 0.3s ease';

            // Lazy load images
            const images = document.querySelectorAll('img[loading="lazy"]');
            images.forEach(img => {
                img.addEventListener('load', () => {
                    img.style.opacity = '1';
                });
            });
        });

        // Performance optimization for scroll events
        let ticking = false;

        function updateOnScroll() {
            ticking = false;
        }

        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(updateOnScroll);
                ticking = true;
            }
        });

        // Enhanced mobile experience
        if (window.innerWidth < 768) {
            document.querySelectorAll('.tour-card, .choose-card').forEach(card => {
                card.style.transition = 'transform 0.2s ease, box-shadow 0.2s ease';
            });
        }

        // Filter select enhancements
        document.querySelectorAll('.filter-select').forEach(select => {
            select.addEventListener('change', function() {
                // Add loading animation
                document.body.style.cursor = 'wait';
            });
        });
    </script>
@endsection
