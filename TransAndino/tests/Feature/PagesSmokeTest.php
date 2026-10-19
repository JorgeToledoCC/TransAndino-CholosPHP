<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Departure;
use App\Models\Route;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_todas_las_paginas_responden_correctamente(): void
    {
        $this->actingAs(User::factory()->create());

        $route = Route::factory()->create();
        $bus = Bus::factory()->create();
        $departure = Departure::factory()->create([
            'route_id' => $route->id,
            'bus_id' => $bus->id,
        ]);

        $ticket = Ticket::factory()->create(['departure_id' => $departure->id]);

        $this->get(route('routes.index'))->assertOk();
        $this->get(route('routes.create'))->assertOk();
        $this->get(route('routes.edit', $route))->assertOk();

        $this->get(route('buses.index'))->assertOk();
        $this->get(route('buses.create'))->assertOk();
        $this->get(route('buses.edit', $bus))->assertOk();

        $this->get(route('departures.index'))->assertOk();
        $this->get(route('departures.create'))->assertOk();
        $this->get(route('departures.edit', $departure))->assertOk();

        $this->get(route('departures.tickets.index', $departure))->assertOk();
        $this->get(route('departures.tickets.create', $departure))->assertOk();
        $this->get(route('tickets.show', $ticket))->assertOk();
    }

    public function test_las_paginas_requieren_sesion_iniciada(): void
    {
        $this->get(route('departures.index'))->assertRedirect(route('login'));
        $this->get(route('routes.index'))->assertRedirect(route('login'));
        $this->get(route('buses.index'))->assertRedirect(route('login'));
    }
}
