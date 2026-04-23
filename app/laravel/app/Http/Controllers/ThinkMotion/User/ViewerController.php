<?php

namespace App\Http\Controllers\ThinkMotion\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ViewerController extends Controller
{
    public function index(Request $request){
        //新規の記録5件をもって
        return view('thinkmotion.top');
    }
}
