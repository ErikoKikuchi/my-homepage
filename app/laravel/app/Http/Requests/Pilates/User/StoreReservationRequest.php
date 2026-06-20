<?php

namespace App\Http\Requests\Pilates\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

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
    public function rules(): array
    {
        return [
            'participants'=>['required','in:1,2,3,4'],
            'participants_name'=>['nullable','string','max:100'],
            'note'=>['nullable','string','max:255'],
            'cancelled_reason'=>['nullable','string','max:255'],
            'date'        => ['required', 'date_format:Y-m-d'],
            'time'        => ['required', 'date_format:H:i'],
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
            
        ];
    }
}
