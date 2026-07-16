<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pilates\Guest\GuestController as PilatesGuestController;
use App\Http\Controllers\Pilates\User\MyPageController as PilatesMyPageController;
use App\Http\Controllers\Pilates\User\ViewerController as PilatesViewerController;
use App\Http\Controllers\Pilates\User\ReservationController as PilatesReservationController;
use App\Http\Controllers\Pilates\User\CancellationController as PilatesCancellationController;
use App\Http\Controllers\Pilates\User\BodyMindController as PilatesBodyMindController;
use App\Http\Controllers\Pilates\User\TicketController as PilatesTicketsController;
use App\Http\Controllers\Pilates\Admin\AdminController as PilatesAdminController;
use App\Http\Controllers\Pilates\Admin\LessonSlotController as PilatesAdminLessonSlotController;
use App\Http\Controllers\Pilates\Admin\LessonTemplateController as PilatesAdminLessonTemplateController;
use App\Http\Controllers\Pilates\Admin\ReservationController as PilatesAdminReservationController;
use App\Http\Controllers\Pilates\Admin\ClientController as PilatesAdminClientController;
use App\Http\Controllers\Pilates\Admin\BodyMindController as PilatesAdminBodyMindController;
use App\Http\Controllers\Auth\User\UserLoginController;
use App\Http\Controllers\Pilates\Admin\AccountingController as PilatesAdminAccountingController;
use App\Http\Controllers\Pilates\Admin\LocationController as PilatesAdminLocationController;



// ゲスト用
Route::get('/pilates', [PilatesViewerController::class, 'index']);
Route::get('/pilates/calendar', [PilatesGuestController::class, 'index'])->name('pilates.guest.index');
Route::get('/pilates/slots',[PilatesGuestController::class,'show'])->name('pilates.guest.show');



// ログイン後
Route::prefix('pilates')->middleware(['auth:web', 'verified'])->group(function () {
    Route::get('/mypage', [PilatesMyPageController::class, 'index'])->name('pilates.mypage');
    Route::get('/archive',[PilatesReservationController::class,'archive'])->name('pilates.past.reservation');
    Route::get('/tickets', [PilatesTicketsController::class, 'index'])->name('pilates.tickets');
    Route::patch('/reservations/{reservation}/cancel', [PilatesCancellationController::class,'cancel'])->name('pilates.user.reservation.cancel');
    Route::resource('/reservations', PilatesReservationController::class)->only(['index', 'show', 'create','store'])->names('pilates.user.reservation');
    Route::resource('/bodymind', PilatesBodyMindController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy'])->names('pilates.user.bodymind');
    Route::post('/logout', [UserLoginController::class, 'logout'])->name('pilates.logout');
});

//管理者
Route::prefix('pilates/admin')->middleware(['auth:admin', 'admin.section:pilates', 'admin.2fa'])->group(function () {
    Route::get('/',[PilatesAdminController::class,'index'])->name('pilates.admin.home');
    Route::resource('/lesson-templates',PilatesAdminLessonTemplateController::class)->only(['index', 'create', 'store','edit','update'])->names('pilates.admin.lesson-templates');
    Route::get('/lesson-slots/{slot}/confirm-location',[PilatesAdminClientController::class,'archive'])->name('pilates.admin.location.confirm');
    Route::get('/lesson-slots/{slot}/archive',[PilatesAdminLessonSlotController::class,'archive'])->name('pilates.admin.slots.archive');
    Route::resource('/lesson-slots',PilatesAdminLessonSlotController::class)->only(['index', 'create', 'store','destroy'])->names('pilates.admin.lesson-slots');
    Route::resource('/sessions',PilatesAdminReservationController::class)->only(['index','show', 'create', 'store','edit','update'])->names('pilates.admin.session');
    Route::resource('/accounting',PilatesAdminAccountingController::class)->only(['index','show', 'create', 'store','edit','update'])->names('pilates.admin.accounting');
    Route::get('/clients/archive',[PilatesAdminClientController::class,'archive'])->name('pilates.admin.clients.archive');
    Route::resource('/clients',PilatesAdminClientController::class)->only(['index','show', 'create', 'store','edit','update'])->names('pilates.admin.clients');
    Route::get('/bodymind/archive',[PilatesAdminBodyMindController::class,'archive'])->name('pilates.admin.bodymind.archive');
    Route::resource('/bodymind',PilatesAdminBodyMindController::class)->only(['index','show', 'create', 'store','edit','update'])->names('pilates.admin.bodymind');
    Route::get('/locations/archive',[PilatesAdminLocationController::class,'show'])->name('pilates.admin.location.show');
    Route::patch('/locations/{location}/archive', [PilatesAdminLocationController::class, 'archive'])
    ->name('pilates.admin.location.archive');
    Route::patch('/locations/{location}/restore', [PilatesAdminLocationController::class, 'restore'])
        ->name('pilates.admin.location.restore');
    Route::resource('/location',PilatesAdminLocationController::class)->only(['index', 'create', 'store','edit','update','destroy'])->names('pilates.admin.location');
});