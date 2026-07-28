<?php

namespace app\Enums\Pilates;

enum ReservationStatus: string
{
    case WaitingVenue = 'waiting_venue';
    case Confirmed = 'confirmed';
    case Canceled = 'canceled';
    case NoShow = 'no_show';

    public function label(): string
    {
        return match ($this) {
            self::WaitingVenue => '会場確認待ち',
            self::Confirmed => '確定',
            self::Canceled => 'キャンセル',
            self::NoShow => '無断キャンセル',
        };
    }
}