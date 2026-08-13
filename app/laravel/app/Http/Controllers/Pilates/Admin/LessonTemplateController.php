<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pilates\Admin\StoreLessonTemplateRequest;
use App\Http\Requests\Pilates\Admin\UpdateLessonTemplateRequest;
use App\Models\Pilates\LessonTemplate;

class LessonTemplateController extends Controller
{
    public function index()
    {
        $lessonTemplates = LessonTemplate::orderBy('start_time')->get();

        return view('pages.pilates.admin.lesson-templates.index', compact('lessonTemplates'));
    }

    public function create()
    {
        return view('pages.pilates.admin.lesson-templates.create');
    }

    public function store(StoreLessonTemplateRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        LessonTemplate::create($data);

        return redirect()
            ->route('pilates.admin.lesson-templates.index')
            ->with('message', 'レッスン時間帯テンプレートを作成しました。');
    }

    public function edit(LessonTemplate $lessonTemplate)
    {
        return view('pages.pilates.admin.lesson-templates.edit', compact('lessonTemplate'));
    }

    public function update(UpdateLessonTemplateRequest $request, LessonTemplate $lessonTemplate)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
    
        $lessonTemplate->update($data);
    
        return redirect()
            ->route('pilates.admin.lesson-templates.index')
            ->with('message', 'レッスン時間帯テンプレートを更新しました。');
    }
    public function destroy(LessonTemplate $lessonTemplate)
    {
        if ($lessonTemplate->lessonSlots()->exists()) {
            return redirect()
                ->route('pilates.admin.lesson-templates.index')
                ->with('error', 'このテンプレートは使用中のため削除できません。');
        }
    
        $lessonTemplate->delete();
    
        return redirect()
            ->route('pilates.admin.lesson-templates.index')
            ->with('message', 'テンプレートを削除しました。');
    }
}
