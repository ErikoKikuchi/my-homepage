<?php

namespace App\Http\Controllers\Pilates\User;

use App\Http\Controllers\Controller;
use App\Models\Pilates\Reservation;
use Illuminate\Support\Facades\Gate;
use App\Enums\Pilates\ReservationStatus;

class CancellationController extends Controller
{
    public function cancel(Reservation $reservation)
    {
        $user=auth('web')->user();
        Gate::authorize('reservation.cancel', $reservation);

        if ($reservation->status === ReservationStatus::Canceled) {
            abort(400, 'すでにキャンセル済みです');
        }

        $cutoff = $reservation->lessonSlot->date->copy()->subDay()->setTime(12, 0);

        if (now()->greaterThan($cutoff)) {
        // システムでは完結させず、LINE誘導のみ
        return redirect()->route('pilates.mypage')
            ->with('message', "前日正午以降のキャンセルはLINEにて承っております。\nキャンセル料¥500が発生いたします。");
    }
        $reservation->update([
            'status'=>ReservationStatus::Canceled,
            'cancelled_at'=>now(),
            'cancelled_by'=>'user'

        ]);
        return redirect()->route('pilates.mypage');
    }
}
