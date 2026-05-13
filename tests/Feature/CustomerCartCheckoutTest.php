<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CustomerCartCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_register_add_to_cart_checkout_and_see_order(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

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

        $this->post('/register', [
            'full_name' => 'Customer One',
            'email' => 'customer@example.com',
            'phone' => '08123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ])->assertRedirect('/profile');

        $this->postJson('/cart/items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ])->assertCreated()->assertJsonPath('summary.total_items', 2);

        $checkout = $this->post('/cart/checkout', []);
        $checkout->assertRedirect();

        $this->assertDatabaseHas('sales_orders', [
            'customer_id' => 1,
            'grand_total' => 20000,
        ]);

        $this->assertDatabaseHas('sales_order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'final_total' => 20000,
        ]);

        $this->get('/orders')->assertOk()->assertSee('W', false);
    }

    public function test_sales_can_checkout_for_selected_buyer_with_selected_address(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $sales = User::query()->create([
            'name' => 'Sales One',
            'email' => 'sales@example.com',
            'password' => bcrypt('password123'),
            'role' => 'sales',
            'photo_path' => null,
        ]);

        $buyer = Customer::query()->create([
            'customer_code' => 'CUST-001',
            'full_name' => 'Buyer One',
            'account_type' => 'Retail',
            'ktp_number' => '123',
            'npwp' => null,
            'email' => 'buyer@example.com',
            'password' => bcrypt('password123'),
            'status' => 'active',
            'address' => 'Alamat lama',
            'province' => 'Jawa Barat',
            'city' => 'Bandung',
            'postal_code' => '40111',
            'phone' => '081200000001',
            'contact_person' => 'Buyer One',
            'company_name' => null,
            'internal_code' => null,
            'sales_id' => $sales->id,
        ]);

        $buyerAddress = CustomerAddress::query()->create([
            'customer_id' => $buyer->id,
            'label' => 'Kantor',
            'recipient_name' => 'Buyer One',
            'phone' => '081200000001',
            'address' => 'Jl. Test No. 1',
            'province' => 'Jawa Barat',
            'city' => 'Bandung',
            'postal_code' => '40111',
            'is_active' => true,
        ]);

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

        $this->actingAs($sales);

        $this->postJson('/cart/items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ])->assertCreated()->assertJsonPath('summary.total_items', 2);

        $cart = Cart::query()->whereNull('customer_id')->latest('id')->first();
        $this->assertNotNull($cart);
        $cartSid = $cart->session_id;

        $this->withCookie('pas_cart_sid', $cartSid)->post('/cart/checkout', [
            'customer_id' => $buyer->id,
            'address_id' => $buyerAddress->id,
        ])->assertRedirect();

        $this->assertDatabaseHas('sales_orders', [
            'customer_id' => $buyer->id,
            'sales_id' => $sales->id,
            'delivery_address' => $buyerAddress->full_address,
            'grand_total' => 20000,
        ]);
    }

    public function test_order_no_increment_and_reset_daily(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        Carbon::setTestNow(Carbon::create(2026, 3, 16, 10, 0, 0));

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

        $this->post('/register', [
            'full_name' => 'Customer One',
            'email' => 'customer@example.com',
            'phone' => '08123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ])->assertRedirect('/profile');

        $this->postJson('/cart/items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ])->assertCreated();
        $this->post('/cart/checkout', [])->assertRedirect();

        $this->postJson('/cart/items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ])->assertCreated();
        $this->post('/cart/checkout', [])->assertRedirect();

        $prefix = 'W'.now()->format('ymd');

        $this->assertDatabaseHas('sales_orders', ['order_no' => $prefix.'0001']);
        $this->assertDatabaseHas('sales_orders', ['order_no' => $prefix.'0002']);

        Carbon::setTestNow(Carbon::create(2026, 3, 17, 10, 0, 0));

        $this->postJson('/cart/items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ])->assertCreated();
        $this->post('/cart/checkout', [])->assertRedirect();

        $prefixNext = 'W'.now()->format('ymd');
        $this->assertDatabaseHas('sales_orders', ['order_no' => $prefixNext.'0001']);
    }
}
