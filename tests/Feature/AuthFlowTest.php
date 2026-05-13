<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_login_page_is_accessible()
    {
        $response = $this->get(route('guest.login'));
        $response->assertStatus(200);
    }

    public function test_customer_and_admin_can_be_logged_in_simultaneously()
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $customer = Customer::create([
            'email' => 'buyer@example.com',
            'password' => Hash::make('password'),
            'full_name' => 'Buyer',
            'phone' => '08112',
            'status' => 'active'
        ]);

        // 1. Login as Customer
        $this->post(route('guest.login.store'), [
            'login' => 'buyer@example.com',
            'password' => 'password',
        ]);
        $this->assertAuthenticatedAs($customer, 'customer');

        // 2. Login as Admin (in same session)
        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
        $this->assertAuthenticatedAs($admin, 'web');

        // 3. Verify BOTH are still logged in
        $this->assertAuthenticatedAs($customer, 'customer');
        $this->assertAuthenticatedAs($admin, 'web');

        // 4. Access protected routes for both
        $this->get(route('guest.profile.index'))->assertStatus(200);
        $this->get(route('admin.dashboard'))->assertStatus(200);

        // 5. Logout Customer ONLY
        $this->post(route('guest.logout'));
        $this->assertGuest('customer');
        $this->assertAuthenticatedAs($admin, 'web'); // Admin should still be logged in

        // 6. Logout Admin
        $this->post(route('admin.logout'));
        $this->assertGuest('web');
        $this->assertGuest('customer');
    }

    public function test_customer_cannot_login_if_pending()
    {
        $customer = Customer::create([
            'email' => 'pending@example.com',
            'password' => Hash::make('password'),
            'full_name' => 'Pending Customer',
            'phone' => '08999',
            'customer_code' => 'C02',
            'account_type' => 'Retail',
            'ktp_number' => '123',
            'contact_person' => 'Mr. P',
            'status' => 'pending',
        ]);

        $response = $this->post(route('guest.login.store'), [
            'login' => 'pending@example.com',
            'password' => 'password',
        ]);

        // Should redirect back with error
        $response->assertSessionHasErrors(['login']);
        $this->assertGuest('customer');
    }

    public function test_admin_login_page_is_accessible()
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
    }

    public function test_customer_can_login()
    {
        $customer = Customer::create([
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'full_name' => 'Test Customer',
            'phone' => '08123456789',
            'customer_code' => 'CUST001',
            'account_type' => 'Retail',
            'ktp_number' => '1234567890123456',
            'contact_person' => 'Mr. Tester',
            'status' => 'active', // Explicitly set status to active
        ]);

        $response = $this->post(route('guest.login.store'), [
            'login' => 'customer@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/profile');
        $this->assertAuthenticated('customer');
        $response->assertSessionHas('success');
    }

    public function test_sales_can_login_via_guest_login()
    {
        $sales = User::factory()->create([
            'email' => 'sales@example.com',
            'password' => Hash::make('password'),
            'role' => 'sales',
        ]);

        $response = $this->post(route('guest.login.store'), [
            'login' => 'sales@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/profile');
        $this->assertAuthenticated('web');
        $response->assertSessionHas('success');
    }

    public function test_admin_cannot_login_via_guest_login()
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $response = $this->post(route('guest.login.store'), [
            'login' => 'admin@example.com',
            'password' => 'password',
        ]);

        // Should fail and redirect back
        $response->assertSessionHasErrors();
        $this->assertGuest('web');
    }

    public function test_admin_can_login_via_admin_login()
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $response = $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated('web');
    }

    public function test_sales_cannot_login_via_admin_login()
    {
        $sales = User::factory()->create([
            'email' => 'sales@example.com',
            'password' => Hash::make('password'),
            'role' => 'sales',
        ]);

        $response = $this->post(route('admin.login.store'), [
            'email' => 'sales@example.com',
            'password' => 'password',
        ]);

        // Should fail authentication/authorization logic
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest('web');
    }

    public function test_guest_cannot_access_cart()
    {
        $response = $this->get(route('guest.cart.index'));
        
        $response->assertRedirect(route('guest.login'));
        $response->assertSessionHas('info'); // Updated to info based on previous middleware change
    }

    public function test_guest_cannot_access_admin_dashboard()
    {
        $response = $this->get(route('admin.dashboard'));
        
        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHas('error');
    }

    public function test_sales_cannot_access_admin_dashboard()
    {
        $sales = User::factory()->create([
            'role' => 'sales',
        ]);

        $this->actingAs($sales, 'web');

        $response = $this->get(route('admin.dashboard'));
        
        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHas('error');
    }

    public function test_customer_cannot_access_admin_dashboard()
    {
        $customer = Customer::create([
            'email' => 'cust@example.com',
            'password' => Hash::make('password'),
            'full_name' => 'Cust',
            'phone' => '08111',
            'customer_code' => 'C01',
            'account_type' => 'Retail',
            'ktp_number' => '1234567890123456',
            'contact_person' => 'Mr. Tester',
            'status' => 'active'
        ]);

        $this->actingAs($customer, 'customer');

        $response = $this->get(route('admin.dashboard'));
        
        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHas('error');
    }

    public function test_customer_can_see_admin_login_page()
    {
        $customer = Customer::create([
            'email' => 'buyer@example.com',
            'password' => Hash::make('password'),
            'full_name' => 'Buyer',
            'phone' => '08112',
            'status' => 'active'
        ]);

        $this->actingAs($customer, 'customer');

        $response = $this->get(route('admin.login'));
        
        $response->assertStatus(200);
    }
}
