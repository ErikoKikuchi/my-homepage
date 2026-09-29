<?php

namespace App\Providers;

use App\Policies\Admin\ReservationPolicy as AdminReservationPolicy;
use App\Policies\User\ReservationPolicy as UserReservationPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
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
        // 顧客側
        Gate::define('reservation.view', [UserReservationPolicy::class, 'view']);
        Gate::define('reservation.cancel', [UserReservationPolicy::class, 'cancel']);

        // 管理者側
        Gate::define('reservation.confirm', [AdminReservationPolicy::class, 'confirm']);

        $this->loadMigrationsFrom([
            database_path('migrations/thinkmotion'),
            database_path('migrations/client'),
        ]);

        View::composer(['partials.login-link', 'partials.logout-form'], function ($view) {
            $view->with('section', request()->is('thinkmotion', 'thinkmotion/*') ? 'thinkmotion' : 'pilates');
        });
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return config('app.frontend_url')
            .'/auth/reset-password/'.$token
            .'?email='.urlencode($user->getEmailForPasswordReset());
        });
    }
}
