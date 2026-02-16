@extends('layouts.app')

@section('title', 'Modelos')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>Modelos de Equipos</h2>
    <a href="{{ route('modelos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Modelo
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-striped table-hover align-middle">
    <thead class="table-light">
        <tr>
            <!-- <th>ID</th> -->
            <th>Modelo</th>
            <th>Marca</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($modelos as $m)
            <tr>
                <!-- <td>{{ $m->id_modelo }}</td> -->
                <td>{{ $m->nombre_modelo }}</td>
                <td>{{ $m->marca->nombre_marca ?? 'N/A' }}</td>
                <td>
                    <a href="{{ route('modelos.show', $m) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('modelos.edit', $m) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('modelos.destroy', $m) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este modelo?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">No hay modelos registrados.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection