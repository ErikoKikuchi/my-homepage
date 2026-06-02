<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\Admin\AdminLoginController;
use App\Http\Controllers\Auth\Admin\AdminTwoFactorController;
use App\Http\Controllers\Auth\Admin\AdminTwoFactorSetupController;
use App\Http\Controllers\Auth\Admin\AdminHomeController;
use App\Http\Controllers\Auth\User\UserRegisterController;
use App\Http\Controllers\Auth\User\UserLoginController;



Route::middleware('guest')->group(function(){
    Route::post('/register',[UserRegisterController::class,'register']);
    Route::post('/login',[UserLoginController::class,'login'])->name('login');
    Route::get('admin/login', [AdminLoginController::class,'showForm']);
    Route::post('/admin/login',[AdminLoginController::class,'adminLogin']);
}); 

Route::middleware('auth:admin')->group(function(){
    Route::post('/admin/logout', [AdminLoginController::class, 'adminLogout']);
    Route::get('/admin/two-factor/verify', [AdminTwoFactorController::class, 'showVerifyForm'])->name('two-factor-verify');
    Route::get('/admin/two-factor', [AdminTwoFactorController::class, 'showForm'])->name('admin.two-factor');
    Route::post('/admin/two-factor/verify', [AdminTwoFactorController::class, 'verify']);
    Route::get('/admin/two-factor/setup', [AdminTwoFactorSetupController::class, 'showSetupForm'])->name('two-factor-setup');
    Route::post('/admin/two-factor/setup', [AdminTwoFactorSetupController::class, 'setup']);
    Route::get('/admin/home', [AdminHomeController::class,'index'])->name('admin.home');
});

Route::middleware('auth:web')->group(function () {
    Route::post('/logout', [UserLoginController::class, 'logout']);
    Route::get('/email/verify', function () {return view('auth.verify-email');
    })->name('verification.notice');
    Route::get('/redirect', function () {return redirect()->away(config('services.mailtrap.sandbox_url'));}) ->name('verification.open');
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back();
    })->middleware('throttle:6,1')->name('verification.send');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        $user = $request->user();
        return $user->is_medical
            ? redirect()->route('profile.register')
            : redirect()->route('pilates.mypage');
    })->middleware(['auth:web'])->name('verification.verify');
});

