<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Favorite;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Profile;

class ItemTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAllProductsAreDisplayed()
    {
        Artisan::call('db:seed', ['--class' => 'UsersTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ItemsTableSeeder']);

        $response = $this->get('/?tab=recommended&keyword=');

        $response->assertStatus(200);
        $response->assertViewHas('recommendedItems');
    }

    public function testSoldLabelForPurchasedItems()
    {
        Artisan::call('db:seed', ['--class' => 'UsersTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ItemsTableSeeder']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::first();

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment' => 'card',
            'postal_code' => '000-0000',
            'address' => '123 Test St',
            'building' => 'Test Building',
        ]);

        $response = $this->get('/?tab=recommended&keyword=');
        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    public function testUserDoesNotSeeTheirOwnProducts()
    {
        Artisan::call('db:seed', ['--class' => 'UsersTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ItemsTableSeeder']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test User Item',
            'description' => 'This is an item created by the logged-in user.',
            'price' => 1000,
            'condition' => '良好',
            'item_picture' => 'test-image.jpg',
        ]);

        $response = $this->get('/?tab=recommended&keyword=');
        $response->assertStatus(200);
        $response->assertDontSee($item->name);
        $response->assertViewHas('recommendedItems', function ($recommendedItems) use ($user) {
            return $recommendedItems->where('user_id', $user->id)->count() === 0;
        });
    }

    public function testOnlyLikedItemsAreDisplayedInMyList()
    {
        Artisan::call('db:seed', ['--class' => 'UsersTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ItemsTableSeeder']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::first();
        $user->favorites()->create(['item_id' => $item->id]);

        $response = $this->get('/?tab=mylist&keyword=');
        $response->assertStatus(200);
        $response->assertViewHas('favorites');
        $response->assertSee($item->name);

        $favorites = $user->favorites;
        $this->assertTrue($favorites->contains('item_id', $item->id));
    }

    public function testSoldLabelForPurchasedItemsInMyList()
    {
        Artisan::call('db:seed', ['--class' => 'UsersTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ItemsTableSeeder']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::first();

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment' => 'card',
            'postal_code' => '000-0000',
            'address' => '123 Test St',
            'building' => 'Test Building',
        ]);

        $user->favorites()->create([
            'item_id' => $item->id
        ]);

        $response = $this->get('/?tab=mylist&keyword=');
        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    public function testItemsUserHasListedAreNotDisplayedInMyList()
    {
        Artisan::call('db:seed', ['--class' => 'UsersTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ItemsTableSeeder']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test User Item',
            'description' => 'This is an item created by the logged-in user.',
            'price' => 1000,
            'condition' => '良好',
            'item_picture' => 'test-image.jpg',
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get('/?tab=mylist&keyword=');
        $response->assertStatus(200);
        $response->assertDontSee($item->name);
        $response->assertViewHas('favorites', function ($favorites) use ($user) {
            return $favorites->where('user_id', $user->id)->count() === 0;
        });
    }

    public function testNoItemsAreDisplayedForUnauthenticatedUser()
    {
        Artisan::call('db:seed', ['--class' => 'UsersTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ItemsTableSeeder']);

        $item = Item::first();
        $user = User::factory()->create();
        $user->favorites()->create([
            'item_id' => $item->id,
        ]);

        $response = $this->get('/?tab=mylist&keyword=');
        $response->assertStatus(200);
        $response->assertDontSee($item->name);
    }

    public function testSearchFunctionalityWorksForItemNames()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUser = User::factory()->create();

        $item1 = Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'Test Item',
            'description' => 'This is an item created by the logged-in user.',
            'price' => 1000,
            'condition' => '良好',
            'item_picture' => 'test-image.jpg',
        ]);

        $item2 = Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'Another Item',
            'description' => 'This is an item created by the logged-in user.',
            'price' => 1000,
            'condition' => '良好',
            'item_picture' => 'test-image.jpg',
        ]);

        $response = $this->get('/?tab=recommended&keyword=Test');
        $response->assertStatus(200);
        $response->assertSee($item1->name);
        $response->assertDontSee($item2->name);
    }

    public function testSearchStateIsRetainedWhenNavigatingToMyList()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUser = User::factory()->create();

        $item1 = Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'Test Item',
            'description' => 'This is an item created by the logged-in user.',
            'price' => 1000,
            'condition' => '良好',
            'item_picture' => 'test-image.jpg',
        ]);

        $item2 = Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'Another Item',
            'description' => 'This is an item created by the logged-in user.',
            'price' => 1000,
            'condition' => '良好',
            'item_picture' => 'test-image.jpg',
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item1->id,
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item2->id,
        ]);

        $favorites = $user->favorites;
        $this->assertTrue($favorites->contains('item_id', $item1->id));
        $this->assertTrue($favorites->contains('item_id', $item2->id));

        $response = $this->get('/?tab=recommended&keyword=Test');
        $response->assertStatus(200);
        $response = $this->get('/?tab=mylist&keyword=Test');
        $response->assertStatus(200);
        $response->assertSee('Test');
        $response->assertSee($item1->name);
        $response->assertDontSee($item2->name);
    }

    public function testProductDetailPageDisplaysRequiredInformation()
    {
        Artisan::call('db:seed', ['--class' => 'CategoriesTableSeeder']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUser = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'Sample Item',
            'description' => 'This is a sample item description.',
            'price' => 1000,
            'condition' => '良好',
            'item_picture' => 'sample-image.jpg',
        ]);

        $profile = Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => 'Some Address, City',
            'building' => 'Some Building',
            'profile_picture' => 'profile-picture.jpg',
        ]);

        $category1 = Category::first();
        $category2 = Category::skip(1)->first();
        $item->categories()->saveMany([$category1, $category2]);

        $comment = Comment::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'This is a great item!',
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('item', ['item_id' => $item->id]));
        $response->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertSee($item->description);
        $response->assertSee($item->price);
        $response->assertSee($item->condition);
        $response->assertSee(asset('storage/' . $item->item_picture));
        $response->assertSee($category1->name);
        $response->assertSee($category2->name);
        $response->assertSeeText('1');
        $response->assertSee($comment->content);
        $response->assertSee($comment->user->name);
        $response->assertSeeText('1');
    }

    public function testMultipleCategoriesAreDisplayedOnProductDetailPage()
    {
        Artisan::call('db:seed', ['--class' => 'CategoriesTableSeeder']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUser = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'Sample Item',
            'description' => 'This is a sample item description.',
            'price' => 1000,
            'condition' => '良好',
            'item_picture' => 'sample-image.jpg',
        ]);

        $category1 = Category::first();
        $category2 = Category::skip(1)->first();
        $item->categories()->saveMany([$category1, $category2]);

        $response = $this->get(route('item', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee($category1->name);
        $response->assertSee($category2->name);
    }
}
