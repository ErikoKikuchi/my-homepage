<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class GuestPassUsage extends Model
{
    use HasUuids;
    protected $connection = 'client_db';
    
    protected $fillable = [
        'reason',
        'used_at'
    ];

    protected $casts = [
        'used_at'=>'datetime',
    ];

    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function guestPassPurchases():BelongsTo
    {
        return $this->belongsTo(GuestPassPurchase::class);
    }
    public function pilatesSessions():BelongsTo
    {
        return $this->belongsTo(PilatesSession::class);
    }
    public function reservation():BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
    #[scope]
    protected function noShow(Builder $query): void
    {
        $query->where('reason', 'no_show');
    }
}
