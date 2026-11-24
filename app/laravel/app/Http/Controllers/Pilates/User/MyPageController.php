<?php

namespace App\Http\Controllers\Pilates\User;

use App\Enums\Pilates\ReservationStatus;
use App\Http\Controllers\Controller;
use App\Services\Pilates\HopeAchievementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        $client = $request->user('web')->client;

        if ($client === null) {
            return response()->json([
                'clientName' => null,
                'notLineLinkedClient' => true,
                'nextReservationInfo' => null,
                'upcomingReservations' => [],
                'hopes' => [],
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
            'clientName' => $client->name,
            'notLineLinkedClient' => $notLineLinkedClient,
            'nextReservationInfo' => $nextReservationInfo,
            //'remainingTicketCounts' => $remainingTicketCounts,
            'upcomingReservations' => $upcomingReservations,
            'hopes' => $client->hopes()->where('is_active', true)->whereNotNull('hope')
                ->latest()->orderByDesc('id')->get(['id', 'hope']),
        ]);
    }

    public function hopes(Request $request)
    {
        $client = $request->user('web')->client;

        return response()->json([
            'hasClient' => $client !== null,
            'hopes' => $client?->hopes()->whereNotNull('hope')->latest()->orderByDesc('id')
                ->get(['id', 'hope', 'is_active', 'created_at', 'achieved_at']) ?? [],
        ]);
    }

    public function storeHope(Request $request)
    {
        $data = $request->validate(['hope' => ['required', 'string', 'max:16000']]);

        return DB::connection('client_db')->transaction(function () use ($request, $data) {
            $client = $request->user('web')->client()->lockForUpdate()->first();
            abort_unless($client, 403);

            if ($client->hopes()->where('is_active', true)->whereNotNull('hope')->exists()) {
                return response()->json(['message' => 'すでに現在の目標が登録されています。'], 409);
            }

            $client->hopes()->create(['hope' => $data['hope']]);

            return response()->noContent();
        });
    }

    public function updateHope(Request $request, string $hope)
    {
        $data = $request->validate(['hope' => ['required', 'string', 'max:16000']]);

        return DB::connection('client_db')->transaction(function () use ($request, $hope, $data) {
            $client = $request->user('web')->client()->lockForUpdate()->first();
            abort_unless($client, 403);
            $entry = $client->hopes()->findOrFail($hope);

            if (! $entry->is_active) {
                return response()->json(['message' => 'この目標はすでに履歴に移っています。'], 409);
            }

            $entry->revise($data['hope']);

            return response()->noContent();
        });
    }

    public function achieveHope(Request $request, string $hope, HopeAchievementService $achievement)
    {
        $client = $request->user('web')->client;
        abort_unless($client, 403);

        if (! $achievement->mark($client, $hope)) {
            return response()->json(['message' => 'この目標はすでに編集され、履歴に移っています。'], 409);
        }

        return response()->noContent();
    }
}
