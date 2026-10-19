@php
    $bus = $bus ?? null;
    $tipoActual = old('tipo', $bus?->tipo?->value);
@endphp

<div class="grid grid-cols-1 gap-4 md:grid-cols-3">
    <div>
        <label for="placa" class="mb-1 block text-sm font-medium text-stone-700">Placa</label>
        <input type="text" name="placa" id="placa" value="{{ old('placa', $bus?->placa) }}"
               placeholder="1234-ABC" style="text-transform: uppercase"
               class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm uppercase text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
               required>
    </div>

    <div>
        <label for="tipo" class="mb-1 block text-sm font-medium text-stone-700">Tipo</label>
        <select name="tipo" id="tipo"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
                required>
            <option value="" disabled {{ $tipoActual ? '' : 'selected' }}>Selecciona un tipo</option>
            @foreach(\App\Enums\BusType::options() as $value => $label)
                <option value="{{ $value }}" {{ $tipoActual === $value ? 'selected' : '' }}>
                    {{ $label }} (+ Bs {{ number_format(\App\Enums\BusType::from($value)->recargo(), 2) }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="numero_asientos" class="mb-1 block text-sm font-medium text-stone-700">N° de asientos</label>
        <input type="number" name="numero_asientos" id="numero_asientos" min="1" max="80"
               value="{{ old('numero_asientos', $bus?->numero_asientos) }}"
               class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
               required>
    </div>
</div>

<div class="mt-6 flex items-center gap-4">
    <button type="submit"
            class="inline-flex items-center rounded-lg bg-vino-700 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-vino-800">
        Guardar
    </button>
    <a href="{{ route('buses.index') }}"
       class="text-sm text-stone-500 transition hover:text-vino-700 hover:underline">
        Cancelar
    </a>
</div>
