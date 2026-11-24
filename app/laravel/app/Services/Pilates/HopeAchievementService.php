<?php

namespace App\Services\Pilates;

use App\Models\Pilates\Client;
use Illuminate\Support\Facades\DB;

class HopeAchievementService
{
    public function mark(Client $client, string $hopeId): bool
    {
        return DB::connection('client_db')->transaction(function () use ($client, $hopeId) {
            // 管理者によるHOPEの編集と同じクライアント行をロックする。
            $client = Client::query()->lockForUpdate()->findOrFail($client->id);
            $hope = $client->hopes()->findOrFail($hopeId);

            if (! $hope->is_active) {
                return $hope->achieved_at !== null;
            }

            $hope->update([
                'is_active' => false,
                'achieved_at' => now(),
            ]);

            return true;
        });
    }
}
