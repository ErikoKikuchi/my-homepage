<?php

namespace App\Http\Controllers\Pilates\Guest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pilates\Guest\ReservationIndexRequest;
use App\Models\Pilates\LessonSlot;
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

        $slots = LessonSlot::where('is_active',true)
        ->whereBetween('date', [
            $startOfMonth->format('Y-m-d'),
            $startOfMonth->copy()->endOfMonth()->format('Y-m-d'),
        ])
        ->with('reservations')
        ->get();

        $slotMap = $slots->groupBy(fn($slot) => $slot->date->format('Y-m-d'))
            ->map(function($daySlots) {
                $hasAvailable = $daySlots->some(function($slot) {
                    return $slot->reservations
                        ->whereNotIn('status', ['canceled'])
                        ->count() === 0;
                });
                return $hasAvailable ? 'available' : 'full';
            });

        $cells=[];
        for ($i = 0; $i < 42; $i++)
            if ($i - $dayOfWeek<0 || $i>=$daysInMonth+$dayOfWeek){
                $cells[] = null;
            }else{
                $date = $i-$dayOfWeek+1;
                $dateString = $month . '-' . sprintf('%02d', $date);
                $cells[]=[
                    'date'=>$date,
                    'status' => $slotMap[$dateString] ?? null,
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
