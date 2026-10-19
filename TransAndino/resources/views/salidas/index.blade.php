@extends('plantillas.plantilla')

@section('title', 'Salidas')

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <span class="mb-2 block h-1 w-12 rounded-full bg-dorado-500"></span>
        <h2 id="titulo-salidas" class="cursor-pointer select-none font-display text-2xl font-bold text-vino-900">Salidas programadas</h2>
    </div>
    <a href="{{ route('departures.create') }}"
       class="inline-flex items-center gap-2 rounded-lg bg-vino-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-vino-800">
        + Nueva Salida
    </a>
</div>

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-vino-50 text-left text-xs uppercase tracking-wider text-vino-700">
                    <th class="px-4 py-3 font-semibold">Ruta</th>
                    <th class="px-4 py-3 font-semibold">Bus</th>
                    <th class="px-4 py-3 font-semibold">Chofer</th>
                    <th class="px-4 py-3 font-semibold">Fecha</th>
                    <th class="px-4 py-3 font-semibold">Hora</th>
                    <th class="px-4 py-3 text-right font-semibold">Precio final (Bs)</th>
                    <th class="px-4 py-3 text-right font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($departures as $departure)
                    <tr class="align-top transition hover:bg-vino-50/40">
                        <td class="px-4 py-3 font-medium text-stone-800">{{ $departure->route->nombre }}</td>
                        <td class="px-4 py-3">
                            <span class="font-mono text-stone-800">{{ $departure->bus->placa }}</span>
                            <span class="text-xs text-stone-400">({{ $departure->bus->tipo->label() }})</span>
                        </td>
                        <td class="px-4 py-3 text-stone-600">{{ $departure->chofer }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ $departure->fecha->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ $departure->horaCorta() }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-stone-800">
                            {{ number_format((float) $departure->precio_final, 2) }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap items-center justify-end gap-1.5">
                                <a href="{{ route('departures.tickets.create', $departure) }}"
                                   class="rounded-md bg-vino-700 px-2.5 py-1 text-xs font-medium text-white transition hover:bg-vino-800">
                                    Vender
                                </a>
                                <a href="{{ route('departures.tickets.index', $departure) }}"
                                   class="rounded-md bg-dorado-500 px-2.5 py-1 text-xs font-medium text-vino-950 transition hover:bg-dorado-400">
                                    Pasajes
                                </a>
                                <a href="{{ route('departures.edit', $departure) }}"
                                   class="rounded-md border border-vino-200 px-2.5 py-1 text-xs font-medium text-vino-700 transition hover:bg-vino-50">
                                    Editar
                                </a>
                                <form action="{{ route('departures.destroy', $departure) }}" method="POST"
                                      data-confirm="¿Eliminar esta salida de {{ $departure->route->nombre }}? Esta acción no se puede deshacer.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="rounded-md border border-rose-200 px-2.5 py-1 text-xs font-medium text-rose-700 transition hover:bg-rose-50">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-stone-500">
                            No hay salidas programadas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-5">{{ $departures->links() }}</div>
@endsection

@push('styles')
<style>
    .fiesta-imagen {
        position: fixed;
        z-index: 70;
        pointer-events: none;
        will-change: transform, opacity;
    }

    .fiesta-velo {
        position: fixed;
        inset: 0;
        z-index: 60;
        cursor: pointer;
        animation: fiesta-flash .4s steps(1) infinite;
    }

    @keyframes fiesta-flash {
        0% { background-color: rgba(201, 63, 97, .30) }
        16% { background-color: rgba(201, 159, 63, .30) }
        32% { background-color: rgba(53, 11, 23, .38) }
        48% { background-color: rgba(221, 106, 134, .32) }
        64% { background-color: rgba(232, 210, 154, .30) }
        80% { background-color: rgba(140, 28, 57, .34) }
        100% { background-color: rgba(116, 25, 49, .30) }
    }

    @keyframes fiesta-fade { from { opacity: 0 } to { opacity: 1 } }
    @keyframes fiesta-zoom { from { opacity: 0; transform: scale(.15) } to { opacity: 1; transform: scale(1) } }
    @keyframes fiesta-izquierda { from { opacity: 0; transform: translateX(-110vw) } to { opacity: 1; transform: translateX(0) } }
    @keyframes fiesta-derecha { from { opacity: 0; transform: translateX(110vw) } to { opacity: 1; transform: translateX(0) } }
    @keyframes fiesta-arriba { from { opacity: 0; transform: translateY(-110vh) } to { opacity: 1; transform: translateY(0) } }
    @keyframes fiesta-abajo { from { opacity: 0; transform: translateY(110vh) } to { opacity: 1; transform: translateY(0) } }
    @keyframes fiesta-rotar { from { opacity: 0; transform: rotate(-220deg) scale(.15) } to { opacity: 1; transform: rotate(0) scale(1) } }
    @keyframes fiesta-rebote {
        0% { opacity: 0; transform: translateY(-110vh) }
        55% { opacity: 1; transform: translateY(0) }
        70% { transform: translateY(-34px) }
        85% { transform: translateY(0) }
        93% { transform: translateY(-14px) }
        100% { transform: translateY(0) }
    }
    @keyframes fiesta-voltear { from { opacity: 0; transform: perspective(700px) rotateY(95deg) } to { opacity: 1; transform: perspective(700px) rotateY(0) } }
    @keyframes fiesta-cortina { from { opacity: .3; clip-path: inset(0 100% 0 0) } to { opacity: 1; clip-path: inset(0 0 0 0) } }

    .efecto-fade { animation: fiesta-fade .7s ease both }
    .efecto-zoom { animation: fiesta-zoom .7s cubic-bezier(.2, .8, .3, 1) both }
    .efecto-izquierda { animation: fiesta-izquierda .7s cubic-bezier(.2, .8, .3, 1) both }
    .efecto-derecha { animation: fiesta-derecha .7s cubic-bezier(.2, .8, .3, 1) both }
    .efecto-arriba { animation: fiesta-arriba .7s cubic-bezier(.2, .8, .3, 1) both }
    .efecto-abajo { animation: fiesta-abajo .7s cubic-bezier(.2, .8, .3, 1) both }
    .efecto-rotar { animation: fiesta-rotar .85s cubic-bezier(.2, .8, .3, 1) both }
    .efecto-rebote { animation: fiesta-rebote 1s ease both }
    .efecto-voltear { animation: fiesta-voltear .85s ease both }
    .efecto-cortina { animation: fiesta-cortina .7s ease both }

    @media print {
        .fiesta-imagen,
        .fiesta-velo { display: none !important }
    }
</style>
@endpush

@push('scripts')
<script>
    (function () {
        const imagenes = @json($fiestaImagenes);
        const audioUrl = @json($fiestaAudio);
        const disparador = document.getElementById('titulo-salidas');

        if (!disparador || imagenes.length === 0) {
            return;
        }

        const efectos = [
            'efecto-fade', 'efecto-zoom', 'efecto-izquierda', 'efecto-derecha',
            'efecto-arriba', 'efecto-abajo', 'efecto-rotar', 'efecto-rebote',
            'efecto-voltear', 'efecto-cortina'
        ];
        const CANTIDAD = 14;
        const VENTANA_CLICKS = 6000;
        const CLICKS_NECESARIOS = 10;

        let marcas = [];
        let activo = false;
        let velo = null;
        let audio = null;
        const capa = [];

        disparador.addEventListener('click', function () {
            const ahora = Date.now();
            marcas.push(ahora);
            marcas = marcas.filter(function (marca) { return ahora - marca <= VENTANA_CLICKS; });

            if (marcas.length >= CLICKS_NECESARIOS) {
                marcas = [];
                iniciar();
            }
        });

        function elegir(lista) {
            return lista[Math.floor(Math.random() * lista.length)];
        }

        function iniciar() {
            if (activo) {
                cerrar();
            }

            activo = true;

            velo = document.createElement('div');
            velo.className = 'fiesta-velo';
            velo.addEventListener('click', cerrar);
            document.body.appendChild(velo);

            if (audioUrl) {
                audio = new Audio(audioUrl);
                audio.loop = true;
                audio.play().catch(function () {});
            }

            for (let i = 0; i < CANTIDAD; i++) {
                const img = document.createElement('img');
                img.className = 'fiesta-imagen';
                img.src = elegir(imagenes);
                img.alt = '';
                document.body.appendChild(img);
                capa.push(img);
                ciclo(img);
            }
        }

        function ciclo(img) {
            if (!activo) {
                return;
            }

            const tamano = 280 + Math.random() * 320;
            const x = Math.random() * Math.max(0, window.innerWidth - tamano);
            const y = Math.random() * Math.max(0, window.innerHeight - tamano);

            img.style.width = tamano + 'px';
            img.style.height = 'auto';
            img.style.left = x + 'px';
            img.style.top = y + 'px';
            img.style.transition = '';
            img.style.opacity = '';
            img.style.transform = '';

            // Limpia clases previas para poder reutilizar el mismo elemento.
            img.className = 'fiesta-imagen';
            void img.offsetWidth;
            img.classList.add(elegir(efectos));

            img.addEventListener('animationend', function alEntrar() {
                img.removeEventListener('animationend', alEntrar);

                // Un segundo despues de terminar la entrada, desaparece.
                setTimeout(function () {
                    if (!activo) {
                        return;
                    }

                    img.className = 'fiesta-imagen';
                    img.style.transition = 'opacity .3s ease, transform .3s ease';
                    img.style.opacity = '0';
                    img.style.transform = 'scale(.5)';

                    setTimeout(function () {
                        if (activo) {
                            ciclo(img);
                        }
                    }, 320);
                }, 1000);
            });
        }

        function cerrar() {
            activo = false;

            if (velo) {
                velo.remove();
                velo = null;
            }

            if (audio) {
                audio.pause();
                audio.currentTime = 0;
                audio = null;
            }

            capa.splice(0).forEach(function (img) { img.remove(); });
        }
    })();
</script>
@endpush
