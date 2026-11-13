<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PilatesExercise extends Model
{
    use HasUuids;

    protected $connection = 'client_db';

    protected $fillable = ['name', 'supplement', 'precautions'];

    protected $casts = ['photos' => 'array'];
}
