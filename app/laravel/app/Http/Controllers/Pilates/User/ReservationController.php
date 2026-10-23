<?php

namespace App\Http\Controllers\Pilates\User;

use App\Enums\Pilates\ReservationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pilates\User\StoreReservationRequest;
use App\Models\Pilates\Client;
use App\Models\Pilates\LessonSlot;
use App\Models\Pilates\Reservation;
use App\Services\Pilates\ReservationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ReservationController extends Controller
{
    public function __construct(
        private ReservationService $reservationService
    ) {}

    public function index(Request $request)
    {
        return response()->json();
    }

    public function intent(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
            'start' => ['required', 'date_format:H:i'],
        ]);

        $request->session()->put([
            'reservation_date' => $validated['date'],
            'reservation_start' => $validated['start'],
        ]);

        return response()->json([
            'message' => '予約情報を保存しました',
        ]);
    }

    public function create(Request $request)
    {
        $user = $request->user('web');
        abort_unless($user, 401);

        $validated = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
            'start' => ['required', 'date_format:H:i'],
        ]);

        $client = $user->client;

        abort_unless(
            $client,
            403,
            'クライアント情報の登録・紐づけが必要です'
        );

        $slot = LessonSlot::query()
            ->available()
            ->with(['lessonTemplate', 'location'])
            ->where('date', $validated['date'])
            ->whereHas('lessonTemplate', fn ($query) =>
                $query->whereTime('start_time', $validated['start'])
            )
            ->firstOrFail();

        $startsAt = $slot->date->copy()->setTimeFromTimeString(
            $slot->lessonTemplate->start_time
        );

        if ($startsAt->lessThanOrEqualTo(now())) {
            abort(409, '開始時刻を過ぎた枠は予約できません');
        }

        return response()->json([
            'date' => $validated['date'],
            'time' => $validated['start'],
            'dateFormatted' => $slot->date->isoFormat('M月D日(ddd)'),
            'timeFormatted' => Carbon::parse(
                $slot->lessonTemplate->start_time
            )->format('H:i'),
            'venueNote' => $slot->venueNote(),
            'venueFixed' => (bool) $slot->location_id,
            'name' => $client->name,
            'phone' => $client->phone,
        ]);
    }

    public function store(StoreReservationRequest $request)
    {
        $data = $request->validated();
        $user = $request->user('web');

        DB::connection('client_db')->transaction(
            function () use ($data, $user) {
                $slot = LessonSlot::query()
                    ->where('date', $data['date'])
                    ->whereHas('lessonTemplate', fn ($query) =>
                        $query->whereTime('start_time', $data['time'])
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $slot->is_bookable) {
                    abort(409, 'この枠は予約受付を停止しています');
                }

                if ($slot->reservations()->whereNotIn('status', [
                    ReservationStatus::Canceled,
                    ReservationStatus::Rescheduled,
                ])->exists()) {
                    abort(409, 'この枠はすでに予約済みです');
                }

                $client = Client::query()
                    ->where('user_id', $user->id)
                    ->lockForUpdate()
                    ->first();

                abort_unless(
                    $client,
                    403,
                    'クライアント情報の登録・紐づけが必要です'
                );

                $phone = $data['phone'] ?? null;

                if (filled($phone) && $client->phone !== $phone) {
                    $client->update(['phone' => $phone]);
                }

                $this->reservationService->createReservation(
                    $slot,
                    $client,
                    ['note' => $data['note'] ?? null],
                );
            }
        );

        $request->session()->forget([
            'reservation_date',
            'reservation_start',
        ]);

        if ($request->expectsJson()) {
            $request->session()->flash(
                'message',
                "予約を受け付けました。\n"
                ."施設が未定の場合は確保後、LINEにて予約確定のご連絡をいたします。\n"
                ."通常1〜2日以内にご連絡いたします。"
            );

            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()->route('pilates.mypage');
    }

    public function show(Reservation $reservation)
    {
        Gate::authorize('reservation.view', $reservation);

        $reservation->load([
            'lessonSlot.lessonTemplate',
            'location',
        ]);

        return response()->json([
            'booking' => [
                'date' => $reservation->lessonSlot->date
                    ->format('Y年m月d日'),
                'location' => $reservation->status
                    === ReservationStatus::WaitingVenue
                        ? '施設調整中'
                        : $reservation->location?->name,
                'note' => $reservation->note,
                'start_time' => $reservation->lessonSlot
                    ->lessonTemplate->start_time,
                'end_time' => $reservation->lessonSlot
                    ->lessonTemplate->end_time,
            ],
            'isPastCutoff' => $reservation->is_past_cutoff,
        ]);
    }

    public function archive(Request $request)
    {
        $user = $request->user('web');
        abort_unless($user, 401);

        // 未紐づけなら0件。ページネーションの形式は維持する
        $query = Reservation::query();

        if ($user->client === null) {
            $query->whereRaw('1 = 0');
        } else {
            $query->where('client_id', $user->client->id);
        }

        $pastReservations = $query
            ->past()
            ->with(['lessonSlot', 'location'])
            ->orderByDesc(
                LessonSlot::query()
                    ->select('date')
                    ->whereColumn(
                        'lesson_slots.id',
                        'reservations.lesson_slot_id'
                    )
                    ->limit(1)
            )
            ->orderByDesc('created_at')
            ->paginate(10)
            ->through(fn ($reservation) => [
                'uuid' => $reservation->id,
                'date' => $reservation->lessonSlot->date
                    ->format('Y年m月d日'),
                'location' => $reservation->location?->name,
            ]);

        return view('pages.pilates.user.past-reservation', [
            'pastReservations' => $pastReservations,
        ]);
    }
}