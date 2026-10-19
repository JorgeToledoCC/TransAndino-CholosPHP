<?php

namespace App\Http\Controllers;

use App\Enums\TicketStatus;
use App\Http\Requests\StoreTicketRequest;
use App\Models\Departure;
use App\Models\Ticket;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Departure $departure): View
    {
        $departure->load(['route', 'bus']);

        // Solo se muestran los pasajes vigentes; los anulados quedan en Actividad.
        $tickets = $departure->tickets()
            ->vendidos()
            ->orderBy('numero_asiento')
            ->get();

        return view('pasajes.index', compact('departure', 'tickets'));
    }

    /**
     * Muestra el mapa de asientos de la salida para vender un pasaje.
     */
    public function create(Departure $departure): View
    {
        $departure->load(['route', 'bus']);

        $capacidad = $departure->bus->numero_asientos;
        $ocupados = $departure->asientosOcupados();
        $pasajeros = $departure->pasajerosPorAsiento();

        return view('pasajes.crear', compact('departure', 'capacidad', 'ocupados', 'pasajeros'));
    }

    public function store(StoreTicketRequest $request, Departure $departure): RedirectResponse
    {
        $data = $request->validated();

        try {
            $ticket = DB::transaction(function () use ($departure, $data) {
                return $departure->tickets()->create([
                    'nombre_pasajero' => $data['nombre_pasajero'],
                    'ci_pasajero' => $data['ci_pasajero'],
                    'numero_asiento' => $data['numero_asiento'],
                    'estado' => TicketStatus::Vendido,
                ]);
            });
        } catch (QueryException $e) {
            // El indice unico parcial impide la doble venta del mismo asiento.
            if ($this->esViolacionDeUnicidad($e)) {
                return back()
                    ->withInput()
                    ->with('error', "El asiento N° {$data['numero_asiento']} acaba de ser vendido por otro boletero. Elige otro asiento.");
            }

            throw $e;
        }

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', "Pasaje vendido correctamente (asiento {$data['numero_asiento']}).");
    }

    /**
     * Muestra el pasaje emitido con todos sus datos, listo para imprimir.
     */
    public function show(Ticket $ticket): View
    {
        $ticket->load(['departure.route', 'departure.bus']);

        return view('pasajes.ticket', [
            'ticket' => $ticket,
            'fondoTicket' => $this->fondoTicketAleatorio(),
        ]);
    }

    /**
     * Elige al azar una imagen de fondo entre las disponibles en
     * public/images/tickets. Devuelve null si no hay ninguna.
     */
    private function fondoTicketAleatorio(): ?string
    {
        $candidatos = [];

        foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
            foreach (glob(public_path("images/tickets/*.{$ext}")) ?: [] as $archivo) {
                $candidatos[] = 'images/tickets/'.basename($archivo);
            }
        }

        if ($candidatos === []) {
            if (file_exists(public_path('images/ticket-bg.jpg'))) {
                $candidatos[] = 'images/ticket-bg.jpg';
            } elseif (file_exists(public_path('avatars/chiikawa.jpg'))) {
                $candidatos[] = 'avatars/chiikawa.jpg';
            }
        }

        return $candidatos === [] ? null : asset($candidatos[array_rand($candidatos)]);
    }

    /**
     * Anula un pasaje solo si faltan mas de 2 horas para la salida.
     */
    public function cancel(Ticket $ticket): RedirectResponse
    {
        if ($ticket->estado === TicketStatus::Anulado) {
            return back()->with('error', 'Este pasaje ya se encuentra anulado.');
        }

        if (! $ticket->puedeAnularse()) {
            return back()->with('error', 'No se puede anular: faltan 2 horas o menos para la salida.');
        }

        $ticket->update(['estado' => TicketStatus::Anulado]);

        return redirect()
            ->route('departures.tickets.index', $ticket->departure_id)
            ->with('success', "Pasaje del asiento {$ticket->numero_asiento} anulado. El asiento queda libre para la venta.");
    }

    /**
     * Detecta una violacion de restriccion unica (PostgreSQL: 23505, SQLite/MySQL: 23000).
     */
    private function esViolacionDeUnicidad(QueryException $e): bool
    {
        $sqlState = $e->errorInfo[0] ?? null;

        return in_array($sqlState, ['23505', '23000'], true)
            || str_contains(strtoupper($e->getMessage()), 'UNIQUE');
    }
}
