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
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{
    public function showPurchaseForm($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $shippingAddress = session('shipping_address');

        if ($item->user_id === Auth::id()) {
            return redirect()->route('items.show', $item_id)
                ->with('error', '自分が出品した商品は購入できません');
        }

        if (!$shippingAddress) {
            $shippingAddress = $this->getDefaultShippingAddress();
            if (!$shippingAddress) {
                return redirect()->route('profile.edit')->with('error', 'プロフィールの設定が必要です。');
            }
            session(['shipping_address' => $shippingAddress]);
        }

        return view('purchases.show', compact('item', 'shippingAddress'));
    }

    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $shippingAddress = session('shipping_address') ?? $this->getDefaultShippingAddress();
        return view('purchases.address-edit', compact('item', 'shippingAddress'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        $validatedData = $request->validated();

        session([
            'shipping_address' => [
                'postal_code' => $validatedData['shipping_postal_code'],
                'address' => $validatedData['shipping_address'],
                'building' => $validatedData['shipping_building'] ?? null
            ]
        ]);

        return redirect()->route('purchase.show', $item_id)->with('success', '送付先住所を更新しました');
    }

    public function processPurchase(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $shippingAddress = session('shipping_address');

        if (!$shippingAddress) {
            return redirect()->route('purchase.show', $item_id)->with('error', '配送先住所が設定されていません。');
        }

        if ($request->payment_method === 'クレジットカード') {
            return $this->processStripePayment($item, $user, $shippingAddress);
        }

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
        $purchase = $this->savePurchase($user, $item, 'コンビニ支払い', $shippingAddress);

        $item->status = '売却済み';
        $item->save();

        session()->forget('shipping_address');

        return redirect()->route('profile.show', ['tab' => 'buy'])
            ->with('success', '商品を購入しました');
    }

    public function handleStripeSuccess(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $shippingAddress = session('shipping_address');

        if (!$shippingAddress) {
            return redirect()->route('purchase.show', $item_id)->with('error', '配送先住所が設定されていません。');
        }

        $purchase = $this->savePurchase($user, $item, 'クレジットカード', $shippingAddress);

        $item->status = '売却済み';
        $item->save();

        session()->forget('shipping_address');

        return redirect()->route('profile.show', ['tab' => 'buy'])
            ->with('success', '商品を購入しました');
    }

    private function savePurchase($user, $item, $paymentMethod, $shippingAddress)
    {
        return Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => $paymentMethod,
            'shipping_postal_code' => $shippingAddress['postal_code'],
            'shipping_address' => $shippingAddress['address'],
            'shipping_building' => $shippingAddress['building'] ?? null,
            'status' => '支払い済み'
        ]);
    }

    private function getDefaultShippingAddress()
    {
        $user = Auth::user();
        $address = $user->address;

        if ($address) {
            return [
                'postal_code' => $address->postal_code,
                'address' => $address->address,
                'building' => $address->building
            ];
        }

        return null;
    }
}
