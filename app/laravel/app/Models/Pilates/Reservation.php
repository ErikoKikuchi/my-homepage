<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reservation extends Model
{
    use HasUuids;
    protected $connection = 'client_db';

    protected $fillable = [
        'user_id',
        'participants',
        'participants_name',
        'note',
        'status',
        'cancelled_at',
    ];

    protected $casts = [
        'cancelled_at'=>'datetime',
    ];

    //リレーション
    public function lessonSlot():BelongsTo
    {
        return $this->belongsTo(LessonSlot::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Auth\User::class);
    }
    public function location():BelongsToMany
    {
        return $this->belongsToMany(Location::class,'reservation_location','reservation_id','location_id');
    }


    //確定済の予約の絞り込み用
    #[Scope]
    protected function confirmed(Builder $query): void
    {
        $query->where('status', 'confirmed');
    }
    //ユーザーがこれからの予約を確認用
    #[Scope]
    protected function upComing(Builder $query):void
    {
        $query->whereHas('lessonSlot', fn($q) => $q->where('date', '>=', today()));
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
}
