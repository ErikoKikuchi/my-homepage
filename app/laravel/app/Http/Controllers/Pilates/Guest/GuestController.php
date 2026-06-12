<?php

namespace App\Http\Controllers\Pilates\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GuestController extends Controller
{
    public function index(Request $request){
        $startOfMonth = now()->startOfMonth();
        $startOfNextMonth = $startOfMonth->copy()->addMonth();

        return view('pilates.guest.reservation',[
            'dayOfWeek' => $startOfMonth->dayOfWeek,
            'daysInMonth' => $startOfMonth->daysInMonth,
            'dayOfWeekNext' => $startOfNextMonth->dayOfWeek,
            'daysInMonthNext' => $startOfNextMonth->daysInMonth,
        ]);
    }
}
