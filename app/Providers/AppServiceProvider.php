<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('path.public', function () {
            // Detect Local Environment (Windows/Herd)
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                return base_path('public');
            }

            // Detect Production Environment (Hostinger/Linux)
            // This assumes your core files are in 'public_html/orobreeze'
            return base_path('../');
        });
    }

    public function boot(): void
    {
        // DO NOT put any path binding here. 
        // It will override the logic in register().

        Vite::useBuildDirectory('build');
    }
}
