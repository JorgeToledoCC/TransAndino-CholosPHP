<?php

namespace Database\Factories;

use App\Enums\BusType;
use App\Models\Bus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bus>
 */
class BusFactory extends Factory
{
    protected $model = Bus::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'placa' => strtoupper(fake()->unique()->bothify('####-???')),
            'tipo' => fake()->randomElement(BusType::cases()),
            'numero_asientos' => fake()->numberBetween(40, 48),
        ];
    }

    public function tipo(BusType $tipo): static
    {
        return $this->state(fn () => ['tipo' => $tipo]);
    }
}
