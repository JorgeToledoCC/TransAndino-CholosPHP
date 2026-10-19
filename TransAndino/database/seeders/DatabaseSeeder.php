<?php

namespace Database\Seeders;

use App\Enums\BusType;
use App\Enums\TicketStatus;
use App\Models\Bus;
use App\Models\Departure;
use App\Models\Route;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'admin',
            'nombre' => 'Administrador',
            'apellido' => 'General',
            'numero' => '70000000',
            'fecha_nacimiento' => '1990-01-01',
            'is_admin' => true,
            'email' => 'admin@transandino.test',
            'password' => '1234',
            'avatar' => 'chiikawa',
        ]);

        User::factory()->create([
            'name' => 'carlos',
            'nombre' => 'Carlos',
            'apellido' => 'Mamani',
            'numero' => '71111111',
            'fecha_nacimiento' => '1995-05-05',
            'is_admin' => false,
            'email' => 'carlos@transandino.test',
            'password' => '1234',
        ]);

        $routes = collect([
            ['origen' => 'La Paz', 'destino' => 'Oruro', 'duracion' => '04:00', 'precio_base' => 30.00],
            ['origen' => 'La Paz', 'destino' => 'Cochabamba', 'duracion' => '07:30', 'precio_base' => 60.00],
            ['origen' => 'La Paz', 'destino' => 'Potosí', 'duracion' => '09:30', 'precio_base' => 80.00],
            ['origen' => 'La Paz', 'destino' => 'Tarija', 'duracion' => '13:00', 'precio_base' => 120.00],
            ['origen' => 'La Paz', 'destino' => 'Sucre', 'duracion' => '12:00', 'precio_base' => 110.00],
            ['origen' => 'La Paz', 'destino' => 'Santa Cruz', 'duracion' => '17:00', 'precio_base' => 180.00],
            ['origen' => 'La Paz', 'destino' => 'Uyuni', 'duracion' => '10:00', 'precio_base' => 95.00],
            ['origen' => 'La Paz', 'destino' => 'Arica', 'duracion' => '09:00', 'precio_base' => 150.00],
        ])->map(fn (array $ruta) => Route::create($ruta));

        $buses = collect([
            ['placa' => '1234-NRM', 'tipo' => BusType::Normal],
            ['placa' => '2345-SCM', 'tipo' => BusType::Semicama],
            ['placa' => '3456-CMA', 'tipo' => BusType::Cama],
            ['placa' => '4567-NRM', 'tipo' => BusType::Normal],
            ['placa' => '5678-SCM', 'tipo' => BusType::Semicama],
            ['placa' => '6789-CMA', 'tipo' => BusType::Cama],
        ])->map(fn (array $bus) => Bus::create([
            'placa' => $bus['placa'],
            'tipo' => $bus['tipo'],
            'numero_asientos' => rand(40, 48),
        ]));

        // Relacion muchos a muchos: cada bus queda habilitado para 2 o 3 rutas.
        foreach ($routes as $route) {
            $route->buses()->attach(
                $buses->random(rand(2, 3))->pluck('id'),
                ['activo' => true]
            );
        }

        $horarios = ['07:00', '13:00', '20:00'];
        $totalSalidas = 0;
        $totalPasajes = 0;

        foreach ($routes as $route) {
            for ($dia = 1; $dia <= 5; $dia++) {
                foreach ($horarios as $hora) {
                    $bus = $buses->random();

                    $departure = Departure::create([
                        'route_id' => $route->id,
                        'bus_id' => $bus->id,
                        'chofer' => fake()->name(),
                        'fecha' => now()->addDays($dia)->toDateString(),
                        'hora' => $hora,
                        'precio_final' => Departure::calcularPrecioFinal($route, $bus),
                    ]);

                    $totalSalidas++;

                    $asientos = collect(range(1, $bus->numero_asientos))->shuffle();

                    $vendidos = rand(0, 18);
                    foreach ($asientos->take($vendidos) as $asiento) {
                        Ticket::create([
                            'departure_id' => $departure->id,
                            'nombre_pasajero' => fake()->name(),
                            'ci_pasajero' => (string) fake()->numberBetween(1000000, 9999999),
                            'numero_asiento' => $asiento,
                            'estado' => TicketStatus::Vendido,
                        ]);
                        $totalPasajes++;
                    }

                    $anulados = rand(0, 2);
                    foreach ($asientos->slice($vendidos, $anulados) as $asiento) {
                        Ticket::create([
                            'departure_id' => $departure->id,
                            'nombre_pasajero' => fake()->name(),
                            'ci_pasajero' => (string) fake()->numberBetween(1000000, 9999999),
                            'numero_asiento' => $asiento,
                            'estado' => TicketStatus::Anulado,
                        ]);
                        $totalPasajes++;
                    }
                }
            }
        }

        $this->command?->info("Seed completado: {$routes->count()} rutas, {$buses->count()} buses, {$totalSalidas} salidas, {$totalPasajes} pasajes.");
    }
}
