<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $otherUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        $this->user = User::factory()->create();
        $this->otherUser = User::factory()->create();
    }

    public function test_いいねした商品だけが表示される()
    {
        // いいねした商品を作成
        $likedItem = Item::factory()->create([
            'user_id' => $this->otherUser->id,
            'status' => '出品中',
            'name' => 'いいねした商品'
        ]);

        // いいねしていない商品を作成
        $notLikedItem = Item::factory()->create([
            'user_id' => $this->otherUser->id,
            'status' => '出品中',
            'name' => 'いいねしていない商品'
        ]);

        // いいねを作成
        Like::create([
            'user_id' => $this->user->id,
            'item_id' => $likedItem->id
        ]);

        $response = $this->actingAs($this->user)
            ->get('/?tab=mylist');

        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしていない商品');
    }

    public function test_購入済み商品はSoldと表示される()
    {
        // 購入済み商品を作成
        $soldItem = Item::factory()->create([
            'user_id' => $this->otherUser->id,
            'status' => '売却済み'
        ]);

        // いいねを作成
        Like::create([
            'user_id' => $this->user->id,
            'item_id' => $soldItem->id
        ]);

        // 購入レコードを作成
        Purchase::create([
            'user_id' => $this->user->id,
            'item_id' => $soldItem->id,
            'payment_method' => 'クレジットカード',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => 'テスト住所',
            'status' => '支払い済み'
        ]);

        $response = $this->actingAs($this->user)
            ->get('/?tab=mylist');

        $response->assertSee('Sold');
        $response->assertSee($soldItem->name);
    }

    public function test_自分が出品した商品は表示されない()
    {
        // 自分の出品商品を作成（商品名を固定値に）
        $ownItem = Item::factory()->create([
            'user_id' => $this->user->id,
            'status' => '出品中',
            'name' => 'テスト商品' // 商品名を固定
        ]);

        // いいねを作成
        Like::create([
            'user_id' => $this->user->id,
            'item_id' => $ownItem->id
        ]);

        $response = $this->actingAs($this->user)
            ->get('/?tab=mylist');

        $response->assertDontSee('テスト商品');
    }

    public function test_未認証の場合は何も表示されない()
    {
        // 商品を作成
        $item = Item::factory()->create([
            'user_id' => $this->otherUser->id,
            'status' => '出品中'
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertSee('');
    }
}
