<?php

use App\Http\Controllers\Auth\User\UserLoginController;
use App\Http\Controllers\ThinkMotion\Guest\GuestController as ThinkMotionGuestController;
use App\Http\Controllers\ThinkMotion\User\MyPageController as ThinkMotionMyPageController;
use App\Http\Controllers\ThinkMotion\User\UserProfileController;
use App\Http\Controllers\ThinkMotion\User\ViewerController as UserViewerController;
use Illuminate\Support\Facades\Route;

// ゲスト用
Route::get('/', [UserViewerController::class, 'index']);
Route::get('/posts', [ThinkMotionGuestController::class, 'index'])->name('thinkmotion.guest.index');

// ログイン用
Route::post('/login', [UserLoginController::class, 'login'])->name('thinkmotion.login.attempt')->middleware('throttle:login');

// ユーザーログイン後
Route::middleware(['auth:web', 'verified', 'section:thinkmotion'])->group(function () {
    Route::get('/profile/register', [UserProfileController::class, 'register'])->name('profile.register');
    Route::get('/mypage', [ThinkMotionMyPageController::class, 'index'])->name('thinkmotion.mypage');
    Route::post('/logout', [UserLoginController::class, 'logout'])->name('thinkmotion.logout');
});

Route::prefix('thinkmotion/admin')->middleware(['auth:admin', 'admin.section:thinkmotion', 'admin.2fa', 'inertia'])->group(function () {});
