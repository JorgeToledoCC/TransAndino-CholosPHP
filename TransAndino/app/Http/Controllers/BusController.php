<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusRequest;
use App\Http\Requests\UpdateBusRequest;
use App\Models\Bus;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BusController extends Controller
{
    public function index(): View
    {
        $buses = Bus::orderBy('placa')->paginate(10);

        return view('buses.index', compact('buses'));
    }

    public function create(): View
    {
        return view('buses.crear');
    }

    public function store(StoreBusRequest $request): RedirectResponse
    {
        Bus::create($request->validated());

        return redirect()
            ->route('buses.index')
            ->with('success', 'Bus registrado correctamente.');
    }

    public function edit(Bus $bus): View
    {
        return view('buses.editar', compact('bus'));
    }

    public function update(UpdateBusRequest $request, Bus $bus): RedirectResponse
    {
        $bus->update($request->validated());

        return redirect()
            ->route('buses.index')
            ->with('success', 'Bus actualizado correctamente.');
    }

    public function destroy(Bus $bus): RedirectResponse
    {
        if ($bus->departures()->exists()) {
            return back()->with('error', 'No se puede eliminar: el bus tiene salidas asignadas.');
        }

        $bus->delete();

        return redirect()
            ->route('buses.index')
            ->with('success', 'Bus eliminado correctamente.');
    }
}
