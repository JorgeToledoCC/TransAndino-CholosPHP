<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRouteRequest extends FormRequest
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
        return [
            'origen' => ['required', 'string', 'max:255'],
            'destino' => ['required', 'string', 'max:255', 'different:origen'],
            'duracion' => ['required', 'string', 'regex:/^\d{2}:\d{2}$/'],
            'precio_base' => ['required', 'numeric', 'min:0', 'max:999999.99'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'duracion.regex' => 'La duracion debe tener el formato HH:MM (ej. 04:30).',
            'destino.different' => 'El destino no puede ser igual al origen.',
        ];
    }
}
