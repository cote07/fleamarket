<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use Illuminate\Support\Facades\Session as FacadeSession;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLoggedInUserCanPurchaseItem()
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
            'item_picture' => 'sample-image.jpg',
        ]);

        FacadeSession::put('payment_method', 'カード支払い');

        $temporary_address = [
            'postal_code' => '123-4567',
            'address' => 'Tokyo, Japan',
            'building' => 'Sample Building',
        ];
        FacadeSession::put('temporary_address_item' . $item->id, $temporary_address);

        \Stripe\Stripe::setApiKey('sk_test_dummy');

        $mockSession = \Mockery::mock('overload:\Stripe\Checkout\Session');
        $mockSession->shouldReceive('create')
            ->once()
            ->andReturn((object)[
                'url' => route('purchase.success', ['item_id' => $item->id])
            ]);

        $response = $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment' => 'カード支払い',
            'postal_code' => $temporary_address['postal_code'],
            'address' => $temporary_address['address'],
            'building' => $temporary_address['building'],
        ]);

        $response->assertRedirect(route('purchase.success', ['item_id' => $item->id]));
        $this->get(route('purchase.success', ['item_id' => $item->id]));

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment' => 'カード支払い',
            'postal_code' => $temporary_address['postal_code'],
            'address' => $temporary_address['address'],
            'building' => $temporary_address['building'],
        ]);
    }

    public function testLoggedPurchaseItemSold()
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
            'item_picture' => 'sample-image.jpg',
        ]);

        FacadeSession::put('payment_method', 'カード支払い');

        $temporary_address = [
            'postal_code' => '123-4567',
            'address' => 'Tokyo, Japan',
            'building' => 'Sample Building',
        ];
        FacadeSession::put('temporary_address_item' . $item->id, $temporary_address);

        \Stripe\Stripe::setApiKey('sk_test_dummy');

        $mockSession = \Mockery::mock('overload:\Stripe\Checkout\Session');
        $mockSession->shouldReceive('create')
            ->once()
            ->andReturn((object)[
                'url' => route('purchase.success', ['item_id' => $item->id])
            ]);

        $response = $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment' => 'カード支払い',
            'postal_code' => $temporary_address['postal_code'],
            'address' => $temporary_address['address'],
            'building' => $temporary_address['building'],
        ]);

        $response->assertRedirect(route('purchase.success', ['item_id' => $item->id]));
        $this->get(route('purchase.success', ['item_id' => $item->id]));

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment' => 'カード支払い',
            'postal_code' => $temporary_address['postal_code'],
            'address' => $temporary_address['address'],
            'building' => $temporary_address['building'],
        ]);

        $response = $this->get('/?tab=recommended&keyword=');
        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    public function testPurchaseItemAddMypage()
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
            'item_picture' => 'sample-image.jpg',
        ]);

        FacadeSession::put('payment_method', 'カード支払い');

        $temporary_address = [
            'postal_code' => '123-4567',
            'address' => 'Tokyo, Japan',
            'building' => 'Sample Building',
        ];
        FacadeSession::put('temporary_address_item' . $item->id, $temporary_address);

        \Stripe\Stripe::setApiKey('sk_test_dummy');

        $mockSession = \Mockery::mock('overload:\Stripe\Checkout\Session');
        $mockSession->shouldReceive('create')
            ->once()
            ->andReturn((object)[
                'url' => route('purchase.success', ['item_id' => $item->id])
            ]);

        $response = $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment' => 'カード支払い',
            'postal_code' => $temporary_address['postal_code'],
            'address' => $temporary_address['address'],
            'building' => $temporary_address['building'],
        ]);

        $response->assertRedirect(route('purchase.success', ['item_id' => $item->id]));
        $this->get(route('purchase.success', ['item_id' => $item->id]));

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment' => 'カード支払い',
            'postal_code' => $temporary_address['postal_code'],
            'address' => $temporary_address['address'],
            'building' => $temporary_address['building'],
        ]);

        $response = $this->get('/mypage?tab=buy');
        $response->assertSee($item->name);
    }

    public function testUpdatedAddressIsReflectedInPurchaseScreen()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'Sample Item',
            'description' => 'This is a sample item description.',
            'price' => 1000,
            'condition' => '良好',
            'item_picture' => 'sample-image.jpg',
        ]);

        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => 'Address',
            'building' => 'Building',
            'profile_picture' => 'profile.jpg',
        ]);

        $updatedAddressData = [
            'postal_code' => '987-6543',
            'address' => 'New Address',
            'building' => 'New Building',
        ];

        $this->patch(route('address.update', ['item_id' => $item->id]), $updatedAddressData);

        $response = $this->get(route('purchase', ['item_id' => $item->id]));
        $response->assertStatus(200);
        $response->assertSee('987-6543');
        $response->assertSee('New Address');
        $response->assertSee('New Building');
    }

    public function testUserChangeAddressAndPurchaseItem()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'Sample Item',
            'description' => 'This is a sample item description.',
            'price' => 1000,
            'condition' => '良好',
            'item_picture' => 'sample-image.jpg',
        ]);

        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => 'Address',
            'building' => 'Building',
            'profile_picture' => 'profile.jpg',
        ]);

        $updatedAddressData = [
            'postal_code' => '987-6543',
            'address' => 'New Address',
            'building' => 'New Building',
        ];

        $this->patch(route('address.update', ['item_id' => $item->id]), $updatedAddressData);

        FacadeSession::put('payment_method', 'カード支払い');
        FacadeSession::put('temporary_address_item' . $item->id, $updatedAddressData);
        \Stripe\Stripe::setApiKey('sk_test_dummy');

        $mockSession = \Mockery::mock('overload:\Stripe\Checkout\Session');
        $mockSession->shouldReceive('create')
            ->once()
            ->andReturn((object)[
                'url' => route('purchase.success', ['item_id' => $item->id])
            ]);

        $response = $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment' => 'カード支払い',
            'postal_code' => $updatedAddressData['postal_code'],
            'address' => $updatedAddressData['address'],
            'building' => $updatedAddressData['building'],
        ]);

        $response->assertRedirect(route('purchase.success', ['item_id' => $item->id]));
        $this->get(route('purchase.success', ['item_id' => $item->id]));

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment' => 'カード支払い',
            'postal_code' => $updatedAddressData['postal_code'],
            'address' => $updatedAddressData['address'],
            'building' => $updatedAddressData['building'],
        ]);
    }
}
