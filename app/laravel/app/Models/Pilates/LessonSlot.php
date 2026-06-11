<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class LessonSlot extends Model
{
    use HasUuids;
    protected $connection = 'client_db';

    protected $fillable = [
        'date',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    //リレーション先
    public function reservation():HasMany
    {
        return $this->hasMany(Reservation::class);
    }
    public function lessonTemplate():BelongsTo
    {
        return $this->belongsTo(LessonTemplate::class);
    }
    public function location():BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    //レッスンスロットがアクティブで空のものだけ表示
    #[Scope]
    protected function available(Builder $query):void
    {
        $query->where('is_active', true)
        ->whereDoesntHave('reservations', function (Builder $q) {
            $q->whereIn('status',['waiting_venue', 'confirmed']);
        });
    }

    //レッスン日が本日より前のもののみを表示する
    #[Scope]
    protected function upcoming(Builder $query):void
    {
        $query->where('date', '>', now()->toDateString());
    }
}
