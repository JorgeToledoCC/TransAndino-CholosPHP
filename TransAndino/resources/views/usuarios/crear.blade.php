@extends('plantillas.plantilla')

@section('title', 'Nuevo Usuario')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200">
        <div class="border-b border-stone-100 bg-vino-50/60 px-6 py-4">
            <span class="mb-2 block h-1 w-12 rounded-full bg-dorado-500"></span>
            <h2 class="font-display text-lg font-bold text-vino-900">Crear usuario</h2>
            <p class="mt-1 text-xs text-stone-500">Solo el administrador puede registrar nuevas cuentas.</p>
        </div>

        <div class="p-6">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="nombre" class="mb-1 block text-sm font-medium text-stone-700">Nombre</label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                               class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
                               required>
                    </div>

                    <div>
                        <label for="apellido" class="mb-1 block text-sm font-medium text-stone-700">Apellido</label>
                        <input type="text" name="apellido" id="apellido" value="{{ old('apellido') }}"
                               class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
                               required>
                    </div>

                    <div>
                        <label for="numero" class="mb-1 block text-sm font-medium text-stone-700">Número</label>
                        <input type="text" name="numero" id="numero" value="{{ old('numero') }}"
                               class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
                               required>
                    </div>

                    <div>
                        <label for="fecha_nacimiento" class="mb-1 block text-sm font-medium text-stone-700">Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                               class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
                               required>
                    </div>

                    <div>
                        <label for="name" class="mb-1 block text-sm font-medium text-stone-700">Usuario</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" autocomplete="off"
                               class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
                               required>
                    </div>

                    <div>
                        <label for="password" class="mb-1 block text-sm font-medium text-stone-700">Contraseña</label>
                        <input type="password" name="password" id="password" autocomplete="new-password"
                               class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
                               required>
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <button type="submit"
                            class="inline-flex items-center rounded-lg bg-vino-700 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-vino-800">
                        Guardar usuario
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
