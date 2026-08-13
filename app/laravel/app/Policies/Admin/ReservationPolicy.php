<?php

namespace App\Policies\Admin;

use App\Models\Auth\Admin;
use App\Models\Pilates\Reservation;

class ReservationPolicy
{
    /**
     * 管理者代行での予約確定(waiting_venue → confirmed)
     */
    public function confirm(Admin $admin, Reservation $reservation): bool
    {
        return $this->belongsToPilates($admin);
    }

    /**
     * pilatesセクション権限を持つ管理者か
     *
     * AdminSectionMiddleware側で既にルートレベルで担保されているが、
     * Policy層でも同じ判定を持たせることで多層防御とする。
     * 将来ルーティング変更でミドルウェア適用範囲がずれた場合の保険。
     */
    private function belongsToPilates(Admin $admin): bool
    {
        return $admin->sections->contains('key', 'pilates');
    }
}