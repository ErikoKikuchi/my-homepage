<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pilates\Guest\GuestController as PilatesGuestController;
use App\Http\Controllers\Pilates\User\MyPageController as PilatesMyPageController;
use App\Http\Controllers\Pilates\User\ViewerController as PilatesViewerController;
use App\Http\Controllers\Pilates\User\ReservationController as PilatesReservationController;
use App\Http\Controllers\Pilates\User\CancellationController as PilatesCancellationController;
use App\Http\Controllers\Pilates\User\TrainingLogController as PilatesTrainingLogController;
use App\Http\Controllers\Pilates\User\TicketController as PilatesTicketsController;
use App\Http\Controllers\Pilates\Admin\LessonSlotController as PilatesAdminLessonSlotController;
use App\Http\Controllers\Pilates\Admin\LessonTemplateController as PilatesAdminLessonTemplateController;
use App\Http\Controllers\Pilates\Admin\ReservationController as PilatesAdminReservationController;
use App\Http\Controllers\Pilates\Admin\ClientController as PilatesAdminClientController;
use App\Http\Controllers\Pilates\Admin\TrainingLogController as PilatesAdminTrainingLogController;
use App\Http\Controllers\Auth\User\UserLoginController;
use App\Http\Controllers\Pilates\Admin\AccountingController as PilatesAdminAccountingController;
use App\Http\Controllers\Pilates\Admin\LocationController as PilatesAdminLocationController;
use App\Http\Controllers\Pilates\Admin\SessionController as PilatesAdminSessionController;
use App\Http\Controllers\Pilates\Admin\GoalController as PilatesAdminGoalController;
use App\Http\Controllers\Pilates\Admin\HopeController as PilatesAdminHopeController;
use App\Http\Controllers\Pilates\Admin\IntakeFormController as PilatesAdminIntakeFormController;
use App\Http\Controllers\Pilates\Admin\ReservationConfirmationController as PilatesAdminReservationConfirmationController;
use App\Http\Controllers\Pilates\Admin\CalendarController as PilatesAdminCalendarController;
use App\Http\Controllers\Pilates\Admin\ReservationNoshowController as PilatesAdminReservationNoshowController;
use App\Http\Controllers\Pilates\Admin\ClientSearchController as PilatesAdminClientSearchController;



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
    Route::resource('/training-logs', PilatesTrainingLogController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy'])->names('pilates.user.training-logs');
    Route::post('/logout', [UserLoginController::class, 'logout'])->name('pilates.logout');

});

//管理者
Route::prefix('pilates/admin')->middleware(['auth:admin', 'admin.section:pilates', 'admin.2fa'])->group(function () {
    Route::resource('/lesson-templates',PilatesAdminLessonTemplateController::class)->only(['index', 'create', 'store','edit','update','destroy'])->names('pilates.admin.lesson-templates');
    Route::resource('/lesson-slots',PilatesAdminLessonSlotController::class)->only(['index', 'create', 'store','edit','update', 'destroy'])->names('pilates.admin.lesson-slots');
    Route::resource('clients.sessions', PilatesAdminSessionController::class)
    ->shallow()->parameters(['clients' => 'client'])
    ->only(['index','show','create','store','edit','update'])
    ->names('pilates.admin.session');
    Route::resource('clients.accounting', PilatesAdminAccountingController::class)
    ->shallow()->parameters(['clients' => 'client'])
    ->only(['index','show','create','store','edit','update'])
    ->names('pilates.admin.accounting');
    Route::get('/clients/archive',[PilatesAdminClientController::class,'archive'])->name('pilates.admin.clients.archive');
    Route::resource('/clients',PilatesAdminClientController::class)->parameters(['clients' => 'client'])->only(['index','show', 'create', 'store','edit','update'])->names('pilates.admin.clients');
    Route::get('/training-logs/archive',[PilatesAdminTrainingLogController::class,'archive'])->name('pilates.admin.training-logs.archive');
    Route::resource('clients.training-logs', PilatesAdminTrainingLogController::class)
    ->shallow()->parameters(['clients' => 'client'])
    ->only(['index','show','create','store','edit','update'])
    ->names('pilates.admin.training-logs');
    Route::get('/locations/archive',[PilatesAdminLocationController::class,'show'])->name('pilates.admin.location.show');
    Route::patch('/locations/{location}/archive', [PilatesAdminLocationController::class, 'archive'])
    ->name('pilates.admin.location.archive');
    Route::patch('/locations/{location}/restore', [PilatesAdminLocationController::class, 'restore'])
        ->name('pilates.admin.location.restore');
    Route::resource('/location',PilatesAdminLocationController::class)->only(['index', 'create', 'store','edit','update','destroy'])->names('pilates.admin.location');
    Route::get('reservations/pending', [PilatesAdminReservationConfirmationController::class, 'index'])
        ->name('pilates.admin.reservation.pending');
    Route::patch('reservations/{reservation}/confirm', [PilatesAdminReservationConfirmationController::class, 'confirm'])
        ->name('pilates.admin.reservation.confirm');
    Route::resource('lesson-slots.reservations', PilatesAdminReservationController::class)
    ->shallow()
    ->only(['index','create','store','edit','update','destroy'])
    ->names('pilates.admin.reservation');
    Route::patch('/goals/{goal}/toggle-active', [PilatesAdminGoalController::class, 'toggleActive'])
    ->name('pilates.admin.goal.toggle-active');
    Route::resource('clients.goals', PilatesAdminGoalController::class)
    ->shallow()->parameters(['clients' => 'client'])
    ->only(['index','show','create','store','edit','update'])
    ->names('pilates.admin.goal');
    Route::patch('/hopes/{hope}/toggle-active', [PilatesAdminHopeController::class, 'toggleActive'])
    ->name('pilates.admin.hope.toggle-active');
    Route::resource('clients.hopes', PilatesAdminHopeController::class)
    ->shallow()->parameters(['clients' => 'client'])
    ->only(['index','show','create','store','edit','update'])
    ->names('pilates.admin.hope');
    Route::resource('clients.intake-forms', PilatesAdminIntakeFormController::class)
    ->shallow()->parameters(['clients' => 'client'])
    ->only(['index','show','create','store','edit','update'])
    ->names('pilates.admin.intake-forms');
    // カレンダー表示(1-2)
    Route::get('/calendar', [PilatesAdminCalendarController::class, 'index'])
        ->name('pilates.admin.calendar');
    // カレンダー用イベントデータ取得(JS側でfetchする想定)
    Route::get('/calendar/events', [PilatesAdminCalendarController::class, 'index'])
        ->name('pilates.admin.calendar.events');
    Route::patch('reservations/{reservation}/noshow', [PilatesAdminReservationNoshowController::class, 'update'])
    ->name('pilates.admin.reservation.noshow');
    Route::get('/client/search',[PilatesAdminClientSearchController::class,'search'])->name('pilates.admin.client.search');
});