<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike($itemId)
    {
        $user = Auth::user();
        $item = Item::findOrFail($itemId);
        $isLiked = false;
        $likeCount = 0;

        if ($user->likedItems()->where('item_id', $itemId)->exists()) {
            $user->likedItems()->detach($itemId);
            $action = 'unliked';
        } else {
            $user->likedItems()->attach($itemId);
            $action = 'liked';
        }

        $likeCount = $item->likes()->count();

        return response()->json([
            'action' => $action,
            'likeCount' => $likeCount
        ]);
    }
}
