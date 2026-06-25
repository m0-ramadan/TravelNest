<?php

namespace App\Http\Middleware;

use App\Services\JsonTranslationFileService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function __construct(
        protected JsonTranslationFileService $jsonTranslationFileService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $defaultLocale = config('app.locale', 'en');
        $isAdminRequest = $request->is('admin') || $request->is('admin/*');
        $requestedLocale = strtolower(trim((string) $request->query('lang', '')));

        if ($isAdminRequest && in_array($requestedLocale, ['ar', 'en'], true)) {
            $locale = $requestedLocale;
            $request->session()->put('admin_locale', $locale);
            $request->session()->put('locale', $locale);
        } elseif ($isAdminRequest && $request->session()->has('admin_locale')) {
            $locale = (string) $request->session()->get('admin_locale');
        } elseif ($request->session()->has('locale')) {
            $locale = (string) $request->session()->get('locale');
        } else {
            $locale = $isAdminRequest ? 'ar' : $defaultLocale;
        }

        $locale = strtolower(trim($locale));

        $supportedLocales = \Illuminate\Support\Facades\Cache::remember('supported_locales', 3600, function () {
            return \App\Models\Language::where('is_active', true)->pluck('code')->toArray();
        });

        $supportedLocales = array_values(array_unique(array_map(
            static fn ($code) => strtolower(trim((string) $code)),
            $supportedLocales
        )));

        if ($isAdminRequest) {
            $supportedLocales = array_values(array_intersect($supportedLocales, ['ar', 'en']));

            if (empty($supportedLocales)) {
                $supportedLocales = ['ar', 'en'];
            }

            if (!in_array($locale, $supportedLocales, true)) {
                $locale = 'ar';
            }

            $request->session()->put('admin_locale', $locale);
        } elseif (!in_array($locale, $supportedLocales, true)) {
            $locale = strtolower((string) config('app.fallback_locale', 'en'));
        }

        app()->setLocale($locale);
        $this->jsonTranslationFileService->ensureLocaleFile($locale);

        return $next($request);
    }
}
