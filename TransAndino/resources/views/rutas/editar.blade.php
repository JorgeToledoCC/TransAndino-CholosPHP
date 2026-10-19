@extends('plantillas.plantilla')

@section('title', 'Editar Ruta')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200">
        <div class="border-b border-stone-100 bg-vino-50/60 px-6 py-4">
            <h2 class="font-display text-lg font-bold text-vino-900">Editar: {{ $route->nombre }}</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('routes.update', $route) }}" method="POST">
                @csrf
                @method('PUT')
                @include('rutas.formulario')
            </form>
        </div>
    </div>
</div>
@endsection
