<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pilates\Guest\GuestController as PilatesGuestController;
use App\Http\Controllers\Pilates\User\MyPageController as PilatesMyPageController;
use App\Http\Controllers\Pilates\User\ReservationController as PilatesReservationController;
use App\Http\Controllers\Pilates\User\TrainingLogController as PilatesTrainingLogController;
use App\Http\Controllers\Pilates\User\TicketController as PilatesTicketsController;
use App\Http\Controllers\Pilates\Admin\AdminController as PilatesAdminController;
use App\Http\Controllers\Pilates\Admin\ScheduleController as PilatesAdminScheduleController;
use App\Http\Controllers\Pilates\Admin\ReservationController as PilatesAdminReservationController;
use App\Http\Controllers\Pilates\Admin\ClientController as PilatesAdminClientController;
use App\Http\Controllers\Pilates\Admin\TrainingLogController as PilatesAdminTrainingLogController;



// ゲスト用
Route::get('/pilates', [PilatesGuestController::class, 'index'])->name('pilates.guest.index');
Route::get('/pilates/slots',[PilatesGuestController::class,'show'])->name('pilates.guest.show');
Route::get('/pilates/reservations/create', [PilatesReservationController::class, 'create'])->name('pilates.guest.create');


// ログイン後
Route::prefix('pilates')->middleware(['auth:web', 'verified'])->group(function () {
    Route::get('/mypage', [PilatesMyPageController::class, 'index'])->name('pilates.mypage');
    Route::get('/archive',[PilatesReservationController::class,'archive'])->name('pilates.past.reservation');
    Route::get('/tickets', [PilatesTicketsController::class, 'index'])->name('pilates.tickets');
    Route::patch('/reservations/{reservation}/cancel', [PilatesReservationController::class,'cancel'])->name('pilates.user.reservation.cancel');
    Route::resource('/reservations', PilatesReservationController::class)->only(['index', 'show', 'store'])->names('pilates.user.reservation');
    Route::resource('/training-logs',PilatesTrainingLogController::class)->only(['index', 'show','create', 'store','edit','update','destroy'])->names('pilates.user.training-log');
});

//管理者
Route::prefix('pilates/admin')->middleware(['auth:admin', 'admin.2fa'])->group(function () {
    Route::get('/',[PilatesAdminController::class,'index'])->name('pilates.admin');
    Route::get('/schedules',[PilatesAdminScheduleController::class,'index'])->name('pilates.admin.schedule');
    Route::get('/reservations',[PilatesAdminReservationController::class,'index'])->name('pilates.admin.reservation');
    Route::get('/clients/archive',[PilatesAdminClientController::class,'archive'])->name('pilates.admin.clients.archive');
    Route::resource('/clients',PilatesAdminClientController::class)->only(['index','show', 'create', 'store','edit','update'])->names('pilates.admin.client');
    Route::resource('/training-logs',PilatesAdminTrainingLogController::class)->only(['index','show', 'create', 'store','edit','update','destroy'])->names('pilates.admin.training-log');
});