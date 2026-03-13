@extends('layouts.app')

@section('title', 'Sistemas Operativos')

@section('content')

<div>
    <h2>Sistemas Operativos</h2>
    <a href="{{ route('sistemaoperativos.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-circle"></i> Nuevo SO
    </a>
</div>

<div class="card shadow-sm d-inline-block">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Edición</th>
                        <th>Versión</th>
                        <th class="text-center text-nowrap">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($sistemaoperativos as $so)
                    <tr>

                        <td>{{ $so->nombre_so }}</td>

                        <td>{{ $so->edicion ?? 'N/A' }}</td>

                        <td>{{ $so->version ?? 'N/A' }}</td>

                        <td class="text-center text-nowrap">

                            <a href="{{ route('sistemaoperativos.show', $so) }}"
                               class="btn btn-sm btn-info"
                               title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('sistemaoperativos.edit', $so) }}"
                               class="btn btn-sm btn-warning"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('sistemaoperativos.destroy', $so) }}"
                                  method="POST"
                                  class="d-inline-block"
                                  onsubmit="return confirm('¿Eliminar este sistema?')">

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
                        <td colspan="4" class="text-center text-muted">
                            No hay sistemas registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection