<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Carbon\Carbon;
use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['departure_id', 'nombre_pasajero', 'ci_pasajero', 'numero_asiento', 'estado'])]
class Ticket extends Model
{
    /** @use HasFactory<TicketFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'estado' => TicketStatus::class,
            'numero_asiento' => 'integer',
        ];
    }

    public function departure(): BelongsTo
    {
        return $this->belongsTo(Departure::class);
    }

    public function scopeVendidos(Builder $query): Builder
    {
        return $query->where('estado', TicketStatus::Vendido->value);
    }

    /**
     * Regla de negocio: un pasaje solo se puede anular si esta "vendido" y
     * faltan mas de 2 horas para la fecha y hora exacta de la salida.
     */
    public function puedeAnularse(): bool
    {
        if ($this->estado === TicketStatus::Anulado) {
            return false;
        }

        return Carbon::now()->lt($this->departure->fechaHora()->copy()->subHours(2));
    }
}
