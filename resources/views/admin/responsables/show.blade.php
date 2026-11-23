@extends('layouts.app')

@section('title', 'Detalles del Responsable')

@section('content')
<div class="container-fluid py-2">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1 text-dark fw-bold">
                <i class="bi bi-person-lines-fill text-info me-2"></i>Ficha del Responsable
            </h4>
            <span class="text-muted" style="font-size: 0.9rem;">
                Detalle del colaborador y equipos tecnológicos que tiene actualmente asignados
            </span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('responsables.edit', $responsable) }}" class="btn btn-warning px-3 shadow-sm text-dark fw-medium">
                <i class="bi bi-pencil-square me-1"></i> Editar
            </a>
            <a href="{{ route('responsables.index') }}" class="btn btn-outline-secondary px-3 shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Volver al listado
            </a>
        </div>
    </div>

    <div class="row g-3">
        {{-- Tarjeta Perfil del Responsable --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 mb-3">
                <div class="card-body p-4 text-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px;">
                        <i class="bi bi-person-fill fs-1"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">{{ $responsable->nombre_responsable }}</h5>
                    <p class="text-muted mb-2">
                        @if($responsable->cargo)
                            <span class="badge bg-light text-dark border px-2.5 py-1">
                                <i class="bi bi-briefcase text-primary me-1"></i>{{ $responsable->cargo }}
                            </span>
                        @else
                            <span class="text-muted small">Sin cargo asignado</span>
                        @endif
                    </p>
                    <hr>
                    <div class="text-start">
                        <div class="mb-2.5">
                            <span class="text-muted small d-block">OFICINA ASIGNADA:</span>
                            <span class="fw-bold text-dark">
                                <i class="bi bi-geo-alt text-danger me-1"></i>{{ $responsable->oficina->nombre_oficina ?? 'No asignada' }}
                            </span>
                        </div>
                        <div class="mb-2.5">
                            <span class="text-muted small d-block">AGENCIA / SEDE:</span>
                            <span class="fw-semibold text-dark">
                                <i class="bi bi-building text-secondary me-1"></i>{{ $responsable->oficina?->agencia?->nombre_agencia ?? 'No asignada' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-muted small d-block">FECHA REGISTRO:</span>
                            <span class="text-dark small">
                                <i class="bi bi-calendar3 me-1"></i>{{ $responsable->created_at ? $responsable->created_at->format('d/m/Y H:i') : '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dispositivos Asignados --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-light border-bottom py-2.5 px-3">
                    <span class="fw-bold text-dark"><i class="bi bi-devices me-1 text-primary"></i> Dispositivos y Equipos a su Cargo</span>
                </div>
                <div class="card-body p-3">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active py-1.5 px-3" id="pills-computadoras-tab" data-bs-toggle="pill" data-bs-target="#pills-computadoras" type="button" role="tab">
                                <i class="bi bi-pc-display me-1"></i> Computadoras ({{ $responsable->equipos->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-1.5 px-3" id="pills-impresoras-tab" data-bs-toggle="pill" data-bs-target="#pills-impresoras" type="button" role="tab">
                                <i class="bi bi-printer me-1"></i> Impresoras ({{ $responsable->impresoras->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-1.5 px-3" id="pills-termicas-tab" data-bs-toggle="pill" data-bs-target="#pills-termicas" type="button" role="tab">
                                <i class="bi bi-receipt me-1"></i> Térmicas ({{ $responsable->termicas->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-1.5 px-3" id="pills-contabilletes-tab" data-bs-toggle="pill" data-bs-target="#pills-contabilletes" type="button" role="tab">
                                <i class="bi bi-cash-coin me-1"></i> Contabilletes ({{ $responsable->contabilletes->count() }})
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        {{-- Computadoras --}}
                        <div class="tab-pane fade show active" id="pills-computadoras" role="tabpanel">
                            @if($responsable->equipos->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Dispositivo</th>
                                                <th>Nº Serie</th>
                                                <th>Estado</th>
                                                <th>Oficina</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($responsable->equipos as $pc)
                                            <tr>
                                                <td class="fw-bold">{{ $pc->nombre_dispositivo }}</td>
                                                <td>{{ $pc->numero_serie ?: 'N/A' }}</td>
                                                <td><span class="badge bg-secondary">{{ $pc->estado_equipo }}</span></td>
                                                <td>{{ $pc->oficina->nombre_oficina ?? 'N/A' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted small py-3 mb-0 text-center">No tiene computadoras asignadas actualmente.</p>
                            @endif
                        </div>

                        {{-- Impresoras --}}
                        <div class="tab-pane fade" id="pills-impresoras" role="tabpanel">
                            @if($responsable->impresoras->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Marca / Modelo</th>
                                                <th>Nº Serie</th>
                                                <th>IP</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($responsable->impresoras as $imp)
                                            <tr>
                                                <td class="fw-bold">{{ $imp->marca_impresora }} {{ $imp->modelo_impresora }}</td>
                                                <td>{{ $imp->serie_impresora ?: 'N/A' }}</td>
                                                <td><code>{{ $imp->ip_impresora ?: 'N/A' }}</code></td>
                                                <td><span class="badge bg-secondary">{{ $imp->estado_impresora }}</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted small py-3 mb-0 text-center">No tiene impresoras asignadas actualmente.</p>
                            @endif
                        </div>

                        {{-- Térmicas --}}
                        <div class="tab-pane fade" id="pills-termicas" role="tabpanel">
                            @if($responsable->termicas->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Marca / Modelo</th>
                                                <th>Nº Serie</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($responsable->termicas as $term)
                                            <tr>
                                                <td class="fw-bold">{{ $term->marca_termica }} {{ $term->modelo_termica }}</td>
                                                <td>{{ $term->serie_termica ?: 'N/A' }}</td>
                                                <td><span class="badge bg-secondary">{{ $term->estado_termica }}</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted small py-3 mb-0 text-center">No tiene impresoras térmicas asignadas.</p>
                            @endif
                        </div>

                        {{-- Contabilletes --}}
                        <div class="tab-pane fade" id="pills-contabilletes" role="tabpanel">
                            @if($responsable->contabilletes->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Marca / Modelo</th>
                                                <th>Nº Serie</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($responsable->contabilletes as $cont)
                                            <tr>
                                                <td class="fw-bold">{{ $cont->marca_contabilletes }} {{ $cont->modelo_contabilletes }}</td>
                                                <td>{{ $cont->serie_contabilletes ?: 'N/A' }}</td>
                                                <td><span class="badge bg-secondary">{{ $cont->estado_contabilletes }}</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted small py-3 mb-0 text-center">No tiene contadoras de billetes asignadas.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
