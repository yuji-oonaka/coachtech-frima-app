<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        $this->user = User::factory()->create();
    }

    public function test_商品名で部分一致検索ができる()
    {
        // テスト用の商品を作成
        $matchingItem = Item::factory()->create([
            'name' => 'テスト商品ABC',
            'status' => '出品中',
            'user_id' => $this->user->id
        ]);

        $nonMatchingItem = Item::factory()->create([
            'name' => '別の商品XYZ',
            'status' => '出品中',
            'user_id' => $this->user->id
        ]);

        // 検索実行
        $response = $this->get(route('items.search', ['keyword' => 'ABC']));

        // 検証
        $response->assertStatus(200);
        $response->assertSee('テスト商品ABC');
        $response->assertDontSee('別の商品XYZ');
    }

    public function test_検索状態がマイリストでも保持されている()
    {
        $otherUser = User::factory()->create();
        $item = Item::factory()->create([
            'name' => 'テスト商品ABC',
            'status' => '出品中',
            'user_id' => $otherUser->id
        ]);

        Like::create([
            'user_id' => $this->user->id,
            'item_id' => $item->id
        ]);

        // 通常検索
        $response = $this->actingAs($this->user)
            ->get(route('items.search', ['keyword' => 'ABC']));

        $response->assertStatus(200);
        $response->assertSee('テスト商品ABC');

        // マイリストでの検索
        $response = $this->get(route('items.search', [
            'keyword' => 'ABC',
            'tab' => 'mylist'
        ]));

        $response->assertStatus(200);
        $response->assertSee('テスト商品ABC');
    }

    public function test_検索結果が存在しない場合の表示()
    {
        $response = $this->get(route('items.search', [
            'keyword' => '存在しない商品名'
        ]));

        $response->assertStatus(200);
        $response->assertSee('検索結果がありません');
    }
}
