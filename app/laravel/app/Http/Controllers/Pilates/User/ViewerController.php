<?php

namespace App\Http\Controllers\Pilates\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ViewerController extends Controller
{
    public function index(Request $request){
    
        return view('pages.pilates.pilates-top');
    }
}
