@extends('layouts.app')

@section('title', 'Ver Informe #' . $informe->id)

@section('content')
<div class="container-fluid">

    {{-- Breadcrumb --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.informes-impresora.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Historial
        </a>
        <a href="{{ route('admin.impresoras.show', $informe->impresora_id) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-printer"></i> Ver Impresora
        </a>
        <a href="{{ route('admin.informes-impresora.imprimir', $informe->id) }}"
           class="btn btn-sm btn-warning ms-auto" target="_blank">
            <i class="bi bi-printer me-1"></i> Imprimir Informe
        </a>
    </div>

    {{-- Cabecera del informe --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;overflow:hidden">
        <div class="card-body p-0">
            <div class="p-4" style="background:linear-gradient(135deg,#1d2d44 0%,#0d1b2a 100%)">
                <div class="row align-items-center g-3">
                    <div class="col-auto">
                        <div class="d-flex align-items-center justify-content-center rounded-3 text-white"
                             style="width:64px;height:64px;background:rgba(244,162,97,0.2);border:2px solid rgba(244,162,97,0.4)">
                            <i class="bi bi-file-earmark-text fs-2"></i>
                        </div>
                    </div>
                    <div class="col text-white">
                        <h4 class="mb-1 fw-bold">Informe Técnico #{{ $informe->id }}</h4>
                        <p class="mb-0 opacity-75">
                            {{ $informe->impresora->marca_impresora }} {{ $informe->impresora->modelo_impresora }}
                            — Serie: <strong>{{ $informe->impresora->serie_impresora }}</strong>
                        </p>
                    </div>
                    <div class="col-auto text-end">
                        <span class="badge fs-6 bg-{{ $informe->tipo_badge }} bg-opacity-20 text-{{ $informe->tipo_badge }} border border-{{ $informe->tipo_badge }} border-opacity-25 px-3 py-2">
                            {{ $informe->tipo_informe }}
                        </span>
                        <div class="text-white opacity-75 small mt-1">
                            <i class="bi bi-calendar3 me-1"></i>{{ $informe->fecha_informe->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats bar --}}
            <div class="row g-0 border-top" style="background:#f8f9fa">
                <div class="col-md-3 border-end p-3 text-center">
                    <small class="text-muted d-block">Técnico</small>
                    <span class="fw-semibold">{{ $informe->tecnico_nombre }}</span>
                </div>
                <div class="col-md-3 border-end p-3 text-center">
                    <small class="text-muted d-block">Estado del Equipo</small>
                    <span class="badge bg-{{ $informe->estado_badge }}">{{ $informe->estado_texto }}</span>
                </div>
                <div class="col-md-3 border-end p-3 text-center">
                    <small class="text-muted d-block">Contador Copias</small>
                    <span class="fw-semibold">{{ $informe->contador_copias ? number_format($informe->contador_copias) : '—' }}</span>
                </div>
                <div class="col-md-3 p-3 text-center">
                    <small class="text-muted d-block">Garantía</small>
                    @if($informe->en_garantia)
                        <span class="badge bg-success"><i class="bi bi-shield-check me-1"></i>En Garantía</span>
                    @else
                        <span class="text-muted">Sin garantía</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Columna principal --}}
        <div class="col-lg-8">

            {{-- Incidencias --}}
            @if($informe->incidencias)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3 border-0"
                     style="background:rgba(192,57,43,0.07);border-left:4px solid #c0392b !important">
                    <h6 class="mb-0 fw-bold" style="color:#c0392b">
                        <i class="bi bi-exclamation-octagon-fill me-2"></i>Incidencias
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-0" style="white-space:pre-line;line-height:1.7">{{ $informe->incidencias }}</p>
                </div>
            </div>
            @endif

            {{-- Procedimientos --}}
            @if($informe->procedimientos && count($informe->procedimientos) > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3 border-0"
                     style="background:rgba(42,157,143,0.07);border-left:4px solid #2a9d8f !important">
                    <h6 class="mb-0 fw-bold" style="color:#2a9d8f">
                        <i class="bi bi-list-check me-2"></i>Procedimientos Realizados
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0" style="line-height:1.7">
                        @foreach($informe->procedimientos as $paso)
                            <li>{{ $paso }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            {{-- Descripción del problema (Legacy) --}}
            @if($informe->descripcion_problema)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3 border-0"
                     style="background:rgba(159,63,4,0.07);border-left:4px solid #9f3f04 !important">
                    <h6 class="mb-0 fw-bold" style="color:#9f3f04">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Descripción del Problema
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-0" style="white-space:pre-line;line-height:1.7">{{ $informe->descripcion_problema }}</p>
                </div>
            </div>
            @endif

            {{-- Diagnóstico --}}
            @if($informe->diagnostico)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3 border-0"
                     style="background:rgba(42,157,143,0.07);border-left:4px solid #2a9d8f !important">
                    <h6 class="mb-0 fw-bold" style="color:#2a9d8f">
                        <i class="bi bi-search me-2"></i>Diagnóstico Técnico
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-0" style="white-space:pre-line;line-height:1.7">{{ $informe->diagnostico }}</p>
                </div>
            </div>
            @endif

            {{-- Acciones realizadas --}}
            @if($informe->acciones_realizadas)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3 border-0"
                     style="background:rgba(29,45,68,0.07);border-left:4px solid #1d2d44 !important">
                    <h6 class="mb-0 fw-bold" style="color:#1d2d44">
                        <i class="bi bi-tools me-2"></i>Acciones Realizadas
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-0" style="white-space:pre-line;line-height:1.7">{{ $informe->acciones_realizadas }}</p>
                </div>
            </div>
            @endif

            {{-- Recomendaciones --}}
            @if($informe->recomendaciones)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3 border-0"
                     style="background:rgba(233,196,106,0.15);border-left:4px solid #e9c46a !important">
                    <h6 class="mb-0 fw-bold" style="color:#9a6700">
                        <i class="bi bi-lightbulb-fill me-2"></i>Recomendaciones
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-0" style="white-space:pre-line;line-height:1.7">{{ $informe->recomendaciones }}</p>
                </div>
            </div>
            @endif

            {{-- Observaciones --}}
            @if($informe->observaciones)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3 border-0"
                     style="background:rgba(108,117,125,0.07);border-left:4px solid #6c757d !important">
                    <h6 class="mb-0 fw-bold text-secondary">
                        <i class="bi bi-chat-left-text me-2"></i>Observaciones Generales
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-0" style="white-space:pre-line;line-height:1.7">{{ $informe->observaciones }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Columna lateral: evidencias --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header py-3 border-0"
                     style="background:linear-gradient(135deg,#3d5a80,#293241);color:#fff;border-radius:12px 12px 0 0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-camera-fill me-2"></i>Evidencias Fotográficas
                    </h6>
                </div>
                <div class="card-body">
                    @if($informe->imagen_01_path)
                    <div class="mb-4">
                        <p class="fw-semibold small mb-2 text-muted">IMAGEN 01</p>
                        <img src="{{ asset('storage/' . $informe->imagen_01_path) }}"
                             alt="Evidencia 01" class="img-fluid rounded-3 shadow-sm w-100"
                             style="object-fit:cover;max-height:200px">
                        @if($informe->imagen_01_caption)
                        <p class="small text-muted mt-2 mb-0 fst-italic">
                            <i class="bi bi-camera me-1"></i>{{ $informe->imagen_01_caption }}
                        </p>
                        @endif
                    </div>
                    @else
                    <div class="text-center py-3 text-muted mb-4">
                        <i class="bi bi-image fs-2 opacity-25 d-block mb-1"></i>
                        <small>Sin imagen 01</small>
                    </div>
                    @endif

                    @if($informe->imagen_02_path)
                    <div>
                        <p class="fw-semibold small mb-2 text-muted">IMAGEN 02</p>
                        <img src="{{ asset('storage/' . $informe->imagen_02_path) }}"
                             alt="Evidencia 02" class="img-fluid rounded-3 shadow-sm w-100"
                             style="object-fit:cover;max-height:200px">
                        @if($informe->imagen_02_caption)
                        <p class="small text-muted mt-2 mb-0 fst-italic">
                            <i class="bi bi-camera me-1"></i>{{ $informe->imagen_02_caption }}
                        </p>
                        @endif
                    </div>
                    @else
                    <div class="text-center py-3 text-muted">
                        <i class="bi bi-image fs-2 opacity-25 d-block mb-1"></i>
                        <small>Sin imagen 02</small>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Info de registro --}}
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body">
                    <p class="small text-muted mb-1">
                        <i class="bi bi-clock-history me-1"></i>
                        Registrado el {{ $informe->created_at->format('d/m/Y H:i') }}
                    </p>
                    @if($informe->updated_at != $informe->created_at)
                    <p class="small text-muted mb-0">
                        <i class="bi bi-pencil me-1"></i>
                        Modificado el {{ $informe->updated_at->format('d/m/Y H:i') }}
                    </p>
                    @endif
                </div>
            </div>

            {{-- Imprimir Informe --}}
            <div class="mt-3">
                <a href="{{ route('admin.informes-impresora.imprimir', $informe->id) }}"
                   class="btn w-100 fw-semibold text-white"
                   style="background:linear-gradient(135deg,#e9c46a,#f4a261);border:none;border-radius:10px"
                   target="_blank">
                    <i class="bi bi-printer me-2"></i> Imprimir Informe
                </a>
            </div>

            {{-- Acción eliminar --}}
            <div class="mt-3">
                <form action="{{ route('admin.informes-impresora.destroy', $informe->id) }}"
                      method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline-danger w-100"
                            onclick="confirmDelete()">
                        <i class="bi bi-trash me-2"></i> Eliminar Informe
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete() {
    Swal.fire({
        title: '¿Eliminar informe?',
        text: 'Se eliminarán también las imágenes adjuntas. Esta acción no se puede deshacer.',
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
            document.getElementById('deleteForm').submit();
        }
    });
}
</script>
@endpush
