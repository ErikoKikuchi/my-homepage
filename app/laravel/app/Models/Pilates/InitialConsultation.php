<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InitialConsultation extends Model
{
    use HasUuids;
    protected $connection = 'client_db';
    
    protected $fillable = [
        'full_name',
        'date_of_birth',
        'contact',
        'daily_posture',
        'symptoms',
        'symptom_detail',
        'medical_history',
        'current_treatment',
        'session_goal',
        'future_goal',
        'consent_non_medical',
        'consent_health_condition',
        'consent_report_discomfort',
        'consented_at',
        'submitted_at'
    ];

    protected $casts =[
        'date_of_birth'=>'date',
        'consent_non_medical'=>'boolean',
        'consent_health_condition'=>'boolean',
        'consent_report_discomfort'=>'boolean',
        'symptoms' => 'array',
        'consented_at'=>'datetime',
        'submitted_at'=>'datetime',
    ];
    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function intakeForm():BelongsTo
    {
        return $this->belongsTo(IntakeForm::class);
    }
}