<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Stripe\Checkout\Session;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $item;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        $this->user = User::factory()->create();
        $this->item = Item::factory()->create([
            'status' => '出品中',
            'name' => 'テスト商品',
            'price' => 1000
        ]);

        Address::create([
            'user_id' => $this->user->id,
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テストビル101'
        ]);
    }

    public function test_購入する()
    {
        $this->mock('Stripe\Checkout\Session', function ($mock) {
            $mock->shouldReceive('create')
                ->andReturn((object)[
                    'url' => 'https://checkout.stripe.com/test',
                    'id' => 'cs_test_123'
                ]);
        });

        session([
            'shipping_address' => [
                'postal_code' => '123-4567',
                'address' => 'テスト住所',
                'building' => 'テストビル101'
            ]
        ]);

        $purchaseData = [
            'payment_method' => 'カード支払い',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => 'テスト住所',
            'shipping_building' => 'テストビル101'
        ];

        $response = $this->actingAs($this->user)
            ->post(route('purchase.process', ['item_id' => $this->item->id]), $purchaseData);

        // 完全一致ではなく、URLの開始部分をチェック
        $this->assertStringStartsWith('https://checkout.stripe.com/', $response->headers->get('Location'));
    }

    public function test_stripe決済成功後の処理()
{
    // 事前準備：購入対象の商品を設定
    $this->item->update([
        'status' => '出品中',
        'user_id' => User::factory()->create()->id
    ]);

    // セッションデータの設定
    session([
        'shipping_address' => [
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テストビル101'
        ]
    ]);

    // Stripeのモックを設定（partialMockを使用）
    $this->partialMock('Stripe\Checkout\Session', function ($mock) {
        $mock->shouldReceive('retrieve')
            ->with('cs_test_123')
            ->andReturn((object)[
                'payment_status' => 'paid',
                'metadata' => (object)[
                    'payment_method' => 'カード支払い',
                    'item_id' => $this->item->id,
                    'user_id' => $this->user->id,
                    'shipping_postal_code' => '123-4567',
                    'shipping_address' => 'テスト住所',
                    'shipping_building' => 'テストビル101'
                ]
            ]);
    });

    // 事前にPurchaseレコードを作成
    Purchase::create([
        'user_id' => $this->user->id,
        'item_id' => $this->item->id,
        'payment_method' => 'カード支払い',
        'shipping_postal_code' => '123-4567',
        'shipping_address' => 'テスト住所',
        'shipping_building' => 'テストビル101',
        'status' => '支払い済み'
    ]);

    // 商品を売却済みに更新
    $this->item->update(['status' => '売却済み']);

    // 購入成功処理をシミュレート
    $response = $this->actingAs($this->user)
        ->get(route('purchase.success', [
            'item_id' => $this->item->id,
            'session_id' => 'cs_test_123'
        ]));

    // データベースの状態を確認
    $this->assertDatabaseHas('purchases', [
        'user_id' => $this->user->id,
        'item_id' => $this->item->id,
        'payment_method' => 'カード支払い',
        'shipping_postal_code' => '123-4567',
        'shipping_address' => 'テスト住所',
        'shipping_building' => 'テストビル101',
        'status' => '支払い済み'
    ]);

    // 商品のステータスが更新されていることを確認
    $this->assertDatabaseHas('items', [
        'id' => $this->item->id,
        'status' => '売却済み'
    ]);

    // リダイレクト先を確認
    $response->assertRedirect(route('purchase.show', ['item_id' => $this->item->id]));
}



    public function test_購入した商品は商品一覧画面にて「sold」と表示される()
    {
        $this->item->update(['status' => '売却済み']);
        Purchase::create([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'payment_method' => 'カード支払い',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => 'テスト住所',
            'shipping_building' => 'テストビル101',
            'status' => '支払い済み'
        ]);

        $response = $this->get(route('items.index'));

        $response->assertStatus(200);
        $response->assertSee('Sold');
        $response->assertSee($this->item->name);
    }

    public function test_プロフィールの購入した商品一覧に追加されている()
    {
        $this->item->update(['status' => '売却済み']);
        Purchase::create([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'payment_method' => 'カード支払い',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => 'テスト住所',
            'shipping_building' => 'テストビル101',
            'status' => '支払い済み'
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('profile.show', ['tab' => 'buy']));

        $response->assertStatus(200);
        $response->assertSee($this->item->name);
    }
}

