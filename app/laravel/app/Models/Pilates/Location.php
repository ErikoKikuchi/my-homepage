<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Location extends Model
{
    use HasUuids;
    protected $connection = 'client_db';

    protected $fillable = [
        'name',
        'address',
        'map_url',
        'base_fee',
        'policy',
        'is_active',
    ];

    protected $casts = [
        'is_active'=>'boolean',
    ];

    public function reservations()
    {
        return $this->belongsToMany('reservation_location','location_id','reservation_id');
    }

    // 表示用ラベル
    public function policyNote(): string
    {
        return match ($this->policy) {
            'own'    => '自社スタジオでのレッスンです。',
            'free' => '近隣施設のため、場所代は料金込みとなっています。',
            'shared' => '施設利用料は一部ご負担いただいています（詳細は予約時にご案内します）。',
            'paid' => '外部施設（苫小牧beauty Rubyさん）のため料金にその他経費が上乗せされています。',
        };
    }

    public function isStrict(): bool
    {
        return $this->policy === 'shared';
    }

    public function allowsMinorAdjustment(): bool
    {
        return in_array($this->policy, ['free', 'own', 'paid']);
    }

    public function cancelRule(): string
    {
        return match ($this->policy) {
            'own' => 'キャンセルは前日の正午12：00まで。軽微調整はLINE対応可能。',
            'free' => 'キャンセルは前日の正午12：00まで。軽微調整はLINE対応可能。',
            'shared' => 'キャンセルは前日の正午12：00まで。時間変更は不可（再予約対応）。',
            'paid' => 'キャンセルは前日まで。軽微な時間調整は現場判断で対応可。',
        };
    }

    public function bookingWindow(): int
    {
        return match ($this->policy) {
            'own'    => 1,
            'free' => 3,
            'shared' => 3,
            'paid' => 1,
        };
    }
}