@extends('layouts.app')

@section('title', 'Historial Informes — {{ $impresora->serie_impresora }}')

@section('content')
<div class="container-fluid">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.impresoras.show', $impresora->id) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver a Impresora
        </a>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3" style="border-radius:14px">
                <div class="fs-2 fw-bold" style="color:#1d2d44">{{ $estadisticas['total'] }}</div>
                <div class="text-muted small">Total Informes</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3" style="border-radius:14px">
                <div class="fs-2 fw-bold text-danger">{{ $estadisticas['atascos'] }}</div>
                <div class="text-muted small">Atascos de Papel</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3" style="border-radius:14px">
                <div class="fs-2 fw-bold text-warning">{{ $estadisticas['fallas'] }}</div>
                <div class="text-muted small">Fallas</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3" style="border-radius:14px">
                <div class="fs-2 fw-bold text-success">{{ $estadisticas['mantenimientos'] }}</div>
                <div class="text-muted small">Mantenimientos</div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center py-3 border-0"
             style="background:linear-gradient(135deg,#1d2d44,#0d1b2a);border-radius:12px 12px 0 0">
            <h6 class="mb-0 fw-semibold text-white">
                <i class="bi bi-clock-history me-2"></i>
                Informes de {{ $impresora->marca_impresora }} {{ $impresora->modelo_impresora }}
                — {{ $impresora->serie_impresora }}
            </h6>
            <a href="{{ route('admin.informes-impresora.create', $impresora->id) }}"
               class="btn btn-sm btn-warning fw-semibold">
                <i class="bi bi-plus-lg me-1"></i> Nuevo Informe
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background:#f8f9fa">
                        <tr>
                            <th class="px-4 py-3 text-muted small fw-semibold text-uppercase">#</th>
                            <th class="py-3 text-muted small fw-semibold text-uppercase">Fecha</th>
                            <th class="py-3 text-muted small fw-semibold text-uppercase">Tipo</th>
                            <th class="py-3 text-muted small fw-semibold text-uppercase">Técnico</th>
                            <th class="py-3 text-muted small fw-semibold text-uppercase">Estado</th>
                            <th class="py-3 text-muted small fw-semibold text-uppercase">Contador</th>
                            <th class="py-3 text-muted small fw-semibold text-uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($informes as $informe)
                        <tr>
                            <td class="px-4 text-muted">{{ $informe->id }}</td>
                            <td class="fw-semibold">{{ $informe->fecha_informe->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $informe->tipo_badge }} bg-opacity-15 text-{{ $informe->tipo_badge }} border border-{{ $informe->tipo_badge }} border-opacity-25 px-2 py-1">
                                    {{ $informe->tipo_informe }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $informe->tecnico_nombre }}</td>
                            <td>
                                <span class="badge bg-{{ $informe->estado_badge }}">{{ $informe->estado_texto }}</span>
                            </td>
                            <td class="text-muted">{{ $informe->contador_copias ? number_format($informe->contador_copias) : '—' }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.informes-impresora.show', $informe->id) }}"
                                       class="btn btn-sm btn-outline-primary" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.informes-impresora.imprimir', $informe->id) }}"
                                       class="btn btn-sm btn-outline-warning" title="Imprimir" target="_blank">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            title="Eliminar" onclick="confirmDelete({{ $informe->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="del-{{ $informe->id }}"
                                          action="{{ route('admin.informes-impresora.destroy', $informe->id) }}"
                                          method="POST" style="display:none">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2 opacity-25"></i>
                                No hay informes para esta impresora.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
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
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            confirmButton: 'btn btn-danger me-2',
            cancelButton: 'btn btn-secondary',
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('del-' + id).submit();
        }
    });
}
</script>
@endpush
