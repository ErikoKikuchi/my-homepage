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
    Route::get('/pilates/login', [UserLoginController::class, 'showPilatesForm'])->name('pilates.login');
    Route::post('/pilates/login', [UserLoginController::class, 'login'])->name('pilates.login.attempt');

    Route::get('/thinkmotion/login', [UserLoginController::class, 'showThinkmotionForm'])->name('thinkmotion.login');
    Route::post('/thinkmotion/login', [UserLoginController::class, 'login'])->name('thinkmotion.login.attempt');

    Route::post('/register',[UserRegisterController::class,'register']);
    Route::get('/pilates/admin/login', [AdminLoginController::class,'showPilatesForm'])->name('pilates.admin.login');
    Route::post('/pilates/admin/login',[AdminLoginController::class,'adminLogin'])->name('pilates.admin.login.attempt');
    Route::get('/thinkmotion/admin/login', [AdminLoginController::class,'showThinkmotionForm'])->name('thinkmotion.admin.login');
    Route::post('/thinkmotion/admin/login',[AdminLoginController::class,'adminLogin'])->name('thinkmotion.admin.login.attempt');
}); 



Route::prefix('pilates')->middleware(['auth:admin','admin.section:pilates'])->group(function(){
    Route::get('/admin/two-factor', [AdminTwoFactorController::class, 'showForm'])->name('pilates.admin.two-factor');
    Route::post('/admin/two-factor/verify', [AdminTwoFactorController::class, 'verify'])->name('pilates.admin.two-factor.verify');
    Route::get('/admin/two-factor/setup', [AdminTwoFactorSetupController::class, 'showSetupForm'])->name('pilates.admin.two-factor.setup');
    Route::post('/admin/two-factor/setup', [AdminTwoFactorSetupController::class, 'setup'])->name('pilates.admin.two-factor.setup.attempt');
});

Route::prefix('thinkmotion')->middleware(['auth:admin','admin.section:thinkmotion'])->group(function(){
    Route::get('/admin/two-factor', [AdminTwoFactorController::class, 'showForm'])->name('thinkmotion.admin.two-factor');
    Route::post('/admin/two-factor/verify', [AdminTwoFactorController::class, 'verify'])->name('thinkmotion.admin.two-factor.verify');
    Route::get('/admin/two-factor/setup', [AdminTwoFactorSetupController::class, 'showSetupForm'])->name('thinkmotion.admin.two-factor.setup');
    Route::post('/admin/two-factor/setup', [AdminTwoFactorSetupController::class, 'setup'])->name('thinkmotion.admin.two-factor.setup.attempt');
});

Route::prefix('pilates')->middleware(['auth:admin','admin.section:pilates', 'admin.2fa'])->group(function(){
    Route::get('/admin/home', [AdminHomeController::class,'index'])->name('pilates.admin.home');
    Route::post('/admin/logout', [AdminLoginController::class, 'adminLogout']);
});

Route::prefix('thinkmotion')->middleware(['auth:admin','admin.section:thinkmotion', 'admin.2fa'])->group(function(){
    Route::get('/admin/home', [AdminHomeController::class,'index'])->name('thinkmotion.admin.home');
    Route::post('/admin/logout', [AdminLoginController::class, 'adminLogout']);
});

Route::middleware('auth:web')->group(function () {
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

Route::middleware(['auth:web', 'verified'])->group(function(){
    Route::post('/logout', [UserLoginController::class, 'logout'])->name('logout');
});
