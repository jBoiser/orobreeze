<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;

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
        $this->app->bind('path.public', function() {
            return base_path('../'); 
        });

        // Tell Vite the build directory is in the root of public_html
        Vite::useBuildDirectory('build');
    }

}
