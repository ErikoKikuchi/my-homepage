<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntakeForm extends Model
{
    use HasUuids;
    protected $connection = 'client_db';
    
    protected $fillable = [
        'submitted_at'
    ];
    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
