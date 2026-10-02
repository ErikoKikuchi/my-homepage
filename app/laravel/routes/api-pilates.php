<?php

use App\Http\Controllers\Auth\User\CurrentPilatesUserController;
use App\Http\Controllers\Auth\User\UserLoginController;
use App\Http\Controllers\Pilates\Guest\GuestController as PilatesGuestController;
use App\Http\Controllers\Pilates\User\CancellationController as PilatesCancellationController;
use App\Http\Controllers\Pilates\User\MyPageController as PilatesMyPageController;
use App\Http\Controllers\Pilates\User\ReservationController as PilatesReservationController;
use App\Http\Controllers\Pilates\User\TicketController as PilatesTicketsController;
use App\Http\Controllers\Pilates\User\TrainingLogController as PilatesTrainingLogController;
use App\Http\Controllers\Pilates\User\ViewerController as PilatesViewerController;
use Illuminate\Support\Facades\Route;

// ゲスト用
Route::get('/', [PilatesViewerController::class, 'index']);
Route::prefix('/reservation')->group(function () {
    Route::get('/calendar', [PilatesGuestController::class, 'index'])->name('pilates.guest.index');
    Route::get('/slots', [PilatesGuestController::class, 'show'])->name('pilates.guest.show');
    Route::post('/intent', [PilatesReservationController::class, 'intent'])
        ->name('pilates.reservation.intent');
});

// ログイン/ログアウト用
Route::post('/login', [UserLoginController::class, 'login'])->name('pilates.login.attempt')->middleware('throttle:login');
Route::post('/logout', [UserLoginController::class, 'logout'])
    ->name('pilates.logout');
Route::get('/user', CurrentPilatesUserController::class)
    ->middleware('auth:web');

// ログイン後
Route::middleware(['auth:web', 'verified', 'section:pilates','auth:sanctum'])->group(function () {
    Route::get('/mypage', [PilatesMyPageController::class, 'index'])->name('pilates.mypage');
    Route::get('/archive', [PilatesReservationController::class, 'archive'])->name('pilates.past.reservation');
    Route::get('/tickets', [PilatesTicketsController::class, 'index'])->name('pilates.tickets');
    Route::patch('/reservations/{reservation}/cancel', [PilatesCancellationController::class, 'cancel'])->name('pilates.user.reservation.cancel');
    Route::resource('/reservations', PilatesReservationController::class)->only(['index', 'show', 'create', 'store'])->names('api.pilates.user.reservation');
    Route::resource('/training-logs', PilatesTrainingLogController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy'])->names('pilates.user.training-logs');
});
