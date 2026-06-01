@php
    $navigationDestinations = collect($navigationDestinations ?? []);
@endphp

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h4>{{ __('Etro Tours') }}</h4>
                <p>{{ __('Etro Tours best travel agency in Egypt specialized in providing professional advice on planning Travel Packages, Nile Cruises and Day Tours.') }}
                </p>
                <ul class="footer-contact-list">
                    <li><i class="la la-map-marker" style="color: var(--rich-gold); margin-right: 8px;"></i>
                        {{ __('Luxor, Egypt') }}
                    </li>
                    <li><i class="la la-phone" style="color: var(--rich-gold); margin-right: 8px;"></i><a
                            href="tel:+201553383000">+20 15 53383000</a></li>
                    <li><i class="lab la-whatsapp" style="color: var(--rich-gold); margin-right: 8px;"></i><a
                            href="https://wa.me/201553383000" target="_blank">+20 15 53383000</a></li>
                    <li><i class="la la-envelope" style="color: var(--rich-gold); margin-right: 8px;"></i><a
                            href="https://etrotours.com/cdn-cgi/l/email-protection#c2abaca4ad82aeb7baadb0a3aca6a3b1b5a3aceca1adaf"><span
                                class="__cf_email__"
                                data-cfemail="b5dcdbd3daf5d9c0cddac7d4dbd1d4c6c2d4db9bd6dad8">{{ __('[email protected]') }}</span></a>
                    </li>
                </ul>
            </div>

	            <div class="footer-section">
	                <h4>{{ __('Destinations') }}</h4>
	                <ul>
	                    @forelse ($navigationDestinations as $destination)
	                        <li><a href="{{ $destination['url'] }}"><i
	                                    class="las la-chevron-right mr-1"></i>{{ $destination['title'] }}</a></li>
	                    @empty
	                        <li><a href="{{ route('website.destinations.index') }}"><i
	                                    class="las la-chevron-right mr-1"></i>{{ __('View Destinations') }}</a></li>
	                    @endforelse
	                </ul>
	            </div>

            <div class="footer-section">
                <h4>{{ __('General') }}</h4>
                <ul>
                    <li><a href="{{ route('website.pages.show', 'about-etrotours') }}"><i
                                class="las la-chevron-right mr-1"></i>{{ __('About Etro Tours') }}</a></li>
                    <li><a href="{{ route('website.pages.show', 'why-etrotours') }}"><i
                                class="las la-chevron-right mr-1"></i>{{ __('Why Etro Tours') }}</a></li>
                    <li><a href="{{ route('website.pages.show', 'terms-and-conditions') }}"><i
                                class="las la-chevron-right mr-1"></i>{{ __('Terms and Conditions') }}</a></li>
                    <li><a href="{{ route('website.pages.show', 'privacy-policy') }}"><i
                                class="las la-chevron-right mr-1"></i>{{ __('Privacy Policy') }}</a>
                    </li>
                    <li><a href="{{ route('website.pages.show', 'travel-tips') }}"><i
                                class="las la-chevron-right mr-1"></i>{{ __('Travel Tips') }}</a>
                    </li>
                    <li><a href="{{ route('website.blogs.index') }}"><i
                                class="las la-chevron-right mr-1"></i>{{ __('Blog') }}</a>
                    </li>
                </ul>
            </div>

            <div class="footer-section">
                <div class="tripadvisor-award">
                    <div class="award-header">
                        <i class="la la-trophy award-trophy"></i>
                        <span class="award-badge">2026</span>
                    </div>
                    <div class="award-image-container">
                        <img loading="lazy" src="{{ request()->root() }}/website/photos/tripadvisor/TC_L_2026.svg"
                            alt="{{ __('Tripadvisor 2026 Travelers\' Choice Award') }}" class="award-image">
                        <div class="award-glow"></div>
                    </div>
                    <div class="award-content">
                        <h3 class="award-title">
                            <a href="https://www.tripadvisor.com/"
                                target="_blank">
                                {{ __('Travelers\' Choice Award') }}
                            </a>
                        </h3>
                        <p class="award-subtitle">{{ __('Top 10% of Experiences Worldwide') }}</p>
                        <div class="award-stats">
                            <div class="stat-item">
                                <i class="la la-star"></i>
                                <span>{{ __('5.0 Rating') }}</span>
                            </div>
                            <div class="stat-item">
                                <i class="la la-users"></i>
                                <span>{{ __('1000+ Reviews') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom-section"
            style="border-top: 1px solid rgba(197, 149, 91, 0.3); padding-top: 30px; margin-top: 40px;">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="footer-links">
                        <ul style="display: flex;list-style: none;padding: 0;margin: 0;gap: 20px;flex-wrap: wrap;">
                            <li><a href="{{ route('website.pages.show', 'terms-and-conditions') }}"
                                    style="color: rgba(255,255,255,0.8); text-decoration: none; transition: color 0.3s ease;">{{ __('Terms and Conditions') }}</a>
                            </li>
                            <li><a href="{{ route('website.pages.show', 'about-etrotours') }}"
                                    style="color: rgba(255,255,255,0.8); text-decoration: none; transition: color 0.3s ease;">{{ __('About Us') }}</a>
                            </li>
                            <li><a href="{{ route('website.contact.index') }}"
                                    style="color: rgba(255,255,255,0.8); text-decoration: none; transition: color 0.3s ease;">{{ __('Contact Us') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="social-links" style="text-align: right;">
                        <ul
                            style="display: flex; list-style: none; padding: 0; margin: 0; gap: 15px; justify-content: flex-end;">
                            <li><a href="https://www.facebook.com/share/1CwkGsZJXe/?mibextid=wwXIfr" target="_blank"
                                    aria-label="{{ __('Facebook') }}"
                                    style="color: var(--rich-gold); font-size: 1.5rem; transition: all 0.3s ease;"><i
                                        class="lab la-facebook-f"></i></a></li>
                            <li><a href="https://www.instagram.com/etro_tours?igsh=MTl5cXpqeXlpMWtvZg%3D%3D&utm_source=qr" target="_blank"
                                    aria-label="{{ __('Instagram') }}"
                                    style="color: var(--rich-gold); font-size: 1.5rem; transition: all 0.3s ease;"><i
                                        class="lab la-instagram"></i></a></li>
                            <li><a href="https://www.tripadvisor.com/"
                                    target="_blank" aria-label="{{ __('TripAdvisor') }}"
                                    style="color: var(--rich-gold); font-size: 1.5rem; transition: all 0.3s ease;"><i
                                        class="la la-tripadvisor"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom"
            style="border-top: 1px solid rgba(197, 149, 91, 0.2); padding-top: 25px; margin-top: 25px;">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <p style="margin: 0; opacity: 0.8; font-size: 0.95rem;">
                        {{ __('© 2026 Copyright to') }} <a href="{{ route('website.home') }}"
                            style="font-weight: 700; color: var(--rich-gold); text-decoration: none;">{{ __('Etro Tours') }}</a>
                    </p>
                </div>
                <div class="col-lg-5">
                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px;">
                        <span style="font-size: 0.9rem; opacity: 0.8;">{{ __('We Accept') }}</span>
                        <img loading="lazy" width="55" height="20"
                            src="{{ request()->root() }}/website/photos/cards.png" alt="{{ __('Payment Methods') }}"
                            style="opacity: 0.9;">
                    </div>
                </div>
            </div>
        </div>

    </div>



</footer>
