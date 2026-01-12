@extends('website.layouts.master')

@section('title', 'Home - Luxor and Aswan Travel')


@section('css')
    <style>
        @media (min-width: 992px) {
            .col-lg-1-7 {
                flex: 0 0 auto;
                width: 14.2857%;
            }
        }



        /* Enhanced color input focus states */
        .form-control:focus {
            border-color: var(--rich-gold);
            box-shadow: 0 0 0 0.25rem rgba(197, 149, 91, 0.25);
            outline: none;
            transform: translateY(-2px);
        }

        /* Enhanced Newsletter form styling */
        input[type="email"] {
            transition: all 0.3s ease;
        }

        input[type="email"]:focus {
            border-color: var(--rich-gold);
            box-shadow: 0 0 0 0.25rem rgba(197, 149, 91, 0.15);
            outline: none;
        }

        /* Enhanced Hero Section with Responsive Heights */
        .hero-section {
            height: 85vh;
            /* Reduced from 100vh to 85vh for better balance */
            min-height: 600px;
            /* Ensure minimum height on very short screens */
            max-height: 900px;
            /* Prevent too tall on very large screens */
            background: linear-gradient(rgb(28 50 92 / 31%), rgb(26 75 102 / 43%)), url({{ request()->root() }}/website/photos/home2.webp);
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            /* Parallax effect on desktop */
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        /* Large Desktop (1400px+) */
        @media (min-width: 1400px) {
            .hero-section {
                height: 80vh;
                max-height: 850px;
            }
        }

        /* Desktop (1200px - 1399px) */
        @media (max-width: 1399px) and (min-width: 1200px) {
            .hero-section {
                height: 85vh;
                min-height: 650px;
                max-height: 800px;
            }
        }

        /* Laptop (992px - 1199px) */
        @media (max-width: 1199px) and (min-width: 992px) {
            .hero-section {
                height: 80vh;
                min-height: 600px;
                max-height: 750px;
            }
        }

        /* Tablet Portrait (768px - 991px) */
        @media (max-width: 991px) and (min-width: 768px) {
            .hero-section {
                height: 75vh;
                min-height: 550px;
                max-height: 700px;
                background-attachment: scroll;
                /* Better performance on tablets */
            }
        }

        /* Mobile Landscape (576px - 767px) */
        @media (max-width: 767px) and (min-width: 576px) {
            .hero-section {
                height: 70vh;
                min-height: 500px;
                max-height: 600px;
                background-attachment: scroll;
            }
        }

        /* Mobile Portrait (320px - 575px) */
        @media (max-width: 575px) {
            .hero-section {
                height: 65vh;
                min-height: 450px;
                max-height: 550px;
                background-attachment: scroll;
                background-position: center center;
            }
        }

        /* Extra Small Mobile (320px and below) */
        @media (max-width: 480px) {
            .hero-section {
                height: 60vh;
                min-height: 400px;
                max-height: 500px;
            }
        }

        /* Very Short Screens (height < 500px) - Landscape phones */
        @media (max-height: 500px) and (orientation: landscape) {
            .hero-section {
                height: 100vh;
                min-height: auto;
                max-height: none;
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

        /* Hero Section Spacing for Fixed Navigation */
        /* Desktop - Account for fixed navbar */
        .hero-content {
            position: relative;
            z-index: 3;
            text-align: center;
            color: white;
            max-width: 1000px;
            margin: 0 auto;
            padding: 120px 20px 0;
            /* Add top padding for fixed nav */
            animation: fadeInUp 1.2s ease-out;
        }

        /* Large Desktop */
        @media (min-width: 1400px) {
            .hero-content {
                padding-top: 140px;
                /* More space for larger navbars */
            }
        }

        /* Desktop (1200px - 1399px) */
        @media (max-width: 1399px) and (min-width: 1200px) {
            .hero-content {
                padding-top: 130px;
            }
        }

        /* Laptop (992px - 1199px) */
        @media (max-width: 1199px) and (min-width: 992px) {
            .hero-content {
                padding-top: 120px;
            }
        }

        /* Tablet Portrait (768px - 991px) */
        @media (max-width: 991px) and (min-width: 768px) {
            .hero-content {
                padding: 100px 30px 0;
                /* Account for mobile navbar */
            }

            .hero-title {
                font-size: clamp(2.5rem, 6vw, 4rem);
            }

            .hero-subtitle {
                font-size: 1.2rem;
                margin-bottom: 35px;
            }
        }

        /* Mobile Landscape (576px - 767px) */
        @media (max-width: 767px) and (min-width: 576px) {
            .hero-content {
                padding: 90px 25px 0;
                /* Mobile navbar spacing */
            }

            .hero-title {
                font-size: clamp(2.2rem, 7vw, 3.5rem);
                margin-bottom: 20px;
            }

            .hero-subtitle {
                font-size: 1.1rem;
                margin-bottom: 30px;
            }

            .hero-cta {
                padding: 16px 35px;
                font-size: 1rem;
            }
        }

        /* Mobile Portrait (320px - 575px) */
        @media (max-width: 575px) {
            .hero-content {
                padding: 80px 20px 0;
                /* Compact mobile navbar */
                text-align: center;
            }

            .hero-title {
                font-size: clamp(1.8rem, 8vw, 2.8rem);
                margin-bottom: 18px;
                line-height: 1.2;
            }

            .hero-subtitle {
                font-size: 1rem;
                margin-bottom: 25px;
                line-height: 1.4;
            }

            .hero-cta {
                padding: 14px 30px;
                font-size: 0.95rem;
                gap: 10px;
            }
        }

        /* Extra Small Mobile (320px and below) */
        @media (max-width: 480px) {
            .hero-content {
                padding: 75px 15px 0;
                /* Minimal navbar on small screens */
            }

            .hero-title {
                font-size: clamp(1.6rem, 9vw, 2.4rem);
                margin-bottom: 15px;
            }

            .hero-subtitle {
                font-size: 0.95rem;
                margin-bottom: 20px;
                opacity: 0.9;
            }

            .hero-cta {
                padding: 12px 25px;
                font-size: 0.9rem;
                border-radius: 40px;
            }
        }

        /* Very Short Screens (height < 500px) - Landscape phones */
        @media (max-height: 500px) and (orientation: landscape) {
            .hero-content {
                padding: 60px 20px 0;
                /* Reduced for landscape but still clear of nav */
            }

            .hero-title {
                font-size: 2rem;
                margin-bottom: 10px;
            }

            .hero-subtitle {
                font-size: 0.9rem;
                margin-bottom: 20px;
            }

            .hero-cta {
                padding: 10px 20px;
                font-size: 0.85rem;
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
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
            animation: pulse 2s infinite;
            border: 2px solid rgba(197, 149, 91, 0.3);
        }

        /* Mobile badge adjustments */
        @media (max-width: 767px) and (min-width: 576px) {
            .hero-badge {
                padding: 10px 25px;
                font-size: 0.9rem;
                margin-bottom: 25px;
            }
        }

        @media (max-width: 575px) {
            .hero-badge {
                padding: 8px 20px;
                font-size: 0.85rem;
                margin-bottom: 20px;
                gap: 8px;
            }
        }

        @media (max-width: 480px) {
            .hero-badge {
                padding: 6px 18px;
                font-size: 0.8rem;
                margin-bottom: 18px;
            }
        }

        @media (max-height: 500px) and (orientation: landscape) {
            .hero-badge {
                margin-bottom: 15px;
                padding: 5px 15px;
                font-size: 0.75rem;
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
            font-size: clamp(3rem, 8vw, 5rem);
            font-weight: 700;
            margin-bottom: 25px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            background: linear-gradient(135deg, #ffffff 0%, var(--cream-elegant) 100%);
            -webkit-background-clip: text;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.4rem;
            margin-bottom: 40px;
            opacity: 0.95;
            font-weight: 300;
            letter-spacing: 1px;
        }

        .hero-cta {
            background: var(--gradient-gold);
            color: var(--primary-navy);
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            transition: all 0.4s ease;
            box-shadow: var(--shadow-gold);
            position: relative;
            overflow: hidden;
        }

        .hero-cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .hero-cta:hover::before {
            left: 100%;
        }

        .hero-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(197, 149, 91, 0.4);
            color: var(--primary-navy);
        }

        /* Enhanced Trust Section - Option 1 with Mobile Optimization */
        .trust-section {
            background: linear-gradient(135deg, var(--pearl-luxury) 0%, rgba(197, 149, 91, 0.05) 100%);
            padding: 50px 0;
            position: relative;
            border-top: 1px solid rgba(197, 149, 91, 0.1);
            border-bottom: 1px solid rgba(197, 149, 91, 0.1);
        }

        .trust-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20" fill="none"><path d="M0 10L10 0L20 10L30 0L40 10L50 0L60 10L70 0L80 10L90 0L100 10V20H0V10Z" fill="rgba(197,149,91,0.03)"/></svg>') repeat-x;
            opacity: 0.5;
        }

        .trust-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .trust-item {
            background: white;
            padding: 25px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 18px;
            box-shadow: var(--shadow-subtle);
            transition: all 0.3s ease;
            border: 2px solid rgba(197, 149, 91, 0.1);
            position: relative;
            overflow: hidden;
        }

        .trust-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--gradient-gold);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .trust-item:hover::before {
            transform: scaleY(1);
        }

        .trust-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
            border-color: var(--rich-gold);
        }

        .trust-item i {
            width: 60px;
            height: 60px;
            background: var(--gradient-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-navy);
            font-size: 1.5rem;
            font-weight: 600;
            box-shadow: var(--shadow-gold);
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .trust-item:hover i {
            transform: scale(1.1);
        }

        .trust-item span {
            color: var(--primary-navy);
            font-weight: 600;
            font-size: 1rem;
            line-height: 1.4;
        }

        /* Enhanced Mobile Responsive - Equal Heights */
        @media (max-width: 992px) {
            .trust-content {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
                align-items: stretch;
                /* Equal height cards */
            }

            .trust-item {
                height: auto;
                min-height: 80px;
                /* Minimum height for consistency */
            }
        }

        @media (max-width: 768px) {
            .trust-section {
                padding: 40px 0;
            }

            .trust-content {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
                padding: 0 15px;
                align-items: stretch;
            }

            .trust-item {
                padding: 18px 15px;
                gap: 12px;
                border-radius: 15px;
                margin: 0;
                min-height: 75px;
                display: flex;
                align-items: center;
            }

            .trust-item i {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
                flex-shrink: 0;
            }

            .trust-item span {
                font-size: 0.85rem;
                font-weight: 500;
                line-height: 1.2;
                flex: 1;
            }
        }

        @media (max-width: 580px) {
            .trust-content {
                grid-template-columns: 1fr;
                gap: 12px;
                padding: 0 10px;
            }

            .trust-item {
                padding: 16px 15px;
                gap: 15px;
                min-height: 70px;
                justify-content: flex-start;
            }

            .trust-item i {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }

            .trust-item span {
                font-size: 0.9rem;
                font-weight: 500;
                line-height: 1.3;
            }
        }

        @media (max-width: 480px) {
            .trust-section {
                padding: 30px 0;
            }

            .trust-content {
                gap: 10px;
                padding: 0 10px;
            }

            .trust-item {
                padding: 15px 12px;
                gap: 12px;
                border-radius: 12px;
                min-height: 65px;
                box-shadow: 0 2px 12px rgba(28, 50, 92, 0.06);
            }

            .trust-item i {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }

            .trust-item span {
                font-size: 0.85rem;
                font-weight: 500;
                line-height: 1.2;
            }

            /* Reduce animation intensity on small screens */
            .trust-item:hover {
                transform: translateY(-2px);
            }

            .trust-item:hover i {
                transform: scale(1.05);
            }
        }

        /* Extra small devices optimization */
        @media (max-width: 360px) {
            .trust-content {
                padding: 0 8px;
                gap: 8px;
            }

            .trust-item {
                padding: 12px 10px;
                gap: 10px;
                min-height: 60px;
            }

            .trust-item i {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .trust-item span {
                font-size: 0.8rem;
                line-height: 1.2;
                font-weight: 500;
            }
        }

        /* Feature Cards Equal Heights Fix */
        .feature-card {
            height: 100%;
            min-height: 320px;
            display: flex;
            flex-direction: column;
        }

        .feature-description {
            flex: 1;
        }

        /* Enhanced TripAdvisor Section */
        .tripadvisor-section {
            background: var(--gradient-elegant);
            padding: 80px 0;
            position: relative;
            border-top: 1px solid rgba(197, 149, 91, 0.2);
        }

        .awards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 30px;
            align-items: center;
            justify-items: center;
        }

        .award-item {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: var(--shadow-subtle);
            transition: all 0.4s ease;
            border: 2px solid rgba(197, 149, 91, 0.1);
        }

        .award-item:hover {
            transform: translateY(-10px) rotate(2deg);
            box-shadow: var(--shadow-dramatic);
            border-color: var(--rich-gold);
        }

        .award-img {
            width: 100%;
            height: 120px;
            object-fit: contain;
            transition: all 0.3s ease;
        }

        /* Why Choose Us - Enhanced */
        .why-choose-section {
            background: var(--pearl-luxury);
            padding: 50px 0;
            position: relative;
            border-top: 1px solid rgba(197, 149, 91, 0.2);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-top: 60px;
        }

        .feature-card {
            background: white;
            padding: 40px;
            border-radius: 25px;
            box-shadow: var(--shadow-subtle);
            transition: all 0.4s ease;
            border: 2px solid rgba(197, 149, 91, 0.1);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
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

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-dramatic);
            border-color: var(--rich-gold);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }

        .feature-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-gold);
            border-radius: 50%;
            transform: scale(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover .feature-icon::before {
            transform: scale(1);
        }

        .feature-icon i {
            position: relative;
            z-index: 2;
        }

        .feature-icon.expertise {
            background: linear-gradient(135deg, #1c325c 0%, #1a4b66 100%);
        }

        .feature-icon.safety {
            background: linear-gradient(135deg, #c5955b 0%, #b8860b 100%);
        }

        .feature-icon.luxury {
            background: linear-gradient(135deg, #8b9b6b 0%, #6b7a4a 100%);
        }

        .feature-icon.support {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        }

        .feature-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-navy);
            margin-bottom: 15px;
        }

        .feature-description {
            color: var(--warm-gray);
            line-height: 1.7;
        }

        /* Enhanced Hot Deals Section */
        .deals-section {
            background: linear-gradient(135deg, var(--cream-elegant) 0%, var(--light-sand) 100%);
            padding: 50px 0;
            position: relative;
            border-top: 1px solid rgba(197, 149, 91, 0.2);
        }

        .deals-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 35px;
            margin-top: 60px;
        }

        /* Add this new media query for medium screens */
        @media (max-width: 1200px) {
            .deals-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .deal-card {
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: var(--shadow-medium);
            transition: all 0.4s ease;
            position: relative;
            border: 2px solid rgba(197, 149, 91, 0.1);
        }

        .deal-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-dramatic);
            border-color: var(--rich-gold);
        }

        .deal-image {
            position: relative;
            height: 280px;
            overflow: hidden;
        }

        .deal-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.4s ease;
        }

        .deal-card:hover .deal-img {
            transform: scale(1.1);
        }

        .deal-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #dc3545;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            z-index: 2;
        }

        .deal-price {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--gradient-gold);
            color: #fff;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            z-index: 2;
            box-shadow: var(--shadow-gold);
        }

        .deal-content {
            padding: 25px 20px 25px 20px;
        }

        .deal-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--primary-navy);
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .deal-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .deal-title a:hover {
            color: var(--rich-gold);
        }

        .deal-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            color: var(--warm-gray);
            font-size: 12px;
            flex-wrap: wrap;
        }

        .deal-meta i {
            color: var(--rich-gold);
            margin-right: 6px;
        }

        .deal-meta span {
            display: flex;
            align-items: center;
            white-space: nowrap;
            flex-shrink: 0;
            background: rgba(197, 149, 91, 0.15);
            padding: 6px 10px;
            border-radius: 6px;
        }

        /* Mobile optimizations */
        @media (max-width: 768px) {
            .deal-meta {
                gap: 10px;
                font-size: 0.85rem;
                padding: 10px 12px;
            }

            .deal-meta span {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 480px) {
            .deal-meta {
                gap: 8px;
                font-size: 0.8rem;
                padding: 8px 10px;
            }

            .deal-meta i {
                margin-right: 4px;
            }
        }

        .deal-description {
            color: var(--warm-gray);
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .deal-features {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .feature-tag {
            background: rgba(197, 149, 91, 0.15);
            color: var(--primary-navy);
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
            border: 1px solid rgba(197, 149, 91, 0.2);
        }

        .deal-btn {
            background: var(--gradient-gold);
            color: var(--primary-navy);
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            width: 100%;
            justify-content: center;
        }

        .deal-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-gold);
            color: var(--primary-navy);
        }


        /* Enhanced Destinations Section */
        .destinations-section {
            background: var(--pearl-luxury);
            padding: 80px 0;
            position: relative;
        }

        .destinations-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20" fill="none"><path d="M0 10L10 0L20 10L30 0L40 10L50 0L60 10L70 0L80 10L90 0L100 10V20H0V10Z" fill="rgba(197,149,91,0.03)"/></svg>') repeat-x;
            opacity: 0.5;
        }

        /* Main destinations grid - 3 columns on large screens */
        .destinations-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 35px;
            margin-top: 60px;
            position: relative;
            z-index: 2;
        }

        .destination-card {
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: var(--shadow-medium);
            transition: all 0.5s ease;
            position: relative;
            border: 2px solid rgba(197, 149, 91, 0.1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .destination-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: var(--shadow-dramatic);
            border-color: var(--rich-gold);
        }

        .destination-image {
            height: 280px;
            overflow: hidden;
            position: relative;
            background: linear-gradient(45deg, var(--primary-navy), var(--rich-gold));
        }

        .destination-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.6s ease;
            opacity: 0.9;
        }

        .destination-card:hover .destination-img {
            transform: scale(1.15);
            opacity: 1;
        }

        .destination-overlay {
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

        .destination-card:hover .destination-overlay {
            opacity: 1;
        }

        .overlay-content {
            text-align: center;
            color: white;
            transform: translateY(20px);
            transition: transform 0.4s ease;
        }

        .destination-card:hover .overlay-content {
            transform: translateY(0);
        }

        .overlay-content i {
            font-size: 3rem;
            margin-bottom: 10px;
            display: block;
        }

        .overlay-content span {
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Image Badges */
        .destination-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(197, 149, 91, 0.95);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            z-index: 3;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .destination-rating {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.95);
            color: var(--primary-navy);
            padding: 8px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
            z-index: 3;
            display: flex;
            align-items: center;
            gap: 5px;
            backdrop-filter: blur(10px);
        }

        .destination-rating i {
            color: #FFD700;
            font-size: 0.9rem;
        }

        /* Enhanced Card Body */
        .destination-body {
            padding: 30px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .destination-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-navy);
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .destination-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .destination-title a:hover {
            color: var(--rich-gold);
        }

        .destination-description {
            color: var(--warm-gray);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 20px;
            flex: 1;
        }

        .destination-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--warm-gray);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .meta-item i {
            color: var(--rich-gold);
            font-size: 1rem;
        }

        .destination-highlights {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .highlight-tag {
            background: rgba(197, 149, 91, 0.1);
            color: var(--primary-navy);
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
            border: 1px solid rgba(197, 149, 91, 0.2);
            transition: all 0.3s ease;
        }

        .destination-card:hover .highlight-tag {
            background: rgba(197, 149, 91, 0.15);
            border-color: var(--rich-gold);
        }

        .destination-footer {
            display: flex;
            justify-content: between;
            align-items: center;
            gap: 15px;
            padding-top: 20px;
            border-top: 1px solid rgba(197, 149, 91, 0.1);
        }

        .destination-btn {
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
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            flex: 1;
            justify-content: center;
            max-width: 100%;
        }

        .destination-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .destination-btn:hover::before {
            left: 100%;
        }

        .destination-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-gold);
            color: var(--primary-navy);
        }

        /* Section Headers */
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
            margin-bottom: 30px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        /* Medium screens - 2 columns */
        @media (max-width: 1200px) {
            .destinations-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 30px;
            }
        }

        /* Tablet screens - 2 columns with smaller gap */
        @media (max-width: 992px) {
            .destinations-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 25px;
            }

            .destination-image {
                height: 250px;
            }

            .destination-body {
                padding: 25px;
            }
        }

        /* Mobile screens - 1 column */
        @media (max-width: 768px) {
            .destinations-section {
                padding: 60px 0;
            }

            .destinations-grid {
                grid-template-columns: 1fr;
                gap: 20px;
                margin-top: 40px;
            }

            .destination-card {
                margin: 0 15px;
                border-radius: 20px;
            }

            .destination-image {
                height: 220px;
            }

            .destination-body {
                padding: 20px;
            }

            .destination-title {
                font-size: 1.5rem;
            }

            .destination-meta {
                gap: 15px;
            }

            .destination-footer {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
            }

            .destination-btn {
                max-width: none;
            }
        }

        /* Small mobile screens - detailed styling */
        @media (max-width: 480px) {
            .destinations-grid {
                margin: 0 10px;
                margin-top: 30px;
            }

            .destination-card {
                margin: 0;
                border-radius: 15px;
            }

            .destination-image {
                height: 200px;
            }

            .destination-body {
                padding: 18px;
            }

            .destination-title {
                font-size: 1.3rem;
                margin-bottom: 12px;
            }

            .destination-description {
                font-size: 0.95rem;
                margin-bottom: 15px;
            }

            .destination-meta {
                gap: 12px;
                margin-bottom: 15px;
            }

            .meta-item {
                font-size: 0.85rem;
            }

            .destination-highlights {
                margin-bottom: 20px;
            }

            .highlight-tag {
                font-size: 0.75rem;
                padding: 5px 10px;
            }

            .destination-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }


        /* Enhanced Testimonials Section */
        .testimonials-section {
            background: var(--gradient-elegant);
            padding: 50px 0;
            position: relative;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 60px;
        }

        .testimonial-card {
            background: white;
            border-radius: 25px;
            padding: 35px;
            box-shadow: var(--shadow-subtle);
            transition: all 0.4s ease;
            position: relative;
            border: 2px solid rgba(197, 149, 91, 0.1);
        }

        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: -10px;
            left: 25px;
            font-size: 4rem;
            color: var(--rich-gold);
            font-family: serif;
            opacity: 0.3;
        }

        .testimonial-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-dramatic);
            border-color: var(--rich-gold);
        }

        .testimonial-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .rating-stars {
            display: flex;
            gap: 3px;
        }

        .rating-stars i {
            color: #FFD700;
            font-size: 1.1rem;
        }

        .verified-badge {
            background: #00aa6c;
            color: white;
            font-size: 0.75rem;
            padding: 6px 12px;
            border-radius: 15px;
            font-weight: 600;
        }

        .testimonial-text {
            color: var(--charcoal-deep);
            line-height: 1.7;
            margin-bottom: 25px;
            font-style: italic;
            position: relative;
            z-index: 2;
        }

        .author-section {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-top: 20px;
            border-top: 1px solid #f1f3f4;
        }

        .author-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--gradient-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-navy);
            font-weight: 700;
            font-size: 1.2rem;
            border: 3px solid var(--light-sand);
        }

        .author-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-navy);
            margin: 0 0 5px;
        }

        .author-location {
            color: var(--warm-gray);
            font-size: 0.9rem;
            margin: 0;
        }

        /* Custom Quote Section */
        .quote-section {
            background: var(--gradient-hero);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
            border-top: 1px solid rgba(197, 149, 91, 0.3);
        }

        .quote-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20" fill="none"><path d="M0 10L10 0L20 10L30 0L40 10L50 0L60 10L70 0L80 10L90 0L100 10V20H0V10Z" fill="rgba(197,149,91,0.1)"/></svg>') repeat-x;
            opacity: 0.3;
        }

        .quote-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
            max-width: 800px;
            margin: 0 auto;
        }

        .quote-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 25px;
            padding: 50px;
            border: 1px solid rgba(197, 149, 91, 0.3);
            box-shadow: var(--shadow-dramatic);
        }

        .quote-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .quote-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 30px;
        }

        .quote-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .quote-feature {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
        }

        .quote-feature i {
            color: var(--rich-gold);
            font-size: 1.2rem;
        }

        .quote-btn {
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
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            box-sizing: border-box;
        }

        .quote-btn i {
            font-size: 1em;
            /* Match the button's font size */
            flex-shrink: 0;
            /* Prevent icon from shrinking */
            line-height: 1;
        }

        /* Mobile adjustments for the icon */
        @media (max-width: 768px) {
            .quote-btn i {
                font-size: 0.9em;
            }
        }

        @media (max-width: 480px) {
            .quote-btn i {
                font-size: 0.85em;
            }
        }

        /* Mobile responsive adjustments */
        @media (max-width: 768px) {
            .quote-btn {
                padding: 12px 20px;
                font-size: 0.95rem;
                white-space: normal;
                /* Allow wrapping on mobile */
                text-align: center;
                min-height: 44px;
                /* Good touch target size */
            }
        }

        @media (max-width: 480px) {
            .quote-btn {
                padding: 10px 16px;
                font-size: 0.9rem;
                width: 100%;
                max-width: 280px;
                /* Prevent it from getting too wide */
            }
        }

        .quote-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(197, 149, 91, 0.4);
            color: var(--primary-navy);
        }

        /* Section Titles */
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
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
            width: 80px;
            height: 4px;
            background: var(--gradient-gold);
            border-radius: 2px;
        }

        .section-subtitle {
            color: var(--warm-gray);
            font-size: 1.1rem;
            text-align: center;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Fixed WhatsApp Button */
        .whatsapp-fixed {
            position: fixed;
            bottom: 100px;
            right: 30px;
            z-index: 1000;
            background: #25d366;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            text-decoration: none;
            box-shadow: var(--shadow-dramatic);
            transition: all 0.3s ease;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }

        .whatsapp-fixed:hover {
            transform: scale(1.1);
            color: white;
        }

        /* Mobile Responsive - Fixed */
        @media (max-width: 768px) {

            .features-grid,
            .deals-grid,
            .destinations-grid,
            .testimonials-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .deal-card,
            .destination-card,
            .testimonial-card {
                margin: 0 10px;
            }

            .quote-card {
                padding: 30px 20px;
                margin: 0 15px;
            }

            .quote-features {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .awards-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
        }


        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }
    </style>

    <style>
        .certificate-img {
            border: 2px solid #ddd;
            border-radius: 8px;
            background: #fff;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .certificate-img:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        @media (min-width: 576px) {
            .sec__title {
                font-size: 2rem;
            }
        }

        @media (min-width: 768px) {
            .sec__title {
                font-size: 2.5rem;
            }

            .certificate-img {
                width: 90%;
                /* Adjust image size for tablets */
            }
        }

        @media (min-width: 992px) {
            .certificate-img {
                width: 80%;
                /* Adjust image size for laptops */
            }
        }

        @media (min-width: 1200px) {
            .certificate-img {
                width: 95%;
                /* Adjust image size for larger screens */
            }
        }
    </style>
@endsection

@section('content')


    <!-- Enhanced Hero Section -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="la la-award"></i>
                    Award-Winning Since 2008
                </div>
                <h1 class="hero-title">Luxor and Aswan Travel</h1>
                <p class="hero-subtitle">Egypt's Premier Luxury Travel Experience</p>
                <a href="index.html#deals" class="hero-cta">
                    <i class="la la-compass"></i>
                    Discover Egypt
                </a>
            </div>
        </div>

    </section>

    <!-- Trust Indicators -->
    <section class="trust-section">
        <div class="container">
            <div class="trust-content">
                <div class="trust-item">
                    <i class="la la-trophy"></i>
                    <span>Award-Winning Service</span>
                </div>
                <div class="trust-item">
                    <i class="la la-certificate"></i>
                    <span>Licensed &amp; Certified</span>
                </div>
                <div class="trust-item">
                    <i class="la la-clock"></i>
                    <span>24/7 Support</span>
                </div>
                <div class="trust-item">
                    <i class="la la-credit-card"></i>
                    <span>Secure Payment</span>
                </div>
            </div>
        </div>
    </section>

    <!-- TripAdvisor Awards Section -->
    <section class="tripadvisor-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title">TripAdvisor Hall of Fame</h2>
                    <p class="section-subtitle">Consistently recognized for excellence in travel experiences</p>
                </div>
            </div>
            <!-- Container Row for desktop -->
            <div class="row d-none d-lg-flex justify-content-center align-items-center">
                <!-- All images in one row on desktop (lg and above) -->
                <div class="col-lg-1-7 text-center my-2">
                    <img loading="lazy" src="{{ request()->root() }}/website/photos/tripadvisor/Travellers-Choice-2019-.png"
                        alt="2021" class="img-fluid certificate-img">
                </div>
                <div class="col-lg-1-7 text-center my-2">
                    <img loading="lazy" src="{{ request()->root() }}/website/photos/tripadvisor/Travellers-Choice-2020.png"
                        alt="2021 duplicate" class="img-fluid certificate-img">
                </div>
                <div class="col-lg-1-7 text-center my-2">
                    <img loading="lazy" src="{{ request()->root() }}/website/photos/tripadvisor/Travellers-Choice-2021.png"
                        alt="2022" class="img-fluid certificate-img">
                </div>
                <div class="col-lg-1-7 text-center my-2">
                    <img loading="lazy" src="{{ request()->root() }}/website/photos/tripadvisor/Travellers-Choice-2025.png"
                        alt="2025" class="img-fluid certificate-img">
                </div>
                <div class="col-lg-1-7 text-center my-2">
                    <img loading="lazy" src="{{ request()->root() }}/website/photos/tripadvisor/Travellers-Choice-2022.png"
                        alt="2023" class="img-fluid certificate-img">
                </div>
                <div class="col-lg-1-7 text-center my-2">
                    <img loading="lazy" src="{{ request()->root() }}/website/photos/tripadvisor/Travellers-Choice-2023.png"
                        alt="2023 duplicate" class="img-fluid certificate-img">
                </div>
                <div class="col-lg-1-7 text-center my-2">
                    <img loading="lazy" src="{{ request()->root() }}/website/photos/tripadvisor/Travellers-Choice-2024-.png"
                        alt="2024" class="img-fluid certificate-img">
                </div>
            </div>

            <!-- Mobile layout -->
            <div class="d-lg-none">
                <!-- First 3 -->
                <div class="row justify-content-center align-items-center">
                    <div class="col-4 text-center my-2">
                        <img loading="lazy"
                            src="{{ request()->root() }}/website/photos/tripadvisor/Travellers-Choice-2019-.png"
                            alt="2021" class="img-fluid certificate-img">
                    </div>
                    <div class="col-4 text-center my-2">
                        <img loading="lazy"
                            src="{{ request()->root() }}/website/photos/tripadvisor/Travellers-Choice-2020.png"
                            alt="2021 duplicate" class="img-fluid certificate-img">
                    </div>
                    <div class="col-4 text-center my-2">
                        <img loading="lazy"
                            src="{{ request()->root() }}/website/photos/tripadvisor/Travellers-Choice-2021.png"
                            alt="2022" class="img-fluid certificate-img">
                    </div>
                </div>

                <!-- Image 4 -->
                <div class="row justify-content-center align-items-center">
                    <div class="col-5 text-center my-2">
                        <img loading="lazy"
                            src="{{ request()->root() }}/website/photos/tripadvisor/Travellers-Choice-2025.png"
                            alt="2025" class="img-fluid certificate-img">
                    </div>
                </div>

                <!-- Last 3 -->
                <div class="row justify-content-center align-items-center">
                    <div class="col-4 text-center my-2">
                        <img loading="lazy"
                            src="{{ request()->root() }}/website/photos/tripadvisor/Travellers-Choice-2022.png"
                            alt="2023" class="img-fluid certificate-img">
                    </div>
                    <div class="col-4 text-center my-2">
                        <img loading="lazy"
                            src="{{ request()->root() }}/website/photos/tripadvisor/Travellers-Choice-2023.png"
                            alt="2023 duplicate" class="img-fluid certificate-img">
                    </div>
                    <div class="col-4 text-center my-2">
                        <img loading="lazy"
                            src="{{ request()->root() }}/website/photos/tripadvisor/Travellers-Choice-2024-.png"
                            alt="2024" class="img-fluid certificate-img">
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose-section">
        <div class="container">
            <h2 class="section-title">Why Choose Us</h2>
            <p class="section-subtitle">Experience the difference that makes us Egypt's premier travel company</p>

            <div class="features-grid">
                <div class="feature-card text-center" style="transform: translateY(0px);">
                    <div class="feature-icon expertise mx-auto">
                        <i class="la la-user-graduate"></i>
                    </div>
                    <h3 class="feature-title">Expert Egyptologists</h3>
                    <p class="feature-description">
                        Our certified Egyptologist guides bring ancient history to life with deep knowledge and
                        passionate storytelling, ensuring an educational and immersive experience.
                    </p>
                </div>

                <div class="feature-card text-center">
                    <div class="feature-icon safety mx-auto">
                        <i class="la la-shield-alt"></i>
                    </div>
                    <h3 class="feature-title">Safety &amp; Security</h3>
                    <p class="feature-description">
                        Your safety is our priority. We maintain the highest safety standards with 24/7 support, modern
                        vehicles, and comprehensive safety protocols.
                    </p>
                </div>

                <div class="feature-card text-center">
                    <div class="feature-icon luxury mx-auto">
                        <i class="la la-star"></i>
                    </div>
                    <h3 class="feature-title">Luxury Experience</h3>
                    <p class="feature-description">
                        From premium accommodations to exclusive access, we provide luxury experiences that exceed
                        expectations and create unforgettable memories.
                    </p>
                </div>

                <div class="feature-card text-center">
                    <div class="feature-icon support mx-auto">
                        <i class="la la-headset"></i>
                    </div>
                    <h3 class="feature-title">Personalized Service</h3>
                    <p class="feature-description">
                        Every journey is tailored to your preferences with dedicated support from planning to departure,
                        ensuring a seamless and personalized experience.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Hot Deals Section -->
    <section id="deals" class="deals-section">
        <div class="container">
            <h2 class="section-title">Featured Experiences</h2>
            <p class="section-subtitle">Discover our most popular tours and create memories that last a lifetime</p>

            <div class="deals-grid">
                <!-- Deal 1 -->
                <div class="deal-card">
                    <div class="deal-image">
                        <div class="deal-badge">Best Seller</div>
                        <div class="deal-price">From $1280</div>
                        <a href="Egypt/package/5-Day-Luxor-to-Aswan-Nile-Cruise-From-Cairo-By-Flight.html">
                            <img src="{{ request()->root() }}/images/15966644721le%20fayan%20nile%20cruise%20ship.png"
                                alt="5 Day Nile Cruise" class="deal-img" loading="lazy">
                        </a>
                    </div>
                    <div class="deal-content">
                        <h3 class="deal-title">
                            <a href="Egypt/package/5-Day-Luxor-to-Aswan-Nile-Cruise-From-Cairo-By-Flight.html">
                                5 Day Luxor to Aswan Nile Cruise
                            </a>
                        </h3>
                        <div class="deal-meta">
                            <span><i class="la la-clock"></i> 5 Days</span>
                            <span><i class="la la-users"></i> Private Tour</span>
                            <span><i class="la la-plane"></i> Flights Included</span>
                        </div>
                        <p class="deal-description">
                            Experience the magic of ancient Egypt on this luxurious Nile cruise from Luxor to Aswan,
                            featuring expert guides and premium accommodations.
                        </p>
                        <div class="deal-features">
                            <span class="feature-tag">Luxury Cruise</span>
                            <span class="feature-tag">All Meals</span>
                            <span class="feature-tag">Expert Guide</span>
                        </div>
                        <a href="Egypt/package/5-Day-Luxor-to-Aswan-Nile-Cruise-From-Cairo-By-Flight.html"
                            class="deal-btn">
                            Explore Journey <i class="la la-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Deal 2 -->
                <div class="deal-card">
                    <div class="deal-image">
                        <div class="deal-price">From $1599</div>
                        <a href="Egypt/package/7-Day-Cairo-and-Nile-Cruise-by-Flight.html">
                            <img src="{{ request()->root() }}/website/photos/spencer-davis-6UWwgpC4n6Y-unsplash.jpg"
                                alt="7 Day Cairo and Nile Cruise" class="deal-img" loading="lazy">
                        </a>
                    </div>
                    <div class="deal-content">
                        <h3 class="deal-title">
                            <a href="Egypt/package/7-Day-Cairo-and-Nile-Cruise-by-Flight.html">
                                7 Day Cairo and Nile Cruise
                            </a>
                        </h3>
                        <div class="deal-meta">
                            <span><i class="la la-clock"></i> 7 Days</span>
                            <span><i class="la la-users"></i> Private Tour</span>
                            <span><i class="la la-map-marker"></i> Cairo + Cruise</span>
                        </div>
                        <p class="deal-description">
                            Discover the wonders of Cairo including the Pyramids and Egyptian Museum, then embark on a
                            luxury Nile cruise experience.
                        </p>
                        <div class="deal-features">
                            <span class="feature-tag">Pyramids Tour</span>
                            <span class="feature-tag">Museum Visit</span>
                            <span class="feature-tag">Nile Cruise</span>
                        </div>
                        <a href="Egypt/package/7-Day-Cairo-and-Nile-Cruise-by-Flight.html" class="deal-btn">
                            Explore Journey <i class="la la-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Deal 3 -->
                <div class="deal-card">
                    <div class="deal-image">
                        <div class="deal-price">From $3292</div>
                        <a href="Egypt/package/10-Day-Jordan-Egypt-Travel-Package.html">
                            <img src="{{ request()->root() }}/website/photos/7xm.xyz857039.jpg"
                                alt="Jordan Egypt Package" class="deal-img" loading="lazy">
                        </a>
                    </div>
                    <div class="deal-content">
                        <h3 class="deal-title">
                            <a href="Egypt/package/10-Day-Jordan-Egypt-Travel-Package.html">
                                10 Day Jordan Egypt Adventure
                            </a>
                        </h3>
                        <div class="deal-meta">
                            <span><i class="la la-clock"></i> 10 Days</span>
                            <span><i class="la la-users"></i> Private Tour</span>
                            <span><i class="la la-globe"></i> Two Countries</span>
                        </div>
                        <p class="deal-description">
                            Epic adventure combining the wonders of Petra in Jordan with Egypt's ancient treasures on
                            one unforgettable journey.
                        </p>
                        <div class="deal-features">
                            <span class="feature-tag">Petra Visit</span>
                            <span class="feature-tag">Multi-Country</span>
                            <span class="feature-tag">VIP Treatment</span>
                        </div>
                        <a href="Egypt/package/10-Day-Jordan-Egypt-Travel-Package.html" class="deal-btn">
                            Explore Journey <i class="la la-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Deal 4 -->
                <div class="deal-card">
                    <div class="deal-image">
                        <div class="deal-badge">Ultra Luxury</div>
                        <div class="deal-price">From $1790</div>
                        <a href="Egypt/cruise/princess-farida-luxury-dahabiya-nile-cruise.html">
                            <img src="{{ request()->root() }}/website/photos/ship-7.jpg" alt="Princess Farida Dahabiya"
                                class="deal-img" loading="lazy">
                        </a>
                    </div>
                    <div class="deal-content">
                        <h3 class="deal-title">
                            <a href="Egypt/cruise/princess-farida-luxury-dahabiya-nile-cruise.html">
                                Princess Farida Luxury Dahabiya
                            </a>
                        </h3>
                        <div class="deal-meta">
                            <span><i class="la la-calendar"></i> Mon/Fri</span>
                            <span><i class="la la-star"></i> Ultra Luxury</span>
                            <span><i class="la la-ship"></i> Dahabiya</span>
                        </div>
                        <p class="deal-description">
                            Experience unparalleled luxury on this intimate Dahabiya sailing yacht with personalized
                            service and exclusive amenities.
                        </p>
                        <div class="deal-features">
                            <span class="feature-tag">Luxury Cruise</span>
                            <span class="feature-tag">Intimate Setting</span>
                            <span class="feature-tag">Premium Service</span>
                        </div>
                        <a href="Egypt/cruise/princess-farida-luxury-dahabiya-nile-cruise.html" class="deal-btn">
                            Explore Journey <i class="la la-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Deal 5 -->
                <div class="deal-card">
                    <div class="deal-image">
                        <div class="deal-price">From $1900</div>
                        <a href="Egypt/cruise/historia-luxury-nile-cruise.html">
                            <img src="{{ request()->root() }}/images/16417397840historia1.jpg"
                                alt="Historia Luxury Cruise" class="deal-img" loading="lazy">
                        </a>
                    </div>
                    <div class="deal-content">
                        <h3 class="deal-title">
                            <a href="Egypt/cruise/historia-luxury-nile-cruise.html">
                                Historia Luxury Nile Cruise
                            </a>
                        </h3>
                        <div class="deal-meta">
                            <span><i class="la la-calendar"></i> Mon/Fri</span>
                            <span><i class="la la-star"></i> 5-Star</span>
                            <span><i class="la la-ship"></i> Modern Luxury</span>
                        </div>
                        <p class="deal-description">
                            Sail aboard one of the Nile's most luxurious vessels with elegant accommodations and
                            world-class amenities.
                        </p>
                        <div class="deal-features">
                            <span class="feature-tag">5-Star Luxury</span>
                            <span class="feature-tag">Gourmet Dining</span>
                            <span class="feature-tag">Spa Services</span>
                        </div>
                        <a href="Egypt/cruise/historia-luxury-nile-cruise.html" class="deal-btn">
                            Explore Journey <i class="la la-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Deal 6 -->
                <div class="deal-card">
                    <div class="deal-image">
                        <div class="deal-price">From $976</div>
                        <a href="Egypt/cruise/Farah-Nile-Cruise.html">
                            <img src="{{ request()->root() }}/images/15964574910MS%20Mayfair%20Nile%20Cruise.jpg"
                                alt="MS Mayfair Cruise" class="deal-img" loading="lazy">
                        </a>
                    </div>
                    <div class="deal-content">
                        <h3 class="deal-title">
                            <a href="Egypt/cruise/Farah-Nile-Cruise.html">
                                MS Mayfair Nile Cruise
                            </a>
                        </h3>
                        <div class="deal-meta">
                            <span><i class="la la-calendar"></i> Mon/Fri</span>
                            <span><i class="la la-star"></i> 5-Star</span>
                            <span><i class="la la-ship"></i> Classic Elegance</span>
                        </div>
                        <p class="deal-description">
                            Experience exceptional service and comfort on this elegant 5-star cruise vessel sailing the
                            legendary Nile River.
                        </p>
                        <div class="deal-features">
                            <span class="feature-tag">Classic Design</span>
                            <span class="feature-tag">Exceptional Value</span>
                            <span class="feature-tag">Professional Service</span>
                        </div>
                        <a href="Egypt/cruise/Farah-Nile-Cruise.html" class="deal-btn">
                            Explore Journey <i class="la la-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Custom Quote Section -->
    <section class="quote-section">
        <div class="container">
            <div class="quote-content">
                <div class="quote-card">
                    <h2 class="quote-title">Need help planning your trip?</h2>
                    <p class="quote-subtitle">
                        Get in touch with our travel experts to create a personalized Egypt experience.
                        We'll design the perfect itinerary based on your interests and preferences.
                    </p>
                    <div class="quote-features">
                        <div class="quote-feature">
                            <i class="la la-check-circle"></i>
                            <span>100% Customizable Itineraries</span>
                        </div>
                        <div class="quote-feature">
                            <i class="la la-user-graduate"></i>
                            <span>Expert Egyptologist Guides</span>
                        </div>
                        <div class="quote-feature">
                            <i class="la la-headset"></i>
                            <span>24/7 Local Support</span>
                        </div>
                        <div class="quote-feature">
                            <i class="la la-dollar"></i>
                            <span>Best Price Guarantee</span>
                        </div>
                    </div>
                    <a href="tailor-made.php.html" class="quote-btn">
                        <i class="la la-paper-plane"></i>
                        Get Custom Quote
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Destinations Section -->
    <section class="destinations-section">
        <div class="container">
            <h2 class="section-title">Explore Extraordinary Destinations</h2>
            <p class="section-subtitle">
                Embark on unforgettable journeys to the world's most captivating destinations with our expert-guided
                tours,
                luxury accommodations, and personalized experiences that create memories to last a lifetime.
            </p>

            <div class="destinations-grid">
                <!-- Egypt -->
                <div class="destination-card">
                    <div class="destination-image">
                        <div class="destination-badge">Most Popular</div>
                        <img src="{{ request()->root() }}/website/photos/Dest/Egypt.jpg" alt="Egypt Destination"
                            class="destination-img" loading="lazy">
                        <div class="destination-overlay">
                            <div class="overlay-content">
                                <i class="la la-eye"></i>
                                <span>Explore Egypt</span>
                            </div>
                        </div>
                    </div>
                    <div class="destination-body">
                        <h3 class="destination-title">
                            <a href="Egypt.html">Ancient Egypt</a>
                        </h3>
                        <p class="destination-description">
                            Discover the land of pharaohs, pyramids, and timeless wonders. From the Great Pyramid of
                            Giza to the temples of Luxor, experience 5,000 years of incredible history with our expert
                            Egyptologist guides.
                        </p>
                        <div class="destination-meta">
                            <div class="meta-item">
                                <i class="la la-clock"></i>
                                <span>1-20 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="la la-map-marker"></i>
                                <span>35+ Sites</span>
                            </div>
                            <div class="meta-item">
                                <i class="la la-users"></i>
                                <span>Private Tours</span>
                            </div>
                        </div>
                        <div class="destination-highlights">
                            <span class="highlight-tag">Pyramids</span>
                            <span class="highlight-tag">Nile Cruise</span>
                            <span class="highlight-tag">Temples</span>
                            <span class="highlight-tag">Museums</span>
                        </div>
                        <div class="destination-footer">
                            <a href="Egypt.html" class="destination-btn">
                                Discover <i class="la la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Jordan -->
                <div class="destination-card">
                    <div class="destination-image">
                        <div class="destination-badge">UNESCO Sites</div>
                        <img src="{{ request()->root() }}/website/photos/Dest/Jordan.jpg" alt="Jordan Destination"
                            class="destination-img" loading="lazy">
                        <div class="destination-overlay">
                            <div class="overlay-content">
                                <i class="la la-eye"></i>
                                <span>Explore Jordan</span>
                            </div>
                        </div>
                    </div>
                    <div class="destination-body">
                        <h3 class="destination-title">
                            <a href="en/Jordan.html">Mystical Jordan</a>
                        </h3>
                        <p class="destination-description">
                            Uncover the rose-red city of Petra, float in the Dead Sea, and explore the dramatic
                            landscapes of Wadi Rum. Jordan offers an enchanting blend of ancient history and natural
                            wonders.
                        </p>
                        <div class="destination-meta">
                            <div class="meta-item">
                                <i class="la la-clock"></i>
                                <span>4-10 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="la la-map-marker"></i>
                                <span>8+ Locations</span>
                            </div>
                            <div class="meta-item">
                                <i class="la la-certificate"></i>
                                <span>UNESCO Sites</span>
                            </div>
                        </div>
                        <div class="destination-highlights">
                            <span class="highlight-tag">Petra</span>
                            <span class="highlight-tag">Dead Sea</span>
                            <span class="highlight-tag">Wadi Rum</span>
                            <span class="highlight-tag">Amman</span>
                        </div>
                        <div class="destination-footer">
                            <a href="en/Jordan.html" class="destination-btn">
                                Discover <i class="la la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Dubai -->
                <div class="destination-card">
                    <div class="destination-image">
                        <div class="destination-badge">Luxury</div>
                        <img src="{{ request()->root() }}/website/photos/Dest/Dubai.jpg" alt="Dubai Destination"
                            class="destination-img" loading="lazy">
                        <div class="destination-overlay">
                            <div class="overlay-content">
                                <i class="la la-eye"></i>
                                <span>Explore Dubai</span>
                            </div>
                        </div>
                    </div>
                    <div class="destination-body">
                        <h3 class="destination-title">
                            <a href="Dubai.html">Modern Dubai</a>
                        </h3>
                        <p class="destination-description">
                            Experience the city of the future with its iconic skyline, luxury shopping, world-class
                            dining, and innovative attractions. Dubai seamlessly blends traditional Arabian culture with
                            cutting-edge modernity.
                        </p>
                        <div class="destination-meta">
                            <div class="meta-item">
                                <i class="la la-clock"></i>
                                <span>3-7 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="la la-building"></i>
                                <span>Modern City</span>
                            </div>
                            <div class="meta-item">
                                <i class="la la-star"></i>
                                <span>Luxury Focus</span>
                            </div>
                        </div>
                        <div class="destination-highlights">
                            <span class="highlight-tag">Burj Khalifa</span>
                            <span class="highlight-tag">Desert Safari</span>
                            <span class="highlight-tag">Shopping</span>
                            <span class="highlight-tag">Marina</span>
                        </div>
                        <div class="destination-footer">
                            <a href="Dubai.html" class="destination-btn">
                                Discover <i class="la la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Morocco -->
                <div class="destination-card">
                    <div class="destination-image">
                        <div class="destination-badge">Cultural</div>
                        <img src="{{ request()->root() }}/website/photos/Dest/Morocco.jpg" alt="Morocco Destination"
                            class="destination-img" loading="lazy">
                        <div class="destination-overlay">
                            <div class="overlay-content">
                                <i class="la la-eye"></i>
                                <span>Explore Morocco</span>
                            </div>
                        </div>
                    </div>
                    <div class="destination-body">
                        <h3 class="destination-title">
                            <a href="Morocco.html">Imperial Morocco</a>
                        </h3>
                        <p class="destination-description">
                            Journey through vibrant souks, majestic palaces, and the golden dunes of the Sahara. Morocco
                            enchants with its rich culture, stunning architecture, and warm hospitality.
                        </p>
                        <div class="destination-meta">
                            <div class="meta-item">
                                <i class="la la-clock"></i>
                                <span>5-12 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="la la-map-marker"></i>
                                <span>Imperial Cities</span>
                            </div>
                            <div class="meta-item">
                                <i class="la la-mountain"></i>
                                <span>Sahara Desert</span>
                            </div>
                        </div>
                        <div class="destination-highlights">
                            <span class="highlight-tag">Marrakech</span>
                            <span class="highlight-tag">Sahara</span>
                            <span class="highlight-tag">Casablanca</span>
                            <span class="highlight-tag">Fez</span>
                        </div>
                        <div class="destination-footer">
                            <a href="Morocco.html" class="destination-btn">
                                Discover <i class="la la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Oman -->
                <div class="destination-card">
                    <div class="destination-image">
                        <div class="destination-badge">Hidden Gem</div>
                        <img src="{{ request()->root() }}/website/photos/Dest/Oman.jpg" alt="Oman Destination"
                            class="destination-img" loading="lazy">
                        <div class="destination-overlay">
                            <div class="overlay-content">
                                <i class="la la-eye"></i>
                                <span>Explore Oman</span>
                            </div>
                        </div>
                    </div>
                    <div class="destination-body">
                        <h3 class="destination-title">
                            <a href="Oman.html">Spectacular Oman</a>
                        </h3>
                        <p class="destination-description">
                            Discover the jewel of Arabia with its dramatic fjords, ancient forts, pristine beaches, and
                            vast deserts. Oman offers authentic Arabian experiences with stunning natural beauty.
                        </p>
                        <div class="destination-meta">
                            <div class="meta-item">
                                <i class="la la-clock"></i>
                                <span>4-9 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="la la-water"></i>
                                <span>Fjords & Beaches</span>
                            </div>
                            <div class="meta-item">
                                <i class="la la-gem"></i>
                                <span>Authentic</span>
                            </div>
                        </div>
                        <div class="destination-highlights">
                            <span class="highlight-tag">Muscat</span>
                            <span class="highlight-tag">Nizwa</span>
                            <span class="highlight-tag">Wahiba Sands</span>
                            <span class="highlight-tag">Musandam</span>
                        </div>
                        <div class="destination-footer">
                            <a href="Oman.html" class="destination-btn">
                                Discover <i class="la la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- African Safari -->
                <div class="destination-card">
                    <div class="destination-image">
                        <div class="destination-badge">Wildlife</div>
                        <img src="{{ request()->root() }}/website/photos/Dest/African-Safari.jpg" alt="African Safari"
                            class="destination-img" loading="lazy">
                        <div class="destination-overlay">
                            <div class="overlay-content">
                                <i class="la la-eye"></i>
                                <span>Explore Safari</span>
                            </div>
                        </div>
                    </div>
                    <div class="destination-body">
                        <h3 class="destination-title">
                            <a href="African-Safari.html">African Safari</a>
                        </h3>
                        <p class="destination-description">
                            Embark on the ultimate wildlife adventure across Africa's most spectacular national parks.
                            Witness the Big Five and experience the raw beauty of the African wilderness.
                        </p>
                        <div class="destination-meta">
                            <div class="meta-item">
                                <i class="la la-clock"></i>
                                <span>6-15 Days</span>
                            </div>
                            <div class="meta-item">
                                <i class="la la-paw"></i>
                                <span>Big Five</span>
                            </div>
                            <div class="meta-item">
                                <i class="la la-camera"></i>
                                <span>Photography</span>
                            </div>
                        </div>
                        <div class="destination-highlights">
                            <span class="highlight-tag">Kenya</span>
                            <span class="highlight-tag">Tanzania</span>
                            <span class="highlight-tag">Big Five</span>
                            <span class="highlight-tag">Migration</span>
                        </div>
                        <div class="destination-footer">
                            <a href="African-Safari.html" class="destination-btn">
                                Discover <i class="la la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <div class="text-center mb-5">
                <div
                    style="background: #00aa6c; color: white; padding: 10px 20px; border-radius: 25px; font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 30px;">
                    <i class="la la-tripadvisor"></i>
                    TripAdvisor Certified Excellence
                </div>
                <h2 class="section-title">Guest Experiences</h2>
                <p class="section-subtitle">Hear from travelers who have experienced the magic of Egypt with us</p>
            </div>

            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="rating-stars">
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                        </div>
                        <span class="verified-badge">Verified</span>
                    </div>
                    <p class="testimonial-text">
                        "This trip did not disappoint! I have traveled to many places and this trip has been my favorite
                        by far. Luxor and Aswan Travel made our trip absolutely perfect."
                    </p>
                    <div class="author-section">
                        <div class="author-avatar">BH</div>
                        <div>
                            <h5 class="author-name">Breanna H</h5>
                            <p class="author-location">
                                <i class="la la-map-marker"></i> United States
                            </p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="rating-stars">
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                        </div>
                        <span class="verified-badge">Verified</span>
                    </div>
                    <p class="testimonial-text">
                        "Loved the trip! Thanks for the amazing journey through The Nile! Wonderful views, exceptional
                        staff and incredible food. Special thanks to our guide Mr. Mohammed."
                    </p>
                    <div class="author-section">
                        <div class="author-avatar">SP</div>
                        <div>
                            <h5 class="author-name">Sergey P</h5>
                            <p class="author-location">
                                <i class="la la-map-marker"></i> Italy
                            </p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="rating-stars">
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                        </div>
                        <span class="verified-badge">Verified</span>
                    </div>
                    <p class="testimonial-text">
                        "Luxor and Aswan Travel exceeded all expectations and gave us an experience of a lifetime. Our
                        personal Egyptologist Hassan was absolutely incredible."
                    </p>
                    <div class="author-section">
                        <div class="author-avatar">DC</div>
                        <div>
                            <h5 class="author-name">Deana C</h5>
                            <p class="author-location">
                                <i class="la la-map-marker"></i> Australia
                            </p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="rating-stars">
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                        </div>
                        <span class="verified-badge">Verified</span>
                    </div>
                    <p class="testimonial-text">
                        "We booked a 2 week luxury private tour and WOW! Our trip was better than we could have
                        imagined. Highly recommend Luxor and Aswan Travel!"
                    </p>
                    <div class="author-section">
                        <div class="author-avatar">GB</div>
                        <div>
                            <h5 class="author-name">GingerSnapBarnes</h5>
                            <p class="author-location">
                                <i class="la la-map-marker"></i> Canada
                            </p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="rating-stars">
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                        </div>
                        <span class="verified-badge">Verified</span>
                    </div>
                    <p class="testimonial-text">
                        "Outstanding service from start to finish. The attention to detail and personalized care made
                        our Egyptian adventure truly unforgettable. Highly recommend!"
                    </p>
                    <div class="author-section">
                        <div class="author-avatar">MR</div>
                        <div>
                            <h5 class="author-name">Michael R</h5>
                            <p class="author-location">
                                <i class="la la-map-marker"></i> United Kingdom
                            </p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="rating-stars">
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                        </div>
                        <span class="verified-badge">Verified</span>
                    </div>
                    <p class="testimonial-text">
                        "Professional, knowledgeable, and incredibly helpful. Our Nile cruise exceeded all expectations.
                        The team went above and beyond for the perfect vacation."
                    </p>
                    <div class="author-section">
                        <div class="author-avatar">SL</div>
                        <div>
                            <h5 class="author-name">Sarah L</h5>
                            <p class="author-location">
                                <i class="la la-map-marker"></i> Germany
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center mt-5">
                <a href="https://www.tripadvisor.com/Attraction_Review-g294205-d12148903-Reviews-Luxor_and_Aswan_Travel-Luxor_Nile_River_Valley.html"
                    target="_blank" class="quote-btn">
                    <i class="la la-external-link"></i>
                    Read All Reviews on TripAdvisor
                </a>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <!-- JavaScript -->
    <script src="{{ request()->root() }}/website/js/new/jquery.min.js" type="bbfb53b5999c6c3f61fbade4-text/javascript">
    </script>
    <script src="{{ request()->root() }}/website/js/new/bootstrap.bundle.min.js"
        type="bbfb53b5999c6c3f61fbade4-text/javascript"></script>


    <!-- Custom JavaScript -->
    <script type="bbfb53b5999c6c3f61fbade4-text/javascript">
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
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
        document.querySelectorAll('.deal-card, .destination-card, .testimonial-card, .feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Newsletter form handling
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            if (email) {
                // Simulate form submission
                alert('Thank you for subscribing! You will receive our latest deals and updates.');
                this.querySelector('input[type="email"]').value = '';
            }
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
            // Update any scroll-dependent animations here
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
            // Reduce animation complexity on mobile
            document.querySelectorAll('.feature-card, .deal-card').forEach(card => {
                card.style.transition = 'transform 0.2s ease, box-shadow 0.2s ease';
            });
        }



        // WhatsApp button interaction
        document.querySelector('.whatsapp-fixed').addEventListener('click', function() {
            // Optional: Track WhatsApp clicks for analytics
            console.log('WhatsApp contact initiated');
        });
    </script>
@endsection
