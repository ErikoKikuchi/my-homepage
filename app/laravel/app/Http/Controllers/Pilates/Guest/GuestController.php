<?php

namespace App\Http\Controllers\Pilates\Guest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pilates\Guest\ReservationIndexRequest;
use Carbon\Carbon;

class GuestController extends Controller
{
    public function index(ReservationIndexRequest $request){
        $month = $request->input('month', now()->format('Y-m'));
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $dayOfWeek = $startOfMonth->dayOfWeek;
        $daysInMonth = $startOfMonth->daysInMonth;
        $previous=now()->parse($month)->subMonth()->format('Y-m');
        $next=now()->parse($month)->addMonth()->format('Y-m');

        $slots = [
            '2026-06-18' => 'available',
            '2026-06-20' => 'full',
        ];
        $cells=[];
        for ($i = 0; $i < 42; $i++)
            if ($i - $dayOfWeek<0 || $i>=$daysInMonth+$dayOfWeek){
                $cells[] = null;
            }else{
                $date = $i-$dayOfWeek+1;
                $dateString = $month . '-' . sprintf('%02d', $date);
                $cells[]=[
                    'date'=>$date,
                    'status' => $slots[$dateString] ?? null,
                ];
            }

        if ($request->expectsJson()) {
            return response()->json([
                'month' => $month,
                'cells' => $cells,
                'previous' => $previous,
                'next' => $next,
            ]);
        }

        return view('pilates.guest.reservation', compact('month', 'cells', 'previous', 'next'));
    }
}
