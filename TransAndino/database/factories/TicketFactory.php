<?php

namespace Database\Factories;

use App\Enums\TicketStatus;
use App\Models\Departure;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'departure_id' => Departure::factory(),
            'nombre_pasajero' => fake()->name(),
            'ci_pasajero' => (string) fake()->numberBetween(1000000, 9999999),
            'numero_asiento' => fake()->numberBetween(1, 40),
            'estado' => TicketStatus::Vendido,
        ];
    }

    public function anulado(): static
    {
        return $this->state(fn () => ['estado' => TicketStatus::Anulado]);
    }

    public function asiento(int $numeroAsiento): static
    {
        return $this->state(fn () => ['numero_asiento' => $numeroAsiento]);
    }
}
