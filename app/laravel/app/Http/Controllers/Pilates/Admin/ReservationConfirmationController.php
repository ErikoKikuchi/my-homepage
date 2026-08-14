<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pilates\Reservation;
use App\Models\Pilates\Location;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Enums\Pilates\ReservationStatus;
use Illuminate\Http\Request;

class ReservationConfirmationController extends Controller
{
    public function index(): View
    {
        $reservations = Reservation::with(['lessonSlot.lessonTemplate', 'user'])
            ->where('status', ReservationStatus::WaitingVenue)
            ->orderBy('created_at')
            ->get();

        $locations = Location::orderBy('name')->get();

            return view('pages.pilates.admin.reservations.pending', [
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
    
        $reservation->location()->associate($location);
        $reservation->status = ReservationStatus::Confirmed;
        $reservation->save();

    return back()->with('success', '予約を確定しました');
    }
}