<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class StripeCustomers extends Model
{
    use HasUuids;
    protected $connection = 'client_db';
    
    protected $fillable = [
        'memo',
        'used_at'
    ];

    public function clients():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    public function stripeCustomers():BelongsTo
    {
        return $this->belongsTo(StripeCustomers::class);
    }
}