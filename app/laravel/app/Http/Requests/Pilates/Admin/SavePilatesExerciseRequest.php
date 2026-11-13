<?php

namespace App\Http\Requests\Pilates\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SavePilatesExerciseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'supplement' => ['nullable', 'string', 'max:16000'],
            'precautions' => ['nullable', 'string', 'max:16000'],
            'photos' => ['sometimes', 'array:0,1,2'],
            'photos.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_photos' => ['sometimes', 'array:0,1,2'],
            'remove_photos.*' => ['boolean'],
        ];
    }
}
