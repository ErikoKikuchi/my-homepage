<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;


class Client extends Model
{
    use HasUuids;
    protected $connection = 'client_db';

    protected $fillable = [
        'name',
        'gender',
        'occupation',
        'body_notes',
        'personality_notes',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(\App\Models\Auth\User::class);
    }

    //利用者一覧ー現在利用中の方
    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }
    
    //利用者一覧（利用終了もしくは中止中）
    #[Scope]
    protected function archived(Builder $query): void
    {
        $query->where('is_active', false);
    }
}
