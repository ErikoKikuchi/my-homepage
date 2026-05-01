<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\Admin\AdminLoginController;
use App\Http\Controllers\Auth\Admin\AdminHomeController;
use App\Http\Controllers\Auth\User\UserRegisterController;
use App\Http\Controllers\Auth\User\UserLoginController;



Route::middleware('guest')->group(function(){
    Route::post('/register',[UserRegisterController::class,'register']);
    Route::post('/login',[UserLoginController::class,'login'])->name('login');
    Route::post('/admin/login',[AdminLoginController::class,'adminLogin']);
    Route::get('admin/login', function () {return view('auth.admin-login');})->name('admin.login');
});

Route::middleware('auth:admin')->group(function(){
    Route::post('/admin/logout', [AdminLoginController::class, 'adminLogout']);
    Route::get('/admin/home', [AdminHomeController::class,'index']);
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
        return redirect()->route('profile');
    })->middleware(['auth:web'])->name('verification.verify');
});

