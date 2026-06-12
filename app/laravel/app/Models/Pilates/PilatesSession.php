<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PilatesSession extends Model
{
    use HasUuids;
    protected $connection = 'client_db';
    
    protected $fillable = [
        'session_date',
        'notes'
    ];

    protected $casts =[
        'session_date'=>'datetime'
    ];
    #[Scope]
    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    #[Scope]
    public function reservation():BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
    #[Scope]
    public function sessionDate():Attribute
    {
        return Attribute::make(
            get:fn($value)=>$value->format('Y/m/d')
        );
    }
}
