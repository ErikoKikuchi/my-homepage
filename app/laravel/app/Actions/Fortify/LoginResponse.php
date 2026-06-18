<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $from = $request->session()->pull('login_from');

        return redirect(match ($from) {
            'pilates-reservation' => route('pilates.guest.index'),
            'pilates-training-log' => route('pilates.user.training-log.index'),
            'thinkmotion' => route('thinkmotion.mypage'),
            default => config('fortify.home'),
        });
    }
}