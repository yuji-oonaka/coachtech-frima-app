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
        $prefix = $this->input('prefix', '');
        return [
            $prefix.'postal_code' => 'required|string|regex:/^\d{3}-\d{4}$/',
            $prefix.'address' => 'required|string|max:255',
            $prefix.'building' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        $prefix = $this->input('prefix', '');
        return [
            $prefix.'postal_code.required' => '郵便番号は必須です。',
            $prefix.'postal_code.regex' => '郵便番号はハイフンありの8文字で入力してください。',
            $prefix.'address.required' => '住所は必須です。',
        ];
    }

    protected function prepareForValidation()
    {
        $prefix = $this->input('prefix', '');
        if ($prefix && !str_ends_with($prefix, '_')) {
            $this->merge(['prefix' => $prefix . '_']);
        }
    }
}
