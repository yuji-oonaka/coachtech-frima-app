<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;


class PurchaseController extends Controller
{
    public function showPurchaseForm($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $shippingAddress = session('shipping_address');

        if (!$shippingAddress) {
            $address = $user->address;
            if ($address) {
                $shippingAddress = [
                    'postal_code' => $address->postal_code,
                    'address' => $address->address,
                    'building' => $address->building ?? null
                ];
            } else {
                // ユーザーがアドレスを設定していない場合
                return redirect()->route('profile.edit')->with('error', 'プロフィールの設定が必要です。');
            }
            session(['shipping_address' => $shippingAddress]);
        }

        return view('purchases.show', compact('item', 'shippingAddress'));
    }

    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $address = $user->address;
        return view('purchases.address-edit', compact('item', 'user', 'address'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        // 住所の更新または新規作成
        $address = Address::updateOrCreate(
            ['user_id' => $user->id],
            [
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building
            ]
        );

        // 購入時に使用する住所情報をセッションに保存
        session([
            'shipping_address' => [
                'postal_code' => $address->postal_code,
                'address' => $address->address,
                'building' => $address->building
            ]
        ]);

        return redirect()->route('purchase.show', $item_id)->with('success', '送付先住所を更新しました');
    }

    public function processPurchase(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $shippingAddress = session('shipping_address');

        if (!$shippingAddress) {
            $address = $user->address;
            $shippingAddress = [
                'postal_code' => $address->postal_code,
                'address' => $address->address,
                'building' => $address->building
            ];
        }

        // 支払い方法のバリデーション
        $validPaymentMethods = ['コンビニ支払い', 'クレジットカード'];
        $paymentMethod = in_array($request->payment_method, $validPaymentMethods)
            ? $request->payment_method
            : 'コンビニ支払い'; // デフォルト値

        $purchase = Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => $paymentMethod,
            'shipping_postal_code' => $shippingAddress['postal_code'],
            'shipping_address' => $shippingAddress['address'],
            'shipping_building' => $shippingAddress['building'] ?? null,
            'status' => '支払い済み'
        ]);

        $item->status = '売却済み';
        $item->save();

        return redirect()->route('profile.show', ['tab' => 'buy'])->with('success', '商品を購入しました');
    }
}
