<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class AdminLoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        return Inertia::render('Admin/Auth/Login');
    }


    public function adminLogin(AdminLoginRequest $request)
    {
        $validated = $request->validated();

        $section = $validated['section'];
        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password'],
        ];

        if (! Auth::guard('admin')->attempt($credentials)) {
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
