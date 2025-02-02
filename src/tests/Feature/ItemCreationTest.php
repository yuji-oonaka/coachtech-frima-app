<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ItemCreationTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_商品情報が正しく保存される()
    {
        Storage::fake('public');

        // テスト用カテゴリ作成
        $categories = Category::factory()->count(3)->create();
        $categoryIds = $categories->pluck('id')->implode(',');

        // テスト用画像
        $image = UploadedFile::fake()->image('test-item.jpg');

        $response = $this->actingAs($this->user)
            ->post(route('items.store'), [
                'item_image' => $image,
                'selected_category' => $categoryIds,
                'condition' => '良好',
                'name' => 'テスト商品',
                'description' => 'これはテスト商品の説明です。',
                'price' => 1000,
            ]);

        // リダイレクト確認
        $response->assertRedirect(route('items.show', Item::first()->id));

        // データベース確認
        $item = Item::first();
        $this->assertNotNull($item);
        $this->assertEquals('テスト商品', $item->name);
        $this->assertEquals('これはテスト商品の説明です。', $item->description);
        $this->assertEquals(1000, $item->price);
        $this->assertEquals('良好', $item->condition);
        $this->assertEquals('出品中', $item->status);

        // 画像保存確認
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $item->img_url));

        // カテゴリ関連付け確認
        $this->assertCount(3, $item->categories);
        $this->assertEquals($categories->pluck('id'), $item->categories->pluck('id'));
    }
}
