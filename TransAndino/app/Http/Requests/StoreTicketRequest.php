<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $departure = $this->route('departure');
        $capacidad = $departure?->bus?->numero_asientos ?? 0;

        return [
            'nombre_pasajero' => ['required', 'string', 'max:255'],
            'ci_pasajero' => ['required', 'string', 'max:20'],
            'numero_asiento' => ['required', 'integer', 'min:1', "max:{$capacidad}"],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'numero_asiento.max' => 'El numero de asiento supera la capacidad del bus.',
            'numero_asiento.min' => 'El numero de asiento debe ser al menos 1.',
        ];
    }
}
