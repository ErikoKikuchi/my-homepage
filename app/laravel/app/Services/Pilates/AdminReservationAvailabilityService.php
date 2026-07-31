<?php

namespace App\Services\Pilates;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\Pilates\LessonSlot;
use App\Enums\Pilates\ReservationStatus;

class AdminReservationAvailabilityService
{
    public function resolveSlotStatus(LessonSlot $slot): string
    {
        $reservation=$slot->reservations
            ->firstWhere ('status','!=',ReservationStatus::Canceled);

            return $reservation?->status?->value ?? 'available';
    }

    public function adminBuildWeekMap(string $weekStart): Collection
    {
        $start = Carbon::parse($weekStart)->startOfDay();
        $end = $start->copy()->addDays(6);

        $slots = LessonSlot::whereBetween('date', [
                $start->format('Y-m-d'),
                $end->format('Y-m-d'),
            ])
            ->with(['reservations.user', 'reservations.location', 'location', 'lessonTemplate'])
            ->get();

        return $slots->groupBy(fn($slot) => $slot->date->format('Y-m-d'))
            ->map(fn ($daySlots) => $daySlots->map(fn($slot) =>[
                'id' => $slot->id,
                'time' => $slot->lessonTemplate->start_time . '-' . $slot->lessonTemplate->end_time,
                'location' => $this->resolveSlotLocation($slot),
                'reservations' => $slot->reservations
                    ->where('status', '!=', ReservationStatus::Canceled)
                    ->map(fn($r) => [
                        'name' => $r->user->name,
                        'phone' => $r->user->phone,
                        'participants' => $r->participants,
                    ])->values(),
            ])->values());
    }
    private function resolveSlotLocation(LessonSlot $slot): ?array
    {
        // スロット自体に場所が固定されている場合(例:水曜美容室)
        if ($slot->location) {
            return ['id' => $slot->location->id, 'name' => $slot->location->name];
        }

        // 場所未定で予約が入り、後から確定した場合
        $reservation = $slot->reservations
            ->firstWhere('status', '!=', ReservationStatus::Canceled);

        if ($reservation?->location) {
            return ['id' => $reservation->location->id, 'name' => $reservation->location->name];
        }

        return null;
    }
}