<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $listedItem;
    private $purchasedItem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_img_url' => '/storage/profile_images/test-profile.jpg'
        ]);

        $this->listedItem = Item::factory()->create([
            'user_id' => $this->user->id,
            'name' => '出品した商品',
            'price' => 1000,
            'status' => '出品中',
            'img_url' => '/storage/item_images/listed-item.jpg'
        ]);

        $this->purchasedItem = Item::factory()->create([
            'name' => '購入した商品',
            'price' => 2000,
            'status' => '売却済み',
            'img_url' => '/storage/item_images/purchased-item.jpg'
        ]);

        Purchase::factory()->create([
            'user_id' => $this->user->id,
            'item_id' => $this->purchasedItem->id,
        ]);
    }

    public function test_ユーザー情報取得()
    {
        $response = $this->actingAs($this->user)->get(route('profile.show'));

        $response->assertStatus(200)
            ->assertSee($this->user->name)
            ->assertSee($this->user->profile_img_url)
            ->assertSee($this->listedItem->name)
            ->assertSee($this->listedItem->img_url);

        $response = $this->actingAs($this->user)->get(route('profile.show', ['tab' => 'buy']));

        $response->assertStatus(200)
            ->assertSee($this->purchasedItem->name)
            ->assertSee($this->purchasedItem->img_url);
    }
}
