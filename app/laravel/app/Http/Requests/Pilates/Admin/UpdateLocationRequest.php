<?php

namespace App\Http\Requests\Pilates\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLocationRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'price_addon_per_session' => ['nullable', 'integer', 'min:0'],
            'base_fee' => ['nullable', 'integer', 'min:0'],
            'map_url' => ['nullable', 'url', 'max:255'],
            'is_bookable' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }
 
    public function attributes(): array
    {
        return [
            'name' => '名称',
            'address' => '住所',
            'price_addon_per_session' => '加算料金',
            'base_fee' => '基本料金',
            'map_url' => '地図URL',
            'is_bookable' => '予約可否',
            'is_active' => '有効フラグ',
        ];
    }
    public function messages()
    {
        return[
            'name.required' => '名称を入力してください',
            'name.string' => '名称を正しく入力してください',
            'name.max' => '名称は２５５字以内で入力してください',
            'address.required' => '住所を入力してください',
            'address.string' => '住所を正しく入力してください',
            'address.max' => '住所は２５５字以内で入力してください',
            'price_addon_per_session.integer' => '加算料金は整数で入力してください',
            'price_addon_per_session.min' => '加算料金は０円以上で入力してください',
            'base_fee.integer' => '基本料金は整数で入力してください',
            'base_fee.min' => '基本料金は０円以上で入力してください',
            'map_url.url' =>'地図URLはURL形式で入力してください',
            'map_url.max' =>'地図URLは２５５字以内で入力してください',

        ];
    }
}
