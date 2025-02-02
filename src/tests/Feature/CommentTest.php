<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
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
            'status' => '出品中'
        ]);
    }

    public function test_ログイン済みユーザーはコメントを送信できる()
    {
        $response = $this->actingAs($this->user)
            ->from(route('items.show', $this->item->id))
            ->post(route('comments.store', ['item_id' => $this->item->id]), [
                'content' => 'テストコメントです。'
            ]);

        $response->assertRedirect(route('items.show', $this->item->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('comments', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'content' => 'テストコメントです。'
        ]);

        // コメント数の確認
        $this->assertEquals(1, $this->item->comments()->count());
    }

    public function test_未ログインユーザーはコメントを送信できない()
    {
        $response = $this->post(route('comments.store', ['item_id' => $this->item->id]), [
            'content' => 'テストコメントです。'
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('comments', [
            'item_id' => $this->item->id,
            'content' => 'テストコメントです。'
        ]);
    }

    public function test_コメントが入力されていない場合バリデーションメッセージが表示される()
    {
        $response = $this->actingAs($this->user)
            ->post(route('comments.store', ['item_id' => $this->item->id]), [
                'content' => ''
            ]);

        $response->assertInvalid(['content']);
        $response->assertSessionHasErrors([
            'content' => 'コメントを入力してください'
        ]);
    }

    public function test_コメントが255文字以上の場合バリデーションメッセージが表示される()
    {
        $longComment = str_repeat('あ', 256);

        $response = $this->actingAs($this->user)
            ->post(route('comments.store', ['item_id' => $this->item->id]), [
                'content' => $longComment
            ]);

        $response->assertInvalid(['content']);
        $response->assertSessionHasErrors([
            'content' => 'コメントは255文字以内で入力してください'
        ]);
    }
}
