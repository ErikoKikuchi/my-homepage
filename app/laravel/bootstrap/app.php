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
    },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.2fa' => \App\Http\Middleware\Admin2FAMiddleware::class,
        ]);
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin', 'admin/*')) {
                return route('admin.login');
            }
        
            $from = match (true) {
                $request->is('pilates/reservations/create') => 'pilates-reservation',
                $request->is('thinkmotion/*') => 'thinkmotion',
                default => null,
            };
        
            $query = $from ? '?from=' . $from : '';
        
            return $request->is('thinkmotion', 'thinkmotion/*')
                ? route('thinkmotion.login') . $query
                : route('pilates.login') . $query;
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
