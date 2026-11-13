<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pilates\Client;
use App\Models\Pilates\PilatesExercise;
use App\Models\Pilates\PilatesSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Enums\Pilates\ReservationStatus;
use App\Http\Requests\Pilates\Admin\CreatePilatesSessionRequest;
use App\Http\Requests\Pilates\Admin\StorePilatesSessionRequest;

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
            ->where('session_date', '<', $today->copy()->addDay())
            ->whereHas('reservation', fn ($query) => $query->where('client_id', $client->id))
            ->orderByDesc('session_date')
            ->orderBy('id')
            ->paginate(20, ['id', 'session_date', 'is_draft']);

        return Inertia::render('Admin/Pilates/Sessions/Index', [
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
        $reservation = $client->reservations()
        ->where('status', ReservationStatus::Confirmed)
        ->whereHas('lessonSlot', fn ($query) => $query->where('date', '<=', today()->toDateString()))
        ->whereDoesntHave('pilatesSession')
        ->with('lessonSlot.lessonTemplate')
        ->findOrFail($request->validated('reservation_id'));

        return Inertia::render('Admin/Pilates/Sessions/Create', [
            'exerciseOptions' => PilatesExercise::orderBy('name')->orderBy('id')
                ->get(['id', 'name', 'supplement', 'precautions']),
            'client' => $client->only('id', 'name', 'body_notes'),
            'goals' => $client->goals()->where('is_active', true)->latest()->get(['id', 'hope', 'outlook', 'goal']),
            'hopes' => $client->hopes()->where('is_active', true)->latest()->get(['id', 'hope']),
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
        $reservation=$client->reservations()->where('status', ReservationStatus::Confirmed)
        ->whereHas('lessonSlot', fn ($query) => $query->where('date', '<=', today()->toDateString()))
        ->whereDoesntHave('pilatesSession')
        ->with('lessonSlot.lessonTemplate')
        ->findOrFail($request->validated('reservation_id'));

        DB::connection('client_db')->transaction(function () use ($sessionContents, $reservation,$client,$request) {
            $session = new PilatesSession([
                'notes' => $sessionContents['notes'] ?? null,
                'session_date' => $reservation->lessonSlot->date->copy()->setTimeFromTimeString($reservation->lessonSlot->lessonTemplate->start_time),
            ]);

            $session->client()->associate($client);
            $session->reservation()->associate($reservation);
            $session->is_draft = $request->boolean('is_draft');
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

    /**
     * Display the specified resource.
     */
    public function show(Request $request,Client $client)
    {
        return  Inertia::render('Admin/Pilates/Sessions/Show',[
            'session'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
