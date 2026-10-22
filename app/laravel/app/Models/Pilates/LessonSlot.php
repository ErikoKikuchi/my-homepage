<?php

namespace App\Models\Pilates;

use App\Enums\Pilates\ReservationStatus;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;


class LessonSlot extends Model
{
    use HasUuids;

    protected $connection = 'client_db';

    protected $fillable = [
        'date',
        'is_bookable',
    ];

    protected $casts = [
        'is_bookable' => 'boolean',
        'date' => 'date',
    ];

    // リレーション先
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function lessonTemplate(): BelongsTo
    {
        return $this->belongsTo(LessonTemplate::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function venueNote(): string
    {
        if ($this->location) {
            if ($this->location->is_paid_venue) {
                return "外部施設({$this->location->name}さん)のため、会場追加料金は1回あたり+{$this->location->price_addon_per_session}円です。";
            }

            return "こちらのレッスンは{$this->location->name}での開催です。";
        }

        return '遠浅公民館を第一選択に、安平町スポーツセンター・町民会館のいずれかで開催予定です。施設利用料は一部ご負担いただくこともございます。お身体の状態やお子さまの年齢など、移動に配慮が必要な場合や、その他の事情により特定の場所を希望される場合は、備考欄にご記入ください。';
    }

    // レッスンスロットがアクティブで空のものだけ表示
    #[Scope]
    protected function available(Builder $query): void
    {
        $query->where('is_bookable', true)
            ->whereDoesntHave('reservations', function (Builder $q) {
                $q->whereIn('reservations.status', [
                    ReservationStatus::WaitingVenue,
                    ReservationStatus::Confirmed,
                ]);
            });
    }


}
