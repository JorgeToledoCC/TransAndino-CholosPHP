<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'numero' => ['required', 'string', 'max:50'],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'name' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users,name'],
            'password' => ['required', 'string', 'min:4'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'Ese nombre de usuario ya está en uso.',
            'name.alpha_dash' => 'El usuario solo puede tener letras, números, guiones y guiones bajos.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'password.min' => 'La contraseña debe tener al menos 4 caracteres.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'usuario',
            'fecha_nacimiento' => 'fecha de nacimiento',
        ];
    }
}
