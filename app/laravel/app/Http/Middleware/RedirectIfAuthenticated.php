<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // ↓ ここを guard ごとに分岐させる
                if ($guard === 'admin') {
                    return redirect()->route('admin.home');
                }

                if ($request->is('thinkmotion/*')) {
                    return redirect()->route('thinkmotion.mypage');
                }

                return redirect()->route('pilates.mypage');
            }
        }

        return $next($request);
    }
}
