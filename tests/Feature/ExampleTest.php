<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test if the application is up and running.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/up');
        $response->assertStatus(200);
    }
}
