<?php

use App\Http\Controllers\Auth\User\UserRegisterController;
use App\Http\Controllers\Auth\User\UserLoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;

Route::middleware('guest:web')->group(function () {
    Route::post('/two-factor-challenge', [UserLoginController::class, 'challenge'])->middleware('throttle:user-two-factor');
    Route::post('/register', [UserRegisterController::class, 'register']);
});

Route::post('/forgot-password', function (\Laravel\Fortify\Http\Requests\SendPasswordResetLinkRequest $request, PasswordResetLinkController $controller) {
    abort_if($request->user('web'), 409, 'ログイン中はアカウント設定から再設定メールを送信してください。');

    return $controller->store($request);
})->name('password.email');

Route::post('/reset-password', function (Request $request, NewPasswordController $controller) {
    $user = $request->user('web');
    if ($user) {
        $email = $request->input('email');
        abort_unless(is_string($email) && strcasecmp($user->email, $email) === 0, 403, '別のアカウントの再設定リンクです。ログアウトしてから開き直してください。');
        $request->merge(['email' => $user->email]);
    }

    return $controller->store($request);
})->name('password.update');

Route::middleware('auth:web')->group(function () {
    Route::get('/user', function (Request $request) {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->is_pilates_user
                        ? $user->client?->name
                        : $user->profile?->display_name,
                'email' => $user->email,
                'is_pilates_user' => $user->is_pilates_user,
                'is_medical' => $user->is_medical, 
                'email_verified' => $user->hasVerifiedEmail(),
            ],
        ]);
    });
    Route::get('/email/verify', function () {
        return redirect()->away(config('app.frontend_url').'/auth/email/verify');
    })->name('verification.notice');
    Route::get('/redirect', function () {
        return redirect()->away(config('services.mailtrap.sandbox_url'));
    })->name('verification.open');
    Route::post('/email/verification-notification', function (Request $request) {
        $user = $request->user();
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'すでに認証済みです。']);
        }
        $user->sendEmailVerificationNotification();

        return response()->json(['message' => '認証メールを再送しました。']);
    })->middleware('throttle:6,1')->name('verification.send');
});
