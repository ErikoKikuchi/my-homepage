<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auth\User;
use App\Http\Requests\Pilates\Admin\StoreClientRequest;
use App\Http\Requests\Pilates\Admin\UpdateClientRequest;
use App\Models\Pilates\Client;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->where('is_pilates_user', true)
            ->whereDoesntHave('client', function ($q) {
                $q->where('is_active', false);
            })
            ->withMax('lessonSlotsViaReservations', 'date')
            ->with('client')
            ->orderByDesc('is_client')
            ->orderBy('name')
            ->paginate(20);

        return view('pages.pilates.admin.clients.index', compact('users'));
    }
    public function show(Client $client)
    {
    
        $client->load('user');
    
        return view('pages.pilates.admin.clients.show', compact('client'));
    }
    public function store(StoreClientRequest $request)
    {
        $validated = $request->validated();

        $user = User::findOrFail($validated['user_id']);

        if ($user->is_client) {
            return response()->json(
                ['message' => '既にクライアント登録済みのユーザーです。'],
                422,
            );
        }

        try {
        \DB::transaction(function () use ($user, $validated) {
            $user->client()->create([
                'name' => $user->name,
                'gender' => $validated['gender'],
                'is_active'=>true
            ]);

            $user->update(['is_client' => true]);
        });

    } catch (\Throwable $e) {
        \Log::error('クライアント登録処理に失敗しました', [
            'user_id' => $user->id,
            'gender' => $validated['gender'],
            'error' => $e->getMessage(),
        ]);
        return response()->json(['message' => '登録処理に失敗しました。'], 500);
    }
        return response()->json(['message' => 'クライアント登録が完了しました。'], 201);
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $validated = $request->validated();

        if (array_key_exists('name', $validated)) {
            $client->user->update(['name' => $validated['name']]);
        }

        $client->update(collect($validated)->except('name')->toArray());

        return response()->json([
            'message' => '更新しました。',
            'client' => $client->fresh(),
        ]);
    }
    public function archive(Request $request)
    {
        $clients = User::query()
            ->where('is_pilates_user', true)
            ->where('is_client', true)
            ->whereHas('client', fn ($q) => $q->where('is_active', false))
            ->withMax('lessonSlotsViaReservations', 'date')
            ->with('client')
            ->orderBy('name')
            ->paginate(20);

        return view('pages.pilates.admin.clients.archive', compact('clients'));
    }
}
