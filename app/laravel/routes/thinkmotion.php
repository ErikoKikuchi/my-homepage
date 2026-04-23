<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThinkMotion\User\ViewerController as UserViewerController;

Route::get('/thinkmotion',[UserViewerController::class,'index']);