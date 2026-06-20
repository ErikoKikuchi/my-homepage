<?php

namespace App\Http\Controllers\Pilates\Guest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pilates\Guest\ReservationIndexRequest;
use Carbon\Carbon;
use App\Services\Pilates\ReservationAvailabilityService;

class GuestController extends Controller
{
    public function __construct(
        private ReservationAvailabilityService $availabilityService
    ) {}
    public function index(ReservationIndexRequest $request){
        $month = $request->input('month', now()->format('Y-m'));
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $dayOfWeek = $startOfMonth->dayOfWeek;
        $daysInMonth = $startOfMonth->daysInMonth;
        $previous=now()->parse($month)->subMonth()->format('Y-m');
        $next=now()->parse($month)->addMonth()->format('Y-m');

        $slotMap = $this->availabilityService->buildSlotMap($month);

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
    public function show(Request $request){
        $date = $request->query('date');
        $dayOfWeek = Carbon::parse($date)->isoFormat('ddd');
        $times = $this->availabilityService->getAvailableTimes($date);

        return response()->json([
            'date' => $date,
            'dayOfWeek' => $dayOfWeek,
            'times' => $times
        ]);
    }
}
