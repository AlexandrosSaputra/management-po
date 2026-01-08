<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
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
        Model::unguard();

        // Rate limiter for login attempts: limit by (login + IP) to reduce brute-force
        \Illuminate\Support\Facades\RateLimiter::for('login', function (\Illuminate\Http\Request $request) {
            $login = (string) $request->input('login');
            $key = \Illuminate\Support\Str::lower($login) . '|' . $request->ip();
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(5)->by($key);
        });
    }
}
