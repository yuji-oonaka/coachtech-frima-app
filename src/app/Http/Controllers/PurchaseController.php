<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
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

        if ($request->payment_method === 'クレジットカード') {
            return $this->processStripePayment($item, $user, $shippingAddress);
        }

        // コンビニ支払いの場合は既存の処理を実行
        return $this->processConveniencePayment($request, $item, $user, $shippingAddress);
    }

    private function processStripePayment($item, $user, $shippingAddress)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success', ['item_id' => $item->id]),
            'cancel_url' => route('purchase.show', ['item_id' => $item->id]),
            'metadata' => [
                'item_id' => $item->id,
                'user_id' => $user->id,
                'shipping_postal_code' => $shippingAddress['postal_code'],
                'shipping_address' => $shippingAddress['address'],
                'shipping_building' => $shippingAddress['building'] ?? '',
            ],
        ]);

        return redirect($session->url);
    }

    private function processConveniencePayment($request, $item, $user, $shippingAddress)
    {
        // 既存のコンビニ支払い処理
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'コンビニ支払い',
            'shipping_postal_code' => $shippingAddress['postal_code'],
            'shipping_address' => $shippingAddress['address'],
            'shipping_building' => $shippingAddress['building'] ?? null,
            'status' => '支払い済み'
        ]);

        $item->status = '売却済み';
        $item->save();

        return redirect()->route('profile.show', ['tab' => 'buy'])
            ->with('success', '商品を購入しました');
    }

    public function handleStripeSuccess(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $shippingAddress = session('shipping_address');

        $purchase = Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'クレジットカード',
            'shipping_postal_code' => $shippingAddress['postal_code'],
            'shipping_address' => $shippingAddress['address'],
            'shipping_building' => $shippingAddress['building'] ?? null,
            'status' => '支払い済み'
        ]);

        $item->status = '売却済み';
        $item->save();

        return redirect()->route('profile.show', ['tab' => 'buy'])
            ->with('success', '商品を購入しました');
    }
}