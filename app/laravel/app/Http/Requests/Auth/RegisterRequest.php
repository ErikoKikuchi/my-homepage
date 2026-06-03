<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            "name"=> ['required', 'string', 'max:20'],
            "email"=> [
                'required',
                'unique:users,email',
                'string',
                'email',
                'max:255',
                ],
            "password"=> ['required','min:12','string','confirmed'],
            'is_medical' => ['required','boolean','in:0,1'],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required'=>'お名前を入力してください',
            'name.string'=>'お名前を正しく入力してください',
            'name.max'=>'お名前は２０文字以内で入力してください',
            'email.required'=>'メールアドレスを入力してください',
            'email.unique'=>'そのメールアドレスは既に登録されています',
            'email.string'=>'メールアドレスを正しく入力してください',
            'email.email'=>'メールアドレスはメール形式で入力してください',
            'email.max'=>'メールアドレスは２５５文字以内で入力してください',
            'password.required'=>'パスワードを入力してください',
            'password.min'=>'パスワードは12文字以上で入力してください',
            'password.string'=>'パスワードを正しく入力してください',
            'password_confirmation.required'=>'確認用パスワードを入力してください',
            'password_confirmation.min'=>'パスワードは12文字以上で入力してください',
            'password.confirmed'=>'パスワードと一致しません',
            'is_medical.required'=>'選択してください',
            'is_medical.in'=>'選択してください',
        ];
    }
}
