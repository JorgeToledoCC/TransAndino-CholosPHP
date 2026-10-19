<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'user_name', 'action', 'subject_type', 'subject_id', 'description', 'ip_address'])]
class ActivityLog extends Model
{
    /**
     * Registra un evento en el historial de actividad.
     */
    public static function registrar(string $accion, string $descripcion, ?Model $sujeto = null): self
    {
        $usuario = auth()->user();

        return self::create([
            'user_id' => $usuario?->id,
            'user_name' => $usuario?->name,
            'action' => $accion,
            'subject_type' => $sujeto ? class_basename($sujeto) : null,
            'subject_id' => $sujeto?->getKey(),
            'description' => $descripcion,
            'ip_address' => app()->runningInConsole() ? null : request()->ip(),
        ]);
    }

    /**
     * Color del badge segun la accion registrada.
     */
    public function colorClasses(): string
    {
        return match ($this->action) {
            'creado' => 'bg-emerald-100 text-emerald-800',
            'actualizado' => 'bg-dorado-300 text-dorado-700',
            'eliminado', 'anulado' => 'bg-rose-100 text-rose-800',
            'sesion' => 'bg-vino-100 text-vino-800',
            default => 'bg-stone-200 text-stone-700',
        };
    }
}
