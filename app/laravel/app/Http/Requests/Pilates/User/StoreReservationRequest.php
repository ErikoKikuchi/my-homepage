<?php

namespace App\Http\Requests\Pilates\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;


class StoreReservationRequest extends FormRequest
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
    protected function prepareForValidation(): void
    {
        if ($this->filled('phone')) {
            $this->merge([
                'phone' => $this->normalizePhone($this->input('phone')),
            ]);
        }
    
        // participants_phoneは複数人分が混在しうる自由記述のため、正規化のみ行い、
        // 形式(regex)チェックは行わない
        if ($this->filled('participants_phone')) {
            $this->merge([
                'participants_phone' => $this->normalizeParticipantsPhone($this->input('participants_phone')),
            ]);
        }
    }
    
    private function normalizePhone(string $value): string
    {
        $value = mb_convert_kana($value, 'n');
        $value = preg_replace('/[\x{2010}-\x{2015}\x{2212}\x{FF0D}\x{30FC}-]/u', '', $value);
        return trim($value);
    }
    
    private function normalizeParticipantsPhone(string $value): string
    {
        // 全角数字→半角、全角/半角ハイフンを半角ハイフンへ統一(区切りとして温存する必要があるため完全除去はしない)
        $value = mb_convert_kana($value, 'n');
        $value = preg_replace('/[\x{2010}-\x{2015}\x{2212}\x{FF0D}\x{30FC}]/u', '-', $value);
        return trim($value);
    }
    public function rules(): array
    {
        /** @var \App\Models\Auth\User|null $user */
        $user = $this->user();
        return [
            'participants'=>['required','in:1,2,3,4'],
            'participants_name'=>['nullable','string','max:100'],
            'participants_phone'=>['nullable','string','max:255'],
            'note'=>['nullable','string','max:255'],
            'cancelled_reason'=>['nullable','string','max:255'],
            'date'        => ['required', 'date_format:Y-m-d'],
            'time'        => ['required', 'date_format:H:i'],
            'phone' => [
                Rule::requiredIf(fn () => empty($user?->phone)),
                'nullable',
                'string',
                'regex:/^0\d{9,10}$/', // 例: 日本の電話番号形式
],
        ];
    }
    public function messages()
    {
        return[
            'participants.required'=>'参加人数を入力してください',
            'participants.in'=>'参加人数を入力してください',
            'participants_name.string'=>'参加者名を入力してください',
            'participants_name.max'=>'参加者名は100文字以内で入力してください',
            'note.string'=>'備考を正しく入力してください',
            'note.max'=>'備考は２５５文字以内で入力してください',
            'cancelled_reason'=>'キャンセル理由は文字列で入力してください',
            'cancelled_reason.max'=>'キャンセル理由は２５５文字以内で入力してください',
            'time.required'=>'時間を選択してください',
            'time.date_format'=>'時間を正しく選択してください',
            'phone.string'=>'電話番号を正しく入力してください',
            'phone.regex'=>'電話番号は９桁か１０桁で入力してください',
        ];
    }
}
