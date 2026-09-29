<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Enums\Pilates\ReservationStatus;
use App\Http\Controllers\Controller;
use App\Models\Pilates\Reservation;
use Illuminate\Http\Request;

class AdminHomeController extends Controller
{
    public function index(Request $request)
    {
        $section = $request->attributes->get('section');
        $pendingReservationCount = Reservation::where('status', ReservationStatus::WaitingVenue)->count();

        return view("pages.{$section}.admin.dashboard", compact('pendingReservationCount'));
    }
}
