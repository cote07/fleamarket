<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;


class CommentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLoggedInUserCanSendComment()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUser = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'Test User Item',
            'description' => 'This is an item created by the logged-in user.',
            'price' => 1000,
            'condition' => '良好',
            'item_picture' => 'test-image.jpg',
        ]);

        $this->assertEquals(0, $item->comments()->count());

        $response = $this->post(route('comments.store', ['item' => $item->id]), [
            'content' => 'This is a test comment.',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'This is a test comment.',
        ]);

        $item->refresh();
        $this->assertEquals(1, $item->comments()->count());
    }

    public function testGuestUserCannotSendComment()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test User Item',
            'description' => 'This is an item created by the logged-in user.',
            'price' => 1000,
            'condition' => '良好',
            'item_picture' => 'test-image.jpg',
        ]);

        $response = $this->post(route('comments.store', ['item' => $item->id]), [
            'content' => 'This is a test comment.',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('comments', [
            'content' => 'This is a test comment.',
        ]);
    }

    public function testCommentValidation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/items/{item}/comments', [
            'content' => '',
        ]);

        $response->assertSessionHasErrors('content');
        $errors = session('errors');
        $this->assertEquals('コメントを入力してください', $errors->get('content')[0]);
    }

    public function testCommentMaxValidation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $longComment = str_repeat('あ', 256);

        $response = $this->post('/items/{item}/comments', [
            'content' => $longComment,
        ]);

        $response->assertSessionHasErrors('content');
        $errors = session('errors');
        $this->assertEquals('コメントは最大255文字までです', $errors->get('content')[0]);
    }
}
