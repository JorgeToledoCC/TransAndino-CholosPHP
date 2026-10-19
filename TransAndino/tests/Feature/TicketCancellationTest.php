<?php

namespace Tests\Feature;

use App\Enums\TicketStatus;
use App\Models\Departure;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketCancellationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_se_puede_anular_un_pasaje_con_mas_de_dos_horas(): void
    {
        $cuando = now()->addHours(5);

        $departure = Departure::factory()->create([
            'fecha' => $cuando->toDateString(),
            'hora' => $cuando->format('H:i'),
        ]);

        $ticket = Ticket::factory()->asiento(3)->create(['departure_id' => $departure->id]);

        $this->assertTrue($ticket->puedeAnularse());

        $response = $this->patch(route('tickets.cancel', $ticket));

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tickets', ['id' => $ticket->id, 'estado' => 'anulado']);
    }

    public function test_no_se_puede_anular_un_pasaje_con_menos_de_dos_horas(): void
    {
        $cuando = now()->addHour();

        $departure = Departure::factory()->create([
            'fecha' => $cuando->toDateString(),
            'hora' => $cuando->format('H:i'),
        ]);

        $ticket = Ticket::factory()->asiento(3)->create(['departure_id' => $departure->id]);

        $this->assertFalse($ticket->puedeAnularse());

        $response = $this->patch(route('tickets.cancel', $ticket));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('tickets', ['id' => $ticket->id, 'estado' => 'vendido']);
    }

    public function test_un_asiento_anulado_queda_libre_para_la_venta(): void
    {
        $cuando = now()->addHours(5);

        $departure = Departure::factory()->create([
            'fecha' => $cuando->toDateString(),
            'hora' => $cuando->format('H:i'),
        ]);

        $ticket = Ticket::factory()->asiento(3)->create(['departure_id' => $departure->id]);
        $ticket->update(['estado' => TicketStatus::Anulado]);

        $response = $this->post(route('departures.tickets.store', $departure), [
            'nombre_pasajero' => 'Nuevo Pasajero',
            'ci_pasajero' => '9999999',
            'numero_asiento' => 3,
        ]);

        $response->assertSessionHas('success');
        $this->assertSame(2, $departure->tickets()->count());
        $this->assertSame(1, $departure->tickets()->vendidos()->count());
    }
}
