<?php

namespace App\Http\Requests\Pilates\User;

use App\Services\Pilates\PhoneNumberNormalizerService;
use Illuminate\Foundation\Http\FormRequest;
class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user('web');

        return $user !== null
            && $user->hasVerifiedEmail()
            && $user->client !== null;
    }

    protected function prepareForValidation(): void
    {
        $phone = $this->input('phone');

        if (is_string($phone) && trim($phone) !== '') {
            $this->merge([
                'phone' => app(PhoneNumberNormalizerService::class)
                    ->normalize($phone),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date_format:Y-m-d'],
            'time' => ['required', 'date_format:H:i'],
            'note' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable','string','max:255','regex:/\A0[0-9]+\z/',],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => '日付を選択してください',
            'date.date_format' => '日付を正しく選択してください',
            'time.required' => '時間を選択してください',
            'time.date_format' => '時間を正しく選択してください',
            'note.string' => '備考を正しく入力してください',
            'note.max' => '備考は255文字以内で入力してください',
            'phone.required' => '電話番号を入力してください',
            'phone.string' => '電話番号を正しく入力してください',
            'phone.max' => '電話番号を正しく入力してください',
            'phone.regex' => '電話番号を正しく入力してください',
        ];
    }
}