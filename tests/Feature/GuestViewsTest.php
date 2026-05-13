<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestViewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_home_renders(): void
    {
        $category = ProductCategory::query()->create(['category_code' => 'POWER_TOOL', 'name' => 'POWER TOOL']);
        $brand = ProductBrand::query()->create(['brand_code' => 'BOSCH', 'brand_name' => 'BOSCH']);

        Product::query()->create([
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

        $this->get('/')
            ->assertOk()
            ->assertSee('PAS', false);
    }
}
