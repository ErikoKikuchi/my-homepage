<?php

namespace App\Http\Controllers\Pilates\User;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Fortify;

class AccountTwoFactorController extends Controller
{
    public function update(Request $request, string $operation)
    {
        $data = $request->validate([
            'password' => ['required', 'string'],
            'code' => [$operation === 'confirm' ? 'required' : 'nullable', 'digits:6'],
        ]);

        $userId = $request->user('web')->getKey();

        return (new User)->getConnection()->transaction(function () use ($userId, $operation, $data) {
            $user = User::query()->lockForUpdate()->findOrFail($userId);
            if (! Hash::check($data['password'], $user->getAuthPassword())) {
                throw ValidationException::withMessages(['password' => '現在のパスワードが正しくありません。']);
            }

            $payload = match ($operation) {
                'setup' => $this->setup($user),
                'confirm' => $this->confirm($user, $data['code']),
                'recovery' => $this->recovery($user),
                'disable' => $this->disable($user),
            };

            return response()->json($payload)->header('Cache-Control', 'no-store');
        });
    }

    private function setup(User $user): array
    {
        abort_if($user->hasEnabledTwoFactorAuthentication(), 409, '二要素認証は設定済みです。');
        app(EnableTwoFactorAuthentication::class)($user);

        return [
            'twoFactor' => 'pending',
            'qrSvg' => $user->twoFactorQrCodeSvg(),
            'setupKey' => Fortify::currentEncrypter()->decrypt($user->two_factor_secret),
        ];
    }

    private function confirm(User $user, string $code): array
    {
        abort_if(! $user->two_factor_secret || $user->hasEnabledTwoFactorAuthentication(), 409, '設定状況を更新してください。');
        app(ConfirmTwoFactorAuthentication::class)($user, $code);
        $user->setRememberToken(Str::random(60));
        $user->save();

        return $this->recovery($user);
    }

    private function recovery(User $user): array
    {
        abort_unless($user->hasEnabledTwoFactorAuthentication(), 409, '二要素認証を設定してください。');

        return ['twoFactor' => 'enabled', 'recoveryCodes' => $user->recoveryCodes()];
    }

    private function disable(User $user): array
    {
        app(DisableTwoFactorAuthentication::class)($user);

        return ['twoFactor' => 'disabled'];
    }
}
