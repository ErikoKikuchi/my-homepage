<?php

namespace App\Http\Requests\Pilates\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
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
            'user_id' => [
                'required',
                'uuid',
                Rule::exists('users', 'id')->where('is_pilates_user', true),
            ],
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
        ];
    }
    public function messages(): array
    {
        return [
            'user_id.required' => 'ユーザーが指定されていません。',
            'user_id.exists' => '対象のユーザーが見つかりません。',
            'gender.required' => '性別を選択してください。',
            'gender.in' => '性別の値が不正です。',
        ];
    }
}
