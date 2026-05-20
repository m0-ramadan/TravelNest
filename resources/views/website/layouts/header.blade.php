  @php
      $shoreExcursionsUrl = route('website.tours.all', ['type' => 'shore_excursion']);
  @endphp

  <!-- Enhanced Navigation -->
  <nav class="navbar navbar-expand-lg">
      <div class="container">
          <a class="navbar-brand" href="{{ route('website.home') }}">
              <img class="d-none d-lg-block" src="{{ asset('website/logo/logo-lat.png') }}"
                  alt="{{ __('Etro Tours') }}" width="200" height="64">
              <img class="d-lg-none" src="{{ asset('website/logo/logo-lat.png') }}"
                  alt="{{ __('Etro Tours') }}" width="148" height="48">
          </a>

          <!-- Mobile Actions -->
          <div class="d-lg-none mobile-actions">
              <button type="button" aria-label="{{ __('Dark Mode') }}" title="{{ __('Dark Mode') }}"
                  class="mobile-action-btn theme-toggle-btn" data-theme-toggle data-dark-label="{{ __('Dark Mode') }}"
                  data-light-label="{{ __('Light Mode') }}">
                  <i class="la la-moon"></i>
              </button>
              <a href="{{ route('website.search.index') }}" aria-label="{{ __('Search') }}" class="mobile-action-btn">
                  <i class="la la-search"></i>
              </a>
              <a href="tel:+19172678628" aria-label="{{ __('Call Us') }}" class="mobile-action-btn">
                  <i class="la la-phone"></i>
              </a>
              <a href="viber://chat?number=201004880015" target="_blank" aria-label="{{ __('Viber') }}"
                  class="mobile-action-btn viber">
                  <i class="lab la-viber"></i>
              </a>
              <a href="https://wa.me/201553383000" target="_blank"
                  aria-label="{{ __('WhatsApp') }}" class="mobile-action-btn whatsapp">
                  <i class="lab la-whatsapp"></i>
              </a>
          </div>

          <!-- Mobile Toggle Button -->
          <div class="d-lg-none mobile-toggle" onclick="toggleMobileMenu()">
              <div class="hamburger" id="hamburger">
                  <span></span>
                  <span></span>
                  <span></span>
              </div>
          </div>

          <!-- Desktop Navigation -->
          <div class="d-none d-lg-flex w-100 justify-content-between align-items-center">
              <ul class="navbar-nav mx-auto">
                  <li class="nav-item">
                      <a class="nav-link" href="{{ route('website.home') }}">
                          <i class="la la-home"></i>
                          {{ __('Home') }}
                      </a>
                  </li>
                  <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-bs-toggle="dropdown">
                          <i class="la la-globe"></i>
                          {{-- <i class="la la-map-marker"></i> --}}
                          {{ __('Destinations') }}
                      </a>
                      <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="{{ route('website.destinations.index') }}">
                                  <i class="la la-map-marker"></i> {{ __('Egypt') }}
                              </a></li>
                          <li><a class="dropdown-item" href="{{ route('website.destinations.index') }}">
                                  <i class="la la-map-marker"></i> {{ __('Jordan') }}
                              </a></li>
                          <li><a class="dropdown-item" href="{{ route('website.destinations.index') }}">
                                  <i class="la la-map-marker"></i> {{ __('Dubai') }}
                              </a></li>
                          <li><a class="dropdown-item" href="{{ route('website.destinations.show', 'morocco') }}">
                                  <i class="la la-map-marker"></i> {{ __('Morocco') }}
                              </a></li>
                          <li><a class="dropdown-item" href="{{ route('website.destinations.show', 'oman') }}">
                                  <i class="la la-map-marker"></i> {{ __('Oman') }}
                              </a></li>
                          <li><a class="dropdown-item" href="{{ route('website.destinations.show', 'turkey') }}">
                                  <i class="la la-map-marker"></i> {{ __('Turkey') }}
                              </a></li>
                          <li><a class="dropdown-item"
                                  href="{{ route('website.destinations.show', 'african-safari') }}">
                                  <i class="la la-binoculars"></i> {{ __('African Safari') }}
                              </a></li>
                      </ul>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="{{ route('website.multi_country') }}">
                          <i class="la la-globe-americas"></i>
                          {{ __('Multi Country') }}
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="{{ $shoreExcursionsUrl }}">
                          <i class="la la-ship"></i>
                          {{ __('Shore Excursions') }}
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link special-offer" href="{{ route('website.offers') }}"
                          style="width: max-content;">
                          <i class="la la-fire"></i>
                          {{ __('Travel Deals') }}
                      </a>
                  </li>
              </ul>

              <!-- Desktop Actions -->
              <div class="navbar-actions">
                  <button type="button" class="action-btn theme-toggle-btn" aria-label="{{ __('Dark Mode') }}"
                      title="{{ __('Dark Mode') }}" data-theme-toggle data-dark-label="{{ __('Dark Mode') }}"
                      data-light-label="{{ __('Light Mode') }}">
                      <i class="la la-moon"></i>
                  </button>
                  <a href="{{ route('website.search.index') }}" class="action-btn" aria-label="{{ __('Search') }}">
                      <i class="la la-search"></i>
                  </a>
                  <a href="{{ url('tailor-made') }}" class="btn-tailor">
                      <i class="la la-magic"></i>
                      {{ __('Tailor-made') }}
                  </a>
                  <div class="dropdown language-dropdown">
                      @php
                          $currentLocale = app()->getLocale();
                          $activeLanguages = \Illuminate\Support\Facades\Cache::remember(
                              'active_languages',
                              3600,
                              function () {
                                  return \App\Models\Language::where('is_active', true)->orderBy('sort_order')->get();
                              },
                          );
                      @endphp
                      <a class="language-toggle" href="javascript:void(0)" data-bs-toggle="dropdown">
                          <i class="la la-language"></i>
                          <span>{{ strtoupper($currentLocale) }}</span>
                      </a>
                      <ul class="dropdown-menu dropdown-menu-end language-menu">
                          @foreach ($activeLanguages as $lang)
                              <li>
                                  <a class="dropdown-item {{ $currentLocale === $lang->code ? 'active' : '' }}"
                                      href="{{ route('website.lang.switch', $lang->code) }}">
                                      <span
                                          class="flag-icon flag-icon-{{ $lang->code == 'en' ? 'us' : strtolower($lang->code) }}"></span>
                                      {{ $lang->name }}
                                  </a>
                              </li>
                          @endforeach
                      </ul>
                  </div>
              </div>
          </div>
      </div>
  </nav>

  <!-- Modern Mobile Menu -->
  <div class="modern-mobile-menu" id="modernMobileMenu">
      <div class="mobile-menu-header">
          <div class="mobile-menu-brand">
              <img src="{{ asset('website/logo/logo-lat.png') }}" alt="{{ __('Etro Tours') }}"
                  width="132" height="42">
          </div>
          <button class="mobile-close-btn" onclick="toggleMobileMenu()" aria-label="{{ __('Close Menu') }}">
              <i class="la la-times"></i>
          </button>
      </div>

      <div class="mobile-menu-content">
          <div class="mobile-nav-item">
              <a href="{{ route('website.home') }}" class="mobile-nav-link">
                  <i class="la la-home"></i> {{ __('Home') }}
              </a>
          </div>

          <!-- Destinations Submenu -->
          <div class="mobile-nav-item">
              <div class="mobile-destinations-toggle" onclick="toggleMobileDestinations()">
                  <div style="display: flex; align-items: center;">
                      <i class="la la-globe" style="margin-right: 15px;"></i>
                      {{ __('Destinations') }}
                  </div>
                  <i class="la la-angle-down chevron"></i>
              </div>
              <div class="mobile-destinations-submenu" id="mobileDestinationsSubmenu">
                  <div class="mobile-submenu-item">
                      <a href="{{ route('website.destinations.show', 'egypt') }}" class="mobile-submenu-link">
                          <i class="la la-map-marker"></i> {{ __('Egypt') }}
                      </a>
                  </div>
                  <div class="mobile-submenu-item">
                      <a href="{{ route('website.destinations.show', 'jordan') }}" class="mobile-submenu-link">
                          <i class="la la-map-marker"></i> {{ __('Jordan') }}
                      </a>
                  </div>
                  <div class="mobile-submenu-item">
                      <a href="{{ route('website.destinations.show', 'dubai') }}" class="mobile-submenu-link">
                          <i class="la la-map-marker"></i> {{ __('Dubai') }}
                      </a>
                  </div>
                  <div class="mobile-submenu-item">
                      <a href="{{ route('website.destinations.show', 'morocco') }}" class="mobile-submenu-link">
                          <i class="la la-map-marker"></i> {{ __('Morocco') }}
                      </a>
                  </div>
                  <div class="mobile-submenu-item">
                      <a href="{{ route('website.destinations.show', 'oman') }}" class="mobile-submenu-link">
                          <i class="la la-map-marker"></i> {{ __('Oman') }}
                      </a>
                  </div>
                  <div class="mobile-submenu-item">
                      <a href="{{ route('website.destinations.show', 'turkey') }}" class="mobile-submenu-link">
                          <i class="la la-map-marker"></i> {{ __('Turkey') }}
                      </a>
                  </div>
                  {{-- <div class="mobile-submenu-item">
                      <a href="{{ route('website.destinations.show', 'african-safari') }}"
                          class="mobile-submenu-link">
                          <i class="la la-binoculars"></i> {{ __('African Safari') }}
                      </a>
                  </div> --}}
              </div>
          </div>

          <div class="mobile-nav-item">
              <a href="{{ route('website.multi_country') }}" class="mobile-nav-link">
                  <i class="la la-globe-americas"></i> {{ __('Multi Country') }}
              </a>
          </div>

          <div class="mobile-nav-item">
              <a href="{{ $shoreExcursionsUrl }}" class="mobile-nav-link">
                  <i class="la la-ship"></i> {{ __('Shore Excursions') }}
              </a>
          </div>

          <div class="mobile-nav-item">
              <a href="{{ route('website.offers') }}" class="mobile-nav-link special-deals">
                  <i class="la la-fire"></i> {{ __('Travel Deals') }}
              </a>
          </div>

          <div class="mobile-nav-item">
              <a href="{{ route('website.contact.index') }}" class="mobile-nav-link">
                  <i class="la la-envelope"></i> {{ __('Contact Us') }}
              </a>
          </div>

          <div class="mobile-nav-item">
              <a href="{{ url('tailor-made') }}" class="mobile-nav-link">
                  <i class="la la-magic"></i> {{ __('Tailor-made Trips') }}
              </a>
          </div>

          <!-- Language Submenu -->
          <div class="mobile-nav-item">
              <div class="mobile-language-toggle" onclick="toggleMobileLanguage()">
                  <div style="display: flex; align-items: center;">
                      <i class="la la-language" style="margin-right: 12px;"></i>
                      {{ __('Language') }}
                  </div>
                  <i class="la la-angle-down chevron"></i>
              </div>
              <div class="mobile-language-submenu" id="mobileLanguageSubmenu">
                  @foreach ($activeLanguages as $lang)
                      <div class="mobile-language-item">
                          <a href="{{ route('website.lang.switch', $lang->code) }}"
                              class="mobile-language-link {{ $currentLocale === $lang->code ? 'active' : '' }}">
                              <span
                                  class="flag-icon flag-icon-{{ $lang->code == 'en' ? 'us' : strtolower($lang->code) }}"></span>
                              {{ $lang->name }}
                          </a>
                      </div>
                  @endforeach
              </div>
          </div>

          <div class="mobile-actions-grid">
              <button type="button" class="mobile-action-card theme-toggle-btn" data-theme-toggle
                  aria-label="{{ __('Dark Mode') }}" title="{{ __('Dark Mode') }}"
                  data-dark-label="{{ __('Dark Mode') }}" data-light-label="{{ __('Light Mode') }}">
                  <i class="la la-moon"></i>
                  {{ __('Toggle Theme') }}
              </button>

              <a href="tel:+19172678628" class="mobile-action-card">
                  <i class="la la-phone"></i>
                  {{ __('Call Us') }}
              </a>

              <a href="{{ route('website.search.index') }}" class="mobile-action-card">
                  <i class="la la-search"></i>
                  {{ __('Search') }}
              </a>
          </div>

          <a href="{{ route('website.tailor_made.index') }}" class="mobile-enquiry-btn2">
              <i class="la la-paper-plane"></i> {{ __('Plan Your Journey') }}
          </a>
      </div>
  </div>

  <!-- JavaScript -->
  <script>
      // Enhanced Navigation Functions
      function toggleMobileMenu() {
          const mobileMenu = document.getElementById('modernMobileMenu');
          const hamburger = document.getElementById('hamburger');

          mobileMenu.classList.toggle('active');
          hamburger.classList.toggle('active');

          // Prevent body scroll when menu is open
          if (mobileMenu.classList.contains('active')) {
              document.body.style.overflow = 'hidden';
          } else {
              document.body.style.overflow = '';
              closeAllSubmenus();
          }
      }

      function toggleMobileDestinations() {
          const submenu = document.getElementById('mobileDestinationsSubmenu');
          const icon = document.querySelector('.mobile-destinations-toggle i.chevron');

          submenu.classList.toggle('active');
          icon.classList.toggle('rotated');

          // Close other submenu
          const languageSubmenu = document.getElementById('mobileLanguageSubmenu');
          const languageIcon = document.querySelector('.mobile-language-toggle i.chevron');
          if (languageSubmenu.classList.contains('active')) {
              languageSubmenu.classList.remove('active');
              languageIcon.classList.remove('rotated');
          }
      }

      function toggleMobileLanguage() {
          const submenu = document.getElementById('mobileLanguageSubmenu');
          const icon = document.querySelector('.mobile-language-toggle i.chevron');

          submenu.classList.toggle('active');
          icon.classList.toggle('rotated');

          // Close other submenu
          const destinationsSubmenu = document.getElementById('mobileDestinationsSubmenu');
          const destinationsIcon = document.querySelector('.mobile-destinations-toggle i.chevron');
          if (destinationsSubmenu.classList.contains('active')) {
              destinationsSubmenu.classList.remove('active');
              destinationsIcon.classList.remove('rotated');
          }
      }

      function closeAllSubmenus() {
          const submenus = document.querySelectorAll('.mobile-destinations-submenu, .mobile-language-submenu');
          const icons = document.querySelectorAll(
              '.mobile-destinations-toggle i.chevron, .mobile-language-toggle i.chevron');

          submenus.forEach(submenu => submenu.classList.remove('active'));
          icons.forEach(icon => icon.classList.remove('rotated'));
      }

      // Navbar scroll effect
      window.addEventListener('scroll', function() {
          const navbar = document.querySelector('.navbar');
          if (window.scrollY > 50) {
              navbar.classList.add('scrolled');
          } else {
              navbar.classList.remove('scrolled');
          }
      });

      // Close menu on ESC key
      document.addEventListener('keydown', function(e) {
          if (e.key === 'Escape') {
              const mobileMenu = document.getElementById('modernMobileMenu');
              if (mobileMenu.classList.contains('active')) {
                  toggleMobileMenu();
              }
          }
      });


      // Ripple effect for buttons
      function createRipple(e) {
          const button = e.currentTarget;
          const circle = document.createElement('span');
          const diameter = Math.max(button.clientWidth, button.clientHeight);
          const radius = diameter / 2;

          circle.style.width = circle.style.height = `${diameter}px`;
          circle.style.left = `${e.clientX - button.getBoundingClientRect().left - radius}px`;
          circle.style.top = `${e.clientY - button.getBoundingClientRect().top - radius}px`;
          circle.classList.add('ripple');

          const ripple = button.querySelector('.ripple');
          if (ripple) {
              ripple.remove();
          }

          button.appendChild(circle);
      }

      // Add ripple effect to interactive elements
      document.querySelectorAll('.mobile-action-btn, .btn-tailor, .mobile-enquiry-btn2, .action-btn').forEach(button => {
          button.addEventListener('click', createRipple);
      });

      // Add loading effect to navigation links
      document.querySelectorAll('.nav-link, .mobile-nav-link').forEach(link => {
          link.addEventListener('click', function(e) {

              // ❌ تجاهل أي Dropdown
              if (this.classList.contains('dropdown-toggle')) return;
              if (this.closest('.dropdown')) return;

              const href = this.getAttribute('href');
              if (!href || href === '#' || href === 'javascript:void(0)') return;

              const spinner = document.createElement('span');
              spinner.className = 'nav-loading-spinner';
              this.prepend(spinner);

              setTimeout(() => {
                  spinner.remove();
              }, 1000);
          });
      });

      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
          anchor.addEventListener('click', function(e) {
              const href = this.getAttribute('href');

              // تجاهل #
              if (href === '#') return;

              const target = document.querySelector(href);
              if (!target) return;

              e.preventDefault();

              const offsetTop = target.offsetTop - 80;
              window.scrollTo({
                  top: offsetTop,
                  behavior: 'smooth'
              });
          });
      });
  </script>
