<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pilates\Admin\ReservationIndexRequest as AdminReservationIndexRequest;
use Carbon\Carbon;
use App\Services\Pilates\AdminReservationAvailabilityService;

class CalendarController extends Controller
{
    public function __construct(
        private AdminReservationAvailabilityService $availabilityService
    ) {}
    public function index(AdminReservationIndexRequest $request)
    {
        $weekStart = $request->input('week_start', now()->startOfWeek(Carbon::SUNDAY)->format('Y-m-d'));
        $weekMap = $this->availabilityService->adminBuildWeekMap($weekStart);
        $previous=now()->parse($weekStart)->subWeek()->format('Y-m-d');
        $next=now()->parse($weekStart)->addWeek()->format('Y-m-d');;

        if ($request->expectsJson()) {
            return response()->json([
                'weekStart' => $weekStart,
                'weekMap' => $weekMap,
                'previous' => $previous,
                'next' => $next,
            ]);
        }

            return view('pages.pilates.admin.reservations.calendar',  [
                'weekStart' => $weekStart,
            ]);
    }
}
