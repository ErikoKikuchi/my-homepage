<?php

namespace App\Models\ThinkMotion;


use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Profile extends Model
{
    use HasUuids;

    protected $connection = 'mysql';

    protected $fillable=
    [
        'display_name',
        'bio',
        'links',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}