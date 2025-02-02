<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payment_method' => 'required|in:カード支払い,コンビニ支払い',
            'shipping_postal_code' => 'required|string|regex:/^\d{3}-\d{4}$/',
            'shipping_address' => 'required|string|max:255',
            'shipping_building' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください。',
            'payment_method.in' => '無効な支払い方法が選択されました。',
            'shipping_postal_code.required' => '配送先の郵便番号を入力してください。',
            'shipping_postal_code.regex' => '郵便番号は123-4567の形式で入力してください。',
            'shipping_address.required' => '配送先の住所を入力してください。',
        ];
    }
}
