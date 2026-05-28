<?php

namespace App\Http\Controllers\ThinkMotion\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyPageController extends Controller
{
    public function index(Request $request){
        return view('thinkmotion.user.mypage');
    }
}
