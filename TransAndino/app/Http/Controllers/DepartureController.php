<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartureRequest;
use App\Http\Requests\UpdateDepartureRequest;
use App\Models\Bus;
use App\Models\Departure;
use App\Models\Route;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DepartureController extends Controller
{
    public function index(): View
    {
        $departures = Departure::with(['route', 'bus'])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->paginate(10);

        return view('salidas.index', [
            'departures' => $departures,
            'fiestaImagenes' => $this->imagenesDeFiesta(),
            'fiestaAudio' => file_exists(public_path('audio/fiesta.mp3'))
                ? asset('audio/fiesta.mp3')
                : null,
        ]);
    }

    /**
     * Imagenes del huevo de pascua (easter egg) disponibles en
     * public/images/fiesta. Devuelve la lista de URLs.
     *
     * @return array<int, string>
     */
    private function imagenesDeFiesta(): array
    {
        $imagenes = [];

        foreach (['jpg', 'jpeg', 'png', 'webp', 'gif'] as $ext) {
            foreach (glob(public_path("images/fiesta/*.{$ext}")) ?: [] as $archivo) {
                $imagenes[] = asset('images/fiesta/'.basename($archivo));
            }
        }

        return $imagenes;
    }

    public function create(): View
    {
        return view('salidas.crear', [
            'routes' => Route::orderBy('origen')->orderBy('destino')->get(),
            'buses' => Bus::orderBy('placa')->get(),
        ]);
    }

    public function store(StoreDepartureRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $route = Route::findOrFail($data['route_id']);
        $bus = Bus::findOrFail($data['bus_id']);

        // Regla de negocio: el precio final no se ingresa a mano.
        $data['precio_final'] = Departure::calcularPrecioFinal($route, $bus);

        $departure = Departure::create($data);

        return redirect()
            ->route('departures.index')
            ->with('success', 'Salida programada. Precio final calculado: Bs '.number_format((float) $departure->precio_final, 2));
    }

    public function edit(Departure $departure): View
    {
        return view('salidas.editar', [
            'departure' => $departure,
            'routes' => Route::orderBy('origen')->orderBy('destino')->get(),
            'buses' => Bus::orderBy('placa')->get(),
        ]);
    }

    public function update(UpdateDepartureRequest $request, Departure $departure): RedirectResponse
    {
        $data = $request->validated();

        $route = Route::findOrFail($data['route_id']);
        $bus = Bus::findOrFail($data['bus_id']);

        $data['precio_final'] = Departure::calcularPrecioFinal($route, $bus);

        $departure->update($data);

        return redirect()
            ->route('departures.index')
            ->with('success', 'Salida actualizada. Precio final recalculado: Bs '.number_format((float) $departure->precio_final, 2));
    }

    public function destroy(Departure $departure): RedirectResponse
    {
        if ($departure->tickets()->exists()) {
            return back()->with('error', 'No se puede eliminar: la salida ya tiene pasajes registrados.');
        }

        $departure->delete();

        return redirect()
            ->route('departures.index')
            ->with('success', 'Salida eliminada correctamente.');
    }
}
