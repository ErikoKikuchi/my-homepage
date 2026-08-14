<?php

namespace App\Http\Requests\Pilates\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
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
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'line_linked' => ['sometimes', 'required', 'boolean'],
            'is_active' => ['sometimes', 'required', 'boolean'],
        ];
    }
    public function messages()
    {
        return [
            'name.required'=>'氏名を入力してください'
        ];
    }
}
