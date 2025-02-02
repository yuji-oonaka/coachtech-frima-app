<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LikeTest extends TestCase
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
            'name' => 'テスト商品',
            'status' => '出品中'
        ]);
    }

    public function test_いいねを登録できる()
    {
        $response = $this->actingAs($this->user)
            ->post("/items/{$this->item->id}/like");

        $response->assertStatus(200)
            ->assertJson([
                'action' => 'liked',
                'likeCount' => 1
            ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id
        ]);
    }

    public function test_いいね済みの場合はアクティブ状態が返される()
    {
        // 事前にいいねを作成
        Like::create([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('items.show', $this->item->id));

        $response->assertStatus(200);
        $response->assertSee('item-detail__like-button--active');
    }

    public function test_いいねを解除できる()
    {
        // 事前にいいねを作成
        Like::create([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id
        ]);

        $response = $this->actingAs($this->user)
            ->post("/items/{$this->item->id}/like");

        $response->assertStatus(200)
            ->assertJson([
                'action' => 'unliked',
                'likeCount' => 0
            ]);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id
        ]);
    }
}
