<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function postComment(Request $request, $item_id)
    {
        $request->validate([
            'content' => 'required|max:255',
        ], [
            'content.required' => 'コメントを入力してください',
            'content.max' => 'コメントは255文字以内で入力してください'
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $item_id,
            'content' => $request->content
        ]);

        return back()->with('success', 'コメントを投稿しました');
    }
}
