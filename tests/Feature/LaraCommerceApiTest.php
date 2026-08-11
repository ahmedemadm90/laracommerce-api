<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaraCommerceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_register_and_receive_a_token(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated()->assertJsonStructure(['user', 'token']);
        $this->assertDatabaseHas('users', ['email' => 'ada@example.com']);
    }

    public function test_catalog_can_be_searched(): void
    {
        $category = Category::create(['name' => 'Tools', 'slug' => 'tools']);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Mechanical Keyboard',
            'slug' => 'mechanical-keyboard',
            'description' => 'A quiet keyboard.',
            'price' => 89,
            'stock' => 10,
            'sku' => 'KEY-001',
            'is_active' => true,
        ]);

        $this->getJson('/api/v1/products?search=keyboard')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Mechanical Keyboard');
    }

    public function test_customer_can_checkout_cart_transactionally(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Tools', 'slug' => 'tools']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'API Book',
            'slug' => 'api-book',
            'price' => 20,
            'stock' => 4,
            'sku' => 'BOOK-001',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->postJson('/api/v1/cart', ['product_id' => $product->id, 'quantity' => 2])
            ->assertCreated();

        $this->actingAs($user)
            ->postJson('/api/v1/orders', [
                'shipping_address' => [
                    'name' => 'Ada Lovelace',
                    'phone' => '+201000000000',
                    'line1' => '1 Analytical Engine Street',
                    'city' => 'London',
                    'country' => 'United Kingdom',
                ],
            ])
            ->assertCreated()
            ->assertJsonPath('status', 'pending');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 2]);
        $this->assertDatabaseCount('cart_items', 0);
        $this->assertDatabaseCount('orders', 1);
    }
}
