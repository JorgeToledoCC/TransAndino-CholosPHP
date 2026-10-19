<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\Departure;
use App\Models\Route;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Departure>
 */
class DepartureFactory extends Factory
{
    protected $model = Departure::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'route_id' => Route::factory(),
            'bus_id' => Bus::factory(),
            'chofer' => fake()->name(),
            'fecha' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'hora' => fake()->time('H:i'),
            'precio_final' => 0,
        ];
    }

    /**
     * Calcula el precio final a partir de la ruta y el bus asociados.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Departure $departure) {
            $route = $departure->route ?? Route::find($departure->route_id);
            $bus = $departure->bus ?? Bus::find($departure->bus_id);

            if ($route && $bus) {
                $departure->precio_final = Departure::calcularPrecioFinal($route, $bus);
            }
        });
    }
}
