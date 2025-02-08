<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileEditTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        // ユーザーと住所を同時に生成
        $this->user = User::factory()
            ->has(
                Address::factory()->state([
                    'postal_code' => '123-4567',
                    'address' => 'テスト県テスト市テスト町1-2-3',
                    'building' => 'テストビル101'
                ])
            )
            ->create([
                'name' => 'テストユーザー',
                'profile_img_url' => '/storage/profile_images/test-profile.jpg'
            ]);
    }

    public function test_ユーザー情報変更画面の初期値表示()
    {
        $response = $this->actingAs($this->user)->get(route('profile.edit'));

        $response->assertStatus(200)
            ->assertSee($this->user->name)
            ->assertSee($this->user->profile_img_url)
            ->assertSee('123-4567')
            ->assertSee('テスト県テスト市テスト町1-2-3')
            ->assertSee('テストビル101');

        $response->assertSee('value="' . $this->user->name . '"', false)
            ->assertSee('value="123-4567"', false)
            ->assertSee('value="テスト県テスト市テスト町1-2-3"', false)
            ->assertSee('value="テストビル101"', false);
    }
}
