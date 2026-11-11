<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pilates\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Enums\Pilates\ReservationStatus;
use App\Http\Requests\Pilates\Admin\CreatePilatesSessionRequest;

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
            'client' => $client->only([
                'id', 'name', 'name_kana', 'gender', 'phone', 'occupation',
                'personality_notes', 'relationship_note', 'has_unpaid_fee',
            ]),
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
