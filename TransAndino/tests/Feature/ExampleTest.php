<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * El invitado sin sesion es enviado al login.
     */
    public function test_el_invitado_es_redirigido_al_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    /**
     * El usuario autenticado va al listado de salidas.
     */
    public function test_el_usuario_autenticado_es_redirigido_a_departures(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/')->assertRedirect(route('departures.index'));
    }
}
