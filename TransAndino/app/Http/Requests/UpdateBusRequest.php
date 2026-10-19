<?php

namespace App\Http\Requests;

use App\Enums\BusType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBusRequest extends FormRequest
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
            'placa' => [
                'required',
                'string',
                'max:20',
                Rule::unique('buses', 'placa')->ignore($this->route('bus')),
            ],
            'tipo' => ['required', Rule::enum(BusType::class)],
            'numero_asientos' => ['required', 'integer', 'min:1', 'max:80'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'placa.unique' => 'Ya existe un bus registrado con esa placa.',
        ];
    }
}
