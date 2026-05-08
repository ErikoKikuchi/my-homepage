<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminTwoFactorRequest;
use Illuminate\Support\Facades\Auth;

class AdminTwoFactorController extends Controller
{
    public function showForm()
    {
        $admin = Auth::guard('admin')->user();

        if (empty($admin->two_factor_secret)) {
            return redirect()->route('two-factor-setup');
        }

        return view('auth.admin-two-factor-verify');
    }

    public function verify(AdminTwoFactorRequest $request)
    {
        $admin = Auth::guard('admin')->user();

        $google2fa = app(\PragmaRx\Google2FALaravel\Google2FA::class);

        // DBに保存済みのsecretで検証
        $valid = $google2fa->verifyKey(
            $admin->two_factor_secret,  // DBから取得
            $request->two_factor_secret // 入力値
        );

        if (!$valid) {
            return back()->withErrors(['two_factor_secret' => '認証コードが正しくありません']);
        }

        session(['admin_two_factor_verified' => true]);

        return redirect()->route('admin.home');
    }
}

