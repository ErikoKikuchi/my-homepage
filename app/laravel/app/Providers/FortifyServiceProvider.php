<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use App\Actions\Fortify\PasswordResetResponse;
use Laravel\Fortify\Contracts\PasswordResetResponse as PasswordResetResponseContract;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Fortify::ignoreRoutes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->singleton(PasswordResetResponseContract::class, PasswordResetResponse::class);


        Fortify::createUsersUsing(CreateNewUser::class);
            Fortify::registerView(function(){
                return view("auth.register");
            });
            Fortify::requestPasswordResetLinkView(function () {
                return view('auth.forgot-password');
            });
            Fortify::resetPasswordView(function ($request) {
                return view('auth.reset-password', ['request' => $request]);
            });
        //Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::requestPasswordResetLinkView(function (Request $request) {
            $request->session()->put('login_from', $request->query('from'));
            return view('auth.forgot-password');
        });
        //Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            $key = (string) $request->user('admin')?->id . '|' . $request->ip();
            return Limit::perMinute(5)->by($key);
        });
    }
}
