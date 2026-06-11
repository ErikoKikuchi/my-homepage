<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingQuestion extends Model
{
    use HasUuids;
    protected $connection = 'client_db';

    protected $fillable = [
        'question',
        'urgency',
        'answered_at'
    ];

    protected $casts = [
        'answered_at'=>'datetime'
    ];

    //リレーション
    public function trainingLog():BelongsTo
    {
        return $this->belongsTo(TrainingLog::class);
    }
}
