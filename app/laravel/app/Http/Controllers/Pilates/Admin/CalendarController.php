<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pilates\Admin\ReservationIndexRequest as AdminReservationIndexRequest;
use App\Services\Pilates\AdminReservationAvailabilityService;
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
            $weekMap = $this->availabilityService->adminBuildWeekMap($weekStart);
            $previous = Carbon::parse($weekStart)->subWeek()->format('Y-m-d');
            $next = Carbon::parse($weekStart)->addWeek()->format('Y-m-d');

            return response()->json([
                'weekStart' => $weekStart,
                'weekMap' => $weekMap,
                'previous' => $previous,
                'next' => $next,
            ]);
        }

        return Inertia::render('Admin/Pilates/Reservations/Calendar', [
            'weekStart' => $weekStart,
        ]);
    }
}
