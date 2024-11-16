<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserInformationIsDisplayedCorrectly()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
        ]);
        $this->actingAs($user);

        $otherUser = User::factory()->create();

        $item1 = Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'Sample Item',
            'description' => 'This is a sample item description.',
            'price' => 1000,
            'condition' => '良好',
            'item_picture' => 'sample-image.jpg',
        ]);

        $item2 = Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'Sample Purchase Item',
            'description' => 'This is a sample item description.',
            'price' => 1000,
            'condition' => '良好',
            'item_picture' => 'sample-image.jpg',
        ]);

        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => 'Some Address, City',
            'building' => 'Some Building',
            'profile_picture' => 'profile.jpg',
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item2->id,
            'payment' => 'card',
            'postal_code' => '000-0000',
            'address' => '123 Test St',
            'building' => 'Test Building',
        ]);

        $response = $this->get(route('mypage'));
        $response->assertStatus(200);
        $response->assertSee('profile.jpg');
        $response->assertSee('Test User');

        $response = $this->get('/mypage?tab=sell');
        $response->assertStatus(200);
        $response->assertSee('Sample Item');

        $response = $this->get('/mypage?tab=buy');
        $response->assertStatus(200);
        $response->assertSee('Sample Purchase Item');
    }

    public function testUserInformationInitialValuesAreCorrect()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
        ]);
        $this->actingAs($user);

        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => 'Address',
            'building' => 'Building',
            'profile_picture' => 'profile.jpg',
        ]);

        $response = $this->get(route('profile'));
        $response->assertStatus(200);
        $response->assertSee('profile.jpg');
        $response->assertSee('Test User');
        $response->assertSee('123-4567');
        $response->assertSee('Address');
        $response->assertSee('Building');
    }
}
