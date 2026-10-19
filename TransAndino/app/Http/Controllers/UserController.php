<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Formulario para que el administrador cree una cuenta.
     */
    public function create(): View
    {
        return view('usuarios.crear');
    }

    /**
     * Guarda la nueva cuenta (la contraseña se cifra con el cast "hashed").
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()
            ->route('users.create')
            ->with('success', 'Usuario creado correctamente.');
    }
}
