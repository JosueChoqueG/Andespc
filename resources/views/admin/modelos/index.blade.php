@extends('layouts.app')

@section('title', 'Modelos')

@section('content')

<div>
    <h2>Modelo de Equipos</h2>
    <a href="{{ route('modelos.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-circle"></i>
    </a>
</div>

<div class="card shadow-sm d-inline-block">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Modelo</th>
                        <th>Marca</th>
                        <th class="text-center text-nowrap">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($modelos as $m)
                    <tr>
                        <td>{{ $m->nombre_modelo }}</td>
                        <td>{{ $m->marca->nombre_marca ?? 'N/A' }}</td>

                        <td class="text-center text-nowrap">

                            <a href="{{ route('modelos.show', $m) }}"
                               class="btn btn-sm btn-info"
                               title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('modelos.edit', $m) }}"
                               class="btn btn-sm btn-warning"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('modelos.destroy', $m) }}"
                                  method="POST"
                                  class="d-inline-block"
                                  onsubmit="return confirm('¿Eliminar este modelo?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-sm btn-danger"
                                        title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </form>

                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">
                            No hay modelos registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection