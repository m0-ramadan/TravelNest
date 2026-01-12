   <!-- Include Header -->
   <style>
       /* Enhanced Minified Navigation CSS - Updated with Scroll Fixes */
       .dropdown-menu {
           background: #fff;
           border: none;
           border-radius: 12px;
           box-shadow: 0 10px 40px rgba(0, 0, 0, .1);
           padding: 8px;
           margin-top: 8px;
           border: 1px solid rgba(197, 149, 91, .1);
           min-width: 200px;
           opacity: 0;
           visibility: hidden;
           transform: translateY(-10px);
           transition: all .3s ease;
           display: block !important
       }

       .nav-item.dropdown,
       .language-dropdown {
           position: relative
       }

       .nav-item.dropdown .dropdown-toggle,
       .language-dropdown .language-toggle {
           position: relative
       }

       .nav-item.dropdown .dropdown-toggle::after {
           display: none
       }

       .language-dropdown .dropdown-menu {
           opacity: 0;
           visibility: hidden;
           transform: translateY(-10px);
           transition: all .3s ease;
           display: block !important;
           right: 0;
           left: auto;
           transform-origin: top right
       }

       .language-dropdown .dropdown-menu.show {
           transform: translateY(0)
       }

       .mobile-destinations-toggle,
       .mobile-language-toggle {
           display: flex;
           align-items: center;
           justify-content: space-between;
           padding: 14px 18px;
           background: rgba(255, 255, 255, .05);
           border: 2px solid transparent;
           border-radius: 15px;
           color: #fff !important;
           text-decoration: none;
           font-weight: 500;
           font-size: .9rem;
           transition: all .3s ease;
           position: relative;
           overflow: hidden;
           cursor: pointer
       }

       .mobile-destinations-toggle::before,
       .mobile-language-toggle::before {
           content: '';
           position: absolute;
           left: 0;
           top: 0;
           width: 4px;
           height: 100%;
           background: var(--rich-gold);
           transform: scaleY(0);
           transition: transform .3s ease
       }

       .mobile-destinations-toggle:hover::before,
       .mobile-language-toggle:hover::before {
           transform: scaleY(1)
       }

       .mobile-destinations-toggle:hover,
       .mobile-language-toggle:hover {
           background: rgba(197, 149, 91, .2);
           border-color: rgba(197, 149, 91, .5);
           color: var(--rich-gold) !important;
           transform: translateX(10px)
       }

       .mobile-destinations-toggle i.chevron,
       .mobile-language-toggle i.chevron {
           transition: transform .3s ease;
           color: var(--rich-gold);
           font-size: 1rem
       }

       .mobile-destinations-toggle i.chevron.rotated,
       .mobile-language-toggle i.chevron.rotated {
           transform: rotate(180deg)
       }

       .mobile-destinations-submenu,
       .mobile-language-submenu {
           max-height: 0;
           overflow: hidden;
           transition: max-height .4s ease;
           margin-left: 20px;
           margin-top: 8px
       }

       .mobile-destinations-submenu.active,
       .mobile-language-submenu.active {
           max-height: 600px;
           overflow-y: auto
       }

       .mobile-submenu-item,
       .mobile-language-item {
           margin-bottom: 6px
       }

       .mobile-submenu-link,
       .mobile-language-link {
           display: flex;
           align-items: center;
           padding: 12px 16px;
           background: rgba(255, 255, 255, .03);
           border: 1px solid rgba(197, 149, 91, .1);
           border-radius: 10px;
           color: rgba(255, 255, 255, .8) !important;
           text-decoration: none;
           font-weight: 600;
           font-size: .9rem;
           transition: all .3s ease;
           position: relative;
           gap: 8px
       }

       .mobile-submenu-link:hover,
       .mobile-language-link:hover {
           background: rgba(197, 149, 91, .15);
           border-color: rgba(197, 149, 91, .3);
           color: var(--rich-gold) !important;
           transform: translateX(8px)
       }

       .mobile-language-link {
           font-weight: 400
       }

       .mobile-language-link span {
           margin-right: 12px;
           font-size: 1.1rem
       }

       .navbar {
           background: var(--gradient-hero);
           backdrop-filter: blur(25px);
           -webkit-backdrop-filter: blur(25px);
           border-bottom: 1px solid rgba(197, 149, 91, .2);
           box-shadow: 0 4px 30px rgba(28, 50, 92, .1);
           position: fixed;
           top: 0;
           width: 100%;
           z-index: 1001;
           padding: 10px 0;
           transition: all .4s cubic-bezier(.4, 0, .2, 1)
       }

       .navbar-brand {
           transition: all .3s ease;
           padding: 0
       }

       .navbar-brand:hover {
           transform: scale(1.02)
       }

       .navbar-brand img {
           transition: all .3s ease;
           filter: brightness(1.1)
       }

       .mobile-actions {
           display: flex;
           align-items: center;
           gap: 12px;
           margin-right: 8px
       }

       .mobile-action-btn {
           width: 44px;
           height: 44px;
           display: flex;
           align-items: center;
           justify-content: center;
           background: linear-gradient(135deg, rgba(197, 149, 91, .15), rgba(197, 149, 91, .08));
           border-radius: 12px;
           color: var(--rich-gold) !important;
           font-size: 1.3rem;
           text-decoration: none;
           transition: all .4s cubic-bezier(.4, 0, .2, 1);
           border: 1px solid rgba(197, 149, 91, .25);
           box-shadow: 0 2px 8px rgba(0, 0, 0, .1);
           position: relative;
           overflow: hidden
       }

       .mobile-action-btn::before {
           content: '';
           position: absolute;
           top: 0;
           left: -100%;
           width: 100%;
           height: 100%;
           background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .2), transparent);
           transition: left .6s ease
       }

       .mobile-action-btn:hover::before {
           left: 100%
       }

       .mobile-action-btn:hover {
           background: var(--rich-gold);
           color: #fff !important;
           transform: translateY(-2px) scale(1.05);
           box-shadow: 0 6px 20px rgba(197, 149, 91, .4);
           border-color: var(--rich-gold)
       }

       .mobile-action-btn:active {
           transform: translateY(0) scale(.98)
       }

       .mobile-action-btn.viber:hover {
           background: linear-gradient(135deg, #665cac, #7c6bb8);
           border-color: #665cac;
           box-shadow: 0 6px 20px rgba(102, 92, 172, .4)
       }

       .mobile-action-btn.whatsapp:hover {
           background: linear-gradient(135deg, #25d366, #1faa52);
           border-color: #25d366;
           box-shadow: 0 6px 20px rgba(37, 211, 102, .4)
       }

       .navbar-nav {
           align-items: center
       }

       .navbar-nav .nav-link {
           color: rgba(255, 255, 255, .9) !important;
           font-weight: 500;
           font-size: .95rem;
           padding: 8px 16px;
           margin: 0 2px;
           border-radius: 8px;
           transition: all .3s cubic-bezier(.4, 0, .2, 1);
           position: relative;
           text-decoration: none
       }

       .navbar-nav .nav-link:hover {
           color: var(--rich-gold) !important;
           background: rgba(197, 149, 91, .1);
           transform: translateY(-1px)
       }

       .navbar-nav .nav-link i {
           margin-right: 4px;
           font-size: .9rem
       }

       .nav-link.special-offer {
           background: linear-gradient(135deg, #e8235e, #d81159) !important;
           color: #fff !important;
           font-weight: 600;
           border-radius: 20px;
           padding: 8px 16px;
           margin: 0 8px;
           box-shadow: 0 2px 8px rgba(232, 35, 94, .3)
       }

       .nav-link.special-offer:hover {
           background: linear-gradient(135deg, #d81159, #c70e4a) !important;
           color: #fff !important;
           transform: translateY(-2px);
           box-shadow: 0 4px 15px rgba(232, 35, 94, .4)
       }

       .dropdown-item {
           color: var(--primary-navy);
           padding: 10px 16px;
           border-radius: 8px;
           margin: 2px 0;
           transition: all .3s ease;
           display: flex;
           align-items: center;
           gap: 8px;
           font-size: .9rem
       }

       .dropdown-item:hover {
           background: linear-gradient(135deg, var(--light-sand), rgba(197, 149, 91, .1));
           color: var(--primary-navy);
           transform: translateX(4px)
       }

       .dropdown-item i {
           color: var(--rich-gold);
           width: 16px
       }

       .navbar-actions {
           display: flex;
           align-items: center;
           gap: 12px
       }

       .action-btn {
           width: 36px;
           height: 36px;
           display: flex;
           align-items: center;
           justify-content: center;
           background: rgba(255, 255, 255, .1);
           border-radius: 8px;
           color: rgba(255, 255, 255, .8);
           font-size: 1.1rem;
           text-decoration: none;
           transition: all .3s ease;
           border: 1px solid rgba(255, 255, 255, .1)
       }

       .action-btn:hover {
           background: var(--rich-gold);
           color: #fff;
           transform: translateY(-1px);
           box-shadow: 0 4px 12px rgba(197, 149, 91, .3)
       }

       .btn-tailor {
           background: var(--gradient-gold);
           color: var(--primary-navy) !important;
           padding: 8px 16px;
           border-radius: 20px;
           font-weight: 600;
           font-size: .85rem;
           text-decoration: none;
           border: 1px solid rgba(197, 149, 91, .3);
           display: flex;
           align-items: center;
           gap: 6px
       }

       .btn-tailor:hover {
           background: var(--warm-bronze);
           color: var(--primary-navy) !important;
           transform: translateY(-2px);
           box-shadow: 0 6px 20px rgba(197, 149, 91, .4)
       }

       .language-toggle {
           color: rgba(255, 255, 255, .9) !important;
           padding: 6px 12px;
           border-radius: 20px;
           transition: all .3s ease;
           display: flex;
           align-items: center;
           gap: 6px;
           font-size: .85rem;
           text-decoration: none;
           background: rgba(255, 255, 255, .1);
           border: 1px solid rgba(255, 255, 255, .1)
       }

       .language-toggle:hover {
           background: rgba(197, 149, 91, .2);
           color: var(--rich-gold) !important
       }

       .mobile-toggle {
           display: none;
           background: rgba(197, 149, 91, .2);
           border: 2px solid var(--rich-gold);
           border-radius: 12px;
           padding: 8px;
           cursor: pointer;
           transition: all .3s ease;
           position: relative;
           z-index: 1060
       }

       .mobile-toggle:hover {
           background: var(--rich-gold)
       }

       .hamburger {
           width: 30px;
           height: 20px;
           position: relative;
           transform: rotate(0deg);
           transition: .5s ease-in-out;
           cursor: pointer
       }

       .hamburger span {
           display: block;
           position: absolute;
           height: 3px;
           width: 100%;
           background: #fff;
           border-radius: 9px;
           opacity: 1;
           left: 0;
           transform: rotate(0deg);
           transition: .25s ease-in-out
       }

       .hamburger span:nth-child(1) {
           top: 0
       }

       .hamburger span:nth-child(2) {
           top: 8px
       }

       .hamburger span:nth-child(3) {
           top: 16px
       }

       .hamburger.active span:nth-child(1) {
           top: 8px;
           transform: rotate(135deg)
       }

       .hamburger.active span:nth-child(2) {
           opacity: 0;
           left: -60px
       }

       .hamburger.active span:nth-child(3) {
           top: 8px;
           transform: rotate(-135deg)
       }

       .modern-mobile-menu {
           position: fixed;
           top: 0;
           left: 0;
           width: 100%;
           height: 100vh;
           background: var(--gradient-hero);
           backdrop-filter: blur(20px);
           z-index: 1055;
           opacity: 0;
           visibility: hidden;
           transform: translateX(-100%);
           transition: all .4s ease;
           overflow-y: auto;
           overflow-x: hidden;
           -webkit-overflow-scrolling: touch;
           scroll-behavior: smooth
       }

       .modern-mobile-menu.active {
           opacity: 1;
           visibility: visible;
           transform: translateX(0)
       }

       .mobile-menu-header {
           position: fixed;
           top: 0;
           left: 0;
           right: 0;
           background: var(--gradient-hero);
           backdrop-filter: blur(20px);
           padding: 20px 30px;
           display: flex;
           align-items: center;
           justify-content: space-between;
           border-bottom: 1px solid rgba(197, 149, 91, .3);
           z-index: 1060
       }

       .mobile-menu-brand {
           display: flex;
           align-items: center;
           gap: 12px;
           color: #fff;
           font-weight: 600
       }

       .mobile-menu-brand img {
           filter: brightness(1.1);
           height: auto
       }

       .mobile-close-btn {
           width: 44px;
           height: 44px;
           background: rgba(197, 149, 91, .2);
           border: 2px solid var(--rich-gold);
           border-radius: 12px;
           color: #fff;
           font-size: 1.5rem;
           cursor: pointer;
           transition: all .3s ease;
           display: flex;
           align-items: center;
           justify-content: center
       }

       .mobile-close-btn:hover {
           background: var(--rich-gold);
           transform: scale(1.05)
       }

       .mobile-menu-content {
           padding: 120px 30px 150px;
           max-width: 400px;
           min-height: calc(100vh - 120px);
           position: relative
       }

       .mobile-nav-item {
           margin-bottom: 6px
       }

       .mobile-nav-link {
           display: flex;
           align-items: center;
           padding: 14px 18px;
           background: rgba(255, 255, 255, .05);
           border: 2px solid transparent;
           border-radius: 15px;
           color: #fff !important;
           text-decoration: none;
           font-weight: 500;
           font-size: .9rem;
           transition: all .3s ease;
           position: relative;
           overflow: hidden
       }

       .mobile-nav-link::before {
           content: '';
           position: absolute;
           left: 0;
           top: 0;
           width: 4px;
           height: 100%;
           background: var(--rich-gold);
           transform: scaleY(0);
           transition: transform .3s ease
       }

       .mobile-nav-link:hover::before {
           transform: scaleY(1)
       }

       .mobile-nav-link:hover {
           background: rgba(197, 149, 91, .2);
           border-color: rgba(197, 149, 91, .5);
           color: var(--rich-gold) !important;
           transform: translateX(10px)
       }

       .mobile-nav-link i {
           margin-right: 12px;
           width: 18px;
           font-size: 1rem;
           color: var(--rich-gold)
       }

       .mobile-nav-link.special-deals {
           background: linear-gradient(135deg, #e8235e, #d81159);
           color: #fff !important;
           font-weight: 600;
           border-color: #e8235e
       }

       .mobile-nav-link.special-deals:hover {
           background: linear-gradient(135deg, #d81159, #c70e4a);
           color: #fff !important;
           transform: translateX(10px);
           box-shadow: 0 4px 15px rgba(232, 35, 94, .4)
       }

       .mobile-actions-grid {
           display: grid;
           grid-template-columns: 1fr 1fr;
           gap: 15px;
           margin-top: 25px;
           padding-top: 25px;
           border-top: 1px solid rgba(197, 149, 91, .3)
       }

       .mobile-action-card {
           background: rgba(255, 255, 255, .1);
           border: 2px solid rgba(197, 149, 91, .3);
           border-radius: 15px;
           padding: 16px;
           text-align: center;
           color: #fff;
           text-decoration: none;
           font-size: .85rem;
           transition: all .3s ease
       }

       .mobile-action-card:hover {
           background: rgba(197, 149, 91, .2);
           border-color: var(--rich-gold);
           color: #fff;
           transform: translateY(-5px)
       }

       .mobile-action-card i {
           font-size: 1.5rem;
           margin-bottom: 8px;
           display: block;
           color: var(--rich-gold)
       }

       .mobile-enquiry-btn2 {
           background: var(--gradient-gold);
           color: var(--primary-navy) !important;
           border: none;
           padding: 18px 25px;
           border-radius: 25px;
           font-weight: 700;
           text-decoration: none;
           margin-top: 25px;
           display: block;
           text-align: center;
           transition: all .3s ease;
           box-shadow: var(--shadow-gold)
       }

       .mobile-enquiry-btn2:hover {
           transform: translateY(-3px);
           box-shadow: 0 10px 30px rgba(197, 149, 91, .5);
           color: var(--primary-navy) !important
       }

       .language-menu .flag-icon {
           width: 16px;
           height: 12px;
           margin-right: 8px;
           border-radius: 2px
       }

       .navbar-toggler {
           display: none !important
       }

       .navbar-collapse {
           display: none !important
       }

       @media (max-width:991.98px) {

           .navbar-nav,
           .navbar-actions {
               display: none
           }

           .mobile-toggle {
               display: block
           }
       }

       @media (max-width:768px) {
           .navbar {
               padding: 12px 0
           }

           .mobile-actions {
               gap: 10px;
               margin-right: 6px
           }

           .mobile-action-btn {
               width: 42px;
               height: 42px;
               font-size: 1.25rem;
               border-radius: 11px
           }

           .mobile-menu-content {
               padding: 110px 20px 150px
           }
       }

       @media (max-width:480px) {
           .mobile-actions {
               gap: 8px
           }

           .mobile-action-btn {
               width: 40px;
               height: 40px;
               font-size: 1.6rem;
               border-radius: 10px
           }

           .mobile-menu-content {
               padding: 100px 15px 150px
           }

           .mobile-menu-header {
               padding: 15px 20px
           }

           .mobile-menu-brand {
               gap: 8px;
               font-size: .9rem
           }

           .mobile-close-btn {
               width: 40px;
               height: 40px;
               font-size: 1.3rem
           }

           .mobile-actions-grid {
               grid-template-columns: 1fr 1fr;
               gap: 12px
           }

           .mobile-action-card {
               padding: 14px;
               font-size: .8rem
           }

           .mobile-action-card i {
               font-size: 1.3rem;
               margin-bottom: 6px
           }
       }

       .demo-content {
           margin-top: 80px;
           padding: 60px 20px;
           text-align: center;
           background: var(--gradient-elegant);
           min-height: 80vh;
           display: flex;
           align-items: center;
           justify-content: center
       }

       .demo-card {
           background: #fff;
           border-radius: 25px;
           padding: 50px;
           box-shadow: var(--shadow-dramatic);
           max-width: 800px
       }

       .demo-title {
           font-family: 'Playfair Display', serif;
           color: var(--primary-navy);
           font-size: 2.5rem;
           margin-bottom: 20px
       }

       .demo-subtitle {
           color: var(--warm-gray);
           font-size: 1.2rem;
           margin-bottom: 30px
       }

       .demo-features {
           display: grid;
           grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
           gap: 20px;
           margin-top: 40px
       }

       .demo-feature {
           padding: 20px;
           background: var(--light-sand);
           border-radius: 15px;
           text-align: center
       }

       .demo-feature i {
           color: var(--rich-gold);
           font-size: 2rem;
           margin-bottom: 10px
       }

       .demo-feature h4 {
           color: var(--primary-navy);
           margin-bottom: 10px;
           font-weight: 600
       }
   </style>
   <!-- Updated Navigation with New Mobile Menu -->
   <nav class="navbar navbar-expand-lg">
       <div class="container">
           <a class="navbar-brand" href="index.html">
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

           <!-- New Modern Mobile Toggle -->
           <div class="d-lg-none mobile-toggle"
               onclick="if (!window.__cfRLUnblockHandlers) return false; toggleMobileMenu()"
               data-cf-modified-bbfb53b5999c6c3f61fbade4-="">
               <div class="hamburger" id="hamburger">
                   <span></span>
                   <span></span>
                   <span></span>
               </div>
           </div>

           <!-- Desktop Navigation - Side by Side Layout -->
           <div class="d-none d-lg-flex w-100 justify-content-between align-items-center">
               <ul class="navbar-nav mx-auto">
                   <li class="nav-item">
                       <a class="nav-link" href="{{ route('website.home') }}">Home</a>
                   </li>
 <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            Destinations <i class="la la-angle-down"></i>
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
                       <a class="nav-link" href="multicountries/index.html">Multi Country</a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link" href="shore-excursion/index.html">Shore Excursions</a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link special-offer" href="latest-offers.html">
                           <i class="la la-fire"></i> Travel Deals
                       </a>
                   </li>
               </ul>

               <!-- Desktop Actions -->
               <div class="navbar-actions">
                   <a href="search/index.html" class="action-btn" aria-label="Search">
                       <i class="la la-search"></i>
                   </a>
                   <a href="tailor-made.php.html" class="btn-tailor">
                       <i class="la la-magic"></i> Tailor-made
                   </a>
                   <div class="dropdown language-dropdown">
                       <a class="language-toggle" href="index.html#" data-bs-toggle="dropdown">
                           <i class="la la-language"></i>
                           <span>EN</span>
                           <i class="la la-angle-down"></i>
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

   <!-- New Modern Mobile Menu -->
   <div class="modern-mobile-menu" id="modernMobileMenu">
       <!-- Mobile Menu Header with Close Button -->
       <div class="mobile-menu-header">
           <div class="mobile-menu-brand">
               <img src="logo/favicon-lat.webp" alt="Logo" width="40" height="30">
               <span>Luxor & Aswan Travel</span>
           </div>
           <button class="mobile-close-btn"
               onclick="if (!window.__cfRLUnblockHandlers) return false; toggleMobileMenu()" aria-label="Close Menu"
               data-cf-modified-bbfb53b5999c6c3f61fbade4-="">
               <i class="la la-times"></i>
           </button>
       </div>

       <div class="mobile-menu-content">
           <div class="mobile-nav-item">
               <a href="index.html" class="mobile-nav-link">
                   <i class="la la-home"></i> Home
               </a>
           </div>

           <!-- Destinations with Submenu -->
           <div class="mobile-nav-item">
               <div class="mobile-destinations-toggle"
                   onclick="if (!window.__cfRLUnblockHandlers) return false; toggleMobileDestinations()"
                   data-cf-modified-bbfb53b5999c6c3f61fbade4-="">
                   <div style="display: flex; align-items: center;">
                       <i class="la la-map-marker"
                           style="margin-right: 15px; width: 20px; font-size: 1.1rem; color: var(--rich-gold);"></i>
                       Destinations
                   </div>
                   <i class="la la-angle-down chevron"></i>
               </div>
               <div class="mobile-destinations-submenu" id="mobileDestinationsSubmenu">
                   <div class="mobile-submenu-item">
                       <a href="Egypt.html" class="mobile-submenu-link">
                           <i class="la la-map-marker"></i> Egypt
                       </a>
                   </div>
                   <div class="mobile-submenu-item">
                       <a href="en/Jordan.html" class="mobile-submenu-link">
                           <i class="la la-map-marker"></i> Jordan
                       </a>
                   </div>
                   <div class="mobile-submenu-item">
                       <a href="Dubai.html" class="mobile-submenu-link">
                           <i class="la la-map-marker"></i> Dubai
                       </a>
                   </div>
                   <div class="mobile-submenu-item">
                       <a href="Morocco.html" class="mobile-submenu-link">
                           <i class="la la-map-marker"></i> Morocco
                       </a>
                   </div>
                   <div class="mobile-submenu-item">
                       <a href="Oman.html" class="mobile-submenu-link">
                           <i class="la la-map-marker"></i> Oman
                       </a>
                   </div>
                   <div class="mobile-submenu-item">
                       <a href="Turkey.html" class="mobile-submenu-link">
                           <i class="la la-map-marker"></i> Turkey
                       </a>
                   </div>
                   <div class="mobile-submenu-item">
                       <a href="African-Safari.html" class="mobile-submenu-link">
                           <i class="la la-binoculars"></i> African Safari
                       </a>
                   </div>
               </div>
           </div>

           <div class="mobile-nav-item">
               <a href="multicountries/index.html" class="mobile-nav-link">
                   <i class="la la-globe"></i> Multi Country
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

           <!-- Language Selection with Submenu -->
           <div class="mobile-nav-item">
               <div class="mobile-language-toggle"
                   onclick="if (!window.__cfRLUnblockHandlers) return false; toggleMobileLanguage()"
                   data-cf-modified-bbfb53b5999c6c3f61fbade4-="">
                   <div style="display: flex; align-items: center;">
                       <i class="la la-language"
                           style="margin-right: 12px; width: 18px; font-size: 1rem; color: var(--rich-gold);"></i>
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

   <script type="bbfb53b5999c6c3f61fbade4-text/javascript">
        // Enhanced mobile menu scrolling
        function scrollToElementInMobileMenu(elementId) {
            const element = document.getElementById(elementId);
            const mobileMenu = document.getElementById('modernMobileMenu');
            
            if (element && mobileMenu && mobileMenu.classList.contains('active')) {
                // Calculate position relative to mobile menu
                const elementRect = element.getBoundingClientRect();
                const menuRect = mobileMenu.getBoundingClientRect();
                const scrollTop = mobileMenu.scrollTop;
                const targetPosition = elementRect.top - menuRect.top + scrollTop - 20;
                
                mobileMenu.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        }

        // Prevent body scroll when mobile menu is active
        function preventBodyScroll() {
            const scrollY = window.scrollY;
            document.body.style.position = 'fixed';
            document.body.style.top = `-${scrollY}px`;
            document.body.style.width = '100%';
        }

        function restoreBodyScroll() {
            const scrollY = document.body.style.top;
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.width = '';
            window.scrollTo(0, parseInt(scrollY || '0') * -1);
        }

        // Enhanced mobile menu toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('modernMobileMenu');
            const hamburger = document.getElementById('hamburger');
            
            mobileMenu.classList.toggle('active');
            hamburger.classList.toggle('active');
            
            // Enhanced body scroll management
            if (mobileMenu.classList.contains('active')) {
                preventBodyScroll();
            } else {
                restoreBodyScroll();
                // Close any open submenus
                const destinationsSubmenu = document.getElementById('mobileDestinationsSubmenu');
                const languageSubmenu = document.getElementById('mobileLanguageSubmenu');
                if (destinationsSubmenu) destinationsSubmenu.classList.remove('active');
                if (languageSubmenu) languageSubmenu.classList.remove('active');
                
                // Reset chevron icons
                const chevronIcons = document.querySelectorAll('.mobile-destinations-toggle i.chevron, .mobile-language-toggle i.chevron');
                chevronIcons.forEach(icon => icon.classList.remove('rotated'));
            }
        }

        // Enhanced mobile dropdown functionality for countries
        function toggleMobileDestinations() {
            const submenu = document.getElementById('mobileDestinationsSubmenu');
            const icon = document.querySelector('.mobile-destinations-toggle i.chevron');
            
            submenu.classList.toggle('active');
            icon.classList.toggle('rotated');
            
            // Scroll to show the submenu if it's being opened
            if (submenu.classList.contains('active')) {
                setTimeout(() => {
                    const toggle = document.querySelector('.mobile-destinations-toggle');
                    if (toggle) {
                        const mobileMenu = document.getElementById('modernMobileMenu');
                        const toggleRect = toggle.getBoundingClientRect();
                        const menuRect = mobileMenu.getBoundingClientRect();
                        const scrollTop = mobileMenu.scrollTop;
                        const targetPosition = toggleRect.top - menuRect.top + scrollTop - 50;
                        
                        mobileMenu.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                }, 100);
            }
        }

        // Enhanced mobile dropdown functionality for language
        function toggleMobileLanguage() {
            const submenu = document.getElementById('mobileLanguageSubmenu');
            const icon = document.querySelector('.mobile-language-toggle i.chevron');
            
            submenu.classList.toggle('active');
            icon.classList.toggle('rotated');
            
            // Scroll to show the submenu if it's being opened
            if (submenu.classList.contains('active')) {
                setTimeout(() => {
                    const toggle = document.querySelector('.mobile-language-toggle');
                    if (toggle) {
                        const mobileMenu = document.getElementById('modernMobileMenu');
                        const toggleRect = toggle.getBoundingClientRect();
                        const menuRect = mobileMenu.getBoundingClientRect();
                        const scrollTop = mobileMenu.scrollTop;
                        const targetPosition = toggleRect.top - menuRect.top + scrollTop - 50;
                        
                        mobileMenu.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                }, 100);
            }
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('modernMobileMenu');
            const mobileToggle = document.querySelector('.mobile-toggle');
            
            if (!mobileToggle.contains(event.target) && !mobileMenu.contains(event.target)) {
                if (mobileMenu.classList.contains('active')) {
                    toggleMobileMenu();
                }
            }
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Enhanced dropdown functionality for CLICK ONLY
        document.addEventListener('DOMContentLoaded', function() {
            // Handle dropdown click functionality only
            const dropdowns = document.querySelectorAll('.dropdown, .language-dropdown');
            
            dropdowns.forEach(dropdown => {
                const toggle = dropdown.querySelector('.dropdown-toggle, .language-toggle');
                const menu = dropdown.querySelector('.dropdown-menu');
                
                if (toggle && menu) {
                    // Handle click functionality only
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Close other dropdowns
                        dropdowns.forEach(otherDropdown => {
                            if (otherDropdown !== dropdown) {
                                const otherMenu = otherDropdown.querySelector('.dropdown-menu');
                                if (otherMenu) {
                                    otherMenu.style.opacity = '0';
                                    otherMenu.style.visibility = 'hidden';
                                    otherMenu.style.transform = 'translateY(-10px)';
                                }
                            }
                        });
                        
                        // Toggle current dropdown
                        const isVisible = menu.style.opacity === '1';
                        
                        if (isVisible) {
                            menu.style.opacity = '0';
                            menu.style.visibility = 'hidden';
                            menu.style.transform = 'translateY(-10px)';
                        } else {
                            menu.style.display = 'block';
                            setTimeout(() => {
                                menu.style.opacity = '1';
                                menu.style.visibility = 'visible';
                                menu.style.transform = 'translateY(0)';
                            }, 10);
                        }
                    });
                }
            });
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown') && !e.target.closest('.language-dropdown')) {
                    dropdowns.forEach(dropdown => {
                        const menu = dropdown.querySelector('.dropdown-menu');
                        if (menu) {
                            menu.style.opacity = '0';
                            menu.style.visibility = 'hidden';
                            menu.style.transform = 'translateY(-10px)';
                        }
                    });
                }
            });
        });

        // Close mobile menu with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const mobileMenu = document.getElementById('modernMobileMenu');
                if (mobileMenu.classList.contains('active')) {
                    toggleMobileMenu();
                }
            }
        });

        // Add ripple effect to buttons
        function addRippleEffect(e) {
            const button = e.currentTarget;
            const circle = document.createElement('span');
            const diameter = Math.max(button.clientWidth, button.clientHeight);
            const radius = diameter / 2;

            circle.style.width = circle.style.height = `${diameter}px`;
            circle.style.left = `${e.clientX - button.offsetLeft - radius}px`;
            circle.style.top = `${e.clientY - button.offsetTop - radius}px`;
            circle.classList.add('ripple');

            const ripple = button.getElementsByClassName('ripple')[0];
            if (ripple) {
                ripple.remove();
            }

            button.appendChild(circle);
        }

        // Apply ripple effect to mobile action buttons
        document.querySelectorAll('.mobile-action-btn, .mobile-enquiry-btn').forEach(button => {
            button.addEventListener('click', addRippleEffect);
        });
        
    </script>
