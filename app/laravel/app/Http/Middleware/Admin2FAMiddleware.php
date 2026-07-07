<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class Admin2FAMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /** @var Authenticator $authenticator */
        $authenticator = app(Authenticator::class)->boot($request);

        if ($authenticator->isAuthenticated()) {
            return $next($request);
        }
        $section = $request->attributes->get('section');

        return redirect()->route("{$section}.admin.two-factor");
    }
}