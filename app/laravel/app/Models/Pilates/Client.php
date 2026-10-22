<?php

namespace App\Models\Pilates;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Enums\Pilates\ReservationStatus;
use Carbon\Carbon;

class Client extends Model
{
    use HasUuids;

    protected $connection = 'client_db';

    protected $table = 'client_db.clients';

    protected $fillable = [
        'name',
        'name_kana',
        'gender',
        'phone',
        'occupation',
        'body_notes',
        'personality_notes',
        'relationship_note',
        'is_active',
        'has_unpaid_fee',
        'line_linked',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_unpaid_fee' => 'boolean',
        'line_linked' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function goals(): HasMany
    {
        return $this->hasMany(ClientGoal::class);
    }

    public function hopes(): HasMany
    {
        return $this->hasMany(ClientHope::class);
    }

    public function trainingLogs(): HasMany
    {
        return $this->hasMany(TrainingLog::class);
    }

    public function pilatesSessions(): HasMany
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

    // 利用者一覧ー現在利用中の方
    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }

    // 「初回来店前を含む未有効」
    #[Scope]
    protected function inactive(Builder $query): void
    {
        $query->where('is_active', false);
    }

    // LINE登録済
    #[Scope]
    protected function lineLinked(Builder $query): void
    {
        $query->where('line_linked', true);
    }

    // LINE未登録
    #[Scope]
    protected function notLineLinked(Builder $query): void
    {
        $query->where('line_linked', false);
    }
    //boolean判定
    public function isLineLinked(): bool
    {
        return (bool) $this->line_linked;
    }

    // 未払金あり
    #[Scope]
    protected function hasUnpaidFee(Builder $query): void
    {
        $query->where('has_unpaid_fee', true);
    }

    // 未払金なし
    #[Scope]
    protected function alreadyPaid(Builder $query): void
    {
        $query->where('has_unpaid_fee', false);
    }

    // 回数券残数
    //ublic function remainingGuestPassCount(): int
    //{
    //    $purchasedCount = $this->guestPassPurchases()->sum('passes_purchased');
    //    $usedCount = $this->guestPassUsages()->count();

    //    return $purchasedCount - $usedCount;
    //}
    public function lessonSlotsViaReservations(): HasManyThrough
    {
        return $this->hasManyThrough(
            LessonSlot::class,
            Reservation::class,
            'client_id',
            'id',
            'id',
            'lesson_slot_id'
        )->whereIn('reservations.status', [
            ReservationStatus::WaitingVenue,
            ReservationStatus::Confirmed,
        ]);
    }
    protected function latestReservationDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->lesson_slots_via_reservations_max_date
                ? Carbon::parse($this->lesson_slots_via_reservations_max_date)->format('Y-m-d')
                : '--',
        );
    }
}
