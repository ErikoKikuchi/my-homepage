<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class Reservation extends Model
{
    use HasUuids;
    protected $connection = 'client_db';

    protected $fillable = [
        'participants',
        'participants_name',
        'note',
        'status',
        'cancelled_at',
        'cancelled_reason',
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


    //確定済の予約の絞り込み用
    #[Scope]
    protected function confirmed(Builder $query): void
    {
        $query->where('status', 'confirmed');
    }
}
