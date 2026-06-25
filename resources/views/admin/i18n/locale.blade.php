@php
    $requestedAdminLocale = request()->query('lang');
    if (in_array($requestedAdminLocale, ['ar', 'en'], true)) {
        session(['admin_locale' => $requestedAdminLocale]);
    }

    $resolvedAdminLocale = session('admin_locale', app()->getLocale());
    if (!in_array($resolvedAdminLocale, ['ar', 'en'], true)) {
        $resolvedAdminLocale = 'ar';
    }
    app()->setLocale($resolvedAdminLocale);

    if (!function_exists('admin_translation_maps')) {
        function admin_translation_maps(): array
        {
            static $maps;
            if ($maps === null) {
                $maps = require resource_path('views/admin/i18n/translations.php');
            }
            return $maps;
        }
    }

    if (!function_exists('admin_t')) {
        function admin_t($key, array $replace = []): string
        {
            $key = (string) $key;
            $locale = app()->getLocale();
            $maps = admin_translation_maps();
            $translated = $maps[$locale][$key] ?? $key;
            foreach ($replace as $name => $value) {
                $translated = str_replace(':' . $name, (string) $value, $translated);
            }
            return $translated;
        }
    }
@endphp
