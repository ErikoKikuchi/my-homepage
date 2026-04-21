<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;


Route::middleware('guest')->group(function(){
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/admin/login',[AuthController::class,'adminLogin']);
    Route::get('admin/login', function () {return view('admin.auth.login');})->name('admin.login');
});