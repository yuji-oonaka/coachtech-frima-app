<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function listItems()
    {
        $query = Item::query()
            ->whereNot('user_id', Auth::id());

        $items = $query->latest()->get();

        return view('items.index', [
            'items' => $items,
            'tab' => 'recommend'
        ]);
    }

    public function listMyListItems(Request $request)
    {
        // 認証状態に関わらず、空のコレクションを初期値として設定
        $items = collect([]);

        // 認証済みの場合のみ、マイリストのアイテムを取得
        if (Auth::check()) {
            $query = Auth::user()->likedItems();
            if ($request->filled('keyword')) {
                $query->where('name', 'like', "%{$request->keyword}%");
            }
            $items = $query->latest()->get();
        }

        return view('items.index', [
            'items' => $items,
            'tab' => 'mylist',
            'keyword' => $request->keyword
        ]);
    }

    public function searchItems(Request $request)
    {
        $tab = $request->get('tab', 'recommend');
        $items = collect([]);

        if ($tab === 'mylist') {
            if (Auth::check()) {
                $query = Auth::user()->likedItems();
                if ($request->filled('keyword')) {
                    $query->where('name', 'like', "%{$request->keyword}%");
                }
                $items = $query->latest()->get();
            }
        } else {
            $query = Item::query()->whereNot('user_id', Auth::id());
            if ($request->filled('keyword')) {
                $query->where('name', 'like', "%{$request->keyword}%");
            }
            $items = $query->latest()->get();
        }

        return view('items.index', [
            'items' => $items,
            'keyword' => $request->keyword,
            'tab' => $tab,
            'isSearchResult' => true
        ]);
    }


    public function show($id)
    {
        $item = Item::with(['categories', 'comments.user', 'likes'])->findOrFail($id);
        $likeCount = $item->likes->count();
        $commentCount = $item->comments->count();
        $isLiked = false;

        if (Auth::check()) {
            $isLiked = $item->likes->where('user_id', Auth::id())->count() > 0;
        }

        return view('items.show', compact('item', 'likeCount', 'commentCount', 'isLiked'));
    }
}
