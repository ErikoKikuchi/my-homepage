<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class Payment extends Model
{
    use HasUuids;
    protected $connection = 'client_db';
    
    protected $fillable = [
        'amount',
        'currency',
        'status',
        'payment_method',
        'paid_at',
    ];

    protected $casts = [
        'paid_at'=>'datetime',
    ];

    public function clients():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
