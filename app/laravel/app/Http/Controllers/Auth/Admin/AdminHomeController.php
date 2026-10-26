<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Enums\Pilates\ReservationStatus;
use App\Http\Controllers\Controller;
use App\Models\Pilates\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminHomeController extends Controller
{
    public function index(Request $request): Response
    {
        $section = $request->attributes->get('section');
        $page = match ($section) {
            'pilates' => 'Admin/Pilates/Dashboard',
            'thinkmotion' => 'Admin/ThinkMotion/Dashboard',
            default => abort(404),
        };

        $props = [];

        if ($section === 'pilates') {
            $props['pendingReservationCount'] = Reservation::where(
                'status',
                ReservationStatus::WaitingVenue
            )->count();
        }

        return Inertia::render($page, $props);
    }
}