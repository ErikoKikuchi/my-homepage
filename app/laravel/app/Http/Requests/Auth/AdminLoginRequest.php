<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
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
            'email' => [
                'required',
                'email',
                ],
            'password'=>['required','min:12','string'],
        ];
    }
    public function messages()
    {
        return [
            'email.required'=>'メールアドレスを入力してください',
            'email.email'=>'メールアドレスはメール形式で入力してください',
            'password.required'=>'パスワードを入力してください',
            'password.min'=>'パスワードは12文字以上で入力してください',
            'password.string'=>'パスワードを正しく入力してください',
        ];
    }
    public function authenticate(string $section): void
    {
        $credentials = $this->only('email', 'password');

        if (! Auth::guard('admin')->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $admin = Auth::guard('admin')->user();

        if (! $admin->sections->contains('key', $section)) {
            Auth::guard('admin')->logout();

            throw ValidationException::withMessages([
                'email' => __('このセクションへのアクセス権限がありません'),
            ]);
        }

        $this->session()->regenerate();
    }
}
