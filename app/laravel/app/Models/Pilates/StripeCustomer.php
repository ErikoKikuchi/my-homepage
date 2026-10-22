<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeCustomer extends Model
{
    use HasUuids;

    protected $connection = 'client_db';

    protected $fillable = [
        'memo',
        'stripe_customer_id',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

}
