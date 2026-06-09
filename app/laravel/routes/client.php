<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pilates\Guest\GuestController as PilatesGuestController;
use App\Http\Controllers\Pilates\User\MyPageController as PilatesMyPageController;
use App\Http\Controllers\Pilates\User\ReservationController as PilatesReservationController;
use App\Http\Controllers\Pilates\User\TrainingLogController as PilatesTrainingLogController;
use App\Http\Controllers\Pilates\Admin\AdminController as PilatesAdminController;
use App\Http\Controllers\Pilates\Admin\ScheduleController as PilatesAdminScheduleController;
use App\Http\Controllers\Pilates\Admin\ReservationController as PilatesAdminReservationController;
use App\Http\Controllers\Pilates\Admin\ClientsController as PilatesAdminClientsController;
use App\Http\Controllers\Pilates\Admin\TrainingLogController as PilatesAdminTrainingLogController;


// ゲスト用
Route::get('/pilates', [PilatesGuestController::class, 'index'])->name('pilates.guest.index');
Route::resource('/pilates/reservations', PilatesReservationController::class)->only(['index', 'create', 'store','update','destroy']);

// ログイン後
Route::middleware(['auth:web', 'verified'])->group(function () {
    Route::get('/pilates/mypage', [PilatesMyPageController::class, 'index'])->name('pilates.mypage');
    Route::resource('/pilates/training-log',PilatesTrainingLogController::class)->only(['index', 'create', 'store','edit','destroy']);
});

//管理者
Route::middleware(['auth:admin', 'admin.2fa'])->group(function () {
    Route::get('/pilates/admin',[PilatesAdminController::class,'index']);
    Route::get('/pilates/admin/schedule',[PilatesAdminScheduleController::class,'index']);
    Route::get('/pilates/admin/reservations',[PilatesAdminReservationController::class,'index']);
    Route::get('/pilates/admin/clients',[PilatesAdminClientsController::class,'index']);
    Route::get('/pilates/admin/clients/archive',[PilatesAdminClientsController::class,'archive']);
    Route::get('/pilates/admin/clients/{id}',[PilatesAdminClientsController::class,'show']);
    Route::get('/pilates/admin/training-log',[PilatesAdminTrainingLogController::class,'index']);
    Route::get('/pilates/admin/training-log/{id}',[PilatesAdminTrainingLogController::class,'show']);
});