<?php

namespace App\Models\Pilates;


use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Auth\Admin;

class GuestPassUsage extends Model
{
    use HasUuids;

    protected $connection = 'client_db';

    protected $fillable = [
        'pass_type',
        'reason',
        'note',
        'used_at',
        'voided_at',
        'void_reason',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'voided_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function guestPassPurchase(): BelongsTo
    {
        return $this->belongsTo(GuestPassPurchase::class);
    }

    public function pilatesSession(): BelongsTo
    {
        return $this->belongsTo(PilatesSession::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
    public function recordedByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'recorded_by_admin_id');
    }

    public function voidedByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'voided_by_admin_id');
    }
}
