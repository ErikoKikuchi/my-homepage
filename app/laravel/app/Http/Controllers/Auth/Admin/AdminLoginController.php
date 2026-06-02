<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showForm(Request $request)
    {
        return view("auth.admin-login");
    }
    public function adminLogin(AdminLoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'ログイン情報が登録されていません'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route('admin.two-factor');
    }
    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.admin.login');
    }
}
