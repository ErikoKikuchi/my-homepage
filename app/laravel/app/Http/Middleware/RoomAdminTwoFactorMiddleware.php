<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoomAdminTwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    if (!session('room_admin_verified')) {
        // 2FA入力画面にリダイレクト
        // 元のURLをセッションに保存しておくと戻れる
        session(['intended_url' => $request->url()]);
        return redirect()->route('room.two-factor');
    }
    return $next($request);
}
}
