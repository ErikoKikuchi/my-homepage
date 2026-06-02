<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserLoginController extends Controller
{
    public function login(UserLoginRequest $request)
        {
            $credentials = $request->only(['email', 'password']);
            $from=$request->query('from');

            if (!Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
                return back()
                    ->withErrors(['email' => 'ログイン情報が登録されていません'])
                    ->onlyInput('email');
            }

            $request->session()->regenerate();
            $request->session()->forget('url.intended');

            $user = Auth::guard('web')->user();

            if($user->is_medical && !$user->profile_completed)
                {
                    return redirect()->route('profile.register');
                }

            return redirect(
                $from === 'thinkmotion' ? '/thinkmotion/mypage' : '/pilates/mypage'
            );
    }

    //ログアウト
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}