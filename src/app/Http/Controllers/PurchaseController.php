<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use App\Models\User;
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

    public function updatePaymentMethod(Request $request, $item_id)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:カード支払い,コンビニ支払い'
        ]);

        session(['selected_payment_method' => $validated['payment_method']]);

        return response()->json([
            'status' => 'success',
            'payment_method' => $validated['payment_method']
        ]);
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

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentMethod = $request->input('payment_method');
        $paymentMethodTypes = $paymentMethod === 'カード支払い' ? ['card'] : ['konbini'];

        $session = Session::create([
            'payment_method_types' => $paymentMethodTypes,
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
            'success_url' => route('purchase.success', ['item_id' => $item->id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('purchase.show', ['item_id' => $item->id]),
            'metadata' => [
                'item_id' => $item->id,
                'user_id' => $user->id,
                'shipping_postal_code' => $shippingAddress['postal_code'],
                'shipping_address' => $shippingAddress['address'],
                'shipping_building' => $shippingAddress['building'] ?? '',
                'payment_method' => $paymentMethod,
            ],
        ]);

        return redirect($session->url);
    }

    public function handleStripeSuccess(Request $request, $item_id)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = Session::retrieve($request->query('session_id'));

            // クレジットカード決済の場合のみ処理を実行
            if ($session->payment_status === 'paid' && $session->metadata->payment_method === 'カード支払い') {
                $item = Item::findOrFail($session->metadata->item_id);
                $user = User::findOrFail($session->metadata->user_id);
                $shippingAddress = [
                    'postal_code' => $session->metadata->shipping_postal_code,
                    'address' => $session->metadata->shipping_address,
                    'building' => $session->metadata->shipping_building,
                ];

                $purchase = $this->savePurchase($user, $item, $session->metadata->payment_method, $shippingAddress);

                $item->status = '売却済み';
                $item->save();
            }

            return redirect()->route('profile.show', ['tab' => 'buy'])
                ->with('success', '商品を購入しました');
        } catch (\Exception $e) {
            \Log::error('Stripe session retrieval failed: ' . $e->getMessage());
            return redirect()->route('purchase.show', $item_id)
                ->with('error', '購入処理中にエラーが発生しました。もう一度お試しください。');
        }
    }

    public function handleStripeWebhook(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                env('STRIPE_WEBHOOK_SECRET')
            );
        } catch(\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            // コンビニ決済の場合のみ処理を実行
            if ($session->metadata->payment_method === 'コンビニ支払い') {
                $item = Item::findOrFail($session->metadata->item_id);
                $user = User::findOrFail($session->metadata->user_id);
                $shippingAddress = [
                    'postal_code' => $session->metadata->shipping_postal_code,
                    'address' => $session->metadata->shipping_address,
                    'building' => $session->metadata->shipping_building,
                ];

                $purchase = $this->savePurchase($user, $item, $session->metadata->payment_method, $shippingAddress);

                $item->status = '売却済み';
                $item->save();
            }
        }

        return response()->json(['status' => 'success']);
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
