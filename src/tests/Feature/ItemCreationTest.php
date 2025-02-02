<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品出品画面にて必要な情報が保存できること()
    {
        // 1. ユーザーにログインする
        $user = User::factory()->create();
        $this->actingAs($user);

        // カテゴリを作成
        $category = Category::factory()->create();

        // 商品画像をシミュレート
        Storage::fake('public');
        $image = UploadedFile::fake()->image('test_item.jpg');

        // 2. 商品出品画面を開く（この部分はフロントエンドのテストなので、ここでは省略）

        // 3. 各項目に適切な情報を入力して保存する
        $response = $this->post(route('items.store'), [
            'name' => 'テスト商品',
            'description' => 'これはテスト商品の説明です。',
            'price' => 5000,
            'condition' => '良好',
            'selected_category' => $category->id,
            'item_image' => $image,
        ]);

        // リダイレクトの確認
        $response->assertRedirect();

        // 各項目が正しく保存されていることを確認
        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
            'description' => 'これはテスト商品の説明です。',
            'price' => 5000,
            'condition' => '良好',
        ]);

        // 作成されたアイテムを取得
        $item = Item::where('name', 'テスト商品')->first();

        // カテゴリが正しく関連付けられていることを確認
        $this->assertTrue($item->categories->contains($category));

        // 画像が保存されていることを確認
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $item->img_url));
    }
}
