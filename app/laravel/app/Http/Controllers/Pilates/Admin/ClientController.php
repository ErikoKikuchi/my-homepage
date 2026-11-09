<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pilates\Admin\StoreClientRequest;
use App\Http\Requests\Pilates\Admin\UpdateClientRequest;
use App\Models\Auth\User;
use App\Models\Pilates\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $accounts = User::query()
            ->select('users.id', 'users.is_client')
            ->where('is_pilates_user', true)
            ->with(['client' => fn ($query) => $query
                ->select('id', 'user_id', 'name', 'phone', 'has_unpaid_fee', 'line_linked')
                ->withMax('lessonSlotsViaReservations', 'date')])
            ->orderBy(Client::select('name')->whereColumn('client_db.clients.user_id', 'users.id'))
            ->orderBy('users.id');

        $users = (clone $accounts)
            ->where('users.is_client', true)
            ->whereHas('client', fn ($client) => $client->where('is_active', true))
            ->paginate(20)
            ->withQueryString();

        $pendingUsers = $accounts
            ->where('is_client', false)
            ->with('profile:id,user_id,display_name')
            ->paginate(20, pageName: 'pending_user_page')
            ->withQueryString();

        return Inertia::render('Admin/Pilates/Clients/Index', [
            'users' => $users,
            'pendingUsers' => $pendingUsers,
            'standaloneClients' => $this->standaloneClients(),
            'provisionalClients' => $this->standaloneClients(provisional: true),
        ]);
    }

    public function show(Client $client)
    {
        $client->setVisible(['id', 'name', 'gender', 'line_linked', 'is_active', 'body_notes', 'personality_notes']);

        return Inertia::render('Admin/Pilates/Clients/Show', [
            'client' => $client,
            'accountLinked' => $client->user_id !== null,
            'canLinkAccount' => ! $client->is_provisional && $client->user_id === null,
        ]);
    }

    public function store(StoreClientRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::connection('mysql')->transaction(function () use ($validated) {
                $user = User::query()->lockForUpdate()->findOrFail($validated['user_id']);

                if ($user->is_client) {
                    throw ValidationException::withMessages([
                        'user_id' => '既にクライアント登録済みのユーザーです。',
                    ]);
                }

                // The qualified client table uses the same transaction as the user account.
                $client = (new Client)->setConnection($user->getConnectionName())
                    ->newQuery()->firstOrNew(['user_id' => $user->id]);
                $client->fill([
                    'name' => $validated['name'],
                    'gender' => $validated['gender'],
                    'is_active' => true,
                    'is_provisional' => false,
                ]);
                $client->user_id = $user->id;
                $client->save();

                $user->update(['is_client' => true]);
            });
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            report($exception);

            return response()->json(['message' => '登録処理に失敗しました。'], 500);
        }

        return response()->json(['message' => 'クライアント登録が完了しました。'], 201);
    }

    public function registerProvisional(Request $request, Client $client): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
        ]);

        $registered = Client::query()->whereKey($client->id)
            ->whereNull('user_id')
            ->where('is_provisional', true)
            ->where('is_active', false)
            ->update([...$validated, 'is_provisional' => false, 'is_active' => true]);

        if (! $registered) {
            return response()->json(['message' => 'この仮登録はすでに処理済みです。'], 409);
        }

        return response()->json(['message' => 'クライアント登録が完了しました。']);
    }

    public function linkAccount(Request $request, Client $client): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'identity_confirmed' => ['accepted'],
        ]);

        DB::connection('mysql')->transaction(function () use ($client, $validated) {
            $user = User::query()
                ->where('email', $validated['email'])
                ->where('is_pilates_user', true)
                ->whereNull('deleted_at')
                ->whereNotNull('email_verified_at')
                ->lockForUpdate()
                ->first();

            if ($user === null) {
                throw ValidationException::withMessages([
                    'email' => 'メール認証済みのピラティスアカウントが見つかりません。',
                ]);
            }

            // Use the same connection for the account and the qualified client table.
            $clients = (new Client)->setConnection($user->getConnectionName())->newQuery();
            $target = (clone $clients)->lockForUpdate()->findOrFail($client->id);

            if ($target->user_id === $user->id) {
                return;
            }

            if (! $target->is_active || $target->is_provisional || $target->user_id !== null || $user->is_client) {
                throw ValidationException::withMessages([
                    'email' => 'このクライアントとアカウントは紐づけできません。',
                ]);
            }

            $placeholder = $clients->where('user_id', $user->id)->lockForUpdate()->first();

            if ($placeholder !== null) {
                if ($placeholder->is_active || $this->hasClientRecords($placeholder)) {
                    throw ValidationException::withMessages([
                        'email' => 'このアカウント側にも予約やクライアント情報があります。記録の確認と統合が必要です。',
                    ]);
                }

                $placeholder->delete();
            }

            $target->user_id = $user->id;
            $target->save();
            $user->update(['is_client' => true]);
        });

        return response()->json(['message' => 'Webアカウントを紐づけました。']);
    }

    private function hasClientRecords(Client $client): bool
    {
        foreach ($client->only([
            'name_kana', 'gender', 'phone', 'occupation', 'body_notes', 'personality_notes',
            'relationship_note', 'has_unpaid_fee', 'line_linked',
        ]) as $value) {
            if ($value !== null && $value !== false && $value !== '') {
                return true;
            }
        }

        $connection = $client->getConnection();
        foreach ([
            'reservations', 'client_goals', 'client_hopes', 'training_logs', 'pilates_sessions',
            'intake_forms', 'guest_pass_purchases', 'guest_pass_usages', 'stripe_customers', 'payments',
        ] as $table) {
            if ($connection->table("client_db.{$table}")->where('client_id', $client->id)->exists()) {
                return true;
            }
        }

        return $connection->table('client_db.client_merge_histories')
            ->where('source_client_id', $client->id)
            ->orWhere('target_client_id', $client->id)
            ->exists();
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $validated = $request->validated();

        if ($client->is_provisional && array_key_exists('is_active', $validated)) {
            throw ValidationException::withMessages([
                'is_active' => '仮登録の方はクライアント一覧から登録してください。',
            ]);
        }

        $client->update($validated);

        return response()->json([
            'message' => '更新しました。',
            'client' => $client->fresh(),
        ]);
    }

    public function archive(Request $request)
    {
        $clients = User::query()
            ->select('users.id', 'users.is_client')
            ->where('is_pilates_user', true)
            ->where('is_client', true)
            ->whereHas('client', fn ($q) => $q->where('is_active', false))
            ->with(['client' => fn ($query) => $query
                ->select('id', 'user_id', 'name', 'phone', 'has_unpaid_fee', 'line_linked')
                ->withMax('lessonSlotsViaReservations', 'date')])
            ->orderBy(Client::select('name')->whereColumn('client_db.clients.user_id', 'users.id'))
            ->orderBy('users.id')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Pilates/Clients/Archive', [
            'clients' => $clients,
            'standaloneClients' => $this->standaloneClients(archived: true),
        ]);
    }

    private function standaloneClients(bool $archived = false, bool $provisional = false): LengthAwarePaginator
    {
        return Client::query()
            ->select('id', 'name', 'phone', 'has_unpaid_fee', 'line_linked')
            ->whereNull('user_id')
            ->where('is_active', ! $archived && ! $provisional)
            ->where('is_provisional', $provisional)
            ->withMax('lessonSlotsViaReservations', 'date')
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(20, pageName: $provisional ? 'provisional_page' : 'standalone_page')
            ->withQueryString();
    }
}
