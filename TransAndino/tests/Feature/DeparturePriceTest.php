<?php

namespace Tests\Feature;

use App\Enums\BusType;
use App\Models\Bus;
use App\Models\Departure;
use App\Models\Route;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeparturePriceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_el_precio_final_se_calcula_segun_el_tipo_de_bus(): void
    {
        $route = Route::factory()->create(['precio_base' => 100]);

        $this->assertSame(100.0, Departure::calcularPrecioFinal($route, Bus::factory()->tipo(BusType::Normal)->create()));
        $this->assertSame(130.0, Departure::calcularPrecioFinal($route, Bus::factory()->tipo(BusType::Semicama)->create()));
        $this->assertSame(160.0, Departure::calcularPrecioFinal($route, Bus::factory()->tipo(BusType::Cama)->create()));
    }

    public function test_al_programar_una_salida_se_guarda_el_precio_calculado(): void
    {
        $route = Route::factory()->create(['precio_base' => 50]);
        $bus = Bus::factory()->tipo(BusType::Cama)->create();

        $response = $this->post(route('departures.store'), [
            'route_id' => $route->id,
            'bus_id' => $bus->id,
            'chofer' => 'Juan Pérez',
            'fecha' => now()->addDays(2)->toDateString(),
            'hora' => '08:30',
        ]);

        $response->assertRedirect(route('departures.index'));

        $this->assertDatabaseHas('departures', [
            'route_id' => $route->id,
            'bus_id' => $bus->id,
            'precio_final' => 110.00,
        ]);
    }

    public function test_no_permite_programar_una_salida_en_el_pasado(): void
    {
        $route = Route::factory()->create();
        $bus = Bus::factory()->create();

        $response = $this->from(route('departures.create'))->post(route('departures.store'), [
            'route_id' => $route->id,
            'bus_id' => $bus->id,
            'chofer' => 'Juan Pérez',
            'fecha' => now()->subDay()->toDateString(),
            'hora' => '08:30',
        ]);

        $response->assertSessionHasErrors('fecha');
        $this->assertDatabaseCount('departures', 0);
    }
}
