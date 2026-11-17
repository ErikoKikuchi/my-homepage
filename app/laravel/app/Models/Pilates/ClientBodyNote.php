<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientBodyNote extends Model
{
    use HasUuids;

    protected $connection = 'client_db';

    protected $fillable = [
        'body_note',
        'observed_on',
    ];

    protected $casts = [
        'observed_on' => 'date',
    ];

    // リレーション
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}