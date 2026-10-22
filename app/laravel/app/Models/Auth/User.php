<?php

namespace App\Models\Auth;

use App\Models\Pilates\Client;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    use HasUuids;

    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'is_medical',
        'is_client',
        'is_pilates_user',
        'profile_completed',
        'bookshelf_public',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_client' => 'boolean',
            'is_pilates_user' => 'boolean',
            'is_medical' => 'boolean',
            'profile_completed' => 'boolean',
            'bookshelf_public' => 'boolean',
        ];
    }

    // uuidの自動生成
    public $incrementing = false;

    protected $keyType = 'string';


    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    public function canUseTrainingLog(): bool
    {
        return $this->hasVerifiedEmail()
        && $this->client?->is_active === true;
    }
}
