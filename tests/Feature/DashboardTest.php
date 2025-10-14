<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa que usuário não autenticado não pode acessar dashboard.
     *
     * @return void
     */
    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Testa que usuário autenticado pode acessar dashboard.
     *
     * @return void
     */
    public function test_authenticated_user_can_access_dashboard()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Testa que a view dashboard existe e contém elementos básicos.
     *
     * @return void
     */
    public function test_dashboard_view_has_basic_elements()
    {
        $user = User::factory()->create([
            'name' => 'João Silva',
            'email' => 'joao@example.com'
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee($user->name);
    }
}

