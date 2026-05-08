<?php

namespace App\Http\Controllers\Auth\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminTwoFactorRequest;

class AdminTwoFactorSetupController extends Controller
{
    public function showSetupForm()
    {
        /** @var \App\Models\Admin $admin */
        $admin = Auth::guard('admin')->user();
        $google2fa = app(\PragmaRx\Google2FALaravel\Google2FA::class);

        if (empty($admin->two_factor_secret)) {
            $secret = $google2fa->generateSecretKey();
            $admin->two_factor_secret = $secret;
            $admin->save();
        } else {
            $secret = $admin->two_factor_secret;
        }

        $qrUrl = $google2fa->getQRCodeUrl('からだ散歩', $admin->email, $secret);
        return view('auth.admin-two-factor-setup', compact('qrUrl'));
    }
    public function setup(AdminTwoFactorRequest $request)
    {
        $admin = Auth::guard('admin')->user();
        $google2fa = app(\PragmaRx\Google2FALaravel\Google2FA::class);

        $valid = $google2fa->verifyKey(
            $admin->two_factor_secret,
            $request->two_factor_secret
        );

        if (!$valid) {
            return back()->withErrors(['two_factor_secret' => '認証コードが正しくありません']);
        }

        session(['admin_two_factor_verified' => true]);

        return redirect()->route('admin.home');
    }
}
