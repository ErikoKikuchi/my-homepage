<?php

namespace App\Services\Pilates;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\Pilates\LessonSlot;

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
        return $slot->reservations
            ->whereNotIn('status', ['canceled'])
            ->count() === 0;
    }

    //月を受け取り、その月の日付ごとの空き状況を返す
    public function buildSlotMap(string $month): Collection
    {
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $minDate = $this->minBookableDate();

        $slots = LessonSlot::where('is_active', true)
            ->whereBetween('date', [
                $startOfMonth->format('Y-m-d'),
                $startOfMonth->copy()->endOfMonth()->format('Y-m-d'),
            ])
            ->with('reservations')
            ->get();

        return $slots->groupBy(fn($slot) => $slot->date->format('Y-m-d'))
            ->map(function ($daySlots, $dateString) use ($minDate) {
                $availableSlots = $daySlots->filter(fn($slot) => $this->isSlotAvailable($slot));

                if ($availableSlots->isEmpty()) {
                    return 'full';
                }

                return Carbon::parse($dateString)->greaterThanOrEqualTo($minDate)
                    ? 'available'
                    : 'contact_only';
            });
    }

    public function getAvailableTimes(string $date): array
    {
        return LessonSlot::where('is_active', true)
            ->where('date', $date)
            ->with('lessonTemplate')
            ->get()
            ->filter(fn($slot) => $this->isSlotAvailable($slot))
            ->map(fn($slot) => [
                'start' => $slot->lessonTemplate->start_time,
                'end'   => $slot->lessonTemplate->end_time,
                'venueNote' => $slot->venueNote()
            ])
            ->values()
            ->toArray();
    }
}
