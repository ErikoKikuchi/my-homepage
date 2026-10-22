<?php

namespace App\Models\Pilates;

use App\Models\Pilates\Client;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingLog extends Model
{
    use HasUuids;

    protected $connection = 'client_db';

    protected $fillable = [
        'logged_date',
        'mood',
        'free_text',
    ];

    // キャスト
    protected $casts = [
        'logged_date' => 'date',
        'mood' => 'integer',
    ];

    // リレーション
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function trainingQuestion(): HasMany
    {
        return $this->hasMany(TrainingQuestion::class);
    }
    public function categories(): HasMany
    {
        return $this->hasMany(TrainingLogCategory::class);
    }

    // ログインユーザーのログ
    #[Scope]
    public function forUser(Builder $query): void
    {
        $clientId = auth('web')->user()?->client?->id;

        if ($clientId === null) {
            $query->whereRaw('1 = 0');

            return;
        }

        $query->where('client_id', $clientId);
    }

    // urgency=trueのもの
    #[Scope]
    public function urgent(Builder $query): void
{
    $query->whereHas('trainingQuestion', fn (Builder $q) =>
        $q->where('urgency', 'urgent')
    );
}

    // メッセージ有のもの
    #[Scope]
    public function hasQuestion(Builder $query): void
    {
        $query->whereHas('trainingQuestion');
    }

    // logged_date降順
    #[Scope]
    public function latestLogged(Builder $query): void
    {
        $query->orderByDesc('logged_date')
        ->orderByDesc('created_at');
    }

}
