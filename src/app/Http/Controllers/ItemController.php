<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;

class ItemController extends Controller
{
    public function listItems(Request $request)
    {
        $tab = $request->query('tab', 'recommend');

        if ($tab === 'mylist') {
            return $this->listMyListItems($request);
        }

        $user = Auth::user();
        $query = Item::query()
            ->where(function ($query) use ($user) {
                $query->where('status', '出品中')
                    ->where('user_id', '!=', $user ? $user->id : null)
                    ->orWhere(function ($q) use ($user) {
                        $q->where('status', '売却済み')
                        ->where('user_id', '!=', $user ? $user->id : null);
                    });
            });

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
        $conditions = Item::getEnumValues('condition');
        return view('items.create', compact('categories', 'conditions'));
    }

    public function createListing(Request $request)
    {
        $validatedData = $request->validate([
            'item_image' => 'required|image|mimes:jpeg,png|max:2048',
            'selected_category' => 'required',
            'condition' => 'required|in:新品,未使用,目立った傷や汚れなし,傷や汚れあり,全体的に状態が悪い',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        // 画像の保存
        $imagePath = $request->file('item_image')->store('item_images', 'public');

        // 商品の保存
        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $validatedData['name'],
            'img_url' => '/storage/' . $imagePath,
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'condition' => $validatedData['condition'],
            'status' => '出品中',
        ]);

        // カテゴリーの保存
        $categoryIds = explode(',', $validatedData['selected_category']);
        $item->categories()->attach($categoryIds);

        return redirect()->route('items.show', $item->id)->with('success', '商品を出品しました');
    }

    public function processPurchase($item_id)
    {
        $item = Item::findOrFail($item_id);
        $item->status = 'sold';
        $item->save();
    }
}