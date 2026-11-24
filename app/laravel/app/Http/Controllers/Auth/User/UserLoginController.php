<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use Laravel\Fortify\Http\Requests\TwoFactorLoginRequest;

class UserLoginController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        $isThinkmotion = $request->is('api/thinkmotion', 'api/thinkmotion/*');

        $credentials = $request->only(['email', 'password']);
        $credentials[
            $isThinkmotion ? 'is_medical' : 'is_pilates_user'
        ] = true;

        $request->session()->forget(['login.id', 'login.remember', 'login.challenge']);
        $challenged = null;
        $guard = Auth::guard('web');

        if (! $guard->attemptWhen($credentials, function (User $user) use (&$challenged) {
            if ($user->hasEnabledTwoFactorAuthentication()) {
                $challenged = $user;

                return false;
            }

            return true;
        }, $request->boolean('remember'))) {
            if ($challenged !== null) {
                if ($guard->check()) {
                    $guard->logout();
                }
                $request->session()->regenerate(true);
                $request->session()->put([
                    'login.id' => $challenged->getKey(),
                    'login.remember' => $request->boolean('remember'),
                    'login.challenge' => [
                        'until' => now()->addMinutes(5)->getTimestamp(),
                        'thinkmotion' => $isThinkmotion,
                        'fingerprint' => $this->credentialFingerprint($challenged),
                    ],
                ]);

                return response()->json(['redirectTo' => '/auth/two-factor-challenge']);
            }

            return response()->json([
                'message' => 'ログイン情報が登録されていません',
            ], 422);
        }
        $request->session()->regenerate();
        return $this->loginResponse($request, $isThinkmotion);
    }

    public function challenge(TwoFactorLoginRequest $request, TwoFactorAuthenticatedSessionController $fortify)
    {
        $request->validate([
            'code' => ['required_without:recovery_code', 'nullable', 'digits:6'],
            'recovery_code' => ['required_without:code', 'nullable', 'string', 'max:100'],
        ]);
        $challenge = $request->session()->get('login.challenge');
        abort_if(Auth::guard('web')->check(), 409, 'すでにログインしています。');
        abort_if(! $challenge || $challenge['until'] < now()->getTimestamp(), 422, 'ログイン画面からやり直してください。');

        return (new User)->getConnection()->transaction(function () use ($request, $fortify, $challenge) {
            $user = User::query()->lockForUpdate()->find($request->session()->get('login.id'));
            $sectionFlag = $challenge['thinkmotion'] ? 'is_medical' : 'is_pilates_user';

            abort_unless(
                $user && $user->{$sectionFlag} && $user->hasEnabledTwoFactorAuthentication()
                    && hash_equals($challenge['fingerprint'], $this->credentialFingerprint($user)),
                422,
                'ログイン画面からやり直してください。',
            );

            $response = $fortify->store($request);
            if (! $response instanceof TwoFactorLoginResponse) {
                return $response;
            }

            $request->session()->forget('login.challenge');

            return $this->loginResponse($request, $challenge['thinkmotion']);
        });
    }

    private function credentialFingerprint(User $user): string
    {
        return hash('sha256', $user->getAuthPassword().'|'.$user->two_factor_secret);
    }

    private function loginResponse(Request $request, bool $isThinkmotion)
    {
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

        if ($isThinkmotion && ! $user->profile_completed) {
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
