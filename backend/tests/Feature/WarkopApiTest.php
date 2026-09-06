<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarkopApiTest extends TestCase
{
    public function test_can_list_categories(): void
    {
        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'name', 'slug', 'products_count']
                ]
            ]);
    }

    public function test_can_list_products(): void
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'name', 'slug', 'price', 'category', 'variants']
                ]
            ]);
    }

    public function test_can_login_with_valid_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'login' => 'dadang@warkop.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'token',
                    'token_type',
                    'user' => ['id', 'name', 'email', 'role']
                ]
            ]);
    }

    public function test_can_scan_table_qr(): void
    {
        $response = $this->getJson('/api/tables/qr/WD-MEJA-01');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'number' => '01',
                    'qr_code' => 'WD-MEJA-01',
                ]
            ]);
    }

    public function test_can_create_order_as_guest(): void
    {
        $table = Table::where('number', '01')->first();
        $product = Product::first();

        $response = $this->postJson('/api/orders/guest', [
            'type' => 'dine_in',
            'table_id' => $table->id,
            'customer_name' => 'Guest Tester',
            'customer_phone' => '081299998888',
            'payment_method' => 'cash',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'notes' => 'Tidak pakai es',
                ]
            ]
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'type' => 'dine_in',
                    'customer_name' => 'Guest Tester',
                ]
            ]);
    }
}
