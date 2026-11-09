<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Enums\Pilates\ReservationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pilates\Admin\StoreLessonSlotRequest;
use App\Http\Requests\Pilates\Admin\UpdateLessonSlotRequest;
use App\Models\Pilates\LessonSlot;
use App\Models\Pilates\LessonTemplate;
use App\Models\Pilates\Location;
use App\Services\Pilates\AdminReservationAvailabilityService;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class LessonSlotController extends Controller
{
    public function __construct(
        private AdminReservationAvailabilityService $availabilityService
    ) {}

    public function index()
    {
        $lessonSlots = LessonSlot::with('lessonTemplate', 'location:id,name')
            ->where('date', '>=', now()->startOfDay())
            ->orderBy('date')->get();

        return Inertia::render('Admin/Pilates/LessonSlots/Index', compact('lessonSlots'));
    }

    public function create()
    {
        $lessonTemplates = LessonTemplate::where('is_active', true)
            ->orderBy('start_time')
            ->get();
        $locations = Location::where('is_active', true)->orderBy('name')->get();

        return Inertia::render('Admin/Pilates/LessonSlots/Create', compact('lessonTemplates', 'locations'));
    }

    public function store(StoreLessonSlotRequest $request)
    {
        $dates = $request->validated('dates');
        $lessonTemplate = LessonTemplate::findOrFail($request->validated('lesson_template_id'));

        $location = null;
        if ($request->validated('location_id')) {
            $location = Location::findOrFail($request->validated('location_id'));
        }

        $conflicts = [];
        foreach ($dates as $date) {
            if ($this->availabilityService->hasConflict($date, $lessonTemplate)) {
                $conflicts[] = $date;
            }
        }

        if (! empty($conflicts)) {
            return back()
                ->withInput()
                ->with('error', '以下の日付は既存のレッスン枠と重複しています: '.implode(', ', $conflicts));
        }

        DB::transaction(function () use ($dates, $lessonTemplate, $location) {
            foreach ($dates as $date) {
                $lessonSlot = new LessonSlot(['date' => $date]);
                $lessonSlot->lessonTemplate()->associate($lessonTemplate);
                if ($location) {
                    $lessonSlot->location()->associate($location);
                }
                $lessonSlot->save();
            }
        });

        return redirect()
            ->route('pilates.admin.lesson-slots.index')
            ->with('message', count($dates).'件のレッスン枠を作成しました。');
    }

    public function edit(LessonSlot $lessonSlot)
    {
        $lessonTemplates = LessonTemplate::where('is_active', true)
            ->orderBy('start_time')
            ->get();
        $locations = Location::where('is_active', true)->orderBy('name')->get();

        $lessonSlot->load('lessonTemplate', 'location:id,name');

        return Inertia::render('Admin/Pilates/LessonSlots/Edit', compact('lessonSlot', 'lessonTemplates', 'locations'));
    }

    public function update(UpdateLessonSlotRequest $request, LessonSlot $lessonSlot)
    {
        $lessonSlot->fill($request->safe()->only('date'));

        $lessonTemplate = LessonTemplate::findOrFail($request->validated('lesson_template_id'));
        $lessonSlot->lessonTemplate()->associate($lessonTemplate);

        $locationId = $request->validated('location_id');
        $lessonSlot->location()->associate($locationId ? Location::findOrFail($locationId) : null);
        $lessonSlot->save();

        return redirect()
            ->route('pilates.admin.lesson-slots.index')
            ->with('message', 'レッスン枠を更新しました。');
    }

    public function destroy(LessonSlot $lessonSlot)
    {
        $deleted = DB::connection('client_db')->transaction(function () use ($lessonSlot) {
            $slot = LessonSlot::query()->lockForUpdate()->findOrFail($lessonSlot->id);

            $hasActiveReservation = $slot->reservations()
                ->whereNotIn('status', [
                    ReservationStatus::Canceled,
                    ReservationStatus::Rescheduled,
                ])
                ->exists();

            return ! $hasActiveReservation && $slot->delete();
        });

        if (! $deleted) {
            return redirect()
                ->route('pilates.admin.lesson-slots.index')
                ->with('error', 'このレッスン枠は予約が入っているため削除できません。');
        }

        return redirect()
            ->route('pilates.admin.lesson-slots.index')
            ->with('message', 'レッスン枠を削除しました。');
    }
}
