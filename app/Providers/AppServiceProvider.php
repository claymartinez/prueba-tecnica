<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Fuerza HTTPS en producción para evitar contenido mixto (assets por HTTP)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
