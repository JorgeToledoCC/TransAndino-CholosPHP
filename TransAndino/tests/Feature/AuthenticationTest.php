<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_pantalla_de_login_se_muestra(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Iniciar sesión')
            ->assertSee('Usuario');
    }

    public function test_un_usuario_puede_iniciar_sesion(): void
    {
        $user = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@transandino.test',
            'password' => '1234',
        ]);

        $response = $this->post(route('login'), [
            'name' => 'admin',
            'password' => '1234',
        ]);

        $response->assertRedirect(route('departures.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_las_credenciales_invalidas_no_inician_sesion(): void
    {
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@transandino.test',
            'password' => '1234',
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'name' => 'admin',
            'password' => 'incorrecta',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertGuest();
    }

    public function test_un_usuario_puede_cerrar_sesion(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
