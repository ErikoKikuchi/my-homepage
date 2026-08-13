<?php

namespace App\Services\Pilates;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\Pilates\LessonSlot;
use App\Models\Pilates\LessonTemplate;
use App\Enums\Pilates\ReservationStatus;

class AdminReservationAvailabilityService
{
    private const INACTIVE_STATUSES = [
        ReservationStatus::Canceled,
        ReservationStatus::Rescheduled,
    ];

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
                ->whereNotIn('status', self::INACTIVE_STATUSES)
                    ->map(fn($r) => [
                        'id' => $r->id,
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
        ->whereNotIn('status', self::INACTIVE_STATUSES)->first();

        if ($reservation?->location) {
            return ['id' => $reservation->location->id, 'name' => $reservation->location->name];
        }

        return null;
    }
    public function hasConflict(string $date, LessonTemplate $template): bool
    {

        return LessonSlot::query()
            ->whereDate('date', $date)
            ->whereHas('lessonTemplate', function ($q) use ($template) {
                $q->where('start_time', '<', $template->end_time)
                  ->where('end_time', '>', $template->start_time);
            })
            ->exists();
    }
}