<?php

namespace App\Services\Pilates;

use App\Enums\Pilates\ReservationStatus;
use App\Models\Pilates\Client;
use App\Models\Pilates\LessonSlot;
use App\Models\Pilates\LessonTemplate;
use App\Models\Pilates\Location;
use App\Models\Pilates\Reservation;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    public function __construct(
        private AdminReservationAvailabilityService $availabilityService
    ) {}

    public function createReservation(
        LessonSlot $slot,
        Client $client,
        array $attributes = []
    ): Reservation {
        return DB::connection('client_db')->transaction(
            function () use ($slot, $client, $attributes) {
                $slot = LessonSlot::query()
                    ->lockForUpdate()
                    ->findOrFail($slot->id);

                if (! $slot->is_bookable) {
                    abort(409, 'この枠は予約受付を停止しています');
                }

                $startsAt = $slot->date->copy()->setTimeFromTimeString(
                    $slot->lessonTemplate->start_time
                );

                if ($startsAt->lessThanOrEqualTo(now())) {
                    abort(409, '開始時刻を過ぎた枠は予約できません');
                }

                $alreadyReserved = $slot->reservations()
                    ->whereNotIn('status', [
                        ReservationStatus::Canceled,
                        ReservationStatus::Rescheduled,
                    ])
                    ->exists();

                if ($alreadyReserved) {
                    abort(409, 'この枠はすでに予約済みです');
                }

                $reservation = new Reservation([
                    'note' => $attributes['note'] ?? null,
                    'status' => $slot->location_id
                        ? ReservationStatus::Confirmed
                        : ReservationStatus::WaitingVenue,
                ]);

                $reservation->lessonSlot()->associate($slot);
                $reservation->client()->associate($client);
                $reservation->location()->associate($slot->location);
                $reservation->save();

                return $reservation;
            }
        );
    }

    public function rescheduleReservation(
        Reservation $oldReservation,
        string $date,
        LessonTemplate $lessonTemplate,
        ?Location $location
    ): Reservation {
        return DB::connection('client_db')->transaction(
            function () use (
                $oldReservation,
                $date,
                $lessonTemplate,
                $location
            ) {
                // キャンセル・会場確定側もロック順を揃える
                LessonSlot::query()
                    ->lockForUpdate()
                    ->findOrFail($oldReservation->lesson_slot_id);

                $oldReservation = Reservation::query()
                    ->lockForUpdate()
                    ->findOrFail($oldReservation->id);

                if (! in_array($oldReservation->status, [
                    ReservationStatus::WaitingVenue,
                    ReservationStatus::Confirmed,
                ], true)) {
                    abort(409, 'この予約は振替できません');
                }

                if ($this->availabilityService->hasConflict(
                    $date,
                    $lessonTemplate
                )) {
                    abort(409, 'この日時は既存のレッスン枠と重複しています');
                }

                $newSlot = new LessonSlot([
                    'date' => $date,
                    'is_bookable' => true,
                ]);

                $newSlot->lessonTemplate()->associate($lessonTemplate);
                $newSlot->location()->associate($location);
                $newSlot->save();

                $newReservation = $this->createReservation(
                    $newSlot,
                    $oldReservation->client,
                    ['note' => $oldReservation->note],
                );

                $newReservation->rescheduledFrom()
                    ->associate($oldReservation);
                $newReservation->save();

                $oldReservation->rescheduledTo()
                    ->associate($newReservation);
                $oldReservation->status = ReservationStatus::Rescheduled;
                $oldReservation->save();

                return $newReservation;
            }
        );
    }
}