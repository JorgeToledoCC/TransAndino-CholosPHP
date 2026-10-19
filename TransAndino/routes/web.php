<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\DepartureController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Autenticacion: solo invitados (sin sesion).
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::post('logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Todo el panel requiere sesion iniciada.
Route::middleware('auth')->group(function () {
    Route::get('/', fn () => redirect()->route('departures.index'))->name('home');

    // CRUD de rutas y buses.
    Route::resource('routes', RouteController::class)->except('show');
    Route::resource('buses', BusController::class)->except('show');

    // CRUD de salidas programadas.
    Route::resource('departures', DepartureController::class)->except('show');

    // Mapa de asientos y venta de pasajes sobre una salida.
    Route::get('departures/{departure}/tickets', [TicketController::class, 'index'])
        ->name('departures.tickets.index');
    Route::get('departures/{departure}/tickets/create', [TicketController::class, 'create'])
        ->name('departures.tickets.create');
    Route::post('departures/{departure}/tickets', [TicketController::class, 'store'])
        ->name('departures.tickets.store');

    // Vista imprimible del pasaje emitido.
    Route::get('tickets/{ticket}', [TicketController::class, 'show'])
        ->name('tickets.show');

    // Anulacion de pasajes (PATCH, nunca GET).
    Route::patch('tickets/{ticket}/cancel', [TicketController::class, 'cancel'])
        ->name('tickets.cancel');
});

// Privilegios exclusivos del administrador.
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('usuarios/crear', [UserController::class, 'create'])->name('users.create');
    Route::post('usuarios', [UserController::class, 'store'])->name('users.store');

    Route::get('actividad', [ActivityLogController::class, 'index'])->name('activity.index');
});
