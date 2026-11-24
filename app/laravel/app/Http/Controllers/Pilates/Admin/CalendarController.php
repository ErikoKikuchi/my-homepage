<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pilates\Admin\ReservationIndexRequest as AdminReservationIndexRequest;
use App\Services\Pilates\AdminReservationAvailabilityService;
use App\Models\Pilates\ReservationClosure;
use Carbon\Carbon;
use Inertia\Inertia;

class CalendarController extends Controller
{
    public function __construct(
        private AdminReservationAvailabilityService $availabilityService
    ) {}

    public function index(AdminReservationIndexRequest $request)
    {
        $weekStart = $request->input('week_start', now()->startOfWeek(Carbon::SUNDAY)->format('Y-m-d'));

        if ($request->expectsJson()) {
            $closures = ReservationClosure::whereBetween('date', [
                $weekStart,
                Carbon::parse($weekStart)->addDays(6)->toDateString(),
            ])->pluck('period', 'date');
            $weekMap = $this->availabilityService->adminBuildWeekMap($weekStart, $closures);
            $previous = Carbon::parse($weekStart)->subWeek()->format('Y-m-d');
            $next = Carbon::parse($weekStart)->addWeek()->format('Y-m-d');

            return response()->json([
                'weekStart' => $weekStart,
                'weekMap' => $weekMap,
                'closures' => (object) $closures->all(),
                'previous' => $previous,
                'next' => $next,
            ]);
        }

        return Inertia::render('Admin/Pilates/Reservations/Calendar', [
            'weekStart' => $weekStart,
        ]);
    }
}
