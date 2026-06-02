<?php

namespace App\Http\Controllers\ThinkMotion\User;;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function register(Request $request)
    {
        return view('thinkmotion.user.profile-register');
    }
}
