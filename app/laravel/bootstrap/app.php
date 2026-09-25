<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
            then: function () {
                Route::middleware('web')
                    ->group(base_path('routes/thinkmotion.php'));
                Route::middleware('web')
                    ->group(base_path('routes/code.php'));
                Route::middleware('web')
                    ->group(base_path('routes/pilates.php'));
                Route::middleware('web')
                    ->prefix('api/auth')
                    ->group(base_path('routes/auth-user.php'));
                Route::middleware('api')
                    ->prefix('api/pilates')
                    ->group(base_path('routes/api-pilates.php'));
                Route::middleware('api')
                    ->prefix('api/thinkmotion')
                    ->group(base_path('routes/api-thinkmotion.php'));
                    },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.section' => \App\Http\Middleware\AdminSectionMiddleware::class,
            'admin.2fa' => \App\Http\Middleware\Admin2FAMiddleware::class,
            'inertia' => \App\Http\Middleware\HandleInertiaRequests::class,
            'section' => \App\Http\Middleware\UserSectionMiddleware::class,
        ]);
        $middleware->api(prepend: [
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    ]);
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('pilates/admin', 'pilates/admin/*')) {
                return route('pilates.admin.login');
            }

            if ($request->is('thinkmotion/admin', 'thinkmotion/admin/*')) {
                return route('thinkmotion.admin.login');
            }
            if ($request->is('api/*')) {
                abort(401, 'Unauthenticated.');
            }
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
