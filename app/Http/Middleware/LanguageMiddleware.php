<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LanguageMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // تحقق إذا كانت اللغة تأتي من الهيدر
        if ($request->hasHeader('Accept-Language')) {
            $locale = $request->header('Accept-Language');
            app()->setLocale($locale);
        } elseif (session()->has('locale')) {
            // أو من الجلسة
            app()->setLocale(session('locale'));
        }
        
        return $next($request);
    }
}