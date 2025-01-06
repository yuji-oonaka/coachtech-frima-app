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
            'profile_image' => 'nullable|image|mimes:jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'profile_image.image' => 'プロフィール画像は画像ファイルである必要があります。',
            'profile_image.mimes' => 'プロフィール画像は.jpegまたは.png形式である必要があります。',
        ];
    }
}
