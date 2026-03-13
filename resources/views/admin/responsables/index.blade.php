@extends('layouts.app')

@section('title', 'Responsables')

@section('content')

<div>
    <h2>Responsables</h2>
    <a href="{{ route('responsables.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-circle"></i> Nuevo Responsable
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
                    @forelse($responsables as $resp)
                    <tr>

                        <td>{{ $resp->nombre_responsable }}</td>

                        <td class="text-center text-nowrap">

                            <a href="{{ route('responsables.show', $resp) }}"
                               class="btn btn-sm btn-info"
                               title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('responsables.edit', $resp) }}"
                               class="btn btn-sm btn-warning"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('responsables.destroy', $resp) }}"
                                  method="POST"
                                  class="d-inline-block"
                                  onsubmit="return confirm('¿Eliminar este responsable?')">

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
                            No hay responsables registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection