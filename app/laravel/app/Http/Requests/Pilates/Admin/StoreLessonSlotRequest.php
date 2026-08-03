<?php

namespace App\Http\Requests\Pilates\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLessonSlotRequest extends FormRequest
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
            'dates'=> ['required', 'array', 'min:1'],
            'dates.*' => ['date', 'after_or_equal:today'],
            'lesson_template_id' => ['required', 'uuid', 'exists:client_db.lesson_templates,id'],
            'location_id' => ['nullable', 'uuid', 'exists:client_db.locations,id'],
        ];
    }
    public function messages(): array
    {
        return [
            'dates.required'=>'日付を入力してください',
            'dates.*.date' => '日付の形式が正しくありません',
        ];
    }
}
