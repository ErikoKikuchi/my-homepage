<?php

namespace App\Http\Controllers\Pilates\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request){
        return view('pilates.user.mypage');
    }

    public function create(Request $request)
    {
        $date = $request->input('date');
        $dayOfWeek = \Carbon\Carbon::parse($date)->dayOfWeek;

        return view('pilates.guest.reservation-detail', [
            'date' => $date,
            'isWednesday' => $dayOfWeek === 3,
        ]);
    }
}
