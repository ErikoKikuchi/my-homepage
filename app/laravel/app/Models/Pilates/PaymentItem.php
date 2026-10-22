<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentItem extends Model
{
    use HasUuids;

    protected $connection = 'client_db';

    protected $fillable = [
        'item_name',
        'unit_price',
        'quantity',
    ];
    protected $casts = [
    'unit_price' => 'integer',
    'quantity' => 'integer',
];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function guestPassPurchase(): BelongsTo
    {
        return $this->belongsTo(GuestPassPurchase::class);
    }
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
