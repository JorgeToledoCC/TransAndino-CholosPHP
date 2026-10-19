<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_administrador_puede_crear_un_usuario(): void
    {
        $admin = User::factory()->create(['name' => 'admin', 'is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'nombre' => 'Lucía',
            'apellido' => 'Fernández',
            'numero' => '70123456',
            'fecha_nacimiento' => '2000-03-15',
            'name' => 'lucia',
            'password' => '1234',
        ]);

        $response->assertRedirect(route('users.create'));
        $this->assertDatabaseHas('users', ['name' => 'lucia', 'nombre' => 'Lucía', 'apellido' => 'Fernández']);

        $creado = User::where('name', 'lucia')->firstOrFail();
        $this->assertTrue(Hash::check('1234', $creado->password));
        $this->assertFalse($creado->is_admin);
    }

    public function test_un_usuario_normal_no_puede_crear_usuarios(): void
    {
        $user = User::factory()->create(['name' => 'carlos', 'is_admin' => false]);

        $this->actingAs($user)->get(route('users.create'))->assertForbidden();

        $this->actingAs($user)->post(route('users.store'), [
            'nombre' => 'Intruso',
            'apellido' => 'X',
            'numero' => '1',
            'fecha_nacimiento' => '2000-01-01',
            'name' => 'intruso',
            'password' => '1234',
        ])->assertForbidden();

        $this->assertDatabaseMissing('users', ['name' => 'intruso']);
    }

    public function test_un_invitado_no_accede_a_la_creacion_de_usuarios(): void
    {
        $this->get(route('users.create'))->assertRedirect(route('login'));
    }

    public function test_el_usuario_no_se_puede_repetir(): void
    {
        $admin = User::factory()->create(['name' => 'admin', 'is_admin' => true]);
        User::factory()->create(['name' => 'carlos']);

        $response = $this->actingAs($admin)->from(route('users.create'))->post(route('users.store'), [
            'nombre' => 'Otro',
            'apellido' => 'Carlos',
            'numero' => '2',
            'fecha_nacimiento' => '2000-01-01',
            'name' => 'carlos',
            'password' => '1234',
        ]);

        $response->assertSessionHasErrors('name');
    }
}
