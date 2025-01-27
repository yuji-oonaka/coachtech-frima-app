<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログアウトができる()
    {
        // テストユーザーを作成
        $user = User::factory()->create();

        // ユーザーとしてログイン
        $this->actingAs($user);

        // ログイン状態を確認
        $this->assertAuthenticated();

        // ログアウトリクエストを送信
        $response = $this->post('/logout');

        // ログアウト後の検証
        $this->assertGuest();                    // ログアウト状態であることを確認
        $response->assertRedirect('/login');     // ログインページにリダイレクトされることを確認
    }
}
