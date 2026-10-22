<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PilatesSession extends Model
{
    use HasUuids;

    protected $connection = 'client_db';

    protected $fillable = [
        'session_date',
        'notes',
    ];

    protected $casts = [
        'session_date' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

}
