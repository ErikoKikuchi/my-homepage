<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class GuestPassUsage extends Model
{
    use HasUuids;
    protected $connection = 'client_db';
    
    protected $fillable = [
        
    ];

    protected $casts = [
        
    ];

    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    

}
