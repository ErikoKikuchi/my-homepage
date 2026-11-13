<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PilatesSessionExercise extends Model
{
    use HasUuids;

    protected $connection = 'client_db';

    protected $fillable = ['exercise_id', 'custom_name', 'comment', 'position'];

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(PilatesExercise::class);
    }
}
