<?php

namespace App\Http\Controllers\Pilates\User;

use App\Http\Controllers\Controller;
use App\Models\Pilates\Reservation;

class CancellationController extends Controller
{
    public function cancel(Reservation $reservation)
    {
        $user=auth('web')->user();
        $this->authorize('cancel', $reservation);

        $cutoff = $reservation->lessonSlot->date->copy()->subDay()->setTime(12, 0);

        if (now()->greaterThan($cutoff)) {
            $user->client->has_unpaid_fee = true;
            $user->client->save();
        }
        $reservation->update([
            'status'=>'canceled',
            'cancelled_at'=>now(),
            'cancelled_by'=>'user'

        ]);
        return redirect()->route('pilates.mypage');
    }
}
