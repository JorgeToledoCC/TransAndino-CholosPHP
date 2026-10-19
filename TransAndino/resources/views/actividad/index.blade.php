@extends('plantillas.plantilla')

@section('title', 'Actividad')

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <span class="mb-2 block h-1 w-12 rounded-full bg-dorado-500"></span>
        <h2 class="font-display text-2xl font-bold text-vino-900">Registro de actividad</h2>
        <p class="mt-1 text-sm text-stone-500">Historial de todo lo que ha ido pasando en el sistema.</p>
    </div>
    <span class="rounded-full bg-vino-50 px-3 py-1 text-xs font-medium text-vino-700 ring-1 ring-vino-100">
        {{ $registros->total() }} eventos
    </span>
</div>

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-vino-50 text-left text-xs uppercase tracking-wider text-vino-700">
                    <th class="px-4 py-3 font-semibold">Fecha</th>
                    <th class="px-4 py-3 font-semibold">Usuario</th>
                    <th class="px-4 py-3 font-semibold">Acción</th>
                    <th class="px-4 py-3 font-semibold">Detalle</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($registros as $registro)
                    <tr class="align-top transition hover:bg-vino-50/40">
                        <td class="whitespace-nowrap px-4 py-3 text-stone-500">
                            {{ $registro->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="px-4 py-3 font-medium text-stone-800">
                            {{ $registro->user_name ?? 'Sistema' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $registro->colorClasses() }}">
                                {{ ucfirst($registro->action) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-stone-600">{{ $registro->description }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-stone-500">
                            Aún no hay actividad registrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-5">{{ $registros->links() }}</div>
@endsection
