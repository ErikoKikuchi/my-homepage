<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        $isThinkmotion = $request->is('api/thinkmotion', 'api/thinkmotion/*');

        if (! Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            return response()->json([
                'message' => 'ログイン情報が登録されていません',
            ], 422);
        }
        $request->session()->regenerate();
        $request->session()->forget('url.intended');

        /** @var User $user */
        $user = Auth::guard('web')->user();

        // メール未認証は、認証待ち画面へ
        if (! $user->hasVerifiedEmail()) {
            return response()->json([
                'redirectTo' => '/auth/email/verify',
            ]);
        }

        $reservationDate = $request->session()->pull('reservation_date');
        $reservationStart = $request->session()->pull('reservation_start');

        if ($user->is_medical && ! $user->profile_completed) {
            return response()->json([
                'redirectTo' => route('profile.register'),
            ]);
        }
        if (! $isThinkmotion && $reservationDate && $reservationStart) {
            return response()->json([
                'redirectTo' => sprintf(
                    '/pilates/reservation/detail?date=%s&start=%s',
                    $reservationDate,
                    $reservationStart,
                ),
            ]);
        }

        return response()->json([
            'redirectTo' => $isThinkmotion ? '/thinkmotion/mypage' : '/pilates/mypage',
        ]);
    }

    // ログアウト
    public function logout(Request $request)
    {
        $isThinkmotion = $request->is('api/thinkmotion', 'api/thinkmotion/*');
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'redirectTo' => $isThinkmotion ? '/thinkmotion' : '/pilates',
        ]);
    }
}
