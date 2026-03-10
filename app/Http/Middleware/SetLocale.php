<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // الحصول على اللغة من الهيدر
        $locale = $request->header('Accept-Language', config('app.locale'));
        
        // تنظيف وتأكيد اللغة
        $locale = $this->sanitizeLocale($locale);
        
        // تعيين اللغة للتطبيق
        app()->setLocale($locale);
        
        return $next($request);
    }

    /**
     * تنظيف وتأكيد صحة اللغة
     */
    private function sanitizeLocale($locale): string
    {
        // قائمة اللغات المدعومة
        $supportedLocales = ['ar', 'en', 'fr', 'es']; // أضف لغات أخرى حسب الحاجة
        
        // تقسيم اللغة إذا كانت تحتوي على إقليم (مثل en-US)
        $locale = explode('-', $locale)[0];
        $locale = explode('_', $locale)[0];
        
        // التحقق إذا كانت اللغة مدعومة
        if (in_array($locale, $supportedLocales)) {
            return $locale;
        }
        
        // العودة للغة الافتراضية
        return config('app.fallback_locale', 'ar');
    }
}