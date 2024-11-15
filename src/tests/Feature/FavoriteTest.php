<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAddFavorites()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUser = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'Sample Item',
            'description' => 'This is a sample item description.',
            'price' => 1000,
            'condition' => '良好',
            'item_picture' => 'path/to/sample-image.jpg',
        ]);

        $this->assertEquals(0, $item->favorites()->count());

        $response = $this->post(route('favorite.create', ['item_id' => $item->id]));

        $response->assertStatus(302);
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $item->refresh();
        $this->assertEquals(1, $item->favorites()->count());
    }

    public function testRemoveFavorites()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUser = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'Sample Item',
            'description' => 'This is a sample item description.',
            'price' => 1000,
            'condition' => '良好',
            'item_picture' => 'path/to/sample-image.jpg',
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(1, $item->favorites()->count());

        $response = $this->delete(route('favorite.delete', ['item_id' => $item->id]));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $item->refresh();
        $this->assertEquals(0, $item->favorites()->count());
    }

    public function testFavoriteIconColorChanges()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUser = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'Sample Item',
            'description' => 'This is a sample item description.',
            'price' => 1000,
            'condition' => '良好',
            'item_picture' => 'path/to/sample-image.jpg',
        ]);

        $response = $this->post(route('favorite.create', ['item_id' => $item->id]));

        $response = $this->get(route('item', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee('star-icon active');
    }
}
