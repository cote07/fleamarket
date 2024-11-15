<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testEmailValidation()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123'
        ]);

        $response->assertSessionHasErrors('email');
        $errors = session('errors');
        $this->assertEquals('メールアドレスを入力してください', $errors->get('email')[0]);
    }

    public function testPasswordValidation()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => ''
        ]);

        $response->assertSessionHasErrors('password');
        $errors = session('errors');
        $this->assertEquals('パスワードを入力してください', $errors->get('password')[0]);
    }

    public function testInvalidCredentialsValidation()
    {
        $response = $this->post('/login', [
            'email' => 'unregistered@example.com',
            'password' => 'invalidPassword'
        ]);

        $response->assertSessionHasErrors();
        $this->assertStringContainsString('ログイン情報が登録されていません', session('errors')->first());
    }

    public function testSuccessfulLogin()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function testLogout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
