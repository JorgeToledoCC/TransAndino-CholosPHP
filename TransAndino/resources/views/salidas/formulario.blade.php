@php
    $departure = $departure ?? null;
@endphp

<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
    <div>
        <label for="route_id" class="mb-1 block text-sm font-medium text-stone-700">Ruta</label>
        <select name="route_id" id="route_id"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
                required>
            <option value="" disabled {{ old('route_id', $departure?->route_id) ? '' : 'selected' }}>Selecciona una ruta</option>
            @foreach($routes as $route)
                <option value="{{ $route->id }}" {{ (string) old('route_id', $departure?->route_id) === (string) $route->id ? 'selected' : '' }}>
                    {{ $route->nombre }} — Bs {{ number_format((float) $route->precio_base, 2) }} ({{ $route->duracion }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="bus_id" class="mb-1 block text-sm font-medium text-stone-700">Bus</label>
        <select name="bus_id" id="bus_id"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
                required>
            <option value="" disabled {{ old('bus_id', $departure?->bus_id) ? '' : 'selected' }}>Selecciona un bus</option>
            @foreach($buses as $bus)
                <option value="{{ $bus->id }}" {{ (string) old('bus_id', $departure?->bus_id) === (string) $bus->id ? 'selected' : '' }}>
                    {{ $bus->placa }} — {{ $bus->tipo->label() }} ({{ $bus->numero_asientos }} asientos)
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="chofer" class="mb-1 block text-sm font-medium text-stone-700">Chofer</label>
        <input type="text" name="chofer" id="chofer" value="{{ old('chofer', $departure?->chofer) }}"
               class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
               required>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="fecha" class="mb-1 block text-sm font-medium text-stone-700">Fecha</label>
            <input type="date" name="fecha" id="fecha"
                   value="{{ old('fecha', $departure?->fecha?->format('Y-m-d')) }}"
                   class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
                   required>
        </div>
        <div>
            <label for="hora" class="mb-1 block text-sm font-medium text-stone-700">Hora</label>
            <input type="time" name="hora" id="hora"
                   value="{{ old('hora', $departure?->horaCorta()) }}"
                   class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
                   required>
        </div>
    </div>
</div>

<p class="mt-4 flex items-start gap-2 rounded-lg bg-vino-50/70 px-3 py-2 text-xs text-vino-800">
    <svg class="mt-0.5 h-4 w-4 shrink-0 text-dorado-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="9"/><path d="M12 16v-4M12 8h.01"/>
    </svg>
    El precio final se calcula automáticamente: precio base de la ruta + recargo del tipo de bus.
</p>

<div class="mt-6 flex items-center gap-4">
    <button type="submit"
            class="inline-flex items-center rounded-lg bg-vino-700 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-vino-800">
        Guardar
    </button>
    <a href="{{ route('departures.index') }}"
       class="text-sm text-stone-500 transition hover:text-vino-700 hover:underline">
        Cancelar
    </a>
</div>
