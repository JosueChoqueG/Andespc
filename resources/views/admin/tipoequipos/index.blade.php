@extends('layouts.app')

@section('title', 'Tipos de Equipo')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>Tipos de Equipo</h2>
    <a href="{{ route('tipoequipos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Tipo
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-striped table-hover">
    <thead class="table-light">
        <tr>
            <!-- <th>ID</th> -->
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($tipoequipos as $tipoequipo)
            <tr>
                <!-- <td>{{ $tipoequipo->id }}</td> -->
                <td>{{ $tipoequipo->nombre_tipo }}</td>
                <td>
                    <a href="{{ route('tipoequipos.show', $tipoequipo) }}" class="btn btn-sm btn-info">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('tipoequipos.edit', $tipoequipo) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('tipoequipos.destroy', $tipoequipo) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este tipo?')">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-muted">No hay tipos registrados.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
