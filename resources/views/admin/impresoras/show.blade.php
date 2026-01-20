@extends('layouts.app')

@section('content')
<h4>Detalle Impresora</h4>

<ul class="list-group mb-3">
    <li class="list-group-item"><strong>Serie:</strong> {{ $impresora->serie }}</li>
    <li class="list-group-item"><strong>Marca:</strong> {{ $impresora->marca }}</li>
    <li class="list-group-item"><strong>Modelo:</strong> {{ $impresora->modelo }}</li>
    <li class="list-group-item"><strong>Ubicación:</strong> {{ $impresora->ubicacion_actual }}</li>
</ul>

@if(isset($impresora))
    <a href="{{ route('admin.mantenimientos.create', $impresora->id) }}"
       class="btn btn-warning">
       Registrar mantenimiento
    </a>
@endif

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Error</th>
            <th>Fecha</th>
            <th>Garantía</th>
        </tr>
    </thead>
    <tbody>
        @forelse($impresora->mantenimientos as $m)
        <tr>
            <td>{{ $m->tipo_error }}</td>
            <td>{{ $m->fecha_incidencia }}</td>
            <td>{{ $m->estado_garantia }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="text-center">Sin registros</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection

