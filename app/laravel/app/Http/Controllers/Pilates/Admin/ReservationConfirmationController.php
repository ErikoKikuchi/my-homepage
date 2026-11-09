<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Enums\Pilates\ReservationStatus;
use App\Http\Controllers\Controller;
use App\Models\Pilates\Location;
use App\Models\Pilates\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ReservationConfirmationController extends Controller
{
    public function index(): Response
    {
        $reservations = Reservation::with(['lessonSlot.lessonTemplate', 'client:id,name,phone'])
            ->where('status', ReservationStatus::WaitingVenue)
            ->orderBy('created_at')
            ->get();

        $locations = Location::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Admin/Pilates/Reservations/Pending', [
            'reservations' => $reservations,
            'locations' => $locations,
            'lineUrl' => config('services.line.url'),
        ]);
    }

    public function confirm(Request $request, Reservation $reservation): RedirectResponse
    {
        Gate::authorize('reservation.confirm', $reservation);

        $validated = $request->validate([
            'location_id' => ['required', 'uuid', 'exists:client_db.locations,id'],
        ]);

        $location = Location::findOrFail($validated['location_id']);

        $confirmed = Reservation::query()
            ->whereKey($reservation->id)
            ->where('status', ReservationStatus::WaitingVenue)
            ->update([
                'location_id' => $location->id,
                'status' => ReservationStatus::Confirmed,
            ]);

        if (! $confirmed) {
            return back()->with('error', 'この予約はすでに会場確認待ちではありません。');
        }

        return back()->with('success', '予約を確定しました');
    }
}
