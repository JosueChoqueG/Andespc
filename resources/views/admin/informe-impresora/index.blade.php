@extends('layouts.app')

@section('title', 'Historial de Informes')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-3 text-white"
                 style="width:48px;height:48px;background:linear-gradient(135deg,#9f3f04,#e9c46a)">
                <i class="bi bi-file-earmark-text fs-4"></i>
            </div>
            <div>
                <h4 class="mb-0 fw-bold" style="color:#1d2d44">Historial de Informes</h4>
                <small class="text-muted">Todos los informes técnicos de impresoras</small>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.informes-impresora.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Impresora</label>
                    <select name="impresora_id" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($impresoras as $imp)
                            <option value="{{ $imp->id }}" {{ request('impresora_id') == $imp->id ? 'selected' : '' }}>
                                {{ $imp->marca_impresora }} {{ $imp->modelo_impresora }} — {{ $imp->serie_impresora }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small">Tipo</label>
                    <select name="tipo_informe" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach(\App\Models\InformeImpresora::TIPOS_INFORME as $tipo)
                            <option value="{{ $tipo }}" {{ request('tipo_informe') == $tipo ? 'selected' : '' }}>
                                {{ $tipo }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small">Desde</label>
                    <input type="date" name="fecha_inicio" class="form-control form-control-sm"
                           value="{{ request('fecha_inicio') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small">Hasta</label>
                    <input type="date" name="fecha_fin" class="form-control form-control-sm"
                           value="{{ request('fecha_fin') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-search me-1"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.informes-impresora.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background:#f8f9fa">
                        <tr>
                            <th class="px-4 py-3 text-muted small fw-semibold text-uppercase">#</th>
                            <th class="py-3 text-muted small fw-semibold text-uppercase">Impresora</th>
                            <th class="py-3 text-muted small fw-semibold text-uppercase">Fecha</th>
                            <th class="py-3 text-muted small fw-semibold text-uppercase">Tipo</th>
                            <th class="py-3 text-muted small fw-semibold text-uppercase">Técnico</th>
                            <th class="py-3 text-muted small fw-semibold text-uppercase">Estado Equipo</th>
                            <th class="py-3 text-muted small fw-semibold text-uppercase">Garantía</th>
                            <th class="py-3 text-muted small fw-semibold text-uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($informes as $informe)
                        <tr>
                            <td class="px-4 text-muted">{{ $informe->id }}</td>
                            <td>
                                <div class="fw-semibold" style="color:#1d2d44">
                                    {{ $informe->impresora->marca_impresora ?? 'N/A' }}
                                    {{ $informe->impresora->modelo_impresora ?? '' }}
                                </div>
                                <small class="text-muted">{{ $informe->impresora->serie_impresora ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $informe->fecha_informe->format('d/m/Y') }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $informe->tipo_badge }} bg-opacity-15 text-{{ $informe->tipo_badge }} border border-{{ $informe->tipo_badge }} border-opacity-25 px-2 py-1">
                                    {{ $informe->tipo_informe }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $informe->tecnico_nombre }}</td>
                            <td>
                                <span class="badge bg-{{ $informe->estado_badge }}">
                                    {{ $informe->estado_texto }}
                                </span>
                            </td>
                            <td>
                                @if($informe->en_garantia)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                        <i class="bi bi-shield-check me-1"></i>Sí
                                    </span>
                                @else
                                    <span class="text-muted small">No</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.informes-impresora.show', $informe->id) }}"
                                       class="btn btn-sm btn-outline-primary" title="Ver informe">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.informes-impresora.imprimir', $informe->id) }}"
                                       class="btn btn-sm btn-outline-warning" title="Imprimir" target="_blank">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            title="Eliminar"
                                            onclick="confirmDelete({{ $informe->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    {{-- Formulario oculto para el delete --}}
                                    <form id="delete-form-{{ $informe->id }}"
                                          action="{{ route('admin.informes-impresora.destroy', $informe->id) }}"
                                          method="POST" style="display:none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2 opacity-25"></i>
                                No hay informes registrados aún.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($informes->hasPages())
        <div class="card-footer bg-white d-flex justify-content-end py-3">
            {{ $informes->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(id) {
    Swal.fire({
        title: '¿Eliminar informe?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'animate__animated animate__zoomIn animate__faster'
        },
        buttonsStyling: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endpush
