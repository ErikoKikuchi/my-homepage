<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pilates\Admin\StoreLessonSlotRequest;
use App\Http\Requests\Pilates\Admin\UpdateLessonSlotRequest;
use App\Models\Pilates\LessonSlot;
use App\Models\Pilates\LessonTemplate;

class LessonSlotController extends Controller
{
    public function index()
    {
        $lessonSlots = LessonSlot::with('lessonTemplate')
        ->whereNowOrFuture('date')
        ->orderBy('date')->get();
    
        return view('pages.pilates.admin.lesson-slots.index', compact('lessonSlots'));
    }
    public function create()
    {
        $lessonTemplates = LessonTemplate::where('is_active', true)
            ->orderBy('start_time')
            ->get();
    
        return view('pages.pilates.admin.lesson-slots.create', compact('lessonTemplates'));
    }

    public function store(StoreLessonSlotRequest $request)
    {
        $lessonSlot = new LessonSlot($request->safe()->only('date'));
        $lessonTemplate = LessonTemplate::findOrFail($request->validated('lesson_template_id'));
        $lessonSlot->lessonTemplate()->associate($lessonTemplate);
    
        $lessonSlot->save();

        return redirect()
            ->route('pilates.admin.lesson-slots.index')
            ->with('message', 'レッスン枠を作成しました。');
    }

    public function edit(LessonSlot $lessonSlot)
    {
        $lessonTemplates = LessonTemplate::where('is_active', true)
            ->orderBy('start_time')
            ->get();
    
        return view('pages.pilates.admin.lesson-slots.edit', compact('lessonSlot', 'lessonTemplates'));
    }

    public function update(UpdateLessonSlotRequest $request, LessonSlot $lessonSlot)
    {
        $lessonSlot->fill($request->safe()->only('date'));

        $lessonTemplate = LessonTemplate::findOrFail($request->validated('lesson_template_id'));
        $lessonSlot->lessonTemplate()->associate($lessonTemplate);
    
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
