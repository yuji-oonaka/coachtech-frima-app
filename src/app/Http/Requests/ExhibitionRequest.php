<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Item;

class ExhibitionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'item_image' => 'required|image|mimes:jpeg,png|max:2048',
            'selected_category' => 'required',
            'condition' => 'required|in:' . implode(',', Item::getEnumValues('condition')),
            'price' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名は必須です。',
            'description.required' => '商品説明は必須です。',
            'description.max' => '商品説明は255文字以内で入力してください。',
            'item_image.required' => '商品画像は必須です。',
            'item_image.image' => '商品画像は画像ファイルである必要があります。',
            'item_image.mimes' => '商品画像はjpegまたはpng形式である必要があります。',
            'selected_category.required' => '商品のカテゴリーは必須です。',
            'condition.required' => '商品の状態は必須です。',
            'condition.in' => '選択された商品の状態が無効です。',
            'price.required' => '商品価格は必須です。',
            'price.numeric' => '商品価格は数値で入力してください。',
            'price.min' => '商品価格は0円以上で入力してください。',
        ];
    }
}
