<?php

namespace Tests\Feature;

use App\Models\Departure;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketSaleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_al_vender_un_pasaje_se_muestra_el_ticket_emitido(): void
    {
        $departure = Departure::factory()->create([
            'fecha' => now()->addDays(3)->toDateString(),
            'hora' => '10:00',
        ]);

        $response = $this->post(route('departures.tickets.store', $departure), [
            'nombre_pasajero' => 'Ana Quispe',
            'ci_pasajero' => '1234567',
            'numero_asiento' => 5,
        ]);

        $ticket = Ticket::firstOrFail();

        $response->assertRedirect(route('tickets.show', $ticket));
        $response->assertSessionHas('success');

        $this->get(route('tickets.show', $ticket))
            ->assertOk()
            ->assertSee('Ana Quispe')
            ->assertSee('1234567');
    }

    public function test_no_se_puede_vender_el_mismo_asiento_dos_veces(): void
    {
        $departure = Departure::factory()->create([
            'fecha' => now()->addDays(3)->toDateString(),
            'hora' => '10:00',
        ]);

        Ticket::factory()->asiento(5)->create(['departure_id' => $departure->id]);

        $response = $this->from(route('departures.tickets.create', $departure))
            ->post(route('departures.tickets.store', $departure), [
                'nombre_pasajero' => 'Ana Quispe',
                'ci_pasajero' => '1234567',
                'numero_asiento' => 5,
            ]);

        $response->assertSessionHas('error');
        $this->assertSame(1, $departure->tickets()->vendidos()->count());
    }

    public function test_la_validacion_rechaza_un_asiento_fuera_de_capacidad(): void
    {
        $departure = Departure::factory()->create([
            'fecha' => now()->addDays(3)->toDateString(),
            'hora' => '10:00',
        ]);

        $response = $this->from(route('departures.tickets.create', $departure))
            ->post(route('departures.tickets.store', $departure), [
                'nombre_pasajero' => 'Ana Quispe',
                'ci_pasajero' => '1234567',
                'numero_asiento' => $departure->bus->numero_asientos + 1,
            ]);

        $response->assertSessionHasErrors('numero_asiento');
        $this->assertDatabaseCount('tickets', 0);
    }
}
