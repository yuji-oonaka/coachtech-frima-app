<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function showPurchaseForm($item_id)
    {
        $item = Item::findOrFail($item_id);
        $address = Auth::user()->address;
        return view('purchases.show', compact('item', 'address'));
    }

    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $address = $user->address;
        return view('purchases.address-edit', compact('item', 'user', 'address'));
    }

    public function updateAddress(Request $request, $item_id)
    {
        // バリデーションと住所更新のロジックを実装
        // 成功したら購入画面にリダイレクト
        return redirect()->route('purchase.show', $item_id)->with('success', '送付先住所を更新しました');
    }
}
