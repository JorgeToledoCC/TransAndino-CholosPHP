<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesion.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Valida las credenciales e inicia la sesion del usuario.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'name.required' => 'Ingresa tu usuario.',
            'password.required' => 'Ingresa tu contraseña.',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            ActivityLog::registrar('sesion', 'Intento de inicio de sesión fallido (usuario: '.$credentials['name'].')');

            throw ValidationException::withMessages([
                'name' => 'Usuario o contraseña incorrectos. Verifica tus datos e inténtalo de nuevo.',
            ]);
        }

        $request->session()->regenerate();

        ActivityLog::registrar('sesion', 'Inició sesión');

        return redirect()->intended(route('departures.index'));
    }

    /**
     * Cierra la sesion del usuario.
     */
    public function logout(Request $request): RedirectResponse
    {
        ActivityLog::registrar('sesion', 'Cerró sesión');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }
}
