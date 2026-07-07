<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showPilatesForm(Request $request)
    {
        $request->session()->put('pilates_login_from', $request->query('from', 'pilates'));
        return view("auth.pilates-admin-login");
    }
    public function showThinkmotionForm(Request $request)
    {
        $request->session()->put('thinkmotion_login_from', $request->query('from', 'thinkmotion'));
        return view("auth.thinkmotion-admin-login");
    }
    public function adminLogin(AdminLoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        $isThinkmotion = $request->is('thinkmotion','thinkmotion/*');

        if (!Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'ログイン情報が登録されていません'])
                ->onlyInput('email');
        }
        $request->session()->regenerate();
        $request->session()->forget('url.intended');

        $request->session()->put('admin_login_section', $isThinkmotion ? 'thinkmotion' : 'pilates');

        return match (true) {
            $isThinkmotion => redirect()->route('thinkmotion.admin.two-factor'),
            default => redirect()->route('pilates.admin.two-factor'),
        };
    }
    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.admin.login');
    }
}
