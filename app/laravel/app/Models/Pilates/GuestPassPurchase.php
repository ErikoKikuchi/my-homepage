<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestPassPurchase extends Model
{
    use HasUuids;

    protected $connection = 'client_db';

    protected $fillable = [
    'passes_purchased',
    'purchased_at',
    'is_finished',
    'pass_type',
    'imported_at',
    'note',
];

    protected $casts = [
        'passes_purchased' => 'integer',
        'purchased_at' => 'date',
        'is_finished' => 'boolean',
        'imported_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
