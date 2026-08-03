<?php

namespace App\Services\Pilates;

use App\Models\Pilates\LessonSlot;
use App\Models\Pilates\LessonTemplate;
use App\Models\Pilates\Location;
use App\Models\Pilates\Reservation;
use App\Enums\Pilates\ReservationStatus;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    public function __construct(
        private AdminReservationAvailabilityService $availabilityService
    ) {}

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

    /**
     * @throws \RuntimeException 新しい日時が既存スロットと重複している場合
     */
    public function rescheduleReservation(
        Reservation $oldReservation,
        string $date,
        LessonTemplate $lessonTemplate,
        ?Location $location
    ): Reservation {
        return DB::transaction(function () use ($oldReservation, $date, $lessonTemplate, $location) {
            if ($this->availabilityService->hasConflict($date, $lessonTemplate)) {
                throw new \RuntimeException('この日時は既存のレッスン枠と重複しています。');
            }

            $newSlot = new LessonSlot(['date' => $date]);
            $newSlot->lessonTemplate()->associate($lessonTemplate);
            if ($location) {
                $newSlot->location()->associate($location);
            }
            $newSlot->save();

            $newReservation = $this->createReservation($newSlot, [
                'user_id'             => $oldReservation->user_id,
                'participants'        => $oldReservation->participants,
                'participants_name'   => $oldReservation->participants_name,
                'participants_phone'  => $oldReservation->participants_phone,
                'note'                => $oldReservation->note,
                'rescheduled_from_id' => $oldReservation->id,
            ]);

            $oldReservation->update([
                'status'            => ReservationStatus::Rescheduled,
                'rescheduled_to_id' => $newReservation->id,
            ]);

            return $newReservation;
        });
    }
}