{{-- Modal Detalle de Incidencia (Bitácora TI) --}}
<div class="modal-header py-2.5 px-3 text-white" style="background: linear-gradient(135deg, #1d2d44 0%, #0d1b2a 100%);">
    <div class="d-flex align-items-center gap-2">
        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
            <i class="bi bi-journal-text text-white fs-6"></i>
        </div>
        <div>
            <span class="modal-title fw-bold text-white fs-6 d-block lh-sm">
                Ticket #{{ str_pad($incidencia->id, 5, '0', STR_PAD_LEFT) }} &bull; {{ $incidencia->problema }}
            </span>
            <span class="text-white-50" style="font-size: 0.85rem;">
                Registrado el {{ $incidencia->created_at ? $incidencia->created_at->format('d/m/Y \a \l\a\s H:i') : '-' }} 
                ({{ $incidencia->created_at ? $incidencia->created_at->diffForHumans() : '' }})
            </span>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        @php
            $badgeBg = match(strtolower($incidencia->estado)) {
                'atendido' => 'success',
                'pendiente' => 'danger',
                'derivado' => 'warning text-dark',
                default => 'secondary'
            };
        @endphp
        <span class="badge bg-{{ $badgeBg }} rounded-pill px-3 py-1.5" style="font-size: 0.85rem;">
            <i class="bi bi-circle-fill me-1" style="font-size: 0.6rem;"></i>{{ $incidencia->estado }}
        </span>
        <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<div class="modal-body p-3" style="max-height: 82vh; overflow-y: auto; font-size: 0.95rem;">
    {{-- Fila 1: Metadatos Rápidos --}}
    <div class="row g-2.5 mb-2.5">
        <div class="col-md-3 col-sm-6">
            <div class="p-2.5 border rounded-2 bg-white text-center h-100">
                <span class="text-muted d-block" style="font-size: 0.82rem;">TIPO ASISTENCIA</span>
                <span class="fw-bold text-primary fs-6">{{ ucfirst($incidencia->tipo) }}</span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="p-2.5 border rounded-2 bg-white text-center h-100">
                <span class="text-muted d-block" style="font-size: 0.82rem;">MÓDULO</span>
                <span class="fw-bold text-dark fs-6">{{ ucfirst($incidencia->modulo) }}</span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="p-2.5 border rounded-2 bg-white text-center h-100">
                <span class="text-muted d-block" style="font-size: 0.82rem;">PRIORIDAD</span>
                @php
                    $prioBadge = match($incidencia->prioridad) {
                        'Alta' => 'danger',
                        'Media' => 'warning text-dark',
                        'Baja' => 'info text-dark',
                        default => 'secondary'
                    };
                @endphp
                <span class="badge bg-{{ $prioBadge }} px-2.5 py-1">{{ $incidencia->prioridad }}</span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="p-2.5 border rounded-2 bg-white text-center h-100">
                <span class="text-muted d-block" style="font-size: 0.82rem;">ATENDIDO POR</span>
                <span class="fw-bold text-dark d-block text-truncate" title="{{ $incidencia->atendidoPor?->name ?? 'No asignado' }}">
                    <i class="bi bi-person-check text-success me-1"></i>{{ $incidencia->atendidoPor?->name ?? 'No asignado' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Fila 2: Ubicación y Solicitante --}}
    <div class="border rounded-2 p-2.5 mb-2.5 bg-white">
        <div class="text-primary fw-semibold mb-2" style="font-size: 0.9rem;">
            <i class="bi bi-geo-alt me-1"></i> UBICACIÓN Y USUARIO AFECTADO
        </div>
        <div class="row g-2.5">
            <div class="col-md-4">
                <label class="form-label mb-0 text-muted" style="font-size: 0.82rem;">Usuario Afectado</label>
                <div class="fw-bold text-dark">{{ $incidencia->usuario_afectado }}</div>
            </div>
            <div class="col-md-4">
                <label class="form-label mb-0 text-muted" style="font-size: 0.82rem;">Agencia Principal</label>
                <div class="fw-bold text-dark">{{ $incidencia->agencia }}</div>
            </div>
            <div class="col-md-4">
                <label class="form-label mb-0 text-muted" style="font-size: 0.82rem;">Sub-Agencia / Oficina</label>
                <div class="fw-bold text-dark">{{ $incidencia->sub_agencia ?: '— Sede Principal —' }}</div>
            </div>
        </div>
    </div>

    {{-- Fila 3: Diagnóstico y Descripción --}}
    <div class="border rounded-2 p-2.5 mb-2.5 bg-white">
        <div class="text-danger fw-semibold mb-2" style="font-size: 0.9rem;">
            <i class="bi bi-exclamation-triangle me-1"></i> PROBLEMA Y DESCRIPCIÓN DEL INCIDENTE
        </div>
        <div class="mb-2">
            <span class="badge bg-light text-dark border px-2.5 py-1 mb-1 fw-bold">{{ $incidencia->problema }}</span>
        </div>
        <div class="p-2.5 bg-light rounded-2 text-dark" style="white-space: pre-wrap; font-size: 0.95rem; border-left: 4px solid #dc3545;">{{ $incidencia->descripcion }}</div>
    </div>

    {{-- Fila 4: Solución y Procedimiento Aplicado --}}
    <div class="border rounded-2 p-2.5 mb-2 bg-white">
        <div class="text-success fw-semibold mb-2" style="font-size: 0.9rem;">
            <i class="bi bi-check2-circle me-1"></i> PROCEDIMIENTO / SOLUCIÓN TÉCNICA APLICADA
        </div>
        <div class="p-2.5 bg-light rounded-2 text-dark" style="white-space: pre-wrap; font-size: 0.95rem; border-left: 4px solid #198754;">{{ $incidencia->solucion }}</div>
    </div>
</div>

<div class="modal-footer py-2 px-3 bg-light d-flex justify-content-between align-items-center">
    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">
        <i class="bi bi-x-circle me-1"></i> Cerrar
    </button>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-warning btn-sm px-3 fw-medium btn-modal-incidencia" 
                data-url="{{ route('admin.incidencias.edit', $incidencia->id) }}">
            <i class="bi bi-pencil-square me-1"></i> Editar Incidencia
        </button>
    </div>
</div>
