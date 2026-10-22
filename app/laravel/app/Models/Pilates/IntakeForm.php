<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class IntakeForm extends Model
{
    use HasUuids;

    protected $connection = 'client_db';

    protected $fillable = [
        'submitted_at',
        'import_source',
        'import_source_key',
        'imported_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'imported_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    public function initialConsultation(): HasOne
    {
        return $this->hasOne(InitialConsultation::class);
    }
}
