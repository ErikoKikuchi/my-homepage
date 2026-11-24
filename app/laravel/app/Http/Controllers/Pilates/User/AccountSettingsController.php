<?php

namespace App\Http\Controllers\Pilates\User;

use App\Http\Controllers\Controller;
use App\Services\Pilates\PhoneNumberNormalizerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AccountSettingsController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user('web');
        $client = $user->client;

        return response()->json([
            'profile' => $client?->only(['name', 'name_kana', 'phone', 'occupation']),
            'email' => $user->email,
            'twoFactor' => $user->hasEnabledTwoFactorAuthentication() ? 'enabled'
                : ($user->two_factor_secret ? 'pending' : 'disabled'),
        ])->header('Cache-Control', 'no-store');
    }

    public function update(Request $request, PhoneNumberNormalizerService $normalizer)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_kana' => ['present', 'nullable', 'string', 'max:255'],
            'phone' => ['present', 'nullable', 'string', 'max:255'],
            'occupation' => ['present', 'nullable', 'string', 'max:255'],
        ]);
        $client = $request->user('web')->client;
        abort_unless($client, 403, '基本情報の編集にはクライアント情報との連携が必要です。');

        $data['phone'] = $normalizer->normalize($data['phone']);
        $client->update($data);

        return response()->json(['profile' => $client->only(['name', 'name_kana', 'phone', 'occupation'])]);
    }

    public function sendResetLink(Request $request)
    {
        $status = Password::broker(config('fortify.passwords'))->sendResetLink([
            'email' => $request->user('web')->email,
        ]);

        return $status === Password::RESET_LINK_SENT
            ? response()->noContent()
            : response()->json(['message' => __($status)], 422);
    }
}
