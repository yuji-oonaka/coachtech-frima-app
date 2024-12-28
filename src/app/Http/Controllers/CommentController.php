<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|max:255',
            'item_id' => 'required|exists:items,id'
        ], [
            'content.required' => 'コメントを入力してください',
            'content.max' => 'コメントは255文字以内で入力してください'
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $request->item_id,
            'content' => $request->content
        ]);

        return back()->with('success', 'コメントを投稿しました');
    }
}
