<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class GuestPassPurchase extends Model
{
    use HasUuids;
    protected $connection = 'client_db';

    protected $fillable = [
        'passes_purchased',
        'amount_paid',
        'payment_method',
        'purchased_at',
        'is_finished'
    ];

    protected $casts = [
        'purchased_at'=>'datetime',
    ];
    public function clients():BelongsTo{
        return $this->belongsTo(Client::class);
    }

}
