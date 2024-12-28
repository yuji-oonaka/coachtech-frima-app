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
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $query = Auth::user()->likedItems();

        // 検索キーワードが存在する場合、マイリストでも検索を適用
        if ($request->filled('keyword')) {
            $query->where('name', 'like', "%{$request->keyword}%");
        }

        $items = $query->latest()->get();

        return view('items.index', [
            'items' => $items,
            'tab' => 'mylist',
            'keyword' => $request->keyword  // 検索キーワードをビューに渡す
        ]);
    }

    public function searchItems(Request $request)
    {
        $tab = $request->get('tab', 'recommend');

        if ($tab === 'mylist') {
            if (!Auth::check()) {
                return redirect()->route('login');
            }
            
            $query = Auth::user()->likedItems();
        } else {
            $query = Item::query()->whereNot('user_id', Auth::id());
        }

        if ($request->filled('keyword')) {
            $query->where('name', 'like', "%{$request->keyword}%");
        }

        $items = $query->latest()->get();

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
