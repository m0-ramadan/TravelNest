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

        if ($isAdminRequest) {
            $request->session()->put('admin_locale', 'en');
            app()->setLocale('en');
            $this->jsonTranslationFileService->ensureLocaleFile('en');

            return $next($request);
        }

        if ($request->session()->has('locale')) {
            $locale = (string) $request->session()->get('locale');
        } else {
            $locale = $defaultLocale;
        }

        $locale = strtolower(trim($locale));
        if ($locale === 'english') {
            $locale = 'en';
            $request->session()->put('locale', 'en');
        }

        $supportedLocales = \Illuminate\Support\Facades\Cache::remember('supported_locales', 3600, function () {
            return \App\Models\Language::where('is_active', true)->pluck('code')->toArray();
        });

        $supportedLocales = array_values(array_unique(array_map(
            static fn ($code) => strtolower(trim((string) $code)),
            $supportedLocales
        )));

        if (!in_array($locale, $supportedLocales, true)) {
            $locale = strtolower((string) config('app.fallback_locale', 'en'));
        }

        app()->setLocale($locale);
        $this->jsonTranslationFileService->ensureLocaleFile($locale);

        return $next($request);
    }
}
