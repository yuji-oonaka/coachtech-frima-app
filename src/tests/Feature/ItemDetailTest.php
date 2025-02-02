<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $item;
    private $categories;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        // テストユーザーを作成
        $this->user = User::factory()->create([
            'name' => 'テストユーザー'
        ]);

        // カテゴリを作成
        $this->categories = collect();
        $this->categories->push(Category::create(['name' => 'ファッション']));
        $this->categories->push(Category::create(['name' => 'インテリア']));
        $this->categories->push(Category::create(['name' => 'メンズ']));

        // テスト用の商品を作成
        $this->item = Item::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 1000,
            'description' => '商品の説明文です。',
            'condition' => '良好',
            'status' => '出品中',
            'img_url' => '/storage/item_images/test.jpg'
        ]);

        // カテゴリを商品に紐付け
        $this->item->categories()->attach($this->categories->pluck('id'));
    }

    public function test_必要な情報が表示される()
    {
        // コメントを作成
        $commentUser = User::factory()->create(['name' => 'コメントユーザー']);
        $comment = Comment::create([
            'user_id' => $commentUser->id,
            'item_id' => $this->item->id,
            'content' => 'テストコメントです。'
        ]);

        // いいねを作成
        Like::create([
            'user_id' => $commentUser->id,
            'item_id' => $this->item->id
        ]);

        $response = $this->get(route('items.show', $this->item->id));

        $response->assertStatus(200);

        // 基本情報の表示確認
        $response->assertSee($this->item->name);
        $response->assertSee($this->item->brand_name);
        $response->assertSee('1,000');  // 価格の確認を修正
        $response->assertSee('税込');
        $response->assertSee($this->item->description);

        // 商品情報の表示確認
        $response->assertSee('商品の情報');
        $response->assertSee('商品の状態');
        $response->assertSee($this->item->condition);

        // カテゴリの表示確認
        $response->assertSee('カテゴリー');
        foreach ($this->categories as $category) {
            $response->assertSee($category->name);
        }

        // コメント情報の表示確認
        $response->assertSee('コメント(1)');
        $response->assertSee('コメントユーザー');
        $response->assertSee('テストコメントです。');

        // いいね数の表示確認
        $response->assertSee('1');
    }

    public function test_複数選択されたカテゴリが表示されている()
    {
        // 追加のカテゴリを作成
        $additionalCategories = collect();
        $additionalCategories->push(Category::create(['name' => '本']));
        $additionalCategories->push(Category::create(['name' => 'ゲーム']));

        $this->item->categories()->attach($additionalCategories->pluck('id'));

        $response = $this->get(route('items.show', $this->item->id));

        $response->assertStatus(200);
        $response->assertSee('カテゴリー');

        // すべてのカテゴリが表示されていることを確認
        foreach ($this->categories as $category) {
            $response->assertSee($category->name);
        }
        foreach ($additionalCategories as $category) {
            $response->assertSee($category->name);
        }
    }
}
