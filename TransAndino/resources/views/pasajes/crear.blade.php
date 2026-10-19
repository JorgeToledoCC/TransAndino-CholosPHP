@extends('plantillas.plantilla')

@section('title', 'Vender Pasaje')

@push('styles')
<style>
    .asiento { transition: transform .12s ease, box-shadow .12s ease, background-color .12s ease, color .12s ease; }
    .asiento-libre { border: 2px solid #10b981; background: #ffffff; color: #059669; cursor: pointer; }
    .asiento-libre:hover {
        background: #10b981;
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(16, 185, 129, .3);
    }
    .asiento-ocupado { border: 2px solid #e7e5e4; background: #f5f5f4; color: #a8a29e; cursor: not-allowed; }
    .asiento.asiento-seleccionado {
        background: #8c1c39;
        border-color: #8c1c39;
        color: #ffffff;
        box-shadow: 0 0 0 3px rgba(201, 159, 63, .55), 0 6px 14px rgba(140, 28, 57, .3);
        transform: translateY(-2px);
    }
    #asiento-elegido.elegido {
        background-color: #fdf3f5;
        border-color: #c93f61;
        border-style: solid;
        color: #8c1c39;
        font-weight: 600;
    }
    .tooltip-flotante {
        position: fixed;
        transform: translate(-50%, -100%);
        background: #5e162a;
        color: #ffffff;
        font-size: 11px;
        line-height: 1.2;
        padding: 5px 9px;
        border-radius: 8px;
        white-space: nowrap;
        pointer-events: none;
        opacity: 0;
        transition: opacity .12s ease;
        z-index: 50;
        box-shadow: 0 6px 18px rgba(53, 11, 23, .35);
    }
    .tooltip-flotante.visible { opacity: 1; }
    .tooltip-flotante::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border: 5px solid transparent;
        border-top-color: #5e162a;
    }
</style>
@endpush

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
        <a href="{{ route('departures.tickets.index', $departure) }}"
           class="text-sm font-medium text-dorado-300 underline decoration-dorado-500/50 underline-offset-4 transition hover:text-dorado-200">
            Ver pasajes vendidos
        </a>
    </div>
</div>

<div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-2">
    {{-- Mapa de asientos --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200">
        <div class="flex items-baseline justify-between border-b border-stone-100 bg-vino-50/60 px-6 py-4">
            <h2 class="font-display text-lg font-bold text-vino-900">Mapa de asientos</h2>
            <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-vino-700 ring-1 ring-vino-100">
                {{ $capacidad }} asientos
            </span>
        </div>

        <div class="p-6">
            {{-- Leyenda --}}
            <div class="mb-6 flex flex-wrap justify-center gap-2 text-xs">
                <span class="inline-flex items-center gap-2 rounded-full bg-stone-100 px-3 py-1 font-medium text-stone-600">
                    <span class="inline-block h-4 w-4 rounded-md border-2 border-emerald-500 bg-white"></span> Libre
                </span>
                <span class="inline-flex items-center gap-2 rounded-full bg-stone-100 px-3 py-1 font-medium text-stone-600">
                    <span class="inline-block h-4 w-4 rounded-md border-2 border-stone-200 bg-stone-100"></span> Ocupado
                </span>
                <span class="inline-flex items-center gap-2 rounded-full bg-stone-100 px-3 py-1 font-medium text-stone-600">
                    <span class="inline-block h-4 w-4 rounded-md bg-vino-700 ring-2 ring-dorado-400/60"></span> Seleccionado
                </span>
            </div>

            @php
                // Arma las filas de asientos (2 + pasillo + 2). La ultima fila
                // reparte los asientos sobrantes hacia los extremos para que
                // quede simetrica.
                $filas = [];
                $numero = 0;
                while ($numero < $capacidad) {
                    $restantes = $capacidad - $numero;
                    $slots = [null, null, null, null];

                    if ($restantes >= 4) {
                        $slots = [$numero + 1, $numero + 2, $numero + 3, $numero + 4];
                        $numero += 4;
                    } else {
                        $izquierda = (int) ceil($restantes / 2);
                        $derecha = $restantes - $izquierda;

                        for ($i = 0; $i < $izquierda; $i++) {
                            $slots[$i] = $numero + 1 + $i;
                        }
                        for ($i = 0; $i < $derecha; $i++) {
                            $slots[3 - $i] = $numero + $izquierda + 1 + $i;
                        }

                        $numero += $restantes;
                    }

                    $filas[] = $slots;
                }
            @endphp

            {{-- Cuerpo del bus --}}
            <div class="overflow-x-auto">
                <div class="mx-auto w-max">
                    {{-- Cabina / frente del bus --}}
                    <div class="flex items-center justify-center gap-2 rounded-t-2xl bg-gradient-to-r from-vino-900 via-vino-800 to-vino-900 py-2 text-xs font-semibold uppercase tracking-widest text-dorado-300">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <circle cx="12" cy="12" r="9"></circle>
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M12 3v6M12 15v6M3 12h6M15 12h6"></path>
                        </svg>
                        Frente
                    </div>

                    {{-- Piso de asientos --}}
                    <div class="relative rounded-b-2xl bg-white p-4 shadow-inner ring-1 ring-stone-200 sm:p-6">
                        <span class="pointer-events-none absolute bottom-4 left-1/2 top-4 -ml-px border-l-2 border-dashed border-stone-200 sm:bottom-6 sm:top-6" aria-hidden="true"></span>

                        <div class="relative space-y-2.5">
                            @foreach($filas as $slots)
                                <div class="flex items-center justify-center gap-2.5">
                                    @foreach($slots as $indice => $asiento)
                                        @if($indice === 2)
                                            {{-- Pasillo central del bus --}}
                                            <span class="w-8 sm:w-10" aria-hidden="true"></span>
                                        @endif

                                        @if($asiento !== null)
                                            @php
                                                $ocupado = in_array($asiento, $ocupados, true);
                                                $pasajero = $pasajeros[$asiento] ?? null;
                                            @endphp

                                            @if($ocupado)
                                                <span class="asiento asiento-ocupado flex h-11 w-16 items-center justify-center rounded-xl text-sm font-semibold"
                                                      data-asiento="{{ $asiento }}"
                                                      data-nombre="{{ $pasajero ?? 'Asiento ocupado' }}"
                                                      aria-label="Asiento {{ $asiento }} ocupado{{ $pasajero ? ' por '.$pasajero : '' }}">
                                                    {{ $asiento }}
                                                </span>
                                            @else
                                                <button type="button"
                                                        class="asiento asiento-libre flex h-11 w-16 items-center justify-center rounded-xl text-sm font-semibold"
                                                        data-asiento="{{ $asiento }}"
                                                        title="Asiento {{ $asiento }} (libre)">
                                                    {{ $asiento }}
                                                </button>
                                            @endif
                                        @else
                                            <span class="h-11 w-16" aria-hidden="true"></span>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario de venta --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200">
        <div class="border-b border-stone-100 bg-vino-50/60 px-6 py-4">
            <h2 class="font-display text-lg font-bold text-vino-900">Datos del pasajero</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('departures.tickets.store', $departure) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="mb-1 block text-sm font-medium text-stone-700">Asiento seleccionado</label>
                    <div id="asiento-elegido"
                         class="w-full rounded-lg border border-dashed border-stone-300 bg-stone-50 px-3 py-2 text-sm text-stone-400">
                        Aún no seleccionaste un asiento
                    </div>
                    <input type="hidden" name="numero_asiento" id="numero_asiento" value="{{ old('numero_asiento') }}">
                </div>

                <div class="mb-4">
                    <label for="nombre_pasajero" class="mb-1 block text-sm font-medium text-stone-700">Nombre del pasajero</label>
                    <input type="text" name="nombre_pasajero" id="nombre_pasajero"
                           value="{{ old('nombre_pasajero') }}"
                           class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
                           required>
                </div>

                <div class="mb-6">
                    <label for="ci_pasajero" class="mb-1 block text-sm font-medium text-stone-700">Cédula de identidad (CI)</label>
                    <input type="text" name="ci_pasajero" id="ci_pasajero"
                           value="{{ old('ci_pasajero') }}"
                           class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
                           required>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit"
                            id="btn-vender" disabled
                            class="inline-flex items-center rounded-lg bg-vino-700 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-vino-800 disabled:cursor-not-allowed disabled:opacity-40">
                        Vender pasaje
                    </button>
                    <a href="{{ route('departures.index') }}"
                       class="text-sm text-stone-500 transition hover:text-vino-700 hover:underline">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const input = document.getElementById('numero_asiento');
        const display = document.getElementById('asiento-elegido');
        const btn = document.getElementById('btn-vender');
        const asientos = document.querySelectorAll('.asiento-libre');

        asientos.forEach(function (asiento) {
            asiento.addEventListener('click', function () {
                document.querySelectorAll('.asiento-seleccionado').forEach(function (previo) {
                    previo.classList.remove('asiento-seleccionado');
                });

                asiento.classList.add('asiento-seleccionado');

                const numero = asiento.dataset.asiento;
                input.value = numero;
                display.textContent = 'Asiento N° ' + numero;
                display.classList.add('elegido');
                btn.disabled = false;
            });
        });

        // Tooltip flotante con el nombre del pasajero en los asientos ocupados.
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip-flotante';
        tooltip.setAttribute('role', 'tooltip');
        document.body.appendChild(tooltip);

        document.querySelectorAll('.asiento-ocupado').forEach(function (asiento) {
            asiento.addEventListener('mouseenter', function () {
                tooltip.textContent = 'Asiento ' + asiento.dataset.asiento + ' — ' + asiento.dataset.nombre;
                tooltip.classList.add('visible');

                const rect = asiento.getBoundingClientRect();
                tooltip.style.left = (rect.left + rect.width / 2) + 'px';
                tooltip.style.top = (rect.top - 8) + 'px';
            });

            asiento.addEventListener('mouseleave', function () {
                tooltip.classList.remove('visible');
            });
        });
    })();
</script>
@endpush
