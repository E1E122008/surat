<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Pengunjung yang belum login diarahkan ke halaman login
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('login'));
    }
}
