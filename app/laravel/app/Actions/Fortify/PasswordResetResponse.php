<?php

namespace App\Actions\Fortify;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\PasswordResetResponse as PasswordResetResponseContract;

class PasswordResetResponse implements PasswordResetResponseContract
{
    public function toResponse($request): JsonResponse
    {
        $from = $request->session()->pull('login_from');

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json([
            'redirectTo' => $from === 'thinkmotion'
                ? '/auth/thinkmotion/login'
                : '/auth/pilates/login',
        ]);
    }
}