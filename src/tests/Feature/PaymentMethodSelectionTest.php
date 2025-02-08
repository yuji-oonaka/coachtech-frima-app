<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class PaymentMethodSelectionTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $item;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->item = Item::factory()->create([
            'price' => 1000,
            'user_id' => User::factory()->create()->id // 他ユーザーの商品を作成
        ]);

        // 配送先住所をセッションに設定
        Session::put('shipping_address', [
            'postal_code' => '100-0001',
            'address' => '東京都千代田区',
            'building' => 'テストビル'
        ]);
    }

    public function test_支払い方法選択後_小計画面に即時反映される()
    {
        // 1. 認証済みユーザーで購入画面にアクセス
        $response = $this->actingAs($this->user)
            ->get(route('purchase.show', $this->item->id));

        $response->assertOk()
            ->assertSee('支払い方法');

        // 2. カード支払いを選択
        $updateResponse = $this->postJson(route('payment.method.update', $this->item->id), [
            'payment_method' => 'カード支払い'
        ]);

        // 3. JSONレスポンスの確認
        $updateResponse->assertJson([
            'status' => 'success',
            'payment_method' => 'カード支払い'
        ]);

        // 4. セッションの確認
        $this->assertEquals('カード支払い', session('selected_payment_method'));

        // 5. 購入画面を再度取得して表示を確認
        $this->get(route('purchase.show', $this->item->id))
            ->assertSee('カード支払い');
    }
}
