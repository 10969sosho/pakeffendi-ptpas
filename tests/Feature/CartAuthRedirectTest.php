<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartAuthRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cart_redirects_to_login(): void
    {
        $this->get('/cart')->assertRedirect('/login');
    }
}

