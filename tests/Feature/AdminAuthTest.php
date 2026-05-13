<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_redirects_to_login_when_unauthenticated(): void
    {
        $this->get('/admin/dashboard')
            ->assertRedirect('/admin/login');
    }

    public function test_admin_login_allows_admin_user(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
        ]);

        $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ])->assertRedirect('/admin/dashboard');
    }
}
