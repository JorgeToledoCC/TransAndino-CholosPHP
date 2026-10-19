@extends('plantillas.plantilla')

@section('title', 'Pasajes')

@section('content')
{{-- Resumen de la salida --}}
<div class="mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-vino-950 via-vino-900 to-vino-800 p-5 text-white shadow-md ring-1 ring-vino-800">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-[11px] uppercase tracking-[0.2em] text-dorado-300">Ruta</p>
            <p class="font-display text-lg font-bold">{{ $departure->route->nombre }}</p>
        </div>
        <div>
            <p class="text-[11px] uppercase tracking-[0.2em] text-dorado-300">Bus</p>
            <p class="font-semibold">{{ $departure->bus->placa }} ({{ $departure->bus->tipo->label() }})</p>
        </div>
        <div>
            <p class="text-[11px] uppercase tracking-[0.2em] text-dorado-300">Fecha y hora</p>
            <p class="font-semibold">{{ $departure->fecha->format('d/m/Y') }} — {{ $departure->horaCorta() }}</p>
        </div>
        <div>
            <p class="text-[11px] uppercase tracking-[0.2em] text-dorado-300">Precio final</p>
            <p class="font-semibold">Bs {{ number_format((float) $departure->precio_final, 2) }}</p>
        </div>
        <a href="{{ route('departures.tickets.create', $departure) }}"
           class="inline-flex items-center gap-2 rounded-lg bg-dorado-500 px-4 py-2 text-sm font-semibold text-vino-950 shadow-sm transition hover:bg-dorado-400">
            + Vender Pasaje
        </a>
    </div>
</div>

<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <span class="mb-2 block h-1 w-12 rounded-full bg-dorado-500"></span>
        <h2 class="font-display text-2xl font-bold text-vino-900">Pasajes registrados</h2>
        <p class="mt-1 text-sm text-stone-500">
            Solo se muestran los pasajes vigentes. Los anulados quedan guardados en el registro de Actividad.
        </p>
    </div>
    <a href="{{ route('departures.index') }}"
       class="text-sm text-stone-500 transition hover:text-vino-700 hover:underline">
        Volver a salidas
    </a>
</div>

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-vino-50 text-left text-xs uppercase tracking-wider text-vino-700">
                    <th class="px-4 py-3 text-center font-semibold">Asiento</th>
                    <th class="px-4 py-3 font-semibold">Pasajero</th>
                    <th class="px-4 py-3 font-semibold">CI</th>
                    <th class="px-4 py-3 text-right font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($tickets as $ticket)
                    <tr class="transition hover:bg-vino-50/40">
                        <td class="px-4 py-3 text-center font-bold text-vino-800">{{ $ticket->numero_asiento }}</td>
                        <td class="px-4 py-3 font-medium text-stone-800">{{ $ticket->nombre_pasajero }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ $ticket->ci_pasajero }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('tickets.show', $ticket) }}"
                                   class="rounded-md border border-vino-200 px-2.5 py-1 text-xs font-medium text-vino-700 transition hover:bg-vino-50">
                                    Ver
                                </a>
                                @if($ticket->puedeAnularse())
                                    <form action="{{ route('tickets.cancel', $ticket) }}" method="POST"
                                          data-confirm="¿Anular el pasaje del asiento {{ $ticket->numero_asiento }} ({{ $ticket->nombre_pasajero }})? El asiento quedará libre para la venta.">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="rounded-md border border-rose-200 px-2.5 py-1 text-xs font-medium text-rose-700 transition hover:bg-rose-50">
                                            Anular
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-stone-400">No anulable</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-stone-500">
                            Aún no se vendieron pasajes en esta salida.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
