<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Testa se a rota home retorna HTTP 200.
     *
     * @return void
     */
    public function test_home_route_returns_200()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
