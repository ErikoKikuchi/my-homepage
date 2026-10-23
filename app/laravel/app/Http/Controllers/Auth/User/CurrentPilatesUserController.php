<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrentPilatesUserController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user('web');

        if (! $user) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (! $user->is_pilates_user) {
            return response()->json([
                'message' => 'このサービスへのアクセス権限がありません',
            ], 403);
        }

        $client = $user->client;

        if (! $client) {
            return response()->json([
                'message' => 'クライアント情報の登録・紐づけが必要です',
            ], 403);
        }

        return response()->json([
            'user' => [
                'name' => $client->name,
                'canUseTrainingLog' => $user->canUseTrainingLog(),
            ],
        ]);
    }
}
