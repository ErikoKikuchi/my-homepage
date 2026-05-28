<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThinkMotion\Guest\GuestController as ThinkMotionGuestController;;
use App\Http\Controllers\ThinkMotion\User\MyPageController as ThinkMotionMyPageController;
use App\Http\Controllers\ThinkMotion\User\ViewerController as UserViewerController;
use App\Http\Controllers\ThinkMotion\User\UserProfileController;


//ゲスト用
Route::get('/thinkmotion', [UserViewerController::class, 'index']);
Route::get('/thinkmotion/posts',[ThinkMotionGuestController::class,'index'])->name('thinkmotion.guest.index');

//ユーザーログイン後
Route::middleware(['auth:web','verified'])->group(function () {
    Route::get('/profile/register',[UserProfileController::class,'register'])->name('profile.register');
    Route::get('/thinkmotion/mypage',[ThinkMotionMyPageController::class, 'index'])->name('thinkmotion.mypage');
});

//Route::middleware(['auth', 'room.admin.verified'])->group(function(){
//    Route::get('/rooms/{room}/admin', ...);
//    Route::get('/rooms/{room}/admin/posts', ...);
//});