<?php

namespace App\Http\Controllers\Pilates\User;

use App\Enums\Pilates\ReservationStatus;
use App\Http\Controllers\Controller;
use App\Models\Pilates\Reservation;
use Illuminate\Support\Facades\Gate;

class CancellationController extends Controller
{
    public function cancel(Reservation $reservation)
    {
        Gate::authorize('cancel', $reservation);

        if (! in_array($reservation->status, [
            ReservationStatus::WaitingVenue,
            ReservationStatus::Confirmed,
        ], true)) {
            abort(409, 'この予約はキャンセルできません');
        }

        if ($reservation->is_past_cutoff) {
            return response()->json([
                'message' => '前日正午を過ぎたキャンセルはLINEにて承っております。',
            ], 409);
        }
        $reservation->update([
            'status' => ReservationStatus::Canceled,
            'cancelled_at' => now(),
            'cancelled_by' => 'client',

        ]);

        return response()->json([
            'message' => '予約をキャンセルしました',
        ], 200);
    }
}
