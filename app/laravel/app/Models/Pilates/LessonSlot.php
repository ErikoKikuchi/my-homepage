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

/**
 * @property string $id
 * @property string $lesson_template_id
 * @property Carbon $date
 * @property string|null $location_id
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read LessonTemplate $lessonTemplate
 * @property-read Location|null $location
 * @property-read Collection<int, Reservation> $reservations
 * @property-read int|null $reservations_count
 *
 * @method static Builder<static>|LessonSlot available()
 * @method static Builder<static>|LessonSlot newModelQuery()
 * @method static Builder<static>|LessonSlot newQuery()
 * @method static Builder<static>|LessonSlot query()
 * @method static Builder<static>|LessonSlot upcoming()
 * @method static Builder<static>|LessonSlot whereCreatedAt($value)
 * @method static Builder<static>|LessonSlot whereDate($value)
 * @method static Builder<static>|LessonSlot whereId($value)
 * @method static Builder<static>|LessonSlot whereIsActive($value)
 * @method static Builder<static>|LessonSlot whereLessonTemplateId($value)
 * @method static Builder<static>|LessonSlot whereLocationId($value)
 * @method static Builder<static>|LessonSlot whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class LessonSlot extends Model
{
    use HasUuids;

    protected $connection = 'client_db';

    protected $fillable = [
        'date',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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
                return "外部施設({$this->location->name}さん)のため、料金に1回あたり+{$this->location->price_addon_per_session}円が上乗せされています。";
            }

            return "こちらのレッスンは{$this->location->name}での開催です。";
        }

        return '遠浅公民館を第一選択に、安平町スポーツセンター・町民会館のいずれかで開催予定です。施設利用料は一部ご負担いただくこともございます。お身体の状態やお子さまの年齢など、移動に配慮が必要な場合や、その他の事情により特定の場所を希望される場合は、備考欄にご記入ください。';
    }

    // レッスンスロットがアクティブで空のものだけ表示
    #[Scope]
    protected function available(Builder $query): void
    {
        $query->where('is_active', true)
            ->whereDoesntHave('reservations', function (Builder $q) {
                $q->whereIn('reservations.status', [
                    ReservationStatus::WaitingVenue,
                    ReservationStatus::Confirmed,
                ]);
            });
    }

    // レッスン日が本日より前のもののみを表示する
    // #[Scope]
    // protected function upcoming(Builder $query):void
    // {
    //    $query->whereHas('lessonSlot', function ($q) {
    //        $q->where('date', '>', now()->toDateString());
    //    })
    //    ->where('status', '!=', ReservationStatus::Canceled);
    // }

}
