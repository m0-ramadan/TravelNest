<?php

namespace App\Providers;

use App\Services\WebsiteDestinationService;
use Illuminate\Support\Facades\View;
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

        $this->app->singleton(WebsiteDestinationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['website.layouts.header', 'website.layouts.footer'], function ($view) {
            $view->with('navigationDestinations', app(WebsiteDestinationService::class)->homeDestinations());
        });
    }
}
