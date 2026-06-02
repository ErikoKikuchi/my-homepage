<?php

namespace App\Http\Controllers\Auth\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminTwoFactorRequest;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use PragmaRX\Google2FAQRCode\Google2FA;

class AdminTwoFactorSetupController extends Controller
{
    public function showSetupForm()
    {
        /** @var \App\Models\Auth\Admin $admin */
        $admin = Auth::guard('admin')->user();
        $google2fa = new Google2FA();

        if (empty($admin->two_factor_secret)) {
            $secret = $google2fa->generateSecretKey();
            $admin->two_factor_secret = $secret;
            $admin->save();
        } else {
            $secret = $admin->two_factor_secret;
        }

        $qrUrl = $google2fa->getQRCodeUrl('からだ散歩', $admin->email, $secret);

        $renderer = new ImageRenderer(new RendererStyle(200), new SvgImageBackEnd());
        $writer = new Writer($renderer);
        $qrCode = $writer->writeString($qrUrl);  // SVG文字列

        return view('auth.admin-two-factor-setup', compact('qrCode'));
    }
    public function setup(AdminTwoFactorRequest $request)
    {
        $admin = Auth::guard('admin')->user();
        $google2fa = new Google2FA();

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
