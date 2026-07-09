<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminTwoFactorRequest;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FAQRCode\Google2FA;
use Illuminate\Http\Request;

class AdminTwoFactorController extends Controller
{
    public function showForm(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $section = $request->attributes->get('section');

        if (empty($admin->two_factor_secret)) {
            return redirect()->route("{$section}.admin.two-factor.setup");
        }

        return view("auth.{$section}.admin-two-factor-verify", compact('section'));
    }

    public function verify(AdminTwoFactorRequest $request)
    {
        $admin = Auth::guard('admin')->user();
        $section = $request->attributes->get('section');
        $google2fa = new Google2FA();

        // DBに保存済みのsecretで検証
        $valid = $google2fa->verifyKey(
            $admin->two_factor_secret,  // DBから取得
            $request->one_time_password // 入力値
        );

        if (!$valid) {
            return back()->withErrors(['two_factor_secret' => '認証コードが正しくありません']);
        }
        session(['admin_two_factor_verified.auth_passed' => true]);
        session(['admin_two_factor_verified.auth_time' => \Carbon\Carbon::now()->toIso8601String()]);

        return redirect("/{$section}.admin.home");
    }
}

