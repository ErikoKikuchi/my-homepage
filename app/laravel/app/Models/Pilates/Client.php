<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasUuids;
    protected $connection = 'client_db';

    protected $fillable = [
        'name',
        'gender',
        'occupation',
        'body_notes',
        'personality_notes',
        'is_active',
        'has_unpaid_fee',
        'line_linked'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(\App\Models\Auth\User::class);
    }
    public function reservation():HasMany
    {
        return $this->hasMany(Reservation::class);
    }
    public function trainingLog():HasMany
    {
        return $this->hasMany(TrainingLog::class);
    }
    public function pilatesSession():HasMany
    {
        return $this->hasMany(PilatesSession::class);
    }
    public function guestPassPurchases()
    {
        return $this->hasMany(GuestPassPurchase::class);
    }
    
    public function guestPassUsages()
    {
        return $this->hasMany(GuestPassUsage::class);
    }

    //利用者一覧ー現在利用中の方
    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }
    
    //利用者一覧（利用終了もしくは中止中：）
    #[Scope]
    protected function archived(Builder $query): void
    {
        $query->where('is_active', false);
    }
    //LINE登録済
    #[Scope]
    protected function lineLinked(Builder $query): void
    {
        $query->where('line_linked', true);
    }
    //LINE未登録
    #[Scope]
    protected function notLineLinked(Builder $query): void
    {
        $query->where('line_linked', false);
    }
    //未払金あり
    #[Scope]
    protected function hasUnpaidFee(Builder $query): void
    {
        $query->where('has_unpaid_fee', true);
    }
    //未払金なし
    #[Scope]
    protected function alreadyPaid(Builder $query): void
    {
        $query->where('has_unpaid_fee', false);
    }
    // 回数券残数
    public function remainingGuestPassCount(): int
    {
        $purchasedCount = $this->guestPassPurchases()->sum('passes_purchased');
        $usedCount = $this->guestPassUsages()->count();
    
        return $purchasedCount - $usedCount;
    }
}
