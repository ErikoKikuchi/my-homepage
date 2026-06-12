<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingLogCategory extends Model
{
    use HasUuids;
    protected $connection = 'client_db';
    
    protected $fillable = [
        'category'
    ];

    public function trainingLog():BelongsTo
    {
        return $this->belongsTo(TrainingLog::class);
    }
}
