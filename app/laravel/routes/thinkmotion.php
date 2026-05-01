<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThinkMotion\User\ViewerController as UserViewerController;
use App\Http\Controllers\ThinkMotion\User\UserProfileController;


Route::get('/thinkmotion',[UserViewerController::class,'index']);

Route::middleware(['auth:web','verified'])->group(function () {
    Route::get('/profile/register',[UserProfileController::class,'register'])->name('profile.register');
});
