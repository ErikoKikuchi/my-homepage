<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;
use App\Models\Pilates\Reservation;
use App\Policies\ReservationPolicy;

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
        Gate::policy(Reservation::class, ReservationPolicy::class);

        $this->loadMigrationsFrom([
            database_path('migrations/thinkmotion'),
            database_path('migrations/client'),
        ]);

        View::composer(['partials.login-link', 'partials.logout-form'], function ($view) {
            $view->with('section', request()->is('thinkmotion', 'thinkmotion/*') ? 'thinkmotion' : 'pilates');
        });
    }
}
