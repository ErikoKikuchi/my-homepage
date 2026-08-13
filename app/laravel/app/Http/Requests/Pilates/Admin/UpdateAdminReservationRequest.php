<?php

namespace App\Http\Requests\Pilates\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminReservationRequest extends FormRequest
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
        'date'                => ['required', 'date', 'after_or_equal:today'],
        'lesson_template_id'  => ['required', 'uuid', 'exists:client_db.lesson_templates,id'],
        'location_id'         => ['nullable', 'uuid', 'exists:client_db.locations,id'],
    ];
    }
}
