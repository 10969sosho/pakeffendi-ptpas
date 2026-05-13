<?php

namespace Tests\Feature;

use Database\Seeders\BuyerSeeder;
use Database\Seeders\ProductBrandSeeder;
use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeedersTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_category_seeder_inserts_all_categories(): void
    {
        $this->seed(ProductCategorySeeder::class);

        $this->assertDatabaseCount('product_categories', count(ProductCategorySeeder::CATEGORIES));
        $this->assertDatabaseHas('product_categories', ['name' => 'POWER TOOL']);
        $this->assertDatabaseHas('product_categories', ['name' => 'WELDING']);
        $this->assertDatabaseHas('product_categories', ['name' => 'AIR TOOL & COMPRESSOR']);
    }

    public function test_buyer_seeder_inserts_buyer(): void
    {
        $this->seed(BuyerSeeder::class);

        $this->assertDatabaseHas('customers', [
            'email' => 'buyer@example.com',
            'full_name' => 'BUYER DEMO',
        ]);
    }

    public function test_product_seeder_inserts_products_and_brands(): void
    {
        $this->seed(ProductSeeder::class);

        $this->assertDatabaseCount('product_categories', count(ProductCategorySeeder::CATEGORIES));
        $this->assertDatabaseCount('product_brands', count(ProductBrandSeeder::BRANDS));
        $this->assertDatabaseHas('products', ['discontinued' => 0]);
    }
}
