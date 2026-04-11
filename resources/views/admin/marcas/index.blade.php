@extends('layouts.app')

@section('title', 'Marcas')

@section('content')
<div>
    <h2>Marcas</h2>
    <a href="{{ route('marcas.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-circle"></i>
    </a>
</div>
<div class="card shadow-sm d-inline-block">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle w-auto">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th class="text-center text-nowrap">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($marcas as $marca)
                    <tr>
                        <td>{{ $marca->nombre_marca }}</td>

                        <td class="text-center text-nowrap">

                            <a href="{{ route('marcas.show', $marca) }}"
                               class="btn btn-sm btn-info"
                               title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('marcas.edit', $marca) }}"
                               class="btn btn-sm btn-warning"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('marcas.destroy', $marca) }}"
                                  method="POST"
                                  class="d-inline-block"
                                  onsubmit="return confirm('¿Eliminar esta marca?')">

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
                            No hay marcas registradas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection