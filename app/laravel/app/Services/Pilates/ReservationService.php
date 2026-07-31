<?php

namespace App\Services\Pilates;

use App\Models\Pilates\LessonSlot;
use App\Models\Pilates\Reservation;
use App\Enums\Pilates\ReservationStatus;

class ReservationService
{
    public function createReservation(LessonSlot $slot, array $attributes): Reservation
    {
        return $slot->reservations()->create([
            ...$attributes,
            'location_id' => $slot->location_id,
            'status' => $slot->location_id
                ? ReservationStatus::Confirmed
                : ReservationStatus::WaitingVenue,
        ]);
    }
}