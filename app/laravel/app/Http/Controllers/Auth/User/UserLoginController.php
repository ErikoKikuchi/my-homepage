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
        $request->session()->put('pilates_login_from', $request->query('from', 'pilates'));
        $request->session()->put('reservation_date', $request->query('date'));
        return view('auth.pilates-user-login');
    }
    
    public function showThinkmotionForm(Request $request)
    {
        $request->session()->put('thinkmotion_login_from', $request->query('from', 'thinkmotion'));
        return view('auth.thinkmotion-user-login');
    }
    public function loginPilates(UserLoginRequest $request)
    {
        return $this->attemptLogin($request, 'pilates');
    }

    public function loginThinkmotion(UserLoginRequest $request)
    {
        return $this->attemptLogin($request, 'thinkmotion');
    }

    private function attemptLogin(UserLoginRequest $request, string $section)
    {
            $credentials = $request->only(['email', 'password']);

            $from = $section === 'thinkmotion'
                ? $request->session()->pull('thinkmotion_login_from')
                : $request->session()->pull('pilates_login_from');

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