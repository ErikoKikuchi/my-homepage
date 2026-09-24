<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Admin\AdminLoginController;
use App\Http\Controllers\Auth\Admin\AdminTwoFactorController;
use App\Http\Controllers\Auth\Admin\AdminTwoFactorSetupController;
use App\Http\Controllers\Auth\Admin\AdminHomeController;


Route::middleware(['guest', 'inertia'])->group(function(){
    Route::get('/pilates/admin/login', [AdminLoginController::class,'showPilatesForm'])->name('pilates.admin.login');
    Route::post('/pilates/admin/login',[AdminLoginController::class,'adminLogin'])->name('pilates.admin.login.attempt')->middleware('throttle:login');
    Route::get('/thinkmotion/admin/login', [AdminLoginController::class,'showThinkmotionForm'])->name('thinkmotion.admin.login');
    Route::post('/thinkmotion/admin/login',[AdminLoginController::class,'adminLogin'])->name('thinkmotion.admin.login.attempt')->middleware('throttle:login');
});



Route::prefix('pilates')->middleware(['auth:admin','admin.section:pilates', 'inertia'])->group(function(){
    Route::get('/admin/two-factor', [AdminTwoFactorController::class, 'showForm'])->name('pilates.admin.two-factor');
    Route::post('/admin/two-factor/verify', [AdminTwoFactorController::class, 'verify'])->name('pilates.admin.two-factor.verify')->middleware('throttle:two-factor');
    Route::get('/admin/two-factor/setup', [AdminTwoFactorSetupController::class, 'showSetupForm'])->name('pilates.admin.two-factor.setup');
    Route::post('/admin/two-factor/setup', [AdminTwoFactorSetupController::class, 'setup'])->name('pilates.admin.two-factor.setup.attempt')->middleware('throttle:two-factor');
});

Route::prefix('thinkmotion')->middleware(['auth:admin','admin.section:thinkmotion', 'inertia'])->group(function(){
    Route::get('/admin/two-factor', [AdminTwoFactorController::class, 'showForm'])->name('thinkmotion.admin.two-factor');
    Route::post('/admin/two-factor/verify', [AdminTwoFactorController::class, 'verify'])->name('thinkmotion.admin.two-factor.verify')->middleware('throttle:two-factor');
    Route::get('/admin/two-factor/setup', [AdminTwoFactorSetupController::class, 'showSetupForm'])->name('thinkmotion.admin.two-factor.setup');
    Route::post('/admin/two-factor/setup', [AdminTwoFactorSetupController::class, 'setup'])->name('thinkmotion.admin.two-factor.setup.attempt')->middleware('throttle:two-factor');
});

Route::prefix('pilates')->middleware(['auth:admin','admin.section:pilates', 'admin.2fa', 'inertia'])->group(function(){
    Route::get('/admin/home', [AdminHomeController::class,'index'])->name('pilates.admin.home');
    Route::post('/admin/logout', [AdminLoginController::class, 'adminLogout'])->name('pilates.admin.logout');
});

Route::prefix('thinkmotion')->middleware(['auth:admin','admin.section:thinkmotion', 'admin.2fa', 'inertia'])->group(function(){
    Route::get('/admin/home', [AdminHomeController::class,'index'])->name('thinkmotion.admin.home');
    Route::post('/admin/logout', [AdminLoginController::class, 'adminLogout'])->name('thinkmotion.admin.logout');;
});
