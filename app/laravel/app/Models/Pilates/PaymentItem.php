<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class PaymentItem extends Model
{
    use HasUuids;
    protected $connection = 'client_db';
    
    protected $fillable = [
        'item_name',
        'unit_price',
        'quantity'
    ];

    public function payments():BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
    public function guestPassPurchases():BelongsTo
    {
        return $this->belongsTo(GuestPassPurchase::class);
    }
}
