<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Enums\Pilates\ReservationStatus;
use App\Http\Controllers\Controller;
use App\Models\Pilates\PilatesSession;
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
            $props['todayReservations']=Reservation::where(
                'status',ReservationStatus::Confirmed)
            ->whereHas('LessonSlot', fn ($query) => $query->where('date', today()->toDateString()))
            ->with(['client:id,name', 'lessonSlot.lessonTemplate','pilatesSession:id,reservation_id,is_draft'])
            ->get()
            ->sortBy(fn ($reservation) => $reservation->lessonSlot->lessonTemplate->start_time)
            ->values();
            $props['draftSession']=PilatesSession::query()
            ->where('is_draft',true)
            ->where('session_date', '<', today())
            ->with('client:id,name')
            ->orderByDesc('session_date')
            ->get(['id', 'client_id', 'session_date']);
            $props['unFilledSession']=Reservation::query()
            ->where('status', ReservationStatus::Confirmed)
            ->past()
            ->with(['client:id,name', 'lessonSlot.lessonTemplate'])
            ->whereDoesntHave('pilatesSession')
            ->get();
        }

        return Inertia::render($page, $props);
    }
}