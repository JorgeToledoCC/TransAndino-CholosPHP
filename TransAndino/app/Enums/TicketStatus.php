<?php

namespace App\Enums;

enum TicketStatus: string
{
    case Vendido = 'vendido';
    case Anulado = 'anulado';

    /**
     * Etiqueta legible para mostrar en las vistas.
     */
    public function label(): string
    {
        return match ($this) {
            self::Vendido => 'Vendido',
            self::Anulado => 'Anulado',
        };
    }

    /**
     * Clases de Tailwind para pintar el badge de estado.
     */
    public function colorClasses(): string
    {
        return match ($this) {
            self::Vendido => 'bg-emerald-100 text-emerald-800',
            self::Anulado => 'bg-vino-100 text-vino-800',
        };
    }
}
