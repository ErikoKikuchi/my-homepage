<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pilates\Admin\StoreLessonSlotRequest;
use App\Http\Requests\Pilates\Admin\UpdateLessonSlotRequest;
use App\Models\Pilates\LessonSlot;
use App\Models\Pilates\LessonTemplate;
use App\Models\Pilates\Location;
use Illuminate\Support\Facades\DB;
use App\Services\Pilates\AdminReservationAvailabilityService;

class LessonSlotController extends Controller
{
    public function __construct(
        private AdminReservationAvailabilityService $availabilityService
    ){}
    public function index()
    {
        $lessonSlots = LessonSlot::with('lessonTemplate')
        ->where('date', '>=', now()->startOfDay())
        ->orderBy('date')->get();
    
        return view('pages.pilates.admin.lesson-slots.index', compact('lessonSlots'));
    }
    public function create()
    {
        $lessonTemplates = LessonTemplate::where('is_active', true)
            ->orderBy('start_time')
            ->get();
        $locations = Location::where('is_active', true)->orderBy('name')->get(); 

        return view('pages.pilates.admin.lesson-slots.create', compact('lessonTemplates', 'locations'));
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
    
        return view('pages.pilates.admin.lesson-slots.edit', compact('lessonSlot', 'lessonTemplates','locations'));
    }

    public function update(UpdateLessonSlotRequest $request, LessonSlot $lessonSlot)
    {
        $lessonSlot->fill($request->safe()->only('date'));

        $lessonTemplate = LessonTemplate::findOrFail($request->validated('lesson_template_id'));
        $lessonSlot->lessonTemplate()->associate($lessonTemplate);
    
        if ($request->validated('location_id')) {
            $location = Location::findOrFail($request->validated('location_id'));
            $lessonSlot->location()->associate($location);
        } 
        $lessonSlot->save();


        return redirect()
            ->route('pilates.admin.lesson-slots.index')
            ->with('message', 'レッスン枠を更新しました。');
    }
    public function destroy(LessonSlot $lessonSlot)
    {
        if ($lessonSlot->reservations()->exists()) {
            return redirect()
                ->route('pilates.admin.lesson-slots.index')
                ->with('error', 'このレッスン枠は予約が入っているため削除できません。');
        }
    
        $lessonSlot->delete();
    
        return redirect()
            ->route('pilates.admin.lesson-slots.index')
            ->with('message', 'レッスン枠を削除しました。');
    }
}
