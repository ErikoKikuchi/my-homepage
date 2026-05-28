<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pilates\Guest\GuestController as PilatesGuestController;
use App\Http\Controllers\Pilates\User\MyPageController as PilatesMyPageController;
use App\Http\Controllers\Pilates\User\ReservationController;

// ゲスト用
Route::get('/pilates', [PilatesGuestController::class, 'index'])->name('pilates.guest.index');

// ログイン後
Route::middleware(['auth:web', 'verified'])->group(function () {
    Route::get('/pilates/mypage', [PilatesMyPageController::class, 'index'])->name('pilates.mypage');
    Route::resource('/pilates/reservations', ReservationController::class)->only(['index', 'create', 'store']);
});