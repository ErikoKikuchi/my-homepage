<?php

namespace App\Actions\Fortify;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\PasswordResetResponse as PasswordResetResponseContract;

class PasswordResetResponse implements PasswordResetResponseContract
{
    public function toResponse($request): JsonResponse
    {
        $from = $request->session()->pull('login_from');

        return response()->json([
            'redirectTo' => $from === 'thinkmotion'
                ? '/auth/thinkmotion/login'
                : '/auth/pilates/login',
        ]);
    }
}