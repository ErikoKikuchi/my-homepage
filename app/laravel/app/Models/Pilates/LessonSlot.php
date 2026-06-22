<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property string $id
 * @property string $lesson_template_id
 * @property \Illuminate\Support\Carbon $date
 * @property string|null $location_id
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pilates\LessonTemplate $lessonTemplate
 * @property-read \App\Models\Pilates\Location|null $location
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pilates\Reservation> $reservations
 * @property-read int|null $reservations_count
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
        'date' => 'datetime',
    ];

    //リレーション先
    public function reservations():HasMany
    {
        return $this->hasMany(Reservation::class);
    }
    public function lessonTemplate():BelongsTo
    {
        return $this->belongsTo(LessonTemplate::class);
    }

    public function venueNote(): string
    {
        if ($this->is_paid_venue) {
            return "外部施設({$this->location->name}さん)のため、料金に1回あたり+{$this->location->price_addon_per_session}円が上乗せされています。";
        }
    
        return '遠浅公民館・町民会館・安平町スポーツセンターのいずれかで開催予定です。施設利用料は一部ご負担いただいています（詳細は予約時にご案内します）。';
    }

    //レッスンスロットがアクティブで空のものだけ表示
    #[Scope]
    protected function available(Builder $query):void
    {
        $query->where('is_active', true)
        ->whereDoesntHave('reservations', function (Builder $q) {
            $q->whereIn('status',['waiting_venue', 'confirmed']);
        });
    }

    //レッスン日が本日より前のもののみを表示する
    #[Scope]
    protected function upcoming(Builder $query):void
    {
        $query->where('date', '>', now()->toDateString());
    }
}
