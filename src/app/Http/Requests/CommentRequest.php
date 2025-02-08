<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 認可が必要な場合はロジックを実装
    }

    public function rules(): array
    {
        return [
            'content' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'コメントを入力してください',
            'content.max' => 'コメントは255文字以内で入力してください'
        ];
    }
}
