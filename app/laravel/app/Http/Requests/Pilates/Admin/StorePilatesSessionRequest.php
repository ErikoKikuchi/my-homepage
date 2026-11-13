<?php

namespace App\Http\Requests\Pilates\Admin;

use App\Models\Pilates\PilatesExercise;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePilatesSessionRequest extends FormRequest
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
            'reservation_id' => ['required', 'uuid'],
            'is_draft' => ['required', 'boolean'],
            'notes' => ['nullable', 'string', 'max:16000'],
            'exercises' => ['sometimes', 'array'],
            'exercises.*' => ['array:exercise_id,custom_name,comment'],
            'exercises.*.exercise_id' => ['bail', 'nullable', 'uuid', Rule::exists(PilatesExercise::class, 'id')],
            'exercises.*.custom_name' => ['nullable', 'string', 'max:16000'],
            'exercises.*.comment' => ['nullable', 'string', 'max:16000'],
        ];
    }
}
