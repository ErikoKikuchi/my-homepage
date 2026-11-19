<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pilates\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class GoalController extends Controller
{
    public function index(Client $client)
    {
        $bodyNotes = $client->bodyNotes()->latest()->orderByDesc('id')
            ->get(['id', 'body_note', 'observed_on']);
        $goals = $client->goals()->latest()->orderByDesc('id')
            ->get(['id', 'outlook', 'goal', 'is_active', 'created_at']);
        $hopes = $client->hopes()->latest()->orderByDesc('id')
            ->get(['id', 'hope', 'is_active', 'created_at']);

        return Inertia::render('Admin/Pilates/Goals/Index', [
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
                'body_notes' => $bodyNotes->first()?->body_note ?? $client->body_notes,
            ],
            'bodyHistory' => $bodyNotes,
            'legacyBodyNote' => $client->body_notes,
            'goals' => $goals->where('is_active', true)->values(),
            'goalHistory' => $goals->where('is_active', false)->values(),
            'hopes' => $hopes->where('is_active', true)->values(),
            'hopeHistory' => $hopes->where('is_active', false)->values(),
        ]);
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'body_note' => ['nullable', 'string', 'max:16000'],
            'new_hope' => ['nullable', 'string', 'max:16000'],
            'hopes' => ['sometimes', 'array'],
            'hopes.*' => ['nullable', 'string', 'max:16000'],
            'outlook' => ['sometimes', 'array'],
            'outlook.*' => ['nullable', 'string', 'max:16000'],
            'goal' => ['sometimes', 'array'],
            'goal.*' => ['nullable', 'string', 'max:16000'],
            'outlook_append' => ['nullable', 'string', 'max:16000'],
            'goal_append' => ['nullable', 'string', 'max:16000'],
        ]);

        DB::connection('client_db')->transaction(function () use ($client, $data) {
            $client = Client::query()->lockForUpdate()->findOrFail($client->id);

            if (isset($data['body_note'])) {
                $client->bodyNotes()->create([
                    'body_note' => $data['body_note'],
                    'observed_on' => today()->toDateString(),
                ]);
            }

            foreach ($client->hopes()->where('is_active', true)->get() as $hope) {
                $edits = $data['hopes'] ?? [];
                if (! array_key_exists($hope->id, $edits) || $edits[$hope->id] === $hope->hope) {
                    continue;
                }

                $hope->update(['is_active' => false]);
                if ($edits[$hope->id] !== null) {
                    $client->hopes()->create(['hope' => $edits[$hope->id]]);
                }
            }

            if (isset($data['new_hope'])) {
                $client->hopes()->create(['hope' => $data['new_hope']]);
            }

            $activeGoals = $client->goals()->where('is_active', true)->get();

            foreach (['outlook' => 'goal', 'goal' => 'outlook'] as $field => $otherField) {
                $edits = $data[$field] ?? [];
                foreach ($activeGoals as $entry) {
                    if ($entry->{$field} === null || ! array_key_exists($entry->id, $edits)) {
                        continue;
                    }

                    $value = $edits[$entry->id];
                    if (($value ?? '') === trim(str_replace("\r\n", "\n", $entry->{$field}))) {
                        continue;
                    }

                    if ($entry->{$otherField} !== null) {
                        // Older records can contain both fields. Archive only the edited text.
                        $archived = $client->goals()->make([
                            $field => $entry->{$field},
                            'is_active' => false,
                        ]);
                        $archived->created_at = $entry->created_at;
                        $archived->save();
                        $entry->update([$field => null]);
                    } else {
                        $entry->update(['is_active' => false]);
                    }

                    if ($value !== null) {
                        $client->goals()->create([$field => $value]);
                    }
                }

                if (isset($data[$field.'_append'])) {
                    $client->goals()->create([$field => $data[$field.'_append']]);
                }
            }
        });

        return redirect()->route('pilates.admin.goal.index', ['client' => $client]);
    }
}
