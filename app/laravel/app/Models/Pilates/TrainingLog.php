<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;


class TrainingLog extends Model
{
    use HasUuids;
    protected $connection = 'client_db';
    
    protected $fillable = [
        'logged_date',
        'mood',
        'free_text'
    ];

    //キャスト
    protected $casts = [
        'logged_date' => 'date',
    ];

    //リレーション
    public function user():BelongsTo
    {
        return $this->belongsTo(\App\Models\Auth\User::class);
    }
    public function trainingQuestion():HasMany
    {
        return $this->hasMany(TrainingQuestion::class);
    }

    //ログインユーザーのログ
    #[Scope]
    public function forUser(Builder $query):void
    {
        /** @var \App\Models\Auth\User|null $user */
        $user = auth('web')->user();
        $query->where('user_id', $user?->id);
    }

    //urgency=trueのもの
    #[Scope]
    public function urgent(Builder $query):void
    {
        $query->where('urgency',true);
    }

    //メッセージ有のもの
    #[Scope]
    public function hasQuestion(Builder $query):void
    {
        $query->whereHas('trainingQuestion');
    }

    //logged_date降順
    #[Scope]
    public function latest(Builder $query):void
    {
        $query->orderBy('logged_date','desc');
    }
    //logged_dateをフォーマットして返す
    protected function loggedDate():Attribute
    {
        return Attribute::make(
            get:fn($value)=>$value->format('Y/m/d')
        );
    }
}
