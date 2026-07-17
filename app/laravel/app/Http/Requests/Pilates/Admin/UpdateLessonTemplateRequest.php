<?php

namespace App\Http\Requests\Pilates\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Pilates\LessonTemplate;

class UpdateLessonTemplateRequest extends FormRequest
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
            'start_time'=>['required', 'date_format:H:i'],
            'end_time'=>[
                'required',
                'after:start_time', 
                'date_format:H:i',
                Rule::unique(LessonTemplate::class, 'end_time')
                ->where(fn ($query) => $query->where('start_time', $this->start_time))->ignore($this->route('lesson_template')),
            ],
            'is_active' => ['boolean'],
        ];
    }
    public function messages()
    {
        return[
            'start_time.required'=> '開始時間を入力してください。',
            'start_time.date_format'=> '正しい時間形式で入力してください。',
            'end_time.required'=> '終了時間を入力してください。',
            'end_time.date_format'=> '正しい時間形式で入力してください。',
            'end_time.after'=> '終了時間もしくは開始時間が不適切な値です。',
            'end_time.unique' => '同じ時間帯のテンプレートが既に存在します。',
        ];
    }
}
