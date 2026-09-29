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

        if (! $user->is_pilates_user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return response()->json([
            'user' => [
                'name' => $user->name,
                'canUseTrainingLog' => $user->canUseTrainingLog(),
            ],
        ]);
    }
}
