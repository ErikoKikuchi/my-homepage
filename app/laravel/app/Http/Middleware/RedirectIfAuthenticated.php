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
            if ($guard === 'admin') {
                $admin = Auth::guard('admin')->user();
                $isThinkmotion = $request->is('thinkmotion', 'thinkmotion/*');

                if ($isThinkmotion && $admin->sections->contains('key', 'thinkmotion')) {
                    return redirect('/thinkmotion/admin/dashboard');
                }

                if (!$isThinkmotion && $admin->sections->contains('key', 'pilates')) {
                    return redirect('/pilates/admin/dashboard');
                }

                // どちらのセクション権限もない場合はログアウトさせて再ログインを促す
                Auth::guard('admin')->logout();
                return redirect()->route(
                    $isThinkmotion ? 'thinkmotion.admin.login' : 'pilates.admin.login'
                );
            }

            if ($request->is('thinkmotion', 'thinkmotion/*')) {
                return redirect()->route('thinkmotion.mypage');
            }

            return redirect()->route('pilates.mypage');
        }
    }

    return $next($request);
}
}
