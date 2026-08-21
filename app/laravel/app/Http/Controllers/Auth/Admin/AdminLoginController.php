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
        return view("auth.pilates.admin-login");
    }
    public function showThinkmotionForm(Request $request)
    {
        $request->session()->put('thinkmotion_login_from', $request->query('from', 'thinkmotion'));
        return view("auth.thinkmotion.admin-login");
    }
    public function adminLogin(AdminLoginRequest $request)
    {
        $section = $request->is('thinkmotion/*') ? 'thinkmotion' : 'pilates';
        $credentials = $request->only(['email', 'password']);
        

        if (!Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'ログイン情報が登録されていません'])
                ->onlyInput('email');
        }
        $admin = Auth::guard('admin')->user();
        if (! $admin->sections->contains('key', $section)) {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()->withErrors(['email' => 'ログイン情報が登録されていません'])->onlyInput('email');
    }
        $request->session()->regenerate();
        $request->session()->forget('url.intended');

        $request->session()->put('admin_login_section', $section);

        return redirect()->route("{$section}.admin.two-factor");
    }
    public function adminLogout(Request $request)
    {
        $section = $request->attributes->get('section');
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route("{$section}.admin.login");
    }
}
