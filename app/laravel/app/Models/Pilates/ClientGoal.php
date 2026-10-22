<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientGoal extends Model
{
    use HasUuids;

    protected $connection = 'client_db';

    protected $fillable = [
        'goal',
        'outlook',
        'hope',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // リレーション
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // 対象のクライアントのゴールを抽出
    #[Scope]
    public function forClient(Builder $query, string $clientId): void
    {
        $query->where('client_id', $clientId);
    }

}
