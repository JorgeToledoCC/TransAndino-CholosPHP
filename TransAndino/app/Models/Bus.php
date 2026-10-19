<?php

namespace App\Models;

use App\Enums\BusType;
use Database\Factories\BusFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['placa', 'tipo', 'numero_asientos'])]
class Bus extends Model
{
    /** @use HasFactory<BusFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tipo' => BusType::class,
            'numero_asientos' => 'integer',
        ];
    }

    public function departures(): HasMany
    {
        return $this->hasMany(Departure::class);
    }

    /**
     * Rutas que este bus esta habilitado a cubrir (muchos a muchos).
     */
    public function routes(): BelongsToMany
    {
        return $this->belongsToMany(Route::class)
            ->withPivot('activo')
            ->withTimestamps();
    }

    /**
     * Recargo (Bs) que aplica este bus segun su tipo.
     */
    public function recargo(): float
    {
        return $this->tipo->recargo();
    }
}
