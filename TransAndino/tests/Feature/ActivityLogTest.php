<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_se_registra_el_inicio_de_sesion(): void
    {
        User::factory()->create(['name' => 'admin', 'password' => '1234', 'is_admin' => true]);

        $this->post(route('login'), ['name' => 'admin', 'password' => '1234']);

        $this->assertDatabaseHas('activity_logs', ['action' => 'sesion', 'user_name' => 'admin']);
    }

    public function test_se_registra_la_creacion_de_una_ruta(): void
    {
        $admin = User::factory()->create(['name' => 'admin', 'is_admin' => true]);

        $this->actingAs($admin)->post(route('routes.store'), [
            'origen' => 'La Paz',
            'destino' => 'Sucre',
            'duracion' => '08:00',
            'precio_base' => 90,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'creado',
            'subject_type' => 'Route',
            'user_name' => 'admin',
        ]);
    }

    public function test_solo_el_administrador_ve_el_registro(): void
    {
        $normal = User::factory()->create(['name' => 'carlos', 'is_admin' => false]);
        $admin = User::factory()->create(['name' => 'admin', 'is_admin' => true]);

        $this->actingAs($normal)->get(route('activity.index'))->assertForbidden();
        $this->actingAs($admin)->get(route('activity.index'))->assertOk();
    }
}
