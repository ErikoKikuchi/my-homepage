<?php

namespace App\Actions\Fortify;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\PasswordResetResponse as PasswordResetResponseContract;

class PasswordResetResponse implements PasswordResetResponseContract
{
    public function toResponse($request): RedirectResponse
    {
        $from = $request->session()->pull('login_from');

        $route = $from === 'thinkmotion'
            ? route('thinkmotion.login')
            : route('pilates.login');

        return redirect($route)->with('status', __('パスワードを変更しました。ログインしてください。'));
    }
}