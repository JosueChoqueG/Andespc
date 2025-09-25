@extends('layouts.app')

@section('title', 'Agencias')

@section('content')
<h2>Agencias</h2>
<a href="{{ route('agencias.create') }}" class="btn btn-success mb-3">+ Nueva Agencia</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($agencias as $agencia)
        <tr>
            <td>{{ $agencia->codigo_agencia }}</td>
            <td>{{ $agencia->nombre_agencia }}</td>
            <td>
                <a href="{{ route('agencias.edit', $agencia) }}" class="btn btn-sm btn-warning">Editar</a>
                <form method="POST" action="{{ route('agencias.destroy', $agencia) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection