<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前は必須です。',
            'profile_image.image' => 'プロフィール画像は画像ファイルである必要があります。',
            'profile_image.mimes' => 'プロフィール画像は.jpegまたは.png形式である必要があります。',
        ];
    }
}
