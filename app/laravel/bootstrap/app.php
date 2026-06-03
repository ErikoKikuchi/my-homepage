<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

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
                    ->group(base_path('routes/client.php'));
    },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.2fa' => \App\Http\Middleware\Admin2FAMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
