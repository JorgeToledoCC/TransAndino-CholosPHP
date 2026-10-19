<?php

namespace App\Enums;

enum BusType: string
{
    case Normal = 'normal';
    case Semicama = 'semicama';
    case Cama = 'cama';

    /**
     * Etiqueta legible para mostrar en las vistas.
     */
    public function label(): string
    {
        return match ($this) {
            self::Normal => 'Normal',
            self::Semicama => 'Semicama',
            self::Cama => 'Cama',
        };
    }

    /**
     * Recargo (Bs) que se suma al precio base de la ruta segun el tipo de bus.
     */
    public function recargo(): float
    {
        return match ($this) {
            self::Normal => 0.0,
            self::Semicama => 30.0,
            self::Cama => 60.0,
        };
    }

    /**
     * Opciones [valor => etiqueta] para los <select> de los formularios.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $type) => [$type->value => $type->label()])
            ->all();
    }
}
