@extends('layouts.app')

@section('title', 'Gestión de Responsables')

@section('content')
<div class="container-fluid">
    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1 text-dark fw-bold">
                <i class="bi bi-people-fill text-primary me-2"></i>Responsables de Equipos
            </h4>
            <span class="text-muted" style="font-size: 0.9rem;">
                Administración de personal asignado, cargos y oficinas correspondientes
            </span>
        </div>
        <div>
            <a href="{{ route('responsables.create') }}" class="btn btn-primary shadow-sm px-3" title="Nuevo Responsable">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Responsable
            </a>
        </div>
    </div>

    {{-- Filtros y Búsqueda --}}
    <div class="card shadow-sm border-0 rounded-3 mb-3">
        <div class="card-body p-3">
            <form action="{{ route('responsables.index') }}" method="GET" class="row g-2.5 align-items-end">
                <div class="col-md-4 col-sm-6">
                    <label class="form-label form-label-sm mb-1 fw-semibold text-muted">Búsqueda rápida</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control form-control-sm border-start-0" 
                               placeholder="Buscar por nombre, cargo, oficina..." 
                               value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <label class="form-label form-label-sm mb-1 fw-semibold text-muted">Oficina / Sede</label>
                    <select name="oficina_id" class="form-select form-select-sm select2">
                        <option value="">Todas las Oficinas</option>
                        @foreach($oficinas as $ofi)
                            <option value="{{ $ofi->id }}" {{ request('oficina_id') == $ofi->id ? 'selected' : '' }}>
                                {{ $ofi->nombre_oficina }} ({{ $ofi->agencia->nombre_agencia ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 col-sm-6">
                    <label class="form-label form-label-sm mb-1 fw-semibold text-muted">Cargo</label>
                    <select name="cargo" class="form-select form-select-sm select2">
                        <option value="">Todos los Cargos</option>
                        @foreach($cargos as $c)
                            <option value="{{ $c }}" {{ request('cargo') == $c ? 'selected' : '' }}>
                                {{ $c }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 col-sm-6 d-flex gap-1">
                    <button type="submit" class="btn btn-outline-primary btn-sm flex-grow-1 shadow-sm">
                        <i class="bi bi-funnel me-1"></i> Filtrar
                    </button>
                    @if(request()->hasAny(['search', 'oficina_id', 'cargo']))
                        <a href="{{ route('responsables.index') }}" class="btn btn-outline-danger btn-sm shadow-sm" title="Limpiar filtros">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de Responsables --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark" style="background-color: #1d2d44; font-size: 0.88rem;">
                    <tr>
                        <th class="py-2.5 ps-3" style="width: 50px;">#</th>
                        <th class="py-2.5" style="width: 110px;">DNI</th>
                        <th class="py-2.5">Responsable</th>
                        <th class="py-2.5">Cargo / Puesto</th>
                        <th class="py-2.5">Oficina Asignada</th>
                        <th class="py-2.5">Agencia</th>
                        <th class="py-2.5 text-center" style="width: 140px;">Acciones</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.92rem;">
                    @forelse($responsables as $resp)
                    <tr>
                        <td class="ps-3 text-muted fw-bold">{{ $resp->id }}</td>
                        <td>
                            @if($resp->dni)
                                <span class="font-monospace fw-bold text-dark badge bg-light text-dark border">
                                    <i class="bi bi-card-heading me-1 text-muted"></i>{{ $resp->dni }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2.5" style="width: 36px; height: 36px;">
                                    <i class="bi bi-person fs-5"></i>
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block">{{ $resp->nombre_responsable }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($resp->cargo)
                                <span class="badge bg-light text-dark border px-2.5 py-1">
                                    <i class="bi bi-briefcase me-1 text-primary"></i>{{ $resp->cargo }}
                                </span>
                            @else
                                <span class="text-muted small italic">— Sin especificar —</span>
                            @endif
                        </td>
                        <td>
                            @if($resp->oficina)
                                <span class="fw-semibold text-dark">
                                    <i class="bi bi-geo-alt text-danger me-1"></i>{{ $resp->oficina->nombre_oficina }}
                                </span>
                            @else
                                <span class="text-muted small">— Sin oficina —</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted small">
                                {{ $resp->oficina?->agencia?->nombre_agencia ?? '—' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('responsables.show', $resp) }}" class="btn btn-sm btn-outline-info p-1 px-2" title="Ver detalles y equipos asignados">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('responsables.edit', $resp) }}" class="btn btn-sm btn-outline-warning p-1 px-2" title="Editar Responsable">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('responsables.destroy', $resp) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Seguro que desea eliminar este responsable?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger p-1 px-2" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-2 text-secondary opacity-50"></i>
                            <h6 class="fw-bold">No se encontraron responsables registrados</h6>
                            <p class="small mb-0">Comience agregando uno nuevo con el botón superior.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($responsables->hasPages())
        <div class="card-footer bg-light border-top py-2.5 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">
                Mostrando {{ $responsables->firstItem() }} a {{ $responsables->lastItem() }} de {{ $responsables->total() }} responsables
            </span>
            <div>
                {{ $responsables->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.jQuery && $.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true
        });
    }
});
</script>
@endpush
@endsection
