<?php

namespace App\Http\Controllers\Pilates\User;

use App\Enums\Pilates\ReservationStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        $client = $request->user('web')->client;

        if ($client === null) {
            return response()->json([
                'notLineLinkedClient' => true,
                'nextReservationInfo' => null,
                'upcomingReservations' => [],
            ]);
        }
        // 次回予約用
        $reservations = $client->reservations()
            ->upComing()
            ->with(['lessonSlot.lessonTemplate', 'location'])
            ->get()
            ->sortBy(fn ($reservation) =>
                $reservation->lessonSlot->date->format('Y-m-d')
                .' '.$reservation->lessonSlot->lessonTemplate->start_time
            )
            ->values();

            $now = now();

            $nextReservation = $reservations->first(function ($reservation) use ($now) {
                $slot = $reservation->lessonSlot;

                $startsAt = $slot->date->copy()->setTimeFromTimeString(
                    $slot->lessonTemplate->start_time
                );

                return $startsAt->greaterThan($now);
            });
        $nextReservationInfo = null;
        if ($nextReservation) {
            $nextReservationInfo = [
                'date' => $nextReservation->lessonSlot->date->format('Y年m月d日'),
                'location' => $nextReservation->status === ReservationStatus::WaitingVenue
                ? '施設調整中'
                : $nextReservation->location?->name,
            ];
        }
        // 回数券残数
        //$remainingTicketCounts = $client?->remainingGuestPassCount() ?? 0;

        // LINE登録
        $notLineLinkedClient = ! $client->isLineLinked();
        // 予約履歴
        $upcomingReservations = $reservations->map(fn ($reservation) => [
            'uuid' => $reservation->id,
            'date' => $reservation->lessonSlot->date->format('Y年m月d日'),
            'location' => $reservation->status === ReservationStatus::WaitingVenue
                ? '施設調整中'
                : $reservation->location?->name,
        ])->toArray();

        return response()->json([
            'notLineLinkedClient' => $notLineLinkedClient,
            'nextReservationInfo' => $nextReservationInfo,
            //'remainingTicketCounts' => $remainingTicketCounts,
            'upcomingReservations' => $upcomingReservations,
        ]);
    }
}
