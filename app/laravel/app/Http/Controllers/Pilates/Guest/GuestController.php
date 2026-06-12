<?php

namespace App\Http\Controllers\Pilates\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index(Request $request){
        return view('pilates.guest.reservation');
    }
}
