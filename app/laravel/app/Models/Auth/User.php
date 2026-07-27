<?php

namespace App\Models\Auth;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Casts\Attribute;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasUuids;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_medical',
        'is_client',
        'is_pilates_user',
        'phone',
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
            'password' => 'hashed',
            'is_client'=>'boolean',
            'is_pilates_user'=>'boolean',
            'is_medical'=>'boolean',
        ];
    }
    //uuidの自動生成
    public $incrementing = false;
    protected $keyType = 'string';
    protected static function booting(): void
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
    public function client():HasOne
    {
        return $this->hasOne(\App\Models\Pilates\Client::class);
    }
    public function reservations():HasMany
    {
        return $this->hasMany(\App\Models\Pilates\Reservation::class);
    }
    public function lessonSlotsViaReservations(): HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Pilates\LessonSlot::class,
            \App\Models\Pilates\Reservation::class,
            'user_id',        // reservations.user_id (Reservation側の外部キー)
            'id',              // lesson_slots.id (LessonSlot側の主キー)
            'id',              // users.id (User側のローカルキー)
            'lesson_slot_id'   // reservations.lesson_slot_id (Reservation側のローカルキー)
        )->whereIn('reservations.status', ['waiting_venue', 'confirmed']);
    }
    protected function latestReservationDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->lesson_slots_via_reservations_max_date
                ? \Carbon\Carbon::parse($this->lesson_slots_via_reservations_max_date)->format('Y-m-d')
                : '--',
        );
    }
}
