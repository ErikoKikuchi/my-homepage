<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Auth\User\UserRegisterController;
use App\Http\Controllers\Auth\User\UserLoginController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;



Route::middleware('guest:web')->group(function(){
    Route::post('/register',[UserRegisterController::class,'register']);
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
        ],
    ]);
    });
    Route::get('/email/verify', function () {return view('auth.verify-email');
    })->name('verification.notice');
    Route::get('/redirect', function () {return redirect()->away(config('services.mailtrap.sandbox_url'));}) ->name('verification.open');
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back();
    })->middleware('throttle:6,1')->name('verification.send');
});


