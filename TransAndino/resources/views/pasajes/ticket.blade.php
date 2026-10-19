@extends('plantillas.plantilla')

@php
    $departure = $ticket->departure;
    $route = $departure->route;
    $bus = $departure->bus;
    $folio = str_pad((string) $ticket->id, 6, '0', STR_PAD_LEFT);
    $codigo = 'TA-'.$folio;
    $precioBase = (float) $route->precio_base;
    $total = (float) $departure->precio_final;
    $recargo = $bus->recargo();
@endphp

@section('title', 'Pasaje '.$codigo)

@push('styles')
    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 12mm;
            }

            body {
                background: #ffffff !important;
            }

            header,
            footer,
            .no-print {
                display: none !important;
            }

            main {
                max-width: none !important;
                padding: 0 !important;
            }

            .ticket-sheet {
                border: 1px solid #d6d3d1 !important;
                box-shadow: none !important;
                break-inside: avoid;
            }

            .ticket-sheet * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
@endpush

@section('content')
<div class="no-print mx-auto mb-5 flex max-w-3xl flex-wrap items-center justify-between gap-3">
    <a href="{{ route('departures.tickets.index', $departure) }}"
       class="inline-flex items-center gap-2 rounded-lg border border-stone-300 bg-white px-4 py-2 text-sm font-medium text-stone-700 shadow-sm transition hover:bg-stone-50">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Volver a los pasajes
    </a>

    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('departures.tickets.create', $departure) }}"
           class="inline-flex items-center gap-2 rounded-lg border border-vino-200 bg-vino-50 px-4 py-2 text-sm font-semibold text-vino-700 shadow-sm transition hover:bg-vino-100">
            + Vender otro pasaje
        </a>
        <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-2 rounded-lg bg-vino-800 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-vino-900">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 9V3h12v6"/>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                <rect x="6" y="14" width="12" height="7" rx="1"/>
            </svg>
            Imprimir / Guardar PDF
        </button>
    </div>
</div>

<div class="ticket-sheet relative mx-auto max-w-3xl overflow-hidden rounded-3xl bg-white shadow-xl ring-1 ring-stone-200">
    {{-- Marca de agua: se mezcla con el fondo para no tapar los datos --}}
    @if($fondoTicket)
        <img src="{{ $fondoTicket }}" alt="" aria-hidden="true"
             class="pointer-events-none absolute -bottom-12 -right-10 w-72 select-none opacity-[0.10]"
             style="mix-blend-mode: multiply;">
    @endif

    {{-- Encabezado --}}
    <div class="relative flex flex-wrap items-center justify-between gap-4 bg-gradient-to-r from-vino-950 via-vino-900 to-vino-800 px-6 py-5 text-white sm:px-8">
        <div class="flex items-center gap-3">
            <span class="grid h-11 w-11 place-items-center rounded-full bg-dorado-500/15 ring-1 ring-dorado-400/40">
                <svg class="h-6 w-6 text-dorado-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 17V7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10"/>
                    <path d="M4 11h16"/>
                    <path d="M7 19v-2M17 19v-2"/>
                    <circle cx="8.5" cy="14" r="1"/>
                    <circle cx="15.5" cy="14" r="1"/>
                </svg>
            </span>
            <div class="leading-tight">
                <p class="font-display text-xl font-bold">Trans Andino</p>
                <p class="text-[10px] font-medium uppercase tracking-[0.3em] text-dorado-300">Transporte interdepartamental</p>
            </div>
        </div>

        <div class="text-right">
            <p class="text-[10px] font-medium uppercase tracking-[0.25em] text-dorado-300">Pasaje</p>
            <p class="font-mono text-lg font-bold tracking-wider">{{ $codigo }}</p>
            <span class="mt-1 inline-block rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $ticket->estado->colorClasses() }}">
                {{ $ticket->estado->label() }}
            </span>
        </div>
    </div>

    <div class="relative px-6 py-6 sm:px-8">
        {{-- Ruta --}}
        <div class="flex items-center gap-4">
            <div class="min-w-0">
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-stone-400">Origen</p>
                <p class="truncate font-display text-2xl font-bold text-vino-900 sm:text-3xl">{{ $route->origen }}</p>
            </div>

            <div class="flex min-w-[3rem] flex-1 flex-col items-center">
                <span class="mb-1 text-[10px] font-medium uppercase tracking-wider text-stone-400">Duración {{ $route->duracion }}</span>
                <div class="flex w-full items-center gap-1 text-dorado-500">
                    <span class="h-2 w-2 rounded-full border-2 border-dorado-500"></span>
                    <span class="h-px flex-1 bg-dorado-400"></span>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 12h15M13 6l6 6-6 6"/>
                    </svg>
                </div>
            </div>

            <div class="min-w-0 text-right">
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-stone-400">Destino</p>
                <p class="truncate font-display text-2xl font-bold text-vino-900 sm:text-3xl">{{ $route->destino }}</p>
            </div>
        </div>

        {{-- Datos principales --}}
        <div class="mt-6 grid grid-cols-2 gap-x-6 gap-y-4 sm:grid-cols-4">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-stone-400">Fecha</p>
                <p class="text-base font-semibold text-stone-900">{{ $departure->fecha->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-stone-400">Hora</p>
                <p class="text-base font-semibold text-stone-900">{{ $departure->horaCorta() }}</p>
            </div>
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-stone-400">Asiento</p>
                <p class="font-display text-2xl font-bold leading-none text-vino-800">{{ $ticket->numero_asiento }}</p>
            </div>
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-stone-400">Bus</p>
                <p class="text-base font-semibold text-stone-900">{{ $bus->placa }}</p>
                <p class="text-xs text-stone-500">{{ $bus->tipo->label() }} · {{ $bus->numero_asientos }} asientos</p>
            </div>
        </div>

        {{-- Separador troquelado --}}
        <div class="relative my-6">
            <div class="border-t-2 border-dashed border-stone-200"></div>
            <span class="absolute -left-10 top-1/2 h-7 w-7 -translate-y-1/2 rounded-full bg-stone-100 ring-1 ring-stone-200"></span>
            <span class="absolute -right-10 top-1/2 h-7 w-7 -translate-y-1/2 rounded-full bg-stone-100 ring-1 ring-stone-200"></span>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            {{-- Pasajero --}}
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-stone-400">Pasajero</p>
                <p class="mt-1 text-lg font-semibold text-stone-900">{{ $ticket->nombre_pasajero }}</p>
                <p class="text-sm text-stone-500">CI: {{ $ticket->ci_pasajero }}</p>
            </div>

            {{-- Unidad --}}
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-stone-400">Unidad y chofer</p>
                <p class="mt-1 text-lg font-semibold text-stone-900">{{ $bus->placa }} · {{ $bus->tipo->label() }}</p>
                <p class="text-sm text-stone-500">Chofer: {{ $departure->chofer ?: 'Por asignar' }}</p>
            </div>
        </div>

        {{-- Desglose de precio --}}
        <div class="mt-6 rounded-xl border border-vino-100 bg-vino-50/70 p-4">
            <div class="flex items-center justify-between text-sm text-stone-600">
                <span>Precio base ({{ $route->origen }} - {{ $route->destino }})</span>
                <span>Bs {{ number_format($precioBase, 2) }}</span>
            </div>
            <div class="mt-1.5 flex items-center justify-between text-sm text-stone-600">
                <span>Recargo por bus {{ $bus->tipo->label() }}</span>
                <span>Bs {{ number_format($recargo, 2) }}</span>
            </div>
            <div class="mt-2.5 flex items-center justify-between border-t border-vino-200 pt-2.5">
                <span class="text-sm font-semibold uppercase tracking-wide text-vino-900">Total pagado</span>
                <span class="font-display text-xl font-bold text-vino-900">Bs {{ number_format($total, 2) }}</span>
            </div>
        </div>

        {{-- Pie técnico --}}
        <div class="mt-6 flex flex-wrap items-center justify-between gap-2 text-[11px] text-stone-500">
            <p>Emitido el {{ $ticket->created_at->format('d/m/Y') }} a las {{ $ticket->created_at->format('H:i') }}</p>
            <p>Válido solo para la fecha, hora y asiento indicados.</p>
        </div>

        {{-- Código decorativo --}}
        <div class="mt-5 flex flex-col items-center gap-1">
            <div class="h-12 w-64"
                 style="background-image: repeating-linear-gradient(90deg, #350b17 0 2px, transparent 2px 5px, #350b17 5px 7px, transparent 7px 11px, #350b17 11px 12px, transparent 12px 17px);"></div>
            <p class="font-mono text-[10px] tracking-[0.45em] text-stone-400">{{ $codigo }}</p>
        </div>
    </div>

    <div class="relative border-t border-stone-100 bg-stone-50 px-6 py-3 text-center text-[11px] leading-relaxed text-stone-500 sm:px-8">
        Presente este pasaje al abordar. La anulación solo procede hasta 2 horas antes de la salida.
    </div>
</div>
@endsection
