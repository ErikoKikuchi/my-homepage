<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AdminTwoFactorRequest extends FormRequest
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
            'two_factor_secret'=>['required','digits:6'],
        ];
    }
    public function messages()
    {
        return [
            'two_factor_secret.required'=> '6桁の数字を入力してください',
            'two_factor_secret.digits'=>'6桁の数字を入力してください',
        ];
    }
}