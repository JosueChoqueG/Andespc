@extends('layouts.app')

@section('title', 'Responsables')

@section('content')

<div>
    <h2>Responsables</h2>
    
    <div class="d-flex align-items-center gap-3 mb-3">
        <a href="{{ route('responsables.create') }}" class="btn btn-primary" title="Nuevo Responsable">
            <i class="bi bi-plus-circle"></i>
        </a>

        <form action="{{ route('responsables.index') }}" method="GET" class="d-flex">
            <select name="search" class="form-select select2 me-2" style="width: 250px;">
                <option value="">Buscar Responsable...</option>
                @foreach($allResponsables as $resp)
                    <option value="{{ $resp->nombre_responsable }}" {{ request('search') == $resp->nombre_responsable ? 'selected' : '' }}>
                        {{ $resp->nombre_responsable }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-search"></i> Buscar
            </button>
            @if(request('search'))
                <a href="{{ route('responsables.index') }}" class="btn btn-outline-danger btn-sm ms-2" title="Limpiar filtro">
                    <i class="bi bi-x-circle"></i>
                </a>
            @endif
        </form>
    </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.jQuery && $.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: 'resolve'
        });
    }
});
</script>
@endpush
@endsection