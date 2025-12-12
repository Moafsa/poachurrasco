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
        // Force HTTPS only in production and when APP_URL uses HTTPS
        // Use HTTP locally (localhost, 127.0.0.1, or local/development environment)
        $appUrl = config('app.url', '');
        $appEnv = config('app.env', 'production');
        
        // Check if running locally
        $isLocal = in_array($appEnv, ['local', 'development']) || 
                   str_contains(strtolower($appUrl), 'localhost') || 
                   str_contains($appUrl, '127.0.0.1') ||
                   str_contains($appUrl, ':8000') ||
                   str_contains($appUrl, ':5173');
        
        // Only force HTTPS if NOT local AND APP_URL explicitly uses HTTPS
        if (!$isLocal && !empty($appUrl) && str_starts_with($appUrl, 'https://')) {
            URL::forceScheme('https');
        } elseif ($isLocal) {
            // Explicitly force HTTP for local development
            URL::forceScheme('http');
        }
    }
}
