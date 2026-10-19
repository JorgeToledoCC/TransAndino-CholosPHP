@php
    $route = $route ?? null;
@endphp

<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
    <div>
        <label for="origen" class="mb-1 block text-sm font-medium text-stone-700">Origen</label>
        <input type="text" name="origen" id="origen" value="{{ old('origen', $route?->origen ?? 'La Paz') }}"
               class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
               required>
    </div>

    <div>
        <label for="destino" class="mb-1 block text-sm font-medium text-stone-700">Destino</label>
        <input type="text" name="destino" id="destino" value="{{ old('destino', $route?->destino) }}"
               class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
               required>
    </div>

    <div>
        <label for="duracion" class="mb-1 block text-sm font-medium text-stone-700">Duración (HH:MM)</label>
        <input type="text" name="duracion" id="duracion" value="{{ old('duracion', $route?->duracion) }}"
               placeholder="04:30"
               class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
               required>
    </div>

    <div>
        <label for="precio_base" class="mb-1 block text-sm font-medium text-stone-700">Precio base (Bs)</label>
        <input type="number" name="precio_base" id="precio_base" step="0.01" min="0"
               value="{{ old('precio_base', $route?->precio_base) }}"
               class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
               required>
    </div>
</div>

<div class="mt-6 flex items-center gap-4">
    <button type="submit"
            class="inline-flex items-center rounded-lg bg-vino-700 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-vino-800">
        Guardar
    </button>
    <a href="{{ route('routes.index') }}"
       class="text-sm text-stone-500 transition hover:text-vino-700 hover:underline">
        Cancelar
    </a>
</div>
