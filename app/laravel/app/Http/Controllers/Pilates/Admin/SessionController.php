<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pilates\Client;
use App\Models\Pilates\PilatesExercise;
use App\Models\Pilates\PilatesSession;
use App\Models\Pilates\Reservation;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Enums\Pilates\ReservationStatus;
use App\Http\Requests\Pilates\Admin\CreatePilatesSessionRequest;
use App\Http\Requests\Pilates\Admin\StorePilatesSessionRequest;
use App\Http\Requests\Pilates\Admin\UpdatePilatesSessionRequest;
use Inertia\Response;

class SessionController extends Controller
{
    public function clients()
    {
        return Inertia::render('Admin/Pilates/Sessions/Clients', [
            'clients' => Client::query()
                ->active()
                ->where('is_provisional', false)
                ->orderBy('name')
                ->orderBy('id')
                ->paginate(20, ['id', 'name']),
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Client $client)
    {
        $today = today();
        $sessions = $client->pilatesSessions()
            ->whereHas('reservation', fn ($query) => $query->where('client_id', $client->id))
            ->where(function ($query) use ($today) {
                $query->where('session_date', '<', $today->copy()->addDay())
                    ->orWhere('is_draft', true);
            })
            ->orderByDesc('session_date')
            ->orderBy('id')
            ->with('reservation:id,status')
            ->paginate(20, ['id', 'session_date', 'is_draft', 'reservation_id'])
            ->through(fn (PilatesSession $session) => [
                'id' => $session->id,
                'session_date' => $this->hasReservationInformation($session->reservation)
                    ? $session->session_date : null,
                'is_draft' => $session->is_draft,
            ]);

        $firstUpcoming = $sessions->currentPage() === 1 ? $this->firstUpcomingReservation($client) : null;

        return Inertia::render('Admin/Pilates/Sessions/Index', [
            'firstUpcoming' => $firstUpcoming ? [
                'id' => $firstUpcoming->id,
                'date' => $firstUpcoming->lessonSlot->date->toDateString(),
                'startTime' => $firstUpcoming->lessonSlot->lessonTemplate->start_time,
            ] : null,
            'client' => $client->only(['id', 'name', 'has_unpaid_fee']),
            'sessions' => $sessions,
            'todayUnrecorded' => $sessions->currentPage() === 1
                ? $client->reservations()
                    ->where('status', ReservationStatus::Confirmed)
                    ->whereHas('lessonSlot', fn ($query) => $query->where('date', $today->toDateString()))
                    ->whereDoesntHave('pilatesSession')
                    ->with('lessonSlot.lessonTemplate')
                    ->get()
                    ->map(fn ($reservation) => [
                        'id' => $reservation->id,
                        'startTime' => $reservation->lessonSlot->lessonTemplate->start_time,
                    ])
                    ->sortBy('startTime')
                    ->values()
                : [],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreatePilatesSessionRequest $request,Client $client)
    {
        $reservation = $this->recordableReservation($client, $request->validated('reservation_id'));

        return Inertia::render('Admin/Pilates/Sessions/Create', [
            'exerciseOptions' => PilatesExercise::orderBy('name')->orderBy('id')
                ->get(['id', 'name', 'supplement', 'precautions']),
            'client' => $client->sessionInformation(),
            'goals' => $client->goals()->where('is_active', true)->latest()->get(['id', 'outlook', 'goal']),
            'hopes' => $client->hopes()->where('is_active', true)->latest()->get(['id', 'hope']),
            'draftOnly' => $reservation->lessonSlot->date->gt(today()),
            'reservation' => [
                'id' => $reservation->id,
                'date' => $reservation->lessonSlot->date->toDateString(),
                'startTime' => $reservation->lessonSlot->lessonTemplate->start_time,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePilatesSessionRequest $request,Client $client)
    {
        $sessionContents = $request->validated();
        DB::connection('client_db')->transaction(function () use ($sessionContents, $client) {
            $client = Client::query()->lockForUpdate()->findOrFail($client->id);
            $reservation = $this->recordableReservation($client, $sessionContents['reservation_id'], lock: true);
            $this->validateSaveMode($reservation, (bool) $sessionContents['is_draft']);

            $session = new PilatesSession([
                'notes' => $sessionContents['notes'] ?? null,
                'session_date' => $reservation->lessonSlot->date->copy()->setTimeFromTimeString($reservation->lessonSlot->lessonTemplate->start_time),
            ]);

            $session->client()->associate($client);
            $session->reservation()->associate($reservation);
            $session->is_draft = (bool) $sessionContents['is_draft'];
            $session->save();
            $position = 1;
            foreach ($sessionContents['exercises'] ?? [] as $exercise) {
                $session->exercises()->create([
                    'exercise_id'=>$exercise['exercise_id'] ?? null,
                    'custom_name'=>$exercise['custom_name'] ?? null,
                    'comment'=>$exercise['comment'] ?? null,
                    'position'=>$position++,
                ]);
            }
        });
        return redirect()->route('pilates.admin.session.index', ['client' => $client]);
    }

    private function firstUpcomingReservation(Client $client): ?Reservation
    {
        if ($client->pilatesSessions()
            ->where(fn ($query) => $query->where('is_draft', false)
                ->orWhereHas('reservation', fn ($query) => $query->active()))
            ->exists()) {
            return null;
        }

        $first = $client->reservations()
            ->where('status', ReservationStatus::Confirmed)
            ->with('lessonSlot.lessonTemplate')
            ->get()
            ->sortBy(fn (Reservation $reservation) => [
                $reservation->lessonSlot->date->toDateString(),
                $reservation->lessonSlot->lessonTemplate->start_time,
                $reservation->id,
            ])
            ->first();

        return $first && $first->lessonSlot->date->gt(today()) ? $first : null;
    }

    private function recordableReservation(Client $client, string $reservationId, bool $lock = false): Reservation
    {
        $reservation = $client->reservations()
            ->where('status', ReservationStatus::Confirmed)
            ->whereDoesntHave('pilatesSession')
            ->with('lessonSlot.lessonTemplate')
            ->when($lock, fn ($query) => $query->lockForUpdate())
            ->findOrFail($reservationId);

        abort_if(
            $reservation->lessonSlot->date->gt(today())
                && $this->firstUpcomingReservation($client)?->id !== $reservation->id,
            404,
        );

        return $reservation;
    }

    private function hasReservationInformation(Reservation $reservation): bool
    {
        return ! in_array($reservation->status, [
            ReservationStatus::Canceled,
            ReservationStatus::Rescheduled,
        ], true);
    }

    private function validateSaveMode(Reservation $reservation, bool $isDraft, bool $wasDraft = true): void
    {
        if ($wasDraft && ! $isDraft && $reservation->status !== ReservationStatus::Confirmed) {
            throw ValidationException::withMessages([
                'is_draft' => '確定保存には確定済みの予約が必要です。',
            ]);
        }

        if ($reservation->lessonSlot->date->gt(today()) && ! $isDraft) {
            throw ValidationException::withMessages([
                'is_draft' => '確定保存は予約当日からできます。',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PilatesSession $session):Response
    {
        $session->load(['client','exercises','reservation.lessonSlot.lessonTemplate',]);

        $client = $session->client;
        $reservation = $session->reservation;
        $lessonSlot = $reservation->lessonSlot;

        return  Inertia::render('Admin/Pilates/Sessions/Show',[
            'client' => $client->sessionInformation(),
            'lesson' => $this->hasReservationInformation($reservation) ? [
                'date' => $lessonSlot->date->format('Y/m/d'),
                'start_time' => $lessonSlot->lessonTemplate->start_time,
            ] : null,
            'goals' => $client->goals()->where('is_active', true)->latest()->get(['id', 'outlook', 'goal']),
            'hopes' => $client->hopes()->where('is_active', true)->latest()->get(['id', 'hope']),
            'draftOnly' => $lessonSlot->date->gt(today())
                || ($session->is_draft && $reservation->status !== ReservationStatus::Confirmed),
            'reservationConfirmed' => $reservation->status === ReservationStatus::Confirmed,
            'sessionContents' => $session,
            'exerciseOptions' => PilatesExercise::orderBy('name')
            ->orderBy('id')
            ->get(['id', 'name', 'supplement', 'precautions']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePilatesSessionRequest $request,PilatesSession $session)
    {
        $sessionContents = $request->validated();

        DB::connection('client_db')->transaction(function () use ($session, $sessionContents) {
            $session = PilatesSession::query()->lockForUpdate()->findOrFail($session->id);
            $this->validateSaveMode($session->reservation, (bool) $sessionContents['is_draft'], $session->is_draft);

            $session->notes = $sessionContents['notes'] ?? null;
            $session->is_draft = (bool) $sessionContents['is_draft'];
            $session->save();

            $session->exercises()->delete();
            $position = 1;

            foreach ($sessionContents['exercises'] ?? [] as $exercise) {
                $session->exercises()->create([
                    'exercise_id' => $exercise['exercise_id'] ?? null,
                    'custom_name' => $exercise['custom_name'] ?? null,
                    'comment' => $exercise['comment'] ?? null,
                    'position' => $position++,
                ]);
            }
        }
    );

    return redirect()->route('pilates.admin.session.index', [
        'client' => $session->client,
    ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
