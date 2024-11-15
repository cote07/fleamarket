<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\UploadedFile;

class SellTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLoggedInUserCanSellItemWithExistingImage()
    {
        Artisan::call('db:seed', ['--class' => 'CategoriesTableSeeder']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $category = Category::first();

        $imagePath = public_path('img/sample.jpg');
        $image = new UploadedFile($imagePath, 'sample.jpg', 'image/jpg', null, true);

        $itemData = [
            'name' => 'Test User Item',
            'description' => 'This is an item created by the logged-in user.',
            'price' => 1000,
            'condition' => '良好',
            'item_picture' => $image,
            'content' => [$category->id],
        ];

        $response = $this->post(route('sell.store'), $itemData);
        $response->assertRedirect(route('index'));

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => 'Test User Item',
            'description' => 'This is an item created by the logged-in user.',
            'price' => 1000,
            'condition' => '良好',
        ]);

        $item = Item::where('name', 'Test User Item')->first();
        $this->assertNotNull($item, 'Item was not found in the database');
        $this->assertTrue($item->categories->contains($category), 'The item is not associated with the expected category');
    }
}
