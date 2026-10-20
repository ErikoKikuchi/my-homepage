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
        $user = auth('web')->user();
        Gate::authorize('reservation.cancel', $reservation);

        if ($reservation->status === ReservationStatus::Canceled) {
            abort(400, 'すでにキャンセル済みです');
        }

        $cutoff = $reservation->lessonSlot->date->copy()->subDay()->setTime(12, 0);

        if (now()->greaterThan($cutoff)) {
            // システムでは完結させず、LINE誘導のみ
            return response()->json([
                'message' => '前日正午以降のキャンセルはLINEにて承っております。',
            ], 409);
        }
        $reservation->update([
            'status' => ReservationStatus::Canceled,
            'cancelled_at' => now(),
            'cancelled_by' => 'user',

        ]);

        return response()->json([
            'message' => '予約をキャンセルしました',
        ], 200);
    }
}
