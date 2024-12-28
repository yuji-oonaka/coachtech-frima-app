<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike($itemId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'ログインが必要です'
            ], 401);
        }

        try {
            $user = Auth::user();
            $like = Like::where('user_id', $user->id)
                    ->where('item_id', $itemId)
                    ->first();

            if ($like) {
                $like->delete();
                $action = 'unliked';
            } else {
                Like::create([
                    'user_id' => $user->id,
                    'item_id' => $itemId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $action = 'liked';
            }

            $likeCount = Like::where('item_id', $itemId)->count();

            return response()->json([
                'success' => true,
                'action' => $action,
                'likeCount' => $likeCount
            ]);
        } catch (\Exception $e) {
            \Log::error('いいね処理エラー: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'いいねの処理中にエラーが発生しました'
            ], 500);
        }
    }
}
