<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminSectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $section): Response
    {
        $request->attributes->set('section', $section);

        $admin = Auth::guard('admin')->user();

        if (! $admin->sections->contains('key', $section)) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route("{$section}.admin.login")
                ->withErrors(['email' => 'このセクションへのアクセス権限がありません']);
        }

        return $next($request);
    }
}
