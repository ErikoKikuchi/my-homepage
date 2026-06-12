<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class GuestPass extends Model
{
    use HasUuids;
    protected $connection = 'client_db';
    
    protected $fillable = [
        'passes_total',
        'passes_remaining',
        'last_used_at'
    ];

    protected $casts = [
        'last_used_at'=>'date'
    ];

    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    //対象のクライアントの回数券を抽出
    #[Scope]
    public function forClient(Builder $query, string $clientId):void
    {
        $query->where('client_id', $clientId);
    }
    //残回数がある回数券を抽出
    #[Scope]
    public function active(Builder $query):void
    {
        $query->where('passes_remaining','>', 0);
    }

}
