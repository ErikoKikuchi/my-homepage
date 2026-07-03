<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserLoginController extends Controller
{
    public function showPilatesForm(Request $request)
    {
        $request->session()->put('login_from', $request->query('from'));
        $request->session()->put('reservation_date', $request->query('date'));
        return view('auth.pilates-user-login');
    }
    
    public function showThinkmotionForm(Request $request)
    {
        $request->session()->put('login_from', $request->query('from', 'thinkmotion'));
        return view('auth.thinkmotion-user-login');
    }

    public function login(UserLoginRequest $request)
        {
            $credentials = $request->only(['email', 'password']);
            $from = $request->session()->pull('login_from');

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
            $date = $request->session()->pull('reservation_date');

            return redirect(
                match ($from) {
                    'pilates-reservation' => route('pilates.guest.index'),
                    'thinkmotion' => '/thinkmotion/mypage',
                    default => '/pilates/mypage',
                }
            );
    }

    //ログアウト
    public function logout(Request $request)
    {
        $isThinkmotion = $request->is('thinkmotion/*');
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $isThinkmotion
        ? redirect()->route('thinkmotion.login')
        : redirect()->route('pilates.login');
    }
}