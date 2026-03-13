@extends('layouts.app')

@section('title', 'Tipos de Equipo')

@section('content')

<div>
    <h2>Tipos de Equipo</h2>
    <a href="{{ route('tipoequipos.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-circle"></i> Nuevo Tipo
    </a>
</div>

<div class="card shadow-sm d-inline-block">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th class="text-center text-nowrap">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($tipoequipos as $tipoequipo)
                    <tr>
                        <td>{{ $tipoequipo->nombre_tipo }}</td>

                        <td class="text-center text-nowrap">

                            <a href="{{ route('tipoequipos.show', $tipoequipo) }}"
                               class="btn btn-sm btn-info"
                               title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('tipoequipos.edit', $tipoequipo) }}"
                               class="btn btn-sm btn-warning"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('tipoequipos.destroy', $tipoequipo) }}"
                                  method="POST"
                                  class="d-inline-block"
                                  onsubmit="return confirm('¿Eliminar este tipo?')">

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
                        <td colspan="2" class="text-center text-muted">
                            No hay tipos registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection