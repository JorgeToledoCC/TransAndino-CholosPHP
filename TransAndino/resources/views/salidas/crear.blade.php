@extends('plantillas.plantilla')

@section('title', 'Nueva Salida')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200">
        <div class="border-b border-stone-100 bg-vino-50/60 px-6 py-4">
            <h2 class="font-display text-lg font-bold text-vino-900">Programar salida</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('departures.store') }}" method="POST">
                @csrf
                @include('salidas.formulario')
            </form>
        </div>
    </div>
</div>
@endsection
