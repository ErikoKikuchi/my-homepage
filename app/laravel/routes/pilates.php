<?php

use App\Http\Controllers\Pilates\Admin\AccountingController as PilatesAdminAccountingController;
use App\Http\Controllers\Pilates\Admin\ExerciseController;
use App\Http\Controllers\Pilates\Admin\CalendarController as PilatesAdminCalendarController;
use App\Http\Controllers\Pilates\Admin\ClientController as PilatesAdminClientController;
use App\Http\Controllers\Pilates\Admin\ClientSearchController as PilatesAdminClientSearchController;
use App\Http\Controllers\Pilates\Admin\GoalController as PilatesAdminGoalController;
use App\Http\Controllers\Pilates\Admin\IntakeFormController as PilatesAdminIntakeFormController;
use App\Http\Controllers\Pilates\Admin\LessonSlotController as PilatesAdminLessonSlotController;
use App\Http\Controllers\Pilates\Admin\LessonTemplateController as PilatesAdminLessonTemplateController;
use App\Http\Controllers\Pilates\Admin\LocationController as PilatesAdminLocationController;
use App\Http\Controllers\Pilates\Admin\ReservationConfirmationController as PilatesAdminReservationConfirmationController;
use App\Http\Controllers\Pilates\Admin\ReservationController as PilatesAdminReservationController;
use App\Http\Controllers\Pilates\Admin\ReservationNoshowController as PilatesAdminReservationNoshowController;
use App\Http\Controllers\Pilates\Admin\SessionController as PilatesAdminSessionController;
use App\Http\Controllers\Pilates\Admin\TrainingLogController as PilatesAdminTrainingLogController;
use Illuminate\Support\Facades\Route;

// 管理者
Route::prefix('pilates/admin')->middleware(['auth:admin', 'admin.section:pilates', 'admin.2fa', 'inertia'])->group(function () {
    //分析
    Route::prefix('analytics')->name('pilates.admin.analytics.')->group(function () {
        Route::inertia('/', 'Admin/Pilates/Analytics/Index')->name('index');
        Route::get('reservations', [PilatesAdminReservationController::class, 'cancellations'])->name('reservations');
        Route::inertia('accounting', 'Admin/Pilates/Analytics/Accounting/Index')->name('accounting');
        Route::inertia('training-logs', 'Admin/Pilates/Analytics/TrainingLogs/Index')->name('training-logs');
    });

    //スケジュール
    Route::resource('/lesson-templates', PilatesAdminLessonTemplateController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->names('pilates.admin.lesson-templates');
    Route::resource('/lesson-slots', PilatesAdminLessonSlotController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->names('pilates.admin.lesson-slots');

    //セッション記録・ピラティス登録
    Route::get('sessions', [PilatesAdminSessionController::class, 'clients'])
        ->name('pilates.admin.sessions.clients');
    Route::get('exercises/{exercise}/photos/{slot}', [ExerciseController::class, 'photo'])
        ->where('slot', '[0-2]')->name('pilates.admin.exercises.photo');
    Route::resource('exercises', ExerciseController::class)
        ->only(['index', 'store', 'update'])->names('pilates.admin.exercises');
    Route::resource('clients.sessions', PilatesAdminSessionController::class)
        ->shallow()->parameters(['clients' => 'client'])
        ->only(['index', 'show', 'create', 'store', 'update'])
        ->names('pilates.admin.session');

    //会計
    Route::get('accounting', [PilatesAdminAccountingController::class, 'clients'])
        ->name('pilates.admin.accounting.clients');
    Route::resource('clients.accounting', PilatesAdminAccountingController::class)
        ->shallow()->parameters(['clients' => 'client'])
        ->only(['index', 'show', 'create', 'store', 'edit', 'update'])
        ->names('pilates.admin.accounting');

    //クライアント管理
    Route::post('/clients/{client}/register', [PilatesAdminClientController::class, 'registerProvisional'])
        ->name('pilates.admin.clients.register-provisional');
    Route::post('/clients/{client}/account', [PilatesAdminClientController::class, 'linkAccount'])
        ->name('pilates.admin.clients.link-account');
    Route::get('/clients/archive', [PilatesAdminClientController::class, 'archive'])->name('pilates.admin.clients.archive');
    Route::resource('/clients', PilatesAdminClientController::class)->parameters(['clients' => 'client'])->only(['index', 'show', 'create', 'store', 'edit', 'update'])->names('pilates.admin.clients');

    // 自主トレログ
    Route::get('/training-logs/archive', [PilatesAdminTrainingLogController::class, 'archive'])->name('pilates.admin.training-logs.archive');
    Route::resource('clients.training-logs', PilatesAdminTrainingLogController::class)
        ->shallow()->parameters(['clients' => 'client'])
        ->only(['index', 'show', 'create', 'store', 'edit', 'update'])
        ->names('pilates.admin.training-logs');

    //場所管理
    Route::get('/locations/archive', [PilatesAdminLocationController::class, 'show'])->name('pilates.admin.location.show');
    Route::patch('/locations/{location}/archive', [PilatesAdminLocationController::class, 'archive'])
        ->name('pilates.admin.location.archive');
    Route::patch('/locations/{location}/restore', [PilatesAdminLocationController::class, 'restore'])
        ->name('pilates.admin.location.restore');
    Route::resource('/location', PilatesAdminLocationController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->names('pilates.admin.location');

    //予約管理
    Route::get('reservations', [PilatesAdminReservationController::class, 'index'])
        ->name('pilates.admin.reservation.history');
    Route::get('reservations/pending', [PilatesAdminReservationConfirmationController::class, 'index'])
        ->name('pilates.admin.reservation.pending');
    Route::patch('reservations/{reservation}/confirm', [PilatesAdminReservationConfirmationController::class, 'confirm'])
        ->name('pilates.admin.reservation.confirm');
    Route::resource('lesson-slots.reservations', PilatesAdminReservationController::class)
        ->shallow()
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
        ->names('pilates.admin.reservation');

//ゴール設定
    Route::get('/clients/{client}/goals', [PilatesAdminGoalController::class, 'index'])
        ->name('pilates.admin.goal.index');
    Route::patch('/clients/{client}/goals', [PilatesAdminGoalController::class, 'update'])
        ->name('pilates.admin.goal.update');

    //問診表
    Route::resource('clients.intake-forms', PilatesAdminIntakeFormController::class)
        ->shallow()->parameters(['clients' => 'client'])
        ->only(['index', 'show', 'create', 'store', 'edit', 'update'])
        ->names('pilates.admin.intake-forms');
    // カレンダー表示(1-2)
    Route::get('/calendar', [PilatesAdminCalendarController::class, 'index'])
        ->name('pilates.admin.calendar');
    // カレンダー用イベントデータ取得(JS側でfetchする想定)
    Route::get('/calendar/events', [PilatesAdminCalendarController::class, 'index'])
        ->name('pilates.admin.calendar.events');

    //その他
    Route::patch('reservations/{reservation}/noshow', [PilatesAdminReservationNoshowController::class, 'update'])
        ->name('pilates.admin.reservation.noshow');
    Route::get('/client/search', [PilatesAdminClientSearchController::class, 'search'])->name('pilates.admin.client.search');

});
