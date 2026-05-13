<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LogoutSeparationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_logout_does_not_logout_customer()
    {
        // 1. Create Users
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

        // 2. Login Both
        $this->post(route('guest.login.store'), [
            'login' => 'buyer@example.com',
            'password' => 'password',
        ]);
        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($customer, 'customer');
        $this->assertAuthenticatedAs($admin, 'web');

        // 3. Logout Admin
        $this->post(route('admin.logout'));

        // 4. Verify Admin is logged out, Customer is still logged in
        $this->assertGuest('web');
        $this->assertAuthenticatedAs($customer, 'customer');
    }

    public function test_customer_logout_does_not_logout_admin()
    {
        // 1. Create Users
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

        // 2. Login Both
        $this->post(route('guest.login.store'), [
            'login' => 'buyer@example.com',
            'password' => 'password',
        ]);
        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($customer, 'customer');
        $this->assertAuthenticatedAs($admin, 'web');

        // 3. Logout Customer
        $this->post(route('guest.logout'));

        // 4. Verify Customer is logged out, Admin is still logged in
        $this->assertGuest('customer');
        $this->assertAuthenticatedAs($admin, 'web');
    }

    public function test_sales_logout_logs_out_sales()
    {
        // 1. Create Sales
        $sales = User::factory()->create([
            'email' => 'sales@example.com',
            'password' => Hash::make('password'),
            'role' => 'sales',
        ]);

        // 2. Login Sales (via Guest Login)
        $this->post(route('guest.login.store'), [
            'login' => 'sales@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($sales, 'web');

        // 3. Logout Sales (via Guest Logout)
        $this->post(route('guest.logout'));

        // 4. Verify Sales is logged out
        $this->assertGuest('web');
    }

    public function test_sales_and_customer_logout_both_if_logged_in()
    {
        // 1. Create Sales and Customer
        $sales = User::factory()->create([
            'email' => 'sales@example.com',
            'password' => Hash::make('password'),
            'role' => 'sales',
        ]);

        $customer = Customer::create([
            'email' => 'buyer@example.com',
            'password' => Hash::make('password'),
            'full_name' => 'Buyer',
            'phone' => '08112',
            'status' => 'active'
        ]);

        // 2. Login Sales (via Guest Login - this sets 'web' guard)
        // Note: In real world, Sales logs in via Guest Login.
        // If they want to be Customer too, they would login AGAIN as Customer?
        // Wait, the Guest Login form handles EITHER/OR.
        // It checks Customer first, then Sales.
        // If I am Sales, I can't login as Customer unless I use a different email/phone.
        // If I use the same email, it might pick Customer first.
        // But let's assume different accounts.

        // Login Sales
        $this->post(route('guest.login.store'), [
            'login' => 'sales@example.com',
            'password' => 'password',
        ]);

        // Login Customer
        $this->post(route('guest.login.store'), [
            'login' => 'buyer@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($sales, 'web');
        $this->assertAuthenticatedAs($customer, 'customer');

        // 3. Logout (Guest Logout)
        $this->post(route('guest.logout'));

        // 4. Verify BOTH are logged out
        $this->assertGuest('web');
        $this->assertGuest('customer');
    }
}
