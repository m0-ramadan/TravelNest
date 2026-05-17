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

        if ($request->session()->has('locale')) {
            $locale = $request->session()->get('locale');
        } else {
            $locale = $defaultLocale;
        }

        $supportedLocales = \Illuminate\Support\Facades\Cache::remember('supported_locales', 3600, function () {
            return \App\Models\Language::where('is_active', true)->pluck('code')->toArray();
        });

        if (!in_array($locale, $supportedLocales)) {
            $locale = config('app.fallback_locale', 'en');
        }

        app()->setLocale($locale);
        $this->jsonTranslationFileService->ensureLocaleFile($locale);

        return $next($request);
    }
}
