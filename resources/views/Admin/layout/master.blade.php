<!doctype html>
<html lang="ar" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="rtl"
    data-theme="theme-default" data-assets-path="{{ asset('dashboard/assets') }}/"
    data-template="vertical-menu-template-no-customizer">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- تنسيق خاص لمعالجة الأخطاء -->
    <style>
        /* تنسيق للصور الفاشلة */
        img.img-error {
            opacity: 0.7;
            border: 2px dashed #ccc !important;
            background-color: #f8f9fa !important;
            padding: 10px !important;
        }

        /* تحسين الأداء للـ animations */
        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
    <title>
        @yield('title')
    </title>

    <meta name="description" content="" />

    @include('Admin.layout.css')


</head>
@yield('css')

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('Admin.layout.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                @include('Admin.layout.nav')
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @yield('content')

                    <!-- / Content -->

                    <!-- Footer -->
                    @include('Admin.layout.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        {{-- <div class="layout-overlay layout-menu-toggle"></div> --}}

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->
    <form id="form_action_delete" method="POST" class="d-none">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
    </form>
    <form id="form_action_post" method="POST" class="d-none">
        @csrf
    </form>
    <!-- Core JS -->
    @include('Admin.layout.js')
    @yield('js')
    <script>
        // ✅ حل إسعافي: لو أي Swal اتعملت بدون نصوص للأزرار، نركّب نصوص افتراضية
        if (window.Swal && typeof Swal.fire === 'function') {
            const __fire = Swal.fire.bind(Swal);
            Swal.fire = function(opts, ...rest) {
                if (opts && typeof opts === 'object') {
                    if (!opts.confirmButtonText) opts.confirmButtonText = 'موافق';
                    if (opts.showCancelButton && !opts.cancelButtonText) opts.cancelButtonText = 'إلغاء';
                    if (opts.showDenyButton && !opts.denyButtonText) opts.denyButtonText = 'لا';
                }
                return __fire(opts, ...rest);
            };
        }
    </script>
</body>

</html>
