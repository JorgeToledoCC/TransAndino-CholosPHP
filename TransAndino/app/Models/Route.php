<?php

namespace App\Models;

use Database\Factories\RouteFactory;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['origen', 'destino', 'duracion', 'precio_base'])]
#[Appends(['nombre'])]
class Route extends Model
{
    /** @use HasFactory<RouteFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'precio_base' => 'decimal:2',
        ];
    }

    public function departures(): HasMany
    {
        return $this->hasMany(Departure::class);
    }

    /**
     * Buses habilitados para cubrir esta ruta (muchos a muchos).
     */
    public function buses(): BelongsToMany
    {
        return $this->belongsToMany(Bus::class)
            ->withPivot('activo')
            ->withTimestamps();
    }

    /**
     * Nombre de la ruta para mostrar: "La Paz - Oruro".
     */
    public function getNombreAttribute(): string
    {
        return "{$this->origen} - {$this->destino}";
    }
}
