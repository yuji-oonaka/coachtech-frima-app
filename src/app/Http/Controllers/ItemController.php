<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function listItems(Request $request)
    {
        $tab = $request->query('tab', 'recommend');

        if ($tab === 'mylist') {
            return $this->listMyListItems($request);
        }

        $query = Item::query()
            ->whereNot('user_id', Auth::id())
            ->where('status', '出品中');

        $items = $query->latest()->get();

        return view('items.index', [
            'items' => $items,
            'tab' => 'recommend'
        ]);
    }

    public function listMyListItems(Request $request)
    {
        $items = collect([]);
        $keyword = $request->keyword;

        if (Auth::check()) {
            $query = Auth::user()->likedItems();
            if ($request->filled('keyword')) {
                $query->where('name', 'like', "%{$keyword}%");
            }
            $items = $query->latest()->get();
        }

        return view('items.index', [
            'items' => $items,
            'tab' => 'mylist',
            'keyword' => $keyword
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

    public function displayItemDetails($item_id)
    {
        $item = Item::with(['categories', 'comments.user', 'likes'])->findOrFail($item_id);
        $likeCount = $item->likes->count();
        $commentCount = $item->comments->count();
        $isLiked = Auth::check() ? $item->likes->contains('user_id', Auth::id()) : false;

        return view('items.show', compact('item', 'likeCount', 'commentCount', 'isLiked'));
    }

    public function showSellForm()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function createListing(Request $request)
    {
        // バリデーションと商品登録のロジックをここに実装
        // 成功したら商品詳細ページにリダイレクト
    }
}
