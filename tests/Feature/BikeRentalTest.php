<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BikeRentalTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_public_bike_listing_loads(): void
    {
        $response = $this->get(route('bikes.index'));
        $response->assertStatus(200);
    }

    public function test_login_page_loads(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_register_page_loads(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_register_customer_page_loads(): void
    {
        $response = $this->get('/register/customer');
        $response->assertStatus(200);
    }

    public function test_register_company_page_loads(): void
    {
        $response = $this->get('/register/company');
        $response->assertStatus(200);
    }
}
