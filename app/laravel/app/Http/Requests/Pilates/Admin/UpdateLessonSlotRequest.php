<?php

namespace App\Http\Requests\Pilates\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonSlotRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date'=>['required','date_format:Y-m-d'],
            'lesson_template_id' => ['required', 'uuid', 'exists:client_db.lesson_templates,id'],
        ];
    }
    public function messages(): array
    {
        return [
            'date.required'=>'日付を入力してください',
            'date.date_format' => '日付の形式が正しくありません（例: 2025-04-07）',
        ];
    }
}