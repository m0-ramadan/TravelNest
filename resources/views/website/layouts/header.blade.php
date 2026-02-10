  <!-- Enhanced Navigation -->
  <nav class="navbar navbar-expand-lg">
      <div class="container">
          <a class="navbar-brand" href="{{ route('website.home') }}">
              <img class="d-none d-lg-block" src="{{ request()->root() }}/website/logo/logo-lat.png"
                  alt="Luxor and Aswan Travel" width="200" height="64">
              <img class="d-lg-none" src="{{ request()->root() }}/website/logo/favicon-lat.webp"
                  alt="Luxor and Aswan Travel" width="48" height="46">
          </a>

          <!-- Mobile Actions -->
          <div class="d-lg-none mobile-actions">
              <a href="search/index.html" aria-label="Search" class="mobile-action-btn">
                  <i class="la la-search"></i>
              </a>
              <a href="tel:+19172678628" aria-label="Call Us" class="mobile-action-btn">
                  <i class="la la-phone"></i>
              </a>
              <a href="viber://chat?number=201004880015" target="_blank" aria-label="Viber"
                  class="mobile-action-btn viber">
                  <i class="lab la-viber"></i>
              </a>
              <a href="https://api.whatsapp.com/send?phone=19172678628" target="_blank" aria-label="WhatsApp"
                  class="mobile-action-btn whatsapp">
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
                          Home
                      </a>
                  </li>
                  <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-bs-toggle="dropdown">
                          <i class="la la-globe"></i>
                          Destinations
                      </a>
                      <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="https://www.luxorandaswan.com/Egypt">
                                  <i class="la la-map-marker"></i> Egypt
                              </a></li>
                          <li><a class="dropdown-item" href="https://www.luxorandaswan.com/Jordan">
                                  <i class="la la-map-marker"></i> Jordan
                              </a></li>
                          <li><a class="dropdown-item" href="https://www.luxorandaswan.com/Dubai">
                                  <i class="la la-map-marker"></i> Dubai
                              </a></li>
                          <li><a class="dropdown-item" href="https://www.luxorandaswan.com/Morocco">
                                  <i class="la la-map-marker"></i> Morocco
                              </a></li>
                          <li><a class="dropdown-item" href="https://www.luxorandaswan.com/Oman">
                                  <i class="la la-map-marker"></i> Oman
                              </a></li>
                          <li><a class="dropdown-item" href="https://www.luxorandaswan.com/Turkey">
                                  <i class="la la-map-marker"></i> Turkey
                              </a></li>
                          <li><a class="dropdown-item" href="https://www.luxorandaswan.com/African-Safari">
                                  <i class="la la-binoculars"></i> African Safari
                              </a></li>
                      </ul>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="multicountries/index.html">
                          <i class="la la-globe-americas"></i>
                          Multi Country
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="shore-excursion/index.html">
                          <i class="la la-ship"></i>
                          Shore Excursions
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link special-offer" href="latest-offers.html" style="width: max-content;">
                          <i class="la la-fire"></i>
                          Travel Deals
                      </a>
                  </li>
              </ul>

              <!-- Desktop Actions -->
              <div class="navbar-actions">
                  <a href="search/index.html" class="action-btn" aria-label="Search">
                      <i class="la la-search"></i>
                  </a>
                  <a href="tailor-made.php.html" class="btn-tailor">
                      <i class="la la-magic"></i>
                      Tailor-made
                  </a>
                  <div class="dropdown language-dropdown">
                      <a class="language-toggle" href="javascript:void(0)" data-bs-toggle="dropdown">
                          <i class="la la-language"></i>
                          <span>EN</span>
                      </a>
                      <ul class="dropdown-menu dropdown-menu-end language-menu">
                          <li><a class="dropdown-item nturl" href="index.html">
                                  <span class="flag-icon flag-icon-us"></span> English
                              </a></li>
                          <li><a class="dropdown-item" href="https://fr.luxorandaswan.com/" target="_blank">
                                  <span class="flag-icon flag-icon-fr"></span> French
                              </a></li>
                          <li><a class="dropdown-item" href="https://de.luxorandaswan.com/" target="_blank">
                                  <span class="flag-icon flag-icon-de"></span> German
                              </a></li>
                          <li><a class="dropdown-item" href="https://es.luxorandaswan.com/" target="_blank">
                                  <span class="flag-icon flag-icon-es"></span> Spanish
                              </a></li>
                          <li><a class="dropdown-item" href="https://it.luxorandaswan.com/" target="_blank">
                                  <span class="flag-icon flag-icon-it"></span> Italian
                              </a></li>
                          <li><a class="dropdown-item" href="https://pt.luxorandaswan.com/" target="_blank">
                                  <span class="flag-icon flag-icon-pt"></span> Portuguese
                              </a></li>
                          <li><a class="dropdown-item" href="https://ru.luxorandaswan.com/" target="_blank">
                                  <span class="flag-icon flag-icon-ru"></span> Russian
                              </a></li>
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
              <img src="{{ request()->root() }}/website/logo/favicon-lat.webp" alt="Logo" width="40"
                  height="30">
              <span>Luxor & Aswan Travel</span>
          </div>
          <button class="mobile-close-btn" onclick="toggleMobileMenu()" aria-label="Close Menu">
              <i class="la la-times"></i>
          </button>
      </div>

      <div class="mobile-menu-content">
          <div class="mobile-nav-item">
              <a href="{{ route('website.home') }}" class="mobile-nav-link">
                  <i class="la la-home"></i> Home
              </a>
          </div>

          <!-- Destinations Submenu -->
          <div class="mobile-nav-item">
              <div class="mobile-destinations-toggle" onclick="toggleMobileDestinations()">
                  <div style="display: flex; align-items: center;">
                      <i class="la la-globe" style="margin-right: 15px;"></i>
                      Destinations
                  </div>
                  <i class="la la-angle-down chevron"></i>
              </div>
              <div class="mobile-destinations-submenu" id="mobileDestinationsSubmenu">
                  <div class="mobile-submenu-item">
                      <a href="https://www.luxorandaswan.com/Egypt" class="mobile-submenu-link">
                          <i class="la la-map-marker"></i> Egypt
                      </a>
                  </div>
                  <div class="mobile-submenu-item">
                      <a href="https://www.luxorandaswan.com/Jordan" class="mobile-submenu-link">
                          <i class="la la-map-marker"></i> Jordan
                      </a>
                  </div>
                  <div class="mobile-submenu-item">
                      <a href="https://www.luxorandaswan.com/Dubai" class="mobile-submenu-link">
                          <i class="la la-map-marker"></i> Dubai
                      </a>
                  </div>
                  <div class="mobile-submenu-item">
                      <a href="https://www.luxorandaswan.com/Morocco" class="mobile-submenu-link">
                          <i class="la la-map-marker"></i> Morocco
                      </a>
                  </div>
                  <div class="mobile-submenu-item">
                      <a href="https://www.luxorandaswan.com/Oman" class="mobile-submenu-link">
                          <i class="la la-map-marker"></i> Oman
                      </a>
                  </div>
                  <div class="mobile-submenu-item">
                      <a href="https://www.luxorandaswan.com/Turkey" class="mobile-submenu-link">
                          <i class="la la-map-marker"></i> Turkey
                      </a>
                  </div>
                  <div class="mobile-submenu-item">
                      <a href="https://www.luxorandaswan.com/African-Safari" class="mobile-submenu-link">
                          <i class="la la-binoculars"></i> African Safari
                      </a>
                  </div>
              </div>
          </div>

          <div class="mobile-nav-item">
              <a href="multicountries/index.html" class="mobile-nav-link">
                  <i class="la la-globe-americas"></i> Multi Country
              </a>
          </div>

          <div class="mobile-nav-item">
              <a href="shore-excursion/index.html" class="mobile-nav-link">
                  <i class="la la-ship"></i> Shore Excursions
              </a>
          </div>

          <div class="mobile-nav-item">
              <a href="latest-offers.html" class="mobile-nav-link special-deals">
                  <i class="la la-fire"></i> Travel Deals
              </a>
          </div>

          <div class="mobile-nav-item">
              <a href="Contact-Us.html" class="mobile-nav-link">
                  <i class="la la-envelope"></i> Contact Us
              </a>
          </div>

          <div class="mobile-nav-item">
              <a href="tailor-made.php.html" class="mobile-nav-link">
                  <i class="la la-magic"></i> Tailor-made Trips
              </a>
          </div>

          <!-- Language Submenu -->
          <div class="mobile-nav-item">
              <div class="mobile-language-toggle" onclick="toggleMobileLanguage()">
                  <div style="display: flex; align-items: center;">
                      <i class="la la-language" style="margin-right: 12px;"></i>
                      Language
                  </div>
                  <i class="la la-angle-down chevron"></i>
              </div>
              <div class="mobile-language-submenu" id="mobileLanguageSubmenu">
                  <div class="mobile-language-item">
                      <a href="index.html" class="mobile-language-link nturl">
                          <span class="flag-icon flag-icon-us"></span> English
                      </a>
                  </div>
                  <div class="mobile-language-item">
                      <a href="https://fr.luxorandaswan.com/" target="_blank" class="mobile-language-link">
                          <span class="flag-icon flag-icon-fr"></span> French
                      </a>
                  </div>
                  <div class="mobile-language-item">
                      <a href="https://de.luxorandaswan.com/" target="_blank" class="mobile-language-link">
                          <span class="flag-icon flag-icon-de"></span> German
                      </a>
                  </div>
                  <div class="mobile-language-item">
                      <a href="https://es.luxorandaswan.com/" target="_blank" class="mobile-language-link">
                          <span class="flag-icon flag-icon-es"></span> Spanish
                      </a>
                  </div>
                  <div class="mobile-language-item">
                      <a href="https://it.luxorandaswan.com/" target="_blank" class="mobile-language-link">
                          <span class="flag-icon flag-icon-it"></span> Italian
                      </a>
                  </div>
                  <div class="mobile-language-item">
                      <a href="https://pt.luxorandaswan.com/" target="_blank" class="mobile-language-link">
                          <span class="flag-icon flag-icon-pt"></span> Portuguese
                      </a>
                  </div>
                  <div class="mobile-language-item">
                      <a href="https://ru.luxorandaswan.com/" target="_blank" class="mobile-language-link">
                          <span class="flag-icon flag-icon-ru"></span> Russian
                      </a>
                  </div>
              </div>
          </div>

          <div class="mobile-actions-grid">
              <a href="tel:+19172678628" class="mobile-action-card">
                  <i class="la la-phone"></i>
                  Call Us
              </a>

              <a href="search/index.html" class="mobile-action-card">
                  <i class="la la-search"></i>
                  Search
              </a>
          </div>

          <a href="tailor-made.php.html" class="mobile-enquiry-btn2">
              <i class="la la-paper-plane"></i> Plan Your Journey
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
