<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class ClientGoal extends Model
{
    use HasUuids;
    protected $connection = 'client_db';

    protected $fillable = [
        'goal',
        'outlook',
        'hope',
        'achieved_at'
    ];

    protected $casts = [
        'achieved_at'=>'datetime'
    ];
    //リレーション
    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    //対象のクライアントのゴールを抽出
    #[Scope]
    public function forClient(Builder $query, string $clientId):void
    {
        $query->where('client_id', $clientId);
    }

    //作成日降順
    #[Scope]
    public function latest(Builder $query):void
    {
        $query->orderBy('created_at','desc');
    }

}
