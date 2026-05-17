<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->extend('translator', function ($translator, $app) {
            $trans = new \App\Translation\DeepSeekTranslator(
                $app['translation.loader'], 
                $app['config']['app.locale']
            );
            
            $trans->setFallback($app['config']['app.fallback_locale']);
            
            return $trans;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
