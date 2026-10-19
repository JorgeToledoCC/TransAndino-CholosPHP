@extends('plantillas.plantilla')

@section('title', 'Rutas')

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <span class="mb-2 block h-1 w-12 rounded-full bg-dorado-500"></span>
        <h2 class="font-display text-2xl font-bold text-vino-900">Rutas registradas</h2>
    </div>
    <a href="{{ route('routes.create') }}"
       class="inline-flex items-center gap-2 rounded-lg bg-vino-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-vino-800">
        + Nueva Ruta
    </a>
</div>

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-vino-50 text-left text-xs uppercase tracking-wider text-vino-700">
                    <th class="px-4 py-3 font-semibold">#</th>
                    <th class="px-4 py-3 font-semibold">Origen</th>
                    <th class="px-4 py-3 font-semibold">Destino</th>
                    <th class="px-4 py-3 font-semibold">Duración</th>
                    <th class="px-4 py-3 text-right font-semibold">Precio base (Bs)</th>
                    <th class="px-4 py-3 text-right font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($routes as $route)
                    <tr class="transition hover:bg-vino-50/40">
                        <td class="px-4 py-3 text-stone-400">{{ $route->id }}</td>
                        <td class="px-4 py-3 font-medium text-stone-800">{{ $route->origen }}</td>
                        <td class="px-4 py-3 font-medium text-stone-800">{{ $route->destino }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ $route->duracion }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-stone-800">{{ number_format((float) $route->precio_base, 2) }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('routes.edit', $route) }}"
                                   class="rounded-md border border-vino-200 px-2.5 py-1 text-xs font-medium text-vino-700 transition hover:bg-vino-50">
                                    Editar
                                </a>
                                <form action="{{ route('routes.destroy', $route) }}" method="POST"
                                      data-confirm="¿Eliminar la ruta {{ $route->nombre }}? Esta acción no se puede deshacer.">
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
                            No hay rutas registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-5">{{ $routes->links() }}</div>
@endsection
