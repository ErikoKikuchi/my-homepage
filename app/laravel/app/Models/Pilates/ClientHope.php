<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientHope extends Model
{
    use HasUuids;

    protected $connection = 'client_db';

    protected $fillable = [
        'hope',
        'is_active',
        'achieved_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'achieved_at' => 'datetime',
    ];

    public function revise(?string $text): void
    {
        if ($text === $this->hope) {
            return;
        }

        $this->update(['is_active' => false]);

        if ($text !== null) {
            $this->client->hopes()->create(['hope' => $text]);
        }
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}