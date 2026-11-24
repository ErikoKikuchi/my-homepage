<?php

namespace App\Services\Pilates;

use App\Enums\Pilates\ReservationStatus;
use App\Models\Pilates\LessonSlot;
use App\Models\Pilates\ReservationClosure;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReservationAvailabilityService
{
    public function minBookableDate(): Carbon
    {
        $cutoff = now()->copy()->setTime(12, 0);
        $baseDate = now()->lessThan($cutoff) ? now() : now()->addDay();

        return $baseDate->addDays(config('reservation.min_bookable_days'))->startOfDay();
    }

    public function isSlotAvailable(LessonSlot $slot): bool
    {
        return $slot->is_bookable && $slot->reservations
            ->whereNotIn('status', [
                ReservationStatus::Canceled,
                ReservationStatus::Rescheduled,
            ])
            ->count() === 0;
    }

    // 月を受け取り、その月の日付ごとの空き状況を返す
    public function buildSlotMap(string $month, Collection $closures): Collection
    {
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $minDate = $this->minBookableDate();

        $slots = LessonSlot::whereBetween('date', [
            $startOfMonth->format('Y-m-d'),
            $startOfMonth->copy()->endOfMonth()->format('Y-m-d'),
        ])
            ->where('is_bookable', true)
            ->with('reservations', 'lessonTemplate')
            ->get();

        return $slots->groupBy(fn ($slot) => $slot->date->format('Y-m-d'))
            ->map(function ($daySlots, $dateString) use ($minDate, $closures) {
                $date = Carbon::parse($dateString);

                if ($date->lt(now()->startOfDay())) {
                    return null; // 過去日は表示なし
                }

                $openSlots = $daySlots->reject(fn ($slot) => $this->isSlotBlocked($slot, $closures->get($dateString)));

                if ($openSlots->isEmpty()) {
                    return null;
                }

                $availableSlots = $openSlots->filter(fn ($slot) => $this->isSlotAvailable($slot));

                if ($availableSlots->isEmpty()) {
                    return 'full';
                }

                return $date->greaterThanOrEqualTo($minDate)
                    ? 'available'
                    : 'contact_only';
            });
    }

    public function isSlotBlocked(LessonSlot $slot, ?string $period): bool
    {
        return ReservationClosure::blocks($period, $slot->lessonTemplate->start_time, $slot->lessonTemplate->end_time);
    }

    public function getAvailableTimes(string $date, ?string $period): array
    {
        return LessonSlot::where('date', $date)
            ->where('is_bookable', true)
            ->with(['lessonTemplate', 'reservations', 'location'])
            ->get()
            ->map(function (LessonSlot $slot) use ($period) {
                $isBlocked = $this->isSlotBlocked($slot, $period);

                return [
                    'start' => $slot->lessonTemplate->start_time,
                    'end' => $slot->lessonTemplate->end_time,
                    'locationName' => $slot->location?->name,
                    'isAvailable' => ! $isBlocked && $this->isSlotAvailable($slot),
                    'isBlocked' => $isBlocked,
                ];
            })
            ->values()
            ->toArray();
    }
}
