<?php

use App\Http\Controllers\Auth\User\UserRegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;

Route::middleware('guest:web')->group(function () {
    Route::post('/register', [UserRegisterController::class, 'register']);
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

Route::middleware('auth:web')->group(function () {
    Route::get('/user', function (Request $request) {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_pilates_user' => $user->is_pilates_user,
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
