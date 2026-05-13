<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_home_api_returns_payload(): void
    {
        ProductCategory::query()->create(['category_code' => 'POWER_TOOL', 'name' => 'POWER TOOL']);
        ProductBrand::query()->create(['brand_code' => 'BOSCH', 'brand_name' => 'BOSCH']);

        $this->getJson('/api/guest/home')
            ->assertOk()
            ->assertJsonStructure([
                'version',
                'categories',
                'brands',
                'broadcasts',
                'featured_products',
                'about',
            ]);
    }

    public function test_guest_can_create_order(): void
    {
        $category = ProductCategory::query()->create(['category_code' => 'POWER_TOOL', 'name' => 'POWER TOOL']);
        $brand = ProductBrand::query()->create(['brand_code' => 'BOSCH', 'brand_name' => 'BOSCH']);

        $product = Product::query()->create([
            'sku' => 'SKU-00001',
            'name' => 'TEST PRODUCT',
            'product_brand_code' => $brand->brand_code,
            'product_category_code' => $category->category_code,
            'description' => 'desc',
            'unit' => 'pcs',
            'weight_kg' => 1,
            'discontinued' => false,
            'photo_path' => null,
            'price_1' => 10000,
            'qty_1' => 5,
            'disc_1' => 0,
            'qty_2' => 15,
            'disc_2' => 0,
            'qty_3' => 30,
            'disc_3' => 0,
        ]);

        $payload = [
            'customer' => [
                'full_name' => 'Guest Buyer',
                'email' => 'guest@example.com',
                'phone' => '08123456789',
                'address' => 'Jl. Demo',
            ],
            'delivery_to' => 'Guest Buyer',
            'delivery_phone' => '08123456789',
            'delivery_address' => 'Jl. Demo',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ];

        $this->postJson('/api/guest/orders', $payload)
            ->assertCreated()
            ->assertJsonStructure([
                'order_id',
                'order_no',
                'status',
                'grand_total',
            ])
            ->assertJsonPath('grand_total', 20000);

        $this->assertDatabaseHas('sales_orders', [
            'customer_id' => 1,
            'grand_total' => 20000,
        ]);

        $this->assertDatabaseHas('sales_order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'final_total' => 20000,
        ]);
    }
}
