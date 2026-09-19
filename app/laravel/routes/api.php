<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pilates\Guest\GuestController as PilatesGuestController;


Route::post('/pilates/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (! Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['message' => '認証に失敗しました'], 422);
    }

    $request->session()->regenerate();

    return response()->json(['message' => 'ログイン成功']);
})->middleware('guest');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('pilates/reservation')->group(function () {
    Route::get('/calendar', [PilatesGuestController::class, 'index'])->name('pilates.guest.index');
    Route::get('/slots', [PilatesGuestController::class, 'show'])->name('pilates.guest.show');
});