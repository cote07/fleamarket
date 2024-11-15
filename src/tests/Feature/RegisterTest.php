<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNameValidation()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertSessionHasErrors('name');
        $errors = session('errors');
        $this->assertEquals('お名前を入力してください', $errors->get('name')[0]);
    }

    public function testEmailValidation()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertSessionHasErrors('email');
        $errors = session('errors');
        $this->assertEquals('メールアドレスを入力してください', $errors->get('email')[0]);
    }

    public function testPasswordValidation()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => ''
        ]);

        $response->assertSessionHasErrors('password');
        $errors = session('errors');
        $this->assertEquals('パスワードを入力してください', $errors->get('password')[0]);
    }

    public function testPasswordLengthValidation()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short'
        ]);

        $response->assertSessionHasErrors('password');
        $errors = session('errors');
        $this->assertEquals('パスワードは8文字以上で入力してください', $errors->get('password')[0]);
    }

    public function testPasswordConfirmationValidation()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password321'
        ]);

        $response->assertSessionHasErrors('password');
        $errors = session('errors');
        $this->assertEquals('パスワードと一致しません', $errors->get('password')[0]);
    }
}
