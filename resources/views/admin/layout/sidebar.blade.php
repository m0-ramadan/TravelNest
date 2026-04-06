<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.index') }}" class="app-brand-link">
            <span style="width: 75px !important ; height:75px !important" class="app-brand-logo demo"></span>
            <span class="app-brand-text demo menu-text fw-bold" style="font-size: 0.90rem">{{ env('APP_NAME') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        {{-- لوحة معلومات المدير --}}
        <li class="menu-item">
            <div class="menu-link d-flex align-items-center">
                @php
                    $admin = auth('admin')->user();
                @endphp
                <div class="me-3">
                    <img src="{{ get_user_image($admin->image) }}" class="rounded-circle"
                        style="width: 40px; height: 40px; object-fit: cover;" alt="Admin">
                </div>
                <div>
                    <a href="{{ route('admin.index') }}" class="text-body fw-semibold">
                        {{ $admin->name ?? 'Admin' }}
                    </a>
                </div>
            </div>
        </li>

        {{-- لوحة التحكم --}}
        <li class="menu-item {{ request()->routeIs('admin.index') ? 'active' : '' }}">
            <a href="{{ route('admin.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-home"></i>
                <div>الرئيسية</div>
            </a>
        </li>

        {{-- محتوى الرحلات --}}
        @php
            $travelContentOpen = request()->routeIs(
                'admin.countries.*',
                'admin.cities.*',
                'admin.regions.*',
                'admin.destinations.*',
                'admin.package-categories.*',
                'admin.packages.*',
                'admin.package-prices.*',
                'admin.banners.*',
                'admin.testimonials.*',
            );
        @endphp
        <li class="menu-item {{ $travelContentOpen ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-world"></i>
                <div>محتوى الرحلات</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.countries.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.countries.index') }}" class="menu-link">
                        <div>الدول</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.cities.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.cities.index') }}" class="menu-link">
                        <div>المدن</div>
                    </a>
                </li>

                {{-- <li class="menu-item {{ request()->routeIs('admin.regions.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.regions.index') }}" class="menu-link">
                        <div>المناطق</div>
                    </a>
                </li> --}}

                <li class="menu-item {{ request()->routeIs('admin.destinations.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.destinations.index') }}" class="menu-link">
                        <div>الوجهات</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.attractions.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.attractions.index') }}" class="menu-link">
                        <div> المعالم السياحية </div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.package-categories.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.package-categories.index') }}" class="menu-link">
                        <div>تصنيفات الباقات</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.packages.index') }}" class="menu-link">
                        <div>الباقات</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.package-prices.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.package-prices.index') }}" class="menu-link">
                        <div>أسعار الباقات</div>
                    </a>
                </li>

                {{-- <li class="menu-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.banners.index') }}" class="menu-link">
                        <div>البانرات</div>
                    </a>
                </li> --}}

                <li class="menu-item {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.testimonials.index') }}" class="menu-link">
                        <div>آراء العملاء</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- إدارة الحجز --}}
        @php
            $bookingOpen = request()->routeIs(
                'admin.bookings.*',
                'admin.inquiries.*',
                'admin.clients.*',
                'admin.communications.*',
                'admin.payments.*',
                'admin.payment-methods.*',
            );
        @endphp
        <li class="menu-item {{ $bookingOpen ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-briefcase"></i>
                <div>إدارة الحجز</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.bookings.index') }}" class="menu-link">
                        <div>الحجوزات</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.inquiries.index') }}" class="menu-link">
                        <div>الاستفسارات</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.clients.index') }}" class="menu-link">
                        <div>العملاء</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.communications.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.communications.index') }}" class="menu-link">
                        <div>سجلات التواصل</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.payments.index') }}" class="menu-link">
                        <div>المدفوعات</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.payment-methods.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.payment-methods.index') }}" class="menu-link">
                        <div>طرق الدفع</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- المحتوى --}}
        @php
            $contentOpen = request()->routeIs(
                'admin.articles.*',
                'admin.articles.statistics',
                'admin.static-pages.*',
                'admin.faqs.*',
                'admin.menus.*',
            );
        @endphp
        <li class="menu-item {{ $contentOpen ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file-text"></i>
                <div>المحتوى</div>
            </a>
            <ul class="menu-sub">
                <li
                    class="menu-item {{ request()->routeIs('admin.articles.index', 'admin.articles.show', 'admin.articles.edit', 'admin.articles.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.articles.index') }}" class="menu-link">
                        <div>المقالات</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.articles.create-with-ai') ? 'active' : '' }}">
                    <a href="{{ route('admin.articles.create-with-ai') }}" class="menu-link">
                        <div>إنشاء مقال بالذكاء الاصطناعي</div>
                    </a>
                </li>

                {{-- <li class="menu-item {{ request()->routeIs('admin.articles.statistics') ? 'active' : '' }}">
                    <a href="{{ route('admin.articles.statistics') }}" class="menu-link">
                        <div>إحصائيات المقالات</div>
                    </a>
                </li> --}}

                <li class="menu-item {{ request()->routeIs('admin.static-pages.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.static-pages.index') }}" class="menu-link">
                        <div>الصفحات الثابتة</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.faqs.index') }}" class="menu-link">
                        <div>الأسئلة الشائعة</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.menus.index') }}" class="menu-link">
                        <div>القوائم</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- التواصل --}}
        @php
            $communicationOpen = request()->routeIs('admin.contact-us.*', 'admin.subscribe.*', 'admin.social-media.*');
        @endphp
        <li class="menu-item {{ $communicationOpen ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-messages"></i>
                <div>التواصل</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.contact-us.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.contact-us.index') }}" class="menu-link">
                        <div>رسائل التواصل</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.subscribe.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.subscribe.index') }}" class="menu-link">
                        <div>المشتركين</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.social-media.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.social-media.index') }}" class="menu-link">
                        <div>وسائل التواصل الاجتماعي</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- التوطين --}}
        @php
            $localizationOpen = request()->routeIs('admin.languages.*', 'admin.translations.*');
        @endphp
        <li class="menu-item {{ $localizationOpen ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-language"></i>
                <div>التوطين</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.languages.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.languages.index') }}" class="menu-link">
                        <div>اللغات</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.translations.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.translations.index') }}" class="menu-link">
                        <div>الترجمات</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- تحسين محركات البحث --}}
        @php
            $seoOpen = request()->routeIs('admin.seo-meta.*', 'admin.seo-redirects.*');
        @endphp
        <li class="menu-item {{ $seoOpen ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-search"></i>
                <div>SEO</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.seo-meta.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.seo-meta.index') }}" class="menu-link">
                        <div>بيانات SEO</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.seo-redirects.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.seo-redirects.index') }}" class="menu-link">
                        <div>تحويلات الروابط</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- الإدارة --}}
        @php
            $administrationOpen = request()->routeIs(
                'admin.admins.*',
                'admin.users.*',
                'admin.user.*',
                'admin.roles.*',
                'admin.permissions.*',
            );
        @endphp
        <li class="menu-item {{ $administrationOpen ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-user-shield"></i>
                <div>الإدارة</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.admins.index') }}" class="menu-link">
                        <div>المديرين</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.users.*', 'admin.user.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}" class="menu-link">
                        <div>المستخدمين</div>
                    </a>
                </li>
                <li
                    class="menu-item {{ request()->routeIs('admin.roles.index', 'admin.roles.create', 'admin.roles.edit', 'admin.roles.show', 'admin.roles.permissions', 'admin.roles.assign.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.roles.index') }}" class="menu-link">
                        <div>الرتب</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.permissions.index') }}" class="menu-link">
                        <div>الصلاحيات</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- النظام --}}
        @php
            $systemOpen = request()->routeIs('admin.settings.*', 'admin.setting.*', 'admin.errors.*');
        @endphp
        <li class="menu-item {{ $systemOpen ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-settings"></i>
                <div>النظام</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.settings.index') }}" class="menu-link">
                        <div>إعدادات النظام</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.setting.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.setting.pages') }}" class="menu-link">
                        <div>إعدادات الصفحات</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.errors.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.errors.index') }}" class="menu-link">
                        <div>سجلات الأخطاء</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- تسجيل الخروج --}}
        <li class="menu-item mt-3">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="menu-link btn btn-link text-start w-100"
                    style="border: none; background: transparent;">
                    <i class="menu-icon tf-icons ti ti-logout"></i>
                    <div>تسجيل الخروج</div>
                </button>
            </form>
        </li>
    </ul>
</aside>
