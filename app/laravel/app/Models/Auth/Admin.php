<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class Admin extends Authenticatable
{
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected function casts(): array
    {
        return [ 
            'two_factor_secret'=>'encrypted',
            'two_factor_recovery_codes'=>'encrypted',
            'two_factor_confirmed_at'=>'datetime',
            'password' => 'hashed',
        ];
    }
    //uuidの自動生成
    public $incrementing = false;
    protected $keyType = 'string';
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}
