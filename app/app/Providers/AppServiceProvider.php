<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        // Każde zamówienie wysyła e-mail na podany adres - limit chroni
        // przed zalewem fałszywych zamówień z jednego adresu IP.
        RateLimiter::for('orders', fn (Request $request) => [
            Limit::perMinute(10)->by('minute:' . $request->ip()),
            Limit::perHour(30)->by('hour:' . $request->ip()),
        ]);
    }
}
