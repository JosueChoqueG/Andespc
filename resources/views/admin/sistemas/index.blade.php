@extends('layouts.app')

@section('title', 'Sistemas Operativos')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>Sistemas Operativos</h2>
    <a href="{{ route('sistemas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo SO
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-striped table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Edición</th>
            <th>Versión</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sistemas as $so)
            <tr>
                <td>{{ $so->id_so }}</td>
                <td>{{ $so->nombre_so }}</td>
                <td>{{ $so->edicion ?? 'N/A' }}</td>
                <td>{{ $so->version ?? 'N/A' }}</td>
                <td>
                    <a href="{{ route('sistemas.show', $so) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('sistemas.edit', $so) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('sistemas.destroy', $so) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este sistema?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No hay sistemas registrados.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection