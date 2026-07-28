<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Enums\Pilates\ReservationStatus;

class Reservation extends Model
{
    use HasUuids;
    protected $connection = 'client_db';
    protected $table = 'client_db.reservations';

    protected $fillable = [
        'user_id',
        'participants',
        'participants_name',
        'note',
        'status',
        'cancelled_at',
        'cancelled_by'
    ];

    protected function casts(): array
    {
        return [
            'cancelled_at' => 'datetime',
            'status' => ReservationStatus::class,
        ];
    }

    //リレーション
    public function lessonSlot():BelongsTo
    {
        return $this->belongsTo(LessonSlot::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Auth\User::class);
    }
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }


    //未来の予約の絞り込み用
    #[Scope]
    protected function active(Builder $query): void
{
    $query->whereIn('reservations.status', [
        ReservationStatus::WaitingVenue,
        ReservationStatus::Confirmed,
    ]);
}
    //ユーザーがこれからの予約を確認用
    #[Scope]
    protected function upComing(Builder $query):void
    {
        $query->whereHas('lessonSlot', fn($q) => $q->where('date', '>=', today()))->where('status', '!=', ReservationStatus::Canceled);;
    }
    //過去の予約を確認用
    #[Scope]
    protected function past(Builder $query):void
    {
        $query->whereHas('lessonSlot', fn($q) => $q->where('date', '<', today()));
    }
    //ログインユーザの予約のみ表示
    #[Scope]
    protected function forUser(Builder $query):void
    {
        /** @var \App\Models\Auth\User|null $user */
        $user = auth('web')->user();
        $query->where('user_id', $user?->id);
    }
    protected function cancellationCutoff():Attribute
    {
        return Attribute::make(
            get: fn () =>$this->lessonSlot->date->copy()->subDay()->setTime(12, 0));
    }

    protected function isPastCutoff():Attribute
    {
        return Attribute::make(
            get: fn () => now()->greaterThan($this->cancellation_cutoff),
        );
    }
}
