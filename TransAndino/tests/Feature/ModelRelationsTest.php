<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Departure;
use App\Models\Route;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_bus_puede_cubrir_varias_rutas_y_viceversa(): void
    {
        $bus = Bus::factory()->create();
        $rutas = Route::factory()->count(2)->create();

        $bus->routes()->attach($rutas->pluck('id'), ['activo' => true]);

        $this->assertCount(2, $bus->routes);
        $this->assertCount(1, $rutas->first()->buses);
        $this->assertTrue((bool) $bus->routes->first()->pivot->activo);
    }

    public function test_el_seeder_genera_un_volumen_representativo(): void
    {
        $this->seed();

        $this->assertGreaterThanOrEqual(8, Route::count());
        $this->assertGreaterThanOrEqual(6, Bus::count());
        $this->assertGreaterThan(50, Departure::count());
        $this->assertGreaterThan(100, Ticket::count());
    }
}
