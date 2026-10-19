<?php

namespace Database\Factories;

use App\Models\Route;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Route>
 */
class RouteFactory extends Factory
{
    protected $model = Route::class;

    /**
     * Destinos atendidos desde la terminal de La Paz, con su duracion tipica.
     *
     * @var array<string, string>
     */
    protected array $destinos = [
        'Oruro' => '04:00',
        'Cochabamba' => '07:30',
        'Potosí' => '09:30',
        'Tarija' => '13:00',
    ];

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $destino = fake()->randomElement(array_keys($this->destinos));

        return [
            'origen' => 'La Paz',
            'destino' => $destino,
            'duracion' => $this->destinos[$destino],
            'precio_base' => fake()->randomFloat(2, 25, 150),
        ];
    }
}
