@extends('website.layouts.master')

@section('title', 'Dubai Tours & Travel - Luxor and Aswan Travel')

@section('css')
    <style>
        /* Enhanced Hero Section with Responsive Heights */
        .hero-section {
            height: 60vh;
            min-height: 500px;
            max-height: 700px;
            background: linear-gradient(rgba(28, 50, 92, 0.5), rgba(26, 75, 102, 0.6)), url('https://www.luxorandaswan.com/../images/17224767310dubai.jpg');
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
                height: 65vh;
                max-height: 750px;
            }
        }

        /* Desktop (1200px - 1399px) */
        @media (max-width: 1399px) and (min-width: 1200px) {
            .hero-section {
                height: 60vh;
                min-height: 550px;
                max-height: 700px;
            }
        }

        /* Laptop (992px - 1199px) */
        @media (max-width: 1199px) and (min-width: 992px) {
            .hero-section {
                height: 55vh;
                min-height: 500px;
                max-height: 650px;
            }
        }

        /* Tablet Portrait (768px - 991px) */
        @media (max-width: 991px) and (min-width: 768px) {
            .hero-section {
                height: 50vh;
                min-height: 450px;
                max-height: 600px;
                background-attachment: scroll;
            }
        }

        /* Mobile Landscape (576px - 767px) */
        @media (max-width: 767px) and (min-width: 576px) {
            .hero-section {
                height: 45vh;
                min-height: 400px;
                max-height: 550px;
                background-attachment: scroll;
            }
        }

        /* Mobile Portrait (320px - 575px) */
        @media (max-width: 575px) {
            .hero-section {
                height: 40vh;
                min-height: 350px;
                max-height: 500px;
                background-attachment: scroll;
            }
        }

        @media (max-width: 480px) {
            .hero-section {
                height: 35vh;
                min-height: 300px;
                max-height: 450px;
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
                padding: 90px 20px 0;
            }
        }

        @media (max-width: 767px) {
            .hero-content {
                padding: 80px 15px 0;
            }
        }

        @media (max-width: 575px) {
            .hero-content {
                padding: 70px 15px 0;
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
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(197, 149, 91, 0.3);
        }

        @media (max-width: 575px) {
            .hero-badge {
                padding: 8px 20px;
                font-size: 0.9rem;
                margin-bottom: 20px;
            }
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            background: linear-gradient(135deg, #ffffff 0%, #f8f0e0 100%);
            -webkit-background-clip: text;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 30px;
            opacity: 0.95;
            font-weight: 300;
            letter-spacing: 1px;
        }

        @media (max-width: 767px) {
            .hero-subtitle {
                font-size: 1.1rem;
                margin-bottom: 25px;
            }
        }

        @media (max-width: 575px) {
            .hero-subtitle {
                font-size: 1rem;
                margin-bottom: 20px;
            }
        }

        /* Breadcrumb Section */
        .breadcrumb-section {
            background: var(--pearl-luxury);
            padding: 15px 0;
            border-bottom: 1px solid rgba(197, 149, 91, 0.2);
        }

        .breadcrumb-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .breadcrumb {
            background: transparent;
            margin: 0;
            padding: 0;
        }

        .breadcrumb-item {
            color: var(--primary-navy);
            font-size: 0.95rem;
        }

        .breadcrumb-item a {
            color: var(--primary-navy);
            text-decoration: none;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .breadcrumb-item a:hover {
            color: var(--rich-gold);
        }

        .breadcrumb-icon {
            font-size: 1.1rem;
            color: var(--rich-gold);
        }

        .breadcrumb-item.active {
            color: var(--rich-gold);
            font-weight: 600;
        }

        /* Overview Section */
        .overview-section {
            background: var(--pearl-luxury);
            padding: 60px 0;
        }

        .overview-content {
            background: white;
            border-radius: 25px;
            padding: 50px;
            box-shadow: var(--shadow-medium);
            border: 2px solid rgba(197, 149, 91, 0.1);
            transition: all 0.3s ease;
        }

        .overview-content:hover {
            box-shadow: var(--shadow-dramatic);
            border-color: rgba(197, 149, 91, 0.3);
        }

        .overview-title {
            font-family: 'Playfair Display', serif;
            color: var(--primary-navy);
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
        }

        .overview-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--gradient-gold);
            border-radius: 2px;
        }

        .overview-text {
            color: var(--charcoal-deep);
            line-height: 1.8;
            font-size: 1.1rem;
        }

        .overview-text p {
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .overview-section {
                padding: 40px 0;
            }

            .overview-content {
                padding: 30px;
            }

            .overview-title {
                font-size: 1.8rem;
            }

            .overview-text {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .overview-content {
                padding: 25px;
            }

            .overview-title {
                font-size: 1.6rem;
            }
        }

        /* Card Area - Matching Homepage Style */
        .card-area {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 80px 0;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
            color: var(--primary-navy);
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: var(--gradient-gold);
            border-radius: 2px;
        }

        .section-subtitle {
            color: var(--warm-gray);
            font-size: 1.2rem;
            text-align: center;
            margin-bottom: 50px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .card-area {
                padding: 60px 0;
            }

            .section-subtitle {
                font-size: 1.1rem;
                margin-bottom: 40px;
            }
        }

        @media (max-width: 480px) {
            .card-area {
                padding: 50px 0;
            }

            .section-subtitle {
                font-size: 1rem;
                margin-bottom: 30px;
            }
        }

        /* Cruise Card - Enhanced */
        .cruise-card {
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: var(--shadow-medium);
            transition: all 0.4s ease;
            border: 2px solid rgba(197, 149, 91, 0.1);
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .cruise-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-dramatic);
            border-color: var(--rich-gold);
        }

        .cruise-image {
            position: relative;
            overflow: hidden;
            aspect-ratio: 4/3;
            flex-shrink: 0;
            background: linear-gradient(45deg, var(--primary-navy), var(--rich-gold));
        }

        .cruise-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.6s ease;
            opacity: 0.9;
        }

        .cruise-card:hover .cruise-img {
            transform: scale(1.1);
            opacity: 1;
        }

        .cruise-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(28, 50, 92, 0.7) 0%, rgba(197, 149, 91, 0.6) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.4s ease;
            backdrop-filter: blur(2px);
        }

        .cruise-card:hover .cruise-overlay {
            opacity: 1;
        }

        .overlay-content {
            color: white;
            text-align: center;
            transform: translateY(20px);
            transition: transform 0.4s ease;
        }

        .cruise-card:hover .overlay-content {
            transform: translateY(0);
        }

        .overlay-content i {
            display: block;
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .overlay-content span {
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .cruise-content {
            padding: 30px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .cruise-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--primary-navy);
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .cruise-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .cruise-title a:hover {
            color: var(--rich-gold);
        }

        .cruise-description {
            margin-bottom: 20px;
            flex: 1;
        }

        .cruise-description p {
            color: var(--warm-gray);
            line-height: 1.6;
            margin: 0;
            font-size: 1rem;
        }

        .cruise-footer {
            padding-top: 20px;
            border-top: 1px solid rgba(197, 149, 91, 0.2);
            margin-top: auto;
        }

        .btn-cruise {
            background: var(--gradient-gold);
            color: var(--primary-navy);
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.4s ease;
            box-shadow: var(--shadow-gold);
            width: 100%;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .btn-cruise::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-cruise:hover::before {
            left: 100%;
        }

        .btn-cruise:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(197, 149, 91, 0.4);
            color: var(--primary-navy);
        }

        @media (max-width: 768px) {
            .cruise-content {
                padding: 25px;
            }

            .cruise-title {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 480px) {
            .cruise-content {
                padding: 20px;
            }

            .cruise-title {
                font-size: 1.2rem;
            }

            .cruise-description p {
                font-size: 0.95rem;
            }
        }

        /* Why Choose Section - Matching Homepage */
        .why-choose-section {
            background: var(--pearl-luxury);
            padding: 80px 0;
            position: relative;
            border-top: 1px solid rgba(197, 149, 91, 0.2);
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

        .choose-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--gradient-gold);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .choose-card:hover::before {
            transform: scaleX(1);
        }

        .choose-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-dramatic);
            border-color: var(--rich-gold);
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
            transform: scale(1.1);
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

        /* CTA Section - Matching Homepage */
        .luxury-cta-section {
            background: var(--gradient-hero);
            padding: 70px 0;
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
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 25px;
            padding: 50px;
            border: 1px solid rgba(197, 149, 91, 0.3);
            box-shadow: var(--shadow-dramatic);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 30px;
        }

        .cta-icon-container {
            width: 80px;
            height: 80px;
            background: var(--gradient-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: var(--primary-navy);
            box-shadow: var(--shadow-gold);
            flex-shrink: 0;
        }

        .cta-content-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 30px;
        }

        .cta-text-content {
            flex: 1;
            min-width: 300px;
        }

        .cta-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 15px;
        }

        .cta-subtitle {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 20px;
        }

        .trust-features {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .trust-feature {
            display: flex;
            align-items: center;
            gap: 8px;
            color: white;
            font-size: 0.95rem;
        }

        .trust-feature i {
            color: var(--rich-gold);
            font-size: 1.1rem;
        }

        .luxury-cta-btn {
            background: var(--gradient-gold);
            color: var(--primary-navy);
            padding: 16px 35px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-gold);
            white-space: nowrap;
        }

        .luxury-cta-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(197, 149, 91, 0.4);
            color: var(--primary-navy);
        }

        /* Mobile WhatsApp Button */
        .fixed-mobile-btn {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            z-index: 1000;
            display: none;
        }

        @media (max-width: 768px) {
            .fixed-mobile-btn {
                display: block;
            }
        }

        .mobile-enquiry-btn {
            background: #25d366;
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .mobile-enquiry-btn:hover {
            background: #20b859;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
            color: white;
        }

        .mobile-enquiry-btn i {
            font-size: 1.3rem;
        }

        @media (max-width: 768px) {
            .luxury-cta-content {
                padding: 40px;
            }

            .cta-title {
                font-size: 1.8rem;
            }

            .cta-content-wrapper {
                flex-direction: column;
                text-align: center;
            }

            .trust-features {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .luxury-cta-section {
                padding: 50px 0;
            }

            .luxury-cta-content {
                padding: 30px;
            }

            .cta-title {
                font-size: 1.6rem;
            }

            .cta-subtitle {
                font-size: 1rem;
            }

            .trust-feature {
                font-size: 0.9rem;
            }

            .luxury-cta-btn {
                padding: 14px 25px;
                font-size: 1rem;
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
    <!-- End Google Tag Manager -->
    <section class="breadcrumb-section">
        <div class="container">
            <div class="breadcrumb-container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="https://www.luxorandaswan.com/">
                                <i class="la la-home breadcrumb-icon"></i>Home
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Travel Blog
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <!-- Blog Hero Section -->
    <section class="blog-hero">
        <div class="container">
            <div class="blog-content">
                <div class="blog-badge">
                    <i class="la la-newspaper"></i> Travel Insights
                </div>
                <h1 class="blog-title">Luxor and Aswan Travel Blog</h1>
                <p class="blog-subtitle">
                    Discover the wonders of ancient Egypt through our expert travel insights,
                    destination guides, and cultural explorations along the magnificent Nile.
                </p>
            </div>
        </div>
    </section>

    <!-- Blog Content Area -->
    <section class="blog-card-area">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a href="/blog/nile-valley-information/best-month-for-a-nile-river-cruise">
                                        <img src="/../images/17519041161vessels-head2.jpg"
                                            alt="Best Month for a Nile River Cruise: When to Go and Why" class="blog-img"
                                            loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a href="/blog/nile-valley-information/best-month-for-a-nile-river-cruise">
                                            Best Month for a Nile River Cruise: When to Go and Why </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Mon, 07 Jul 2025</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>Discover the ideal time to embark on a breathtaking Nile River cruise and immerse
                                            yourself in the rich history and stunn</p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/nile-valley-information/best-month-for-a-nile-river-cruise"
                                            class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a
                                        href="/blog/nile-valley-information/luxury-nile-cruise-a-journey-of-opulence-and-history">
                                        <img src="/../images/17289450971AdobeStock_485657585.jpg"
                                            alt="Luxury Nile Cruise: A Journey of Opulence and History" class="blog-img"
                                            loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a
                                            href="/blog/nile-valley-information/luxury-nile-cruise-a-journey-of-opulence-and-history">
                                            Luxury Nile Cruise: A Journey of Opulence and History </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Mon, 14 Oct 2024</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>Luxury Nile Cruise: A Journey of Opulence and History
                                        </p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/nile-valley-information/luxury-nile-cruise-a-journey-of-opulence-and-history"
                                            class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a href="/blog/egypt-travel-guide/what-is-the-best-way-to-see-egypt-in-9-days">
                                        <img src="/../images/17237245321pyramids-of-egypt2.jpg"
                                            alt="What is the best way to see Egypt in 9 days?" class="blog-img"
                                            loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a href="/blog/egypt-travel-guide/what-is-the-best-way-to-see-egypt-in-9-days">
                                            What is the best way to see Egypt in 9 days? </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Thu, 15 Aug 2024</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>Discover the ultimate 9-day Egypt itinerary that combines the wonders of Cairo,
                                            Luxor, and Aswan with a luxurious Nile R</p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/egypt-travel-guide/what-is-the-best-way-to-see-egypt-in-9-days"
                                            class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a
                                        href="/blog/nile-valley-information/exploring-ancient-treasures-top-attractions-to-visit-on-a-nile-river-cruise">
                                        <img src="/../images/16882975301MS-Amwaj-cruise2.jpg"
                                            alt="Exploring Ancient Treasures: Top Attractions to Visit on a Nile River Cruise"
                                            class="blog-img" loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a
                                            href="/blog/nile-valley-information/exploring-ancient-treasures-top-attractions-to-visit-on-a-nile-river-cruise">
                                            Exploring Ancient Treasures: Top Attractions to Visit on a Nile River Cruise
                                        </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Sun, 02 Jul 2023</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>Embark on a Nile River cruise and discover Egypt&amp;#39;s ancient treasures.
                                            Explore the Valley of the Kings, marvel at the</p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/nile-valley-information/exploring-ancient-treasures-top-attractions-to-visit-on-a-nile-river-cruise"
                                            class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a href="/blog/aswan-attraction/interesting-facts-abu-simbel-temple">
                                        <img src="/../images/16804026371abu-simbel.jpg"
                                            alt="Interesting Facts Abu Simbel Temple" class="blog-img" loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a href="/blog/aswan-attraction/interesting-facts-abu-simbel-temple">
                                            Interesting Facts Abu Simbel Temple </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Sun, 02 Apr 2023</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>Discover fascinating facts about Abu Simbel Temple, a UNESCO World Heritage Site
                                            in Egypt that boasts incredible history</p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/aswan-attraction/interesting-facts-abu-simbel-temple"
                                            class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a href="/blog/luxor-attraction/luxor-temple-the-ancient-wonder-of-egypt">
                                        <img src="/../images/168040185818af02600-a8f9-11ec-ad74-ab832bebcff9-AdobeStock_488004532-2.jpg"
                                            alt="Luxor Temple: The Ancient Wonder of Egypt" class="blog-img"
                                            loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a href="/blog/luxor-attraction/luxor-temple-the-ancient-wonder-of-egypt">
                                            Luxor Temple: The Ancient Wonder of Egypt </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Sun, 02 Apr 2023</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>Discover the history and significance of Luxor Temple, one of Ancient
                                            Egypt&amp;#39;s grandest temple complexes, built by th</p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/luxor-attraction/luxor-temple-the-ancient-wonder-of-egypt"
                                            class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a href="/blog/luxor-attraction/things-to-do-in-luxor">
                                        <img src="/../images/1680401166125.jpg"
                                            alt="Top Things to Do in Luxor, Egypt: A Guide to Exploring Ancient Temples and Tombs"
                                            class="blog-img" loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a href="/blog/luxor-attraction/things-to-do-in-luxor">
                                            Top Things to Do in Luxor, Egypt: A Guide to Exploring Ancient Temples and Tombs
                                        </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Sun, 02 Apr 2023</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>Explore the top things to do in Luxor, Egypt, including the Karnak Temple
                                            Complex, Valley of the Kings, Luxor Temple, Co</p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/luxor-attraction/things-to-do-in-luxor" class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a href="/blog/cairo-attractions/top-tourist-attractions-in-egypt-cairo">
                                        <img src="/../images/16803968351spencer-davis-ONVA6s03hg8-unsplash-scaled.jpg"
                                            alt="Discover the Best Tourist Attractions in Cairo" class="blog-img"
                                            loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a href="/blog/cairo-attractions/top-tourist-attractions-in-egypt-cairo">
                                            Discover the Best Tourist Attractions in Cairo </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Sun, 02 Apr 2023</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>Discover the top tourist attractions in Cairo, Egypt, including the Great
                                            Pyramids of Giza, Sphinx, Egyptian Museum, and</p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/cairo-attractions/top-tourist-attractions-in-egypt-cairo"
                                            class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a href="/blog/egyptian-history/egyptian-symbol-of-life">
                                        <img src="/../images/167628991312.jpg" alt="Egyptian Symbol of Life"
                                            class="blog-img" loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a href="/blog/egyptian-history/egyptian-symbol-of-life">
                                            Egyptian Symbol of Life </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Mon, 13 Feb 2023</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>Discover the rich history and cultural significance of the Egyptian symbol of
                                            life, the ankh. Explore its use as a symbo</p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/egyptian-history/egyptian-symbol-of-life" class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a href="/blog/aswan-attraction/top-attractions-to-visit-in-aswan">
                                        <img src="/../images/16736998811philae_temple_aswan2.jpg"
                                            alt="Top Attractions to visit in Aswan" class="blog-img" loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a href="/blog/aswan-attraction/top-attractions-to-visit-in-aswan">
                                            Top Attractions to visit in Aswan </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Sat, 14 Jan 2023</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>Discover Aswan top tourist attractions, places to visit in Aswan, places to go in
                                            Aswan, and the historical sites in thi</p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/aswan-attraction/top-attractions-to-visit-in-aswan"
                                            class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a href="/blog/luxor-attraction/guide-to-the-top-luxor-attractions-in-egypt">
                                        <img src="/../images/16605833081egypt-luxor-temple-of-deir-al-bahri.jpg"
                                            alt="Guide to the Top Luxor Attractions in Egypt" class="blog-img"
                                            loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a href="/blog/luxor-attraction/guide-to-the-top-luxor-attractions-in-egypt">
                                            Guide to the Top Luxor Attractions in Egypt </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Mon, 15 Aug 2022</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>Discover Luxor tourist attractions, places to visit in Luxor, places to go in
                                            Luxor, and the historical sites in this an</p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/luxor-attraction/guide-to-the-top-luxor-attractions-in-egypt"
                                            class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a href="/blog/jordan-best-travel-guide/activities-and-things-to-do-in-jordan">
                                        <img src="/../images/16597890471jordan-top-attractions-petra.jpg"
                                            alt="Top Activities and Things to Do in Jordan" class="blog-img"
                                            loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a href="/blog/jordan-best-travel-guide/activities-and-things-to-do-in-jordan">
                                            Top Activities and Things to Do in Jordan </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Sat, 06 Aug 2022</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>Jordan is every travelers dream introduction to the Middle East. The destination
                                            gets travelers up close to world wonder</p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/jordan-best-travel-guide/activities-and-things-to-do-in-jordan"
                                            class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a href="/blog/luxor-attraction/the-ramesseum">
                                        <img src="/../images/1658765694124.jpg" alt="The Ramesseum - Luxor"
                                            class="blog-img" loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a href="/blog/luxor-attraction/the-ramesseum">
                                            The Ramesseum - Luxor </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Mon, 25 Jul 2022</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>This temple was dedicated to Ramesses II and a testament to his power and
                                            influence and it was meant to be the greatest </p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/luxor-attraction/the-ramesseum" class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a href="/blog/egypt-travel-guide/aswan">
                                        <img src="/../images/16587153061upper-egypt-aswan-dams-philae-temple-isis-20570924.jpg"
                                            alt="Aswan, Upper Egypt" class="blog-img" loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a href="/blog/egypt-travel-guide/aswan">
                                            Aswan, Upper Egypt </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Mon, 25 Jul 2022</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>Aswan is the third Biggest City in Egypt and it`s located on the South of Egypt,
                                            used to be called during the Ancient Eg</p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/egypt-travel-guide/aswan" class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a href="/blog/egypt-travel-guide/3-reasons-to-tour-the-jamaican-blue-mountains">
                                        <img src="/../images/1660566729122233.jpg"
                                            alt="3 Reasons to Tour the Jamaican Blue Mountains" class="blog-img"
                                            loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a href="/blog/egypt-travel-guide/3-reasons-to-tour-the-jamaican-blue-mountains">
                                            3 Reasons to Tour the Jamaican Blue Mountains </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Mon, 28 Mar 2022</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>Most probably you heard of the Jamaican Blue Mountains because of their great
                                            coffee. But did you know that you could ac</p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/egypt-travel-guide/3-reasons-to-tour-the-jamaican-blue-mountains"
                                            class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a href="/blog/aswan-attraction/aswan-high-dam">
                                        <img src="/../images/1659567752133334.jpg" alt="Aswan High Dam" class="blog-img"
                                            loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a href="/blog/aswan-attraction/aswan-high-dam">
                                            Aswan High Dam </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Tue, 06 Aug 2019</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>Egypt, Aswan High Dam located near Aswan, the world famous Aswan Dam was an
                                            engineering miracle when it was built in the</p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/aswan-attraction/aswan-high-dam" class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a href="/blog/cairo-attractions/memphis-egypt">
                                        <img src="/../images/165956763610a6141af4c148daad104b0bee1373a12.jpg"
                                            alt="Memphis Egypt" class="blog-img" loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a href="/blog/cairo-attractions/memphis-egypt">
                                            Memphis Egypt </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Tue, 06 Aug 2019</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>Memphis Egypt was founded in 1st Dynasty by King Narmar. Learn more about ancient
                                            Egypt&amp;#39;s capital! Memphis was the f</p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/cairo-attractions/memphis-egypt" class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a href="/blog/cairo-attractions/islamic-art-museum">
                                        <img src="/../images/165956747811.jpg" alt="Islamic Art Museum" class="blog-img"
                                            loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a href="/blog/cairo-attractions/islamic-art-museum">
                                            Islamic Art Museum </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Tue, 06 Aug 2019</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>Cairo Tower is a free-standing concrete tower in Cairo, Egypt At 187 m, it has
                                            been the tallest structure in Egypt and N</p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/cairo-attractions/islamic-art-museum" class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a href="/blog/cairo-attractions/cairo-tower-egypt">
                                        <img src="/../images/1659567353122344.jpg"
                                            alt="Fantastic Information about Cairo Tower - Egypt" class="blog-img"
                                            loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a href="/blog/cairo-attractions/cairo-tower-egypt">
                                            Fantastic Information about Cairo Tower - Egypt </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Tue, 06 Aug 2019</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>Cairo Tower is a free-standing concrete tower in Cairo, Egypt At 187 m, Cairo
                                            Tower has been the tallest structure in Eg</p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/cairo-attractions/cairo-tower-egypt" class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="modern-blog-card">
                                <div class="blog-image">
                                    <a href="/blog/cairo-attractions/bab-zuweila">
                                        <img src="/../images/165956716712Bab-Zuweila.jpg" alt="Bab Zuweila"
                                            class="blog-img" loading="lazy">
                                        <div class="blog-overlay">
                                            <div class="overlay-content">
                                                <i class="la la-eye"></i>
                                                <span>Read Article</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="blog-content-wrapper">
                                    <h3 class="blog-card-title">
                                        <a href="/blog/cairo-attractions/bab-zuweila">
                                            Bab Zuweila </a>
                                    </h3>

                                    <div class="blog-date">
                                        <i class="la la-calendar"></i>
                                        <span>Mon, 05 Aug 2019</span>
                                    </div>

                                    <div class="blog-description">
                                        <p>Bab Zuweila Built in the 11th century, Bab Zuweila was an execution site during
                                            Mamluk times, it is considered as Part o</p>
                                    </div>

                                    <div class="blog-footer">
                                        <a href="/blog/cairo-attractions/bab-zuweila" class="btn-blog">
                                            Read More <i class="la la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-wrapper">

                        <a href="/blog/?from=20" class="pagination-btn">
                            Next Page <i class="la la-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Search Widget -->
                    <div class="luxury-sidebar">
                        <div class="sidebar-widget">
                            <h3 class="sidebar-title">Search Articles</h3>
                            <form action="/search/" method="get" class="search-form">
                                <input type="text" name="keyword" class="search-input"
                                    placeholder="Search for articles...">
                                <input type="hidden" name="source" value="blog">
                                <button type="submit" class="search-btn">
                                    <i class="la la-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Categories Widget -->
                    <div class="luxury-sidebar">
                        <div class="sidebar-widget">
                            <h3 class="sidebar-title">Categories</h3>
                            <div class="category-tags">
                                <a href="/blog/alexandria-attraction" class="category-tag">
                                    Alexandria Attractions </a>
                                <a href="/blog/egyptian-history" class="category-tag">
                                    Ancient Egyptian History </a>
                                <a href="/blog/aswan-attraction" class="category-tag">
                                    Aswan Attractions </a>
                                <a href="/blog/cairo-attractions" class="category-tag">
                                    Cairo Attractions </a>
                                <a href="/blog/egypt-pyramid" class="category-tag">
                                    Egypt Pyramids </a>
                                <a href="/blog/egypt-travel-guide" class="category-tag">
                                    Egypt Travel Guide </a>
                                <a href="/blog/general" class="category-tag">
                                    General </a>
                                <a href="/blog/jordan-best-travel-guide" class="category-tag">
                                    Jordan Travel Guide </a>
                                <a href="/blog/luxor-attraction" class="category-tag">
                                    Luxor Attractions </a>
                                <a href="/blog/morocco-travel-guide" class="category-tag">
                                    Morocco Travel Guide </a>
                                <a href="/blog/nile-valley-information" class="category-tag">
                                    Nile Valley </a>
                            </div>
                        </div>
                    </div>

                    <!-- Popular Articles Widget -->
                    <div class="luxury-sidebar">
                        <div class="sidebar-widget">
                            <h3 class="sidebar-title">Popular Articles</h3>
                            <div class="popular-article">
                                <img src="https://www.luxorandaswan.com/../images/17519041161vessels-head2.jpg"
                                    alt="" class="popular-img">
                                <div class="popular-content">
                                    <h4><a href="/blog/nile-valley-information/best-month-for-a-nile-river-cruise">Best
                                            Month for a Nile River Cruise: When to Go and Why</a></h4>
                                    <p class="popular-date">Mon, 07 Jul 2025</p>
                                </div>
                            </div>
                            <div class="popular-article">
                                <img src="https://www.luxorandaswan.com/../images/17289450971AdobeStock_485657585.jpg"
                                    alt="" class="popular-img">
                                <div class="popular-content">
                                    <h4><a
                                            href="/blog/nile-valley-information/luxury-nile-cruise-a-journey-of-opulence-and-history">Luxury
                                            Nile Cruise: A Journey of Opulence and History</a></h4>
                                    <p class="popular-date">Mon, 14 Oct 2024</p>
                                </div>
                            </div>
                            <div class="popular-article">
                                <img src="https://www.luxorandaswan.com/../images/17237245321pyramids-of-egypt2.jpg"
                                    alt="" class="popular-img">
                                <div class="popular-content">
                                    <h4><a href="/blog/egypt-travel-guide/what-is-the-best-way-to-see-egypt-in-9-days">What
                                            is the best way to see Egypt in 9 days?</a></h4>
                                    <p class="popular-date">Thu, 15 Aug 2024</p>
                                </div>
                            </div>
                            <div class="popular-article">
                                <img src="https://www.luxorandaswan.com/../images/16934689661pexels-maksim-romashkin-11104822.jpg"
                                    alt="" class="popular-img">
                                <div class="popular-content">
                                    <h4><a href="/blog/general/5-types-of-houses-you-will-find-in-thailand">5 Types of
                                            Houses You Will Find in Thailand</a></h4>
                                    <p class="popular-date">Thu, 31 Aug 2023</p>
                                </div>
                            </div>
                            <div class="popular-article">
                                <img src="https://www.luxorandaswan.com/../images/169039154311.jpg" alt=""
                                    class="popular-img">
                                <div class="popular-content">
                                    <h4><a href="/blog/general/indulgent-escapes-unforgettable-luxury-vacations-in-crete">Indulgent
                                            Escapes: Unforgettable Luxury Vacations in Crete</a></h4>
                                    <p class="popular-date">Wed, 26 Jul 2023</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Widget -->
                    <div class="luxury-sidebar">
                        <div class="sidebar-widget">
                            <h3 class="sidebar-title">Follow & Connect</h3>
                            <div class="social-links">
                                <a href="https://www.facebook.com/luxorandaswantravel" target="_blank"
                                    class="social-link">
                                    <i class="lab la-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/LuxorAswanTours" target="_blank" class="social-link">
                                    <i class="lab la-twitter"></i>
                                </a>
                                <a href="https://www.instagram.com/luxor_and_aswan_travel" target="_blank"
                                    class="social-link">
                                    <i class="lab la-instagram"></i>
                                </a>
                                <a href="https://www.tripadvisor.com/Attraction_Review-g294205-d12148903-Reviews-Luxor_and_Aswan_Travel-Luxor_Nile_River_Valley.html"
                                    target="_blank" class="social-link">
                                    <i class="la la-tripadvisor"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="{{ request()->root() }}/website/js/new/jquery.min.js"></script>
    <script src="{{ request()->root() }}/website/js/new/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        const offsetTop = target.offsetTop - 100;
                        window.scrollTo({
                            top: offsetTop,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Card hover effects
            document.querySelectorAll('.cruise-card, .choose-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Loading animation
            window.addEventListener('load', () => {
                document.body.classList.add('loaded');
            });
        });
    </script>
@endsection
