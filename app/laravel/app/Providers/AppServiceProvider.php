<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        $this->loadMigrationsFrom([
            database_path('migrations/thinkmotion'),
            database_path('migrations/client'),
        ]);

        View::composer('partials.login-link', function ($view) {
            $view->with('section', request()->is('thinkmotion', 'thinkmotion/*') ? 'thinkmotion' : 'pilates');
        });
    }
}
