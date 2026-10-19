@extends('plantillas.plantilla')

@section('title', 'Buses')

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <span class="mb-2 block h-1 w-12 rounded-full bg-dorado-500"></span>
        <h2 class="font-display text-2xl font-bold text-vino-900">Flota de buses</h2>
    </div>
    <a href="{{ route('buses.create') }}"
       class="inline-flex items-center gap-2 rounded-lg bg-vino-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-vino-800">
        + Nuevo Bus
    </a>
</div>

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-vino-50 text-left text-xs uppercase tracking-wider text-vino-700">
                    <th class="px-4 py-3 font-semibold">#</th>
                    <th class="px-4 py-3 font-semibold">Placa</th>
                    <th class="px-4 py-3 font-semibold">Tipo</th>
                    <th class="px-4 py-3 text-right font-semibold">Recargo (Bs)</th>
                    <th class="px-4 py-3 text-center font-semibold">N° Asientos</th>
                    <th class="px-4 py-3 text-right font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($buses as $bus)
                    <tr class="transition hover:bg-vino-50/40">
                        <td class="px-4 py-3 text-stone-400">{{ $bus->id }}</td>
                        <td class="px-4 py-3 font-mono font-medium text-stone-800">{{ $bus->placa }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block rounded-full bg-vino-100 px-2.5 py-0.5 text-xs font-semibold text-vino-800">
                                {{ $bus->tipo->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right text-stone-700">+ {{ number_format($bus->recargo(), 2) }}</td>
                        <td class="px-4 py-3 text-center text-stone-700">{{ $bus->numero_asientos }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('buses.edit', $bus) }}"
                                   class="rounded-md border border-vino-200 px-2.5 py-1 text-xs font-medium text-vino-700 transition hover:bg-vino-50">
                                    Editar
                                </a>
                                <form action="{{ route('buses.destroy', $bus) }}" method="POST"
                                      data-confirm="¿Eliminar el bus {{ $bus->placa }}? Esta acción no se puede deshacer.">
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
                        <td colspan="6" class="px-4 py-10 text-center text-stone-500">
                            No hay buses registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-5">{{ $buses->links() }}</div>
@endsection
