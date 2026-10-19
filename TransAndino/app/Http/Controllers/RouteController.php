<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRouteRequest;
use App\Http\Requests\UpdateRouteRequest;
use App\Models\Route;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RouteController extends Controller
{
    public function index(): View
    {
        $routes = Route::orderBy('origen')
            ->orderBy('destino')
            ->paginate(10);

        return view('rutas.index', compact('routes'));
    }

    public function create(): View
    {
        return view('rutas.crear');
    }

    public function store(StoreRouteRequest $request): RedirectResponse
    {
        Route::create($request->validated());

        return redirect()
            ->route('routes.index')
            ->with('success', 'Ruta registrada correctamente.');
    }

    public function edit(Route $route): View
    {
        return view('rutas.editar', compact('route'));
    }

    public function update(UpdateRouteRequest $request, Route $route): RedirectResponse
    {
        $route->update($request->validated());

        return redirect()
            ->route('routes.index')
            ->with('success', 'Ruta actualizada correctamente.');
    }

    public function destroy(Route $route): RedirectResponse
    {
        if ($route->departures()->exists()) {
            return back()->with('error', 'No se puede eliminar: la ruta tiene salidas programadas.');
        }

        $route->delete();

        return redirect()
            ->route('routes.index')
            ->with('success', 'Ruta eliminada correctamente.');
    }
}
