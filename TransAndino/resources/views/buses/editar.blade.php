@extends('plantillas.plantilla')

@section('title', 'Editar Bus')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200">
        <div class="border-b border-stone-100 bg-vino-50/60 px-6 py-4">
            <h2 class="font-display text-lg font-bold text-vino-900">Editar bus: {{ $bus->placa }}</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('buses.update', $bus) }}" method="POST">
                @csrf
                @method('PUT')
                @include('buses.formulario')
            </form>
        </div>
    </div>
</div>
@endsection
