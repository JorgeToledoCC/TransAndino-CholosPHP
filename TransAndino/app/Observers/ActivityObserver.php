<?php

namespace App\Observers;

use App\Enums\TicketStatus;
use App\Models\ActivityLog;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Model;

class ActivityObserver
{
    public function created(Model $model): void
    {
        ActivityLog::registrar('creado', $this->frase($model, 'Registró'), $model);
    }

    public function updated(Model $model): void
    {
        if ($model instanceof Ticket && $model->estado === TicketStatus::Anulado) {
            ActivityLog::registrar(
                'anulado',
                'Anuló el pasaje del asiento '.$model->numero_asiento.' de '.$model->nombre_pasajero,
                $model
            );

            return;
        }

        ActivityLog::registrar('actualizado', $this->frase($model, 'Actualizó'), $model);
    }

    public function deleted(Model $model): void
    {
        ActivityLog::registrar('eliminado', $this->frase($model, 'Eliminó'), $model);
    }

    /**
     * Frase legible que describe el registro afectado.
     */
    protected function frase(Model $model, string $verbo): string
    {
        $tipo = match (class_basename($model)) {
            'Route' => 'la ruta',
            'Bus' => 'el bus',
            'Departure' => 'la salida',
            'Ticket' => 'el pasaje',
            'User' => 'el usuario',
            default => 'el registro',
        };

        $detalle = match (class_basename($model)) {
            'Departure' => trim(($model->route?->nombre ?? 'salida').' del '.optional($model->fecha)->format('d/m/Y')),
            'Ticket' => 'asiento '.$model->numero_asiento.' de '.$model->nombre_pasajero,
            default => $model->nombre ?? $model->placa ?? $model->name ?? ('#'.$model->getKey()),
        };

        return trim($verbo.' '.$tipo.' '.$detalle);
    }
}
