<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;

class ReservationClosure extends Model
{
    protected $connection = 'client_db';

    protected $primaryKey = 'date';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['date', 'period'];

    public static function blocks(?string $period, string $start, string $end): bool
    {
        return match ($period) {
            'all_day' => true,
            'morning' => $start < '12:00',
            'afternoon' => $end > '12:00',
            default => false,
        };
    }
}
