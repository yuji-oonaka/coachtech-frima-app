<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'postal_code' => 'required|string|regex:/^\d{3}-\d{4}$/',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前は必須です。',
            'postal_code.required' => '郵便番号は必須です。',
            'postal_code.regex' => '郵便番号はハイフンありの8文字で入力してください。',
            'address.required' => '住所は必須です。',
        ];
    }
}
