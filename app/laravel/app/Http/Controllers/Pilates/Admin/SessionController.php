<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pilates\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        return Inertia::render('Admin/Pilates/Sessions/Index', [
            'client' => $client->only('id', 'name'),
            'sessions' => $client->pilatesSessions()
                ->whereHas('reservation', fn ($query) => $query->where('client_id', $client->id))
                ->orderByDesc('session_date')
                ->orderBy('id')
                ->get(['id', 'session_date']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
