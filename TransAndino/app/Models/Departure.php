<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Carbon\Carbon;
use Database\Factories\DepartureFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['route_id', 'bus_id', 'chofer', 'fecha', 'hora', 'precio_final'])]
class Departure extends Model
{
    /** @use HasFactory<DepartureFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'precio_final' => 'decimal:2',
        ];
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Fecha y hora exacta de la partida, en la zona horaria de la aplicacion.
     */
    public function fechaHora(): Carbon
    {
        return Carbon::parse(
            $this->fecha->toDateString().' '.$this->hora,
            config('app.timezone')
        );
    }

    /**
     * Hora en formato HH:MM para los inputs de tipo time.
     */
    public function horaCorta(): string
    {
        return substr((string) $this->hora, 0, 5);
    }

    /**
     * Regla de negocio: precio_final = precio_base de la ruta + recargo del bus.
     */
    public static function calcularPrecioFinal(Route $route, Bus $bus): float
    {
        return round((float) $route->precio_base + $bus->recargo(), 2);
    }

    /**
     * Asientos ya vendidos en esta salida.
     *
     * @return array<int, int>
     */
    public function asientosOcupados(): array
    {
        return $this->tickets()
            ->where('estado', TicketStatus::Vendido->value)
            ->pluck('numero_asiento')
            ->all();
    }

    /**
     * Mapa de asiento => nombre del pasajero para los vendidos en esta salida.
     *
     * @return array<int, string>
     */
    public function pasajerosPorAsiento(): array
    {
        return $this->tickets()
            ->where('estado', TicketStatus::Vendido->value)
            ->pluck('nombre_pasajero', 'numero_asiento')
            ->all();
    }
}
