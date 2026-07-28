<?php

namespace App\Policies\User;

use App\Models\Auth\User;
use App\Models\Pilates\Reservation;

class ReservationPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user, Reservation $reservation): bool
    {
        return $user->id === $reservation->user_id;
    }
    
    public function cancel(User $user, Reservation $reservation): bool
    {
        return $user->id === $reservation->user_id;
    }
}
