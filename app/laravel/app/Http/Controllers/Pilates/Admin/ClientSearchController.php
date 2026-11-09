<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pilates\Client;
use App\Services\Pilates\PhoneNumberNormalizerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientSearchController extends Controller
{
    public function search(Request $request, PhoneNumberNormalizerService $normalizer): JsonResponse
    {
        $validated = $request->validate(['q' => ['nullable', 'string', 'max:255']]);
        $query = trim($validated['q'] ?? '');

        if ($query === '') {
            return response()->json([]);
        }

        $clients = Client::query();
        if (preg_match('/^[0-9\-]+$/', $query)) {
            $phone = $normalizer->normalize($query);
            if ($phone === null) {
                return response()->json([]);
            }
            $clients->where('phone', $phone);
        } else {
            $clients->where(fn ($search) => $search
                ->where('name', 'like', "%{$query}%")
                ->orWhere('name_kana', 'like', "%{$query}%"));
        }

        return response()->json($clients->orderBy('name')->limit(10)
            ->get(['id', 'name', 'phone', 'relationship_note']));
    }
}
