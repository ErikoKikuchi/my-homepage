<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminTwoFactorRequest;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FALaravel\Support\Authenticator;
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

        $section = $request->attributes->get('section');

        /** @var Authenticator $authenticator */
        $authenticator = app(Authenticator::class)->boot($request);

        if (! $authenticator->isAuthenticated()) {
            return back()->withErrors(['two_factor_secret' => '認証コードが正しくありません']);
        }
        $request->session()->regenerate();

        return redirect()->route("{$section}.admin.home");
    }
}

