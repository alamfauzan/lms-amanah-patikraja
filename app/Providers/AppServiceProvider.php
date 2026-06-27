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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix untuk shared hosting yang tidak bisa baca composer.json
        $this->app->instance('app.namespace', 'App\\');

        // Paksa HTTPS di production agar asset (CSS/JS) tidak terkena Mixed Content
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
