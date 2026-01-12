<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="canonical" href="index.html">
    <meta name="keywords"
        content="luxury egypt tours,nile cruise luxor aswan,egypt travel packages,private egyptologist guide,luxury nile cruise,cairo luxor aswan tours,custom egypt holidays">
    <meta name="description"
        content="Discover Egypt's wonders with luxury tours, premium Nile cruises between Luxor and Aswan, and expert Egyptologist guides. Award-winning travel experiences since 2008.">
    <meta property="og:title" content="Luxor and Aswan Travel - Egypt's Premier Luxury Travel Experience">
    <meta property="og:description"
        content="Discover Egypt's wonders with luxury tours, premium Nile cruises between Luxor and Aswan, and expert Egyptologist guides. Award-winning travel experiences since 2008.">
    <meta property="og:image" content="https://www.luxorandaswan.com/website/photos/home2.jpg">
    <meta property="og:url" content="https://www.luxorandaswan.com/">
    <meta name="twitter:card" content="summary_large_image">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/manifest.json">
    <link rel="shortcut icon" href="favicon/favicon.ico">


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
</head>

<body>

    @include('website.layouts.header')

    @yield('content')

    <!-- Fixed WhatsApp Button -->
    <a href="https://api.whatsapp.com/send?phone=19172678628" target="_blank" class="whatsapp-fixed">
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
                            Why travel with Luxor and Aswan Travel?
                        </h2>
                        <p class="section-subtitle"
                            style="color: var(--warm-gray); font-size: 1.2rem; max-width: 700px; margin: 0 auto 60px; line-height: 1.6;">
                            Your entire vacation is designed around your requirements with expert guidance every step of
                            the way.
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
                            style="width: 80px; height: 80px; background: var(--gradient-gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; font-size: 2.2rem; color: white; box-shadow: var(--shadow-gold); transition: all 0.3s ease;">
                            <i class="la la-cog"></i>
                        </div>
                        <h3 class="choose-title"
                            style="font-family: 'Playfair Display', serif; color: var(--primary-navy); font-size: 1.4rem; font-weight: 600; margin-bottom: 20px;">
                            100% Tailor made</h3>
                        <div class="choose-features">
                            <div class="feature-item"
                                style="padding: 12px 0; border-bottom: 1px solid rgba(197, 149, 91, 0.2); color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                Your entire vacation is designed around your requirements
                            </div>
                            <div class="feature-item"
                                style="padding: 12px 0; border-bottom: 1px solid rgba(197, 149, 91, 0.2); color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                Explore your interests at your own speed
                            </div>
                            <div class="feature-item"
                                style="padding: 12px 0; border-bottom: 1px solid rgba(197, 149, 91, 0.2); color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                Select your preferred style of accommodations
                            </div>
                            <div class="feature-item"
                                style="padding: 12px 0; color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                Create the perfect trip with the help of our specialists
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
                            style="width: 80px; height: 80px; background: var(--gradient-gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; font-size: 2.2rem; color: white; box-shadow: var(--shadow-gold); transition: all 0.3s ease;">
                            <i class="la la-lightbulb"></i>
                        </div>
                        <h3 class="choose-title"
                            style="font-family: 'Playfair Display', serif; color: var(--primary-navy); font-size: 1.4rem; font-weight: 600; margin-bottom: 20px;">
                            Expert knowledge</h3>
                        <div class="choose-features">
                            <div class="feature-item"
                                style="padding: 12px 0; border-bottom: 1px solid rgba(197, 149, 91, 0.2); color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                All our specialists have traveled extensively or lived in their specialist regions,
                                We're with you every step of the way
                            </div>
                            <div class="feature-item"
                                style="padding: 12px 0; border-bottom: 1px solid rgba(197, 149, 91, 0.2); color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                The same specialist will handle your trip from start to finish
                            </div>
                            <div class="feature-item"
                                style="padding: 12px 0; color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                Make the most of your time and budget
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
                            style="width: 80px; height: 80px; background: var(--gradient-gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; font-size: 2.2rem; color: white; box-shadow: var(--shadow-gold); transition: all 0.3s ease;">
                            <i class="la la-user-graduate"></i>
                        </div>
                        <h3 class="choose-title"
                            style="font-family: 'Playfair Display', serif; color: var(--primary-navy); font-size: 1.4rem; font-weight: 600; margin-bottom: 20px;">
                            The best guides</h3>
                        <div class="choose-features">
                            <div class="feature-item"
                                style="padding: 12px 0; border-bottom: 1px solid rgba(197, 149, 91, 0.2); color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                Make the difference between a good trip and an outstanding one
                            </div>
                            <div class="feature-item"
                                style="padding: 12px 0; border-bottom: 1px solid rgba(197, 149, 91, 0.2); color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                Our leaders will be there to ensure your safety and wellbeing is the number one priority
                            </div>
                            <div class="feature-item"
                                style="padding: 12px 0; color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                Offering more than just dates and names, they strive to offer real insight into their
                                country
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
                            style="width: 80px; height: 80px; background: var(--gradient-gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; font-size: 2.2rem; color: white; box-shadow: var(--shadow-gold); transition: all 0.3s ease;">
                            <i class="la la-shield-alt"></i>
                        </div>
                        <h3 class="choose-title"
                            style="font-family: 'Playfair Display', serif; color: var(--primary-navy); font-size: 1.4rem; font-weight: 600; margin-bottom: 20px;">
                            Fully protected</h3>
                        <div class="choose-features">
                            <div class="feature-item"
                                style="padding: 12px 0; border-bottom: 1px solid rgba(197, 149, 91, 0.2); color: var(--warm-gray); font-size: 0.95rem; line-height: 1.6;">
                                Secure Payment - Use your debit card or credit card. Your transactions are protected by
                                3D Secure and SecureCode.
                            </div>
                            <div class="feature-item" style="padding: 12px 0; text-align: center;">
                                <img loading="lazy" src="flags/cybersource.png" height="100" width="150"
                                    alt="Cybersource Security" style="opacity: 0.8;">
                                <img loading="lazy" src="flags/mpgs.webp" height="100" width="150"
                                    alt="Cybersource Security" style="opacity: 0.8;">
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
                <div class="cta-icon-container">
                    <i class="la la-phone"></i>
                </div>

                <div class="cta-content-wrapper">
                    <div class="cta-text-content">
                        <h2 class="cta-title">Ready to Plan Your Dream Cruise?</h2>
                        <p class="cta-subtitle">Speak with our Egypt specialists for your perfect luxury journey.</p>

                        <div class="trust-features">
                            <div class="trust-feature">
                                <i class="la la-shield-alt"></i>
                                <span>Free Consultation</span>
                            </div>
                            <div class="trust-feature">
                                <i class="la la-clock"></i>
                                <span>24/7 Support</span>
                            </div>
                            <div class="trust-feature">
                                <i class="la la-award"></i>
                                <span>Best Price Guarantee</span>
                            </div>
                        </div>
                    </div>

                    <a href="Contact-Us.html" class="luxury-cta-btn">
                        <i class="la la-calendar-check"></i>
                        Start Planning
                    </a>
                </div>
            </div>
        </div>
    </section>

    @include('website.layouts.footer')


    @yield('js')

</body>

</html>
