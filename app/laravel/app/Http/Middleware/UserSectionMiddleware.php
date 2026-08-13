<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserSectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $section): Response
    {
        $user = Auth::guard('web')->user();

        $allowed = match ($section) {
            'pilates'     => $user->is_pilates_user,
            'thinkmotion' => $user->is_medical,
            default       => false,
        };

        if (! $allowed) {
            abort(403, 'このセクションへのアクセス権限がありません');
        }

        return $next($request);
    }
}
