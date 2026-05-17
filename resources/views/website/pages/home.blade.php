@extends('website.layouts.master')

@section('title', __('Home - Etro Tours'))

@section('css')
    <style>
        /* Hero Section */
        .hero-section {
            position: relative;
            height: 100vh;
            min-height: 600px;
            display: flex;
            align-items: center;
            background: linear-gradient(rgba(28, 50, 92, 0.4), rgba(28, 50, 92, 0.6)), url('{{ asset('website/photos/home2.webp') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin-top: -85px;
            z-index: 1;
        }

        .hero-content {
            color: white;
            max-width: 800px;
            padding-top: 85px;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 20px;
            font-family: 'Playfair Display', serif;
            animation: fadeInUp 1s ease;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 40px;
            line-height: 1.6;
            opacity: 0.9;
            animation: fadeInUp 1s ease 0.2s;
            animation-fill-mode: both;
        }

        .hero-cta {
            display: inline-flex;
            align-items: center;
            padding: 15px 35px;
            background: var(--rich-gold);
            color: white;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            animation: fadeInUp 1s ease 0.4s;
            animation-fill-mode: both;
        }

        .hero-cta:hover {
            background: white;
            color: var(--deep-blue);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .hero-cta i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        /* Trust Indicators */
        .trust-section {
            background: white;
            padding: 30px 0;
            margin-top: -50px;
            position: relative;
            z-index: 2;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .trust-content {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            align-items: center;
            gap: 20px;
        }

        .trust-item {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--deep-blue);
            font-weight: 600;
            text-align: center;
        }

        .trust-item i {
            color: var(--rich-gold);
            font-size: 2rem;
            margin-right: 15px;
        }

        /* Common Section Styles */
        .section-pad {
            padding: 80px 0;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            color: var(--deep-blue);
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-align: center;
        }

        .section-subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 50px;
            font-size: 1.1rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .light-section {
            background-color: #f8f9fa;
        }

        .cream-section {
            background-color: #faf7f2;
        }

        /* Feature Cards */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: rgba(197, 149, 91, 0.1);
            color: var(--rich-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 20px;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            background: var(--rich-gold);
            color: white;
        }

        .feature-title {
            color: var(--deep-blue);
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .feature-description {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0;
        }

        /* Deal Cards */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }

        .deal-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .deal-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .card-image {
            position: relative;
            height: 240px;
            overflow: hidden;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .deal-card:hover .card-image img {
            transform: scale(1.1);
        }

        .badge-top {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--rich-gold);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            z-index: 2;
        }

        .deal-price {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: white;
            color: var(--deep-blue);
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 700;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 2;
        }

        .card-body {
            padding: 25px;
            flex-grow: 1;
        }

        .deal-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .deal-title a {
            color: var(--deep-blue);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .deal-title a:hover {
            color: var(--rich-gold);
        }

        .deal-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
            color: #666;
            font-size: 0.9rem;
        }

        .deal-meta span {
            display: flex;
            align-items: center;
        }

        .deal-meta i {
            color: var(--rich-gold);
            margin-right: 5px;
        }

        .deal-description {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 25px;
        }

        .feature-tag {
            background: #f8f9fa;
            color: var(--deep-blue);
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .deal-btn {
            margin-top: auto;
            width: 100%;
            text-align: center;
            justify-content: center;
        }

        /* Buttons */
        .gold-btn {
            display: inline-flex;
            align-items: center;
            padding: 12px 25px;
            background: var(--rich-gold);
            color: white;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .gold-btn:hover {
            background: #b0824b;
            color: white;
        }

        .gold-btn i {
            margin-left: 8px;
        }

        /* Quote Section */
        .quote-section {
            background: linear-gradient(rgba(28, 50, 92, 0.9), rgba(28, 50, 92, 0.9)), url('{{ asset('website/photos/bg-pattern.jpg') }}');
            padding: 80px 0;
            color: white;
            text-align: center;
        }

        .quote-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: white;
        }

        .quote-features {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            margin: 40px 0;
        }

        .quote-feature {
            display: flex;
            align-items: center;
            font-size: 1.1rem;
        }

        .quote-feature i {
            color: var(--rich-gold);
            font-size: 1.5rem;
            margin-right: 10px;
        }

        /* Destinations Grid */
        .destinations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .destination-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .destination-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .destination-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .destination-title a {
            color: var(--deep-blue);
            text-decoration: none;
        }

        .destination-description {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .destination-meta {
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-bottom: 20px;
            color: #666;
            font-size: 0.9rem;
        }

        .destination-btn {
            width: 100%;
            justify-content: center;
            background: white;
            color: var(--deep-blue);
            border: 1px solid var(--deep-blue);
            text-decoration: none;
        }

        .destination-btn:hover {
            background: #274a7a;
            color: #f8fbff !important;
            border-color: #274a7a;
            box-shadow: 0 10px 20px rgba(28, 50, 92, 0.18);
            text-decoration: none;
        }

        .destination-btn:hover i,
        .destination-btn:focus,
        .destination-btn:focus i {
            color: white !important;
        }

        /* Articles Grid */
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .article-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .article-date {
            color: var(--rich-gold);
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .article-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .article-title a {
            color: var(--deep-blue);
            text-decoration: none;
        }

        .article-excerpt {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Testimonials */
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .testimonial-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .rating-stars {
            color: #fbbc04;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }

        .verified-badge {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-left: 10px;
            vertical-align: middle;
        }

        .testimonial-text {
            color: #444;
            font-style: italic;
            font-size: 1.05rem;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .author-section {
            display: flex;
            align-items: center;
            gap: 15px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--deep-blue);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            overflow: hidden;
        }

        .author-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .author-name {
            font-weight: 700;
            color: var(--deep-blue);
            margin-bottom: 2px;
        }

        /* Newsletter Box */
        .newsletter-box {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .newsletter-form {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .newsletter-form input {
            flex-grow: 1;
            padding: 12px 20px;
            border: 1px solid #ddd;
            border-radius: 25px;
            outline: none;
        }

        .newsletter-form input:focus {
            border-color: var(--rich-gold);
        }

        /* Certificate Images */
        .tripadvisor-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
        }

        .certificate-img {
            height: 110px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
            transition: transform 0.3s ease;
        }

        .certificate-img:hover {
            transform: scale(1.1);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .trust-content {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .trust-item {
                justify-content: flex-start;
                text-align: left;
            }

            .newsletter-form {
                flex-direction: column;
            }

            .newsletter-form button {
                width: 100%;
                justify-content: center;
            }

            .certificate-img {
                height: 90px;
            }
        }
    </style>
@endsection

@section('content')
    <section class="hero-section" id="home">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">{{ __('Etro Tours') }}</h1>
                <p class="hero-subtitle">
                    {{ __('Discover Egypt like never before with Etro Tours — your trusted partner for unforgettable travel experiences, luxury services, and personalized journeys across Egypt.') }}
                </p>
                <a href="#deals" class="hero-cta">
                    <i class="la la-compass"></i>
                    {{ __('Discover Egypt') }}
                </a>
            </div>
        </div>
    </section>

    <section class="trust-section">
        <div class="container">
            <div class="trust-content">
                <div class="trust-item"><i class="la la-trophy"></i><span>{{ __('Award-Winning Service') }}</span></div>
                <div class="trust-item"><i class="la la-certificate"></i><span>{{ __('Licensed & Certified') }}</span></div>
                <div class="trust-item"><i class="la la-clock"></i><span>{{ __('24/7 Support') }}</span></div>
                <div class="trust-item"><i class="la la-credit-card"></i><span>{{ __('Secure Payment') }}</span></div>
            </div>
        </div>
    </section>

    <section class="section-pad light-section">
        <div class="container">
            <h2 class="section-title">{{ __('TripAdvisor Hall of Fame') }}</h2>
            <p class="section-subtitle" style="text-align: center;">
                {{ __('Consistently recognized for excellence in travel experiences') }}</p>
            <div class="tripadvisor-row">
                @foreach (['Travellers-Choice-2019-.png', 'Travellers-Choice-2020.png', 'Travellers-Choice-2021.png', 'Travellers-Choice-2025.png', 'Travellers-Choice-2022.png', 'Travellers-Choice-2023.png', 'Travellers-Choice-2024-.png'] as $award)
                    <img loading="lazy" src="{{ asset('website/photos/tripadvisor/' . $award) }}"
                        alt="{{ __('TripAdvisor Award') }}" class="certificate-img">
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-pad">
        <div class="container">
            <h2 class="section-title">{{ __('Why Choose Us') }}</h2>
            <p class="section-subtitle"style="text-align: center;">
                {{ __('Experience the difference that makes us Egypt\'s premier travel company') }}
            </p>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="la la-user-graduate"></i></div>
                    <h3 class="feature-title">{{ __('Expert Egyptologists') }}</h3>
                    <p class="feature-description">
                        {{ __('Certified Egyptologist guides bring ancient history to life with deep knowledge and passionate storytelling.') }}
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="la la-shield-alt"></i></div>
                    <h3 class="feature-title">{{ __('Safety & Security') }}</h3>
                    <p class="feature-description">
                        {{ __('Modern vehicles, trusted operations, and support standards designed for a smooth travel experience.') }}
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="la la-star"></i></div>
                    <h3 class="feature-title">{{ __('Luxury Experience') }}</h3>
                    <p class="feature-description">
                        {{ __('Premium experiences, curated itineraries, and attention to every detail from arrival to departure.') }}
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="la la-headset"></i></div>
                    <h3 class="feature-title">{{ __('Personalized Service') }}</h3>
                    <p class="feature-description">
                        {{ __('Every journey can be tailored around your timing, budget, preferred style, and interests.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="deals" class="section-pad cream-section">
        <div class="container">
            <h2 class="section-title">{{ __('Featured Experiences') }}</h2>
            <p class="section-subtitle" style="text-align: center;">
                {{ __('Discover our most popular tours and create memories that last a lifetime') }}</p>
            <div class="cards-grid">
                @forelse ($featuredPackages as $package)
                    <div class="deal-card">
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
                        <div class="card-body d-flex flex-column">
                            <h3 class="deal-title"><a href="{{ $package['url'] }}">{{ $package['title'] }}</a></h3>
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
                            <a href="{{ $package['url'] }}" class="gold-btn deal-btn">{{ __('Explore Journey') }} <i
                                    class="la la-arrow-right"></i></a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        {{ __('No featured packages found. Add active packages from the admin panel.') }}</div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="quote-section">
        <div class="container">
            <div class="quote-card">
                <h2 class="quote-title">{{ __('Need help planning your trip?') }}</h2>
                <p>{{ __('Get in touch with our travel experts to create a personalized Egypt experience based on your interests and preferences.') }}
                </p>
                <div class="quote-features">
                    <div class="quote-feature"><i
                            class="la la-check-circle"></i><span>{{ __('100% Customizable Itineraries') }}</span>
                    </div>
                    <div class="quote-feature"><i
                            class="la la-user-graduate"></i><span>{{ __('Expert Egyptologist Guides') }}</span>
                    </div>
                    <div class="quote-feature"><i class="la la-headset"></i><span>{{ __('24/7 Local Support') }}</span>
                    </div>
                    <div class="quote-feature"><i class="la la-dollar"></i><span>{{ __('Best Price Guarantee') }}</span>
                    </div>
                </div>
                <button class="gold-btn" data-bs-toggle="modal" data-bs-target="#quoteModal"><i
                        class="la la-paper-plane"></i> {{ __('Get Custom Quote') }}</button>
            </div>
        </div>
    </section>

    <section class="section-pad light-section">
        <div class="container">
            <h2 class="section-title">{{ __('Explore Extraordinary Destinations') }}</h2>
            <p class="section-subtitle">
                {{ __('Explore the best Egyptian cities, attractions, and journeys connected directly from your database.') }}
            </p>
            <div class="destinations-grid">
                @forelse ($destinations as $destination)
                    <div class="destination-card">
                        <div class="card-image">
                            <div class="badge-top">{{ $destination['country'] ?: __('Destination') }}</div>
                            <a href="{{ $destination['url'] }}">
                                <img src="{{ $destination['image'] }}" alt="{{ $destination['title'] }}"
                                    loading="lazy">
                            </a>
                        </div>
                        <div class="card-body">
                            <h3 class="destination-title"><a
                                    href="{{ $destination['url'] }}">{{ $destination['title'] }}</a></h3>
                            <p class="destination-description">{{ $destination['description'] }}</p>
                            <div class="destination-meta">
                                <span><i class="la la-map-marker"></i>{{ $destination['sites_count'] }}
                                    {{ __('Sites') }}</span>
                                <span><i class="la la-suitcase"></i>{{ $destination['packages_count'] }}
                                    {{ __('Trips') }}</span>
                            </div>
                            <a href="{{ $destination['url'] }}" class="gold-btn destination-btn">{{ __('Discover') }} <i
                                    class="la la-arrow-right"></i></a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        {{ __('No active destinations found. Add active cities from the admin panel.') }}</div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="section-pad">
        <div class="container">
            <h2 class="section-title">{{ __('Latest Travel Guides') }}</h2>
            <p class="section-subtitle" style="text-align: center;">
                {{ __('Useful guides and stories connected from the articles table.') }}</p>
            <div class="articles-grid">
                @forelse ($latestArticles as $article)
                    <div class="article-card">
                        <div class="card-image">
                            <a href="{{ $article['url'] }}"><img src="{{ $article['image'] }}"
                                    alt="{{ $article['title'] }}" loading="lazy"></a>
                        </div>
                        <div class="card-body">
                            <div class="article-date"><i class="la la-calendar"></i> {{ $article['date'] }}</div>
                            <h3 class="article-title"><a href="{{ $article['url'] }}">{{ $article['title'] }}</a></h3>
                            <p class="article-excerpt">{{ $article['excerpt'] }}</p>
                            <a href="{{ $article['url'] }}" class="gold-btn">{{ __('Read More') }} <i
                                    class="la la-arrow-right"></i></a>
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
            <div class="text-center mb-5">
                <div
                    style="background:#2358e6eb;color:#fff;padding:10px 20px;border-radius:25px;font-size:.9rem;font-weight:700;display:inline-flex;align-items:center;gap:8px;margin-bottom:25px;">
                    <i class="la la-tripadvisor"></i> {{ __('TripAdvisor Certified Excellence') }}
                </div>
                <h2 class="section-title">{{ __('Guest Experiences') }}</h2>
                <p class="section-subtitle" style="text-align: center;">
                    {{ __('Hear from travelers who have experienced the magic of Egypt with us') }}</p>
            </div>

            <div class="testimonials-grid">
                @forelse ($testimonials as $testimonial)
                    <div class="testimonial-card">
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
                                <p class="mb-0 text-muted"><i class="la la-check-circle"></i> {{ __('Guest Review') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        {{ __('No testimonials found. Add active testimonials from the admin panel.') }}</div>
                @endforelse
            </div>

            <div class="text-center mt-5">
                <a href="https://www.tripadvisor.com/Attraction_Review-g294205-d12148903-Reviews-Luxor_and_Aswan_Travel-Luxor_Nile_River_Valley.html"
                    target="_blank" class="gold-btn">
                    <i class="la la-external-link"></i> {{ __('Read All Reviews on TripAdvisor') }}
                </a>
            </div>
        </div>
    </section>

    <section class="section-pad cream-section">
        <div class="container">
            <div class="newsletter-box text-center">
                <h2 class="section-title">{{ __('Get Our Latest Travel Deals') }}</h2>
                <p class="section-subtitle" style="text-align: center;">
                    {{ __('Subscribe to receive updates, new packages, and special offers.') }}
                </p>
                <form action="{{ route('website.newsletter.store') }}" method="POST" class="newsletter-form">
                    @csrf
                    <input type="email" name="email" placeholder="{{ __('Enter your email address') }}" required>
                    <button type="submit" class="gold-btn">{{ __('Subscribe') }}</button>
                </form>
            </div>
        </div>
    </section>

    <div class="modal fade" id="quoteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:22px;overflow:hidden;">
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
                            <div class="col-md-6"><input class="form-control" name="full_name"
                                    placeholder="{{ __('Full name') }}" required></div>
                            <div class="col-md-6"><input class="form-control" type="email" name="email"
                                    placeholder="{{ __('Email address') }}" required></div>
                            <div class="col-md-6"><input class="form-control" name="phone"
                                    placeholder="{{ __('Phone / WhatsApp') }}"></div>
                            <div class="col-md-6"><input class="form-control" name="country_name"
                                    placeholder="{{ __('Country') }}">
                            </div>
                            <div class="col-md-4"><input class="form-control" type="date" name="travel_date"></div>
                            <div class="col-md-4"><input class="form-control" type="number" min="1"
                                    name="adults" placeholder="{{ __('Adults') }}"></div>
                            <div class="col-md-4"><input class="form-control" type="number" min="0"
                                    name="children" placeholder="{{ __('Children') }}"></div>
                            <div class="col-12">
                                <textarea class="form-control" name="message" rows="4"
                                    placeholder="{{ __('Tell us about your preferred trip') }}"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="gold-btn">{{ __('Send Request') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
