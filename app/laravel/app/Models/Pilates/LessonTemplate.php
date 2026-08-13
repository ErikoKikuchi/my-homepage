<?php

namespace App\Models\Pilates;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LessonTemplate extends Model
{
    use HasUuids;
    protected $connection = 'client_db';

    protected $fillable = [
        'start_time',
        'end_time',
        'is_active'
    ];

    // LessonTemplate.php
    public function lessonSlots(): HasMany
    {
        return $this->hasMany(LessonSlot::class);
    }
    //開始時間・終了時間の表示整形
    public function startTime():Attribute
    {
        return Attribute::make(
            get: fn(string $value) => \Carbon\Carbon::parse($value)->format('H:i')
        );
    }
    public function endTime():Attribute
    {
        return Attribute::make(
            get: fn(string $value) => \Carbon\Carbon::parse($value)->format('H:i')
        );
    }
}
