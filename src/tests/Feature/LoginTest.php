<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_メールアドレスが入力されていない場合バリデーションメッセージが表示される()
    {
        $response = $this->post('/login', [
            'password' => 'password123'
        ]);

        $response->assertInvalid(['email']);
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください'
        ]);
    }

    public function test_パスワードが入力されていない場合バリデーションメッセージが表示される()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com'
        ]);

        $response->assertInvalid(['password']);
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください'
        ]);
    }

    public function test_入力情報が間違っている場合バリデーションメッセージが表示される()
    {
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertInvalid();
        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません'
        ]);
    }

    public function test_正しい情報が入力された場合ログイン処理が実行される()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }
}
