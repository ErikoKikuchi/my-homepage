<?php

namespace App\Http\Controllers\Pilates\Guest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pilates\Guest\ReservationIndexRequest;
use App\Models\Pilates\ReservationClosure;
use App\Services\Pilates\ReservationAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function __construct(
        private ReservationAvailabilityService $availabilityService
    ) {}

    public function index(ReservationIndexRequest $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $dayOfWeek = $startOfMonth->dayOfWeek;
        $daysInMonth = $startOfMonth->daysInMonth;
        $previous = now()->parse($month)->subMonth()->format('Y-m');
        $next = now()->parse($month)->addMonth()->format('Y-m');

        $closures = ReservationClosure::whereBetween('date', [
            $startOfMonth->toDateString(), $startOfMonth->copy()->endOfMonth()->toDateString(),
        ])->pluck('period', 'date');
        $slotMap = $this->availabilityService->buildSlotMap($month, $closures);
        $today = now()->toDateString();

        $cells = [];
        for ($i = 0; $i < 42; $i++) {
            if ($i - $dayOfWeek < 0 || $i >= $daysInMonth + $dayOfWeek) {
                $cells[] = null;
            } else {
                $date = $i - $dayOfWeek + 1;
                $dateString = $month.'-'.sprintf('%02d', $date);
                $isPast = $dateString < $today;
                $period = $isPast ? null : $closures->get($dateString);
                $cells[] = [
                    'date' => $date,
                    'status' => $period === 'all_day' ? 'unavailable' : ($slotMap[$dateString] ?? null),
                    'closedPeriod' => $period,
                    'canSelect' => ! $isPast && $period !== 'all_day',
                ];
            }
        }

        return response()->json([
            'month' => $month,
            'cells' => $cells,
            'previous' => $previous,
            'next' => $next,
        ]);
    }

    public function show(Request $request)
    {
        $date = $request->validate(['date' => ['required', 'date_format:Y-m-d']])['date'];
        $dayOfWeek = Carbon::parse($date)->isoFormat('ddd');
        $period = ReservationClosure::find($date)?->period;
        $times = $this->availabilityService->getAvailableTimes($date, $period);
        $now = now();
        $earliestTime = $date === $now->toDateString() ? $now->copy()->addMinute()->format('H:i') : '00:00';
        $canRequest = $date >= $now->toDateString() && $period !== 'all_day'
            && ($date !== $now->toDateString() || $now->format('H:i') !== '23:59');

        return response()->json([
            'date' => $date,
            'dayOfWeek' => $dayOfWeek,
            'times' => $times,
            'closedPeriod' => $period,
            'canRequest' => $canRequest,
            'earliestRequestTime' => $period === 'morning' ? max('12:00', $earliestTime) : $earliestTime,
        ]);
    }
}
