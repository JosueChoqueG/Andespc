@extends('layouts.app')

@section('title', 'Dashboard TI - Centro de Control')

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- 1. Barra Superior Compacta / Slim Header --}}
    <div class="card border-0 shadow-sm rounded-3 mb-2.5 overflow-hidden" 
         style="background: linear-gradient(135deg, #0d1b2a 0%, #1d2d44 100%);">
        <div style="height: 3px; background: linear-gradient(90deg, #9f3f04 0%, #f4a261 25%, #2a9d8f 50%, #f4a261 75%, #9f3f04 100%);"></div>
        <div class="card-body py-2 px-3 text-white">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="bg-white bg-opacity-10 rounded-2 p-1.5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-speedometer2 text-warning fs-6"></i>
                    </div>
                    <div>
                        @php
                            $hora = \Carbon\Carbon::now()->hour;
                            $saludo = $hora < 12 ? 'Buenos días' : ($hora < 19 ? 'Buenas tardes' : 'Buenas noches');
                        @endphp
                        <span class="fw-bold font-outfit fs-6 text-white mb-0 d-block lh-1">
                            {{ $saludo }}, {{ Auth::user()->name ?? 'Administrador' }}
                        </span>
                        <small class="text-white-50" style="font-size: 0.75rem;">
                            <span class="text-success"><i class="bi bi-dot"></i></span> Sistema Operativo &bull; {{ \Carbon\Carbon::now()->locale('es')->isoFormat('D MMM YYYY') }}
                        </small>
                    </div>
                </div>

                {{-- Botones de Acceso Rápido Compactos --}}
                <div class="d-flex align-items-center gap-1.5">
                    <a href="{{ route('admin.incidencias.listado') }}" class="btn btn-xs btn-primary shadow-sm px-2.5 py-1 rounded-2 fw-semibold" style="font-size: 0.8rem;">
                        <i class="bi bi-journal-plus me-1"></i> Bitácora Incidencias
                    </a>
                    <a href="{{ route('equipos.index') }}" class="btn btn-xs btn-outline-light px-2 py-1 rounded-2" style="font-size: 0.8rem;">
                        <i class="bi bi-pc-display me-1"></i> Equipos ({{ $totalEquipos }})
                    </a>
                    <a href="{{ route('admin.impresoras.index') }}" class="btn btn-xs btn-outline-light px-2 py-1 rounded-2" style="font-size: 0.8rem;">
                        <i class="bi bi-printer me-1"></i> Impresoras ({{ $totalImpresoras }})
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Fila de KPIs Compactos y Clickeables (5 Indicadores de Altura Reducida ~72px) --}}
    <div class="row g-2 mb-2.5">
        
        {{-- KPI 1: Total Incidencias --}}
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white kpi-compact-card p-2 position-relative cursor-pointer" 
                 onclick="filtrarTablaIncidencias('todos')" title="Click para ver todas">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-bold text-uppercase d-block" style="font-size: 0.68rem; letter-spacing: 0.5px;">Incidencias</span>
                        <h4 class="mb-0 fw-bold text-dark font-outfit counter-val" data-target="{{ $totalIncidencias }}">{{ $totalIncidencias }}</h4>
                        <span class="text-success fw-semibold" style="font-size: 0.7rem;"><i class="bi bi-check2"></i> {{ $tasaResolucion }}% resueltas</span>
                    </div>
                    <div class="rounded-2 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary" style="width: 36px; height: 36px;">
                        <i class="bi bi-journal-text fs-5"></i>
                    </div>
                </div>
                <div class="kpi-compact-bar bg-primary"></div>
            </div>
        </div>

        {{-- KPI 2: Atendidas con Éxito --}}
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white kpi-compact-card p-2 position-relative cursor-pointer" 
                 onclick="filtrarTablaIncidencias('Atendido')" title="Click para filtrar atendidas">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-bold text-uppercase d-block" style="font-size: 0.68rem; letter-spacing: 0.5px;">Atendidas</span>
                        <h4 class="mb-0 fw-bold text-success font-outfit counter-val" data-target="{{ $incidenciasAtendidas }}">{{ $incidenciasAtendidas }}</h4>
                        <span class="text-muted" style="font-size: 0.7rem;">Cerradas</span>
                    </div>
                    <div class="rounded-2 d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success" style="width: 36px; height: 36px;">
                        <i class="bi bi-check2-circle fs-5"></i>
                    </div>
                </div>
                <div class="kpi-compact-bar bg-success"></div>
            </div>
        </div>

        {{-- KPI 3: Pendientes Urgentes --}}
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white kpi-compact-card p-2 position-relative cursor-pointer" 
                 onclick="filtrarTablaIncidencias('Pendiente')" title="Click para ver pendientes">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-bold text-uppercase d-block" style="font-size: 0.68rem; letter-spacing: 0.5px;">Pendientes</span>
                        <h4 class="mb-0 fw-bold text-danger font-outfit counter-val" data-target="{{ $incidenciasPendientes }}">{{ $incidenciasPendientes }}</h4>
                        <span class="text-danger fw-semibold" style="font-size: 0.7rem;">
                            @if($incidenciasPendientes > 0)
                                <i class="bi bi-exclamation-circle-fill"></i> Atención urgente
                            @else
                                <i class="bi bi-check-all"></i> Al día
                            @endif
                        </span>
                    </div>
                    <div class="rounded-2 d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger" style="width: 36px; height: 36px;">
                        <i class="bi bi-clock-history fs-5"></i>
                    </div>
                </div>
                <div class="kpi-compact-bar bg-danger"></div>
            </div>
        </div>

        {{-- KPI 4: Derivadas / En Proceso --}}
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white kpi-compact-card p-2 position-relative cursor-pointer" 
                 onclick="filtrarTablaIncidencias('Derivado')" title="Click para ver derivadas">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-bold text-uppercase d-block" style="font-size: 0.68rem; letter-spacing: 0.5px;">Derivadas</span>
                        <h4 class="mb-0 fw-bold text-warning font-outfit counter-val" data-target="{{ $incidenciasDerivadas }}">{{ $incidenciasDerivadas }}</h4>
                        <span class="text-muted" style="font-size: 0.7rem;">Desarrollo/Tercero</span>
                    </div>
                    <div class="rounded-2 d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning" style="width: 36px; height: 36px;">
                        <i class="bi bi-arrow-right-circle fs-5"></i>
                    </div>
                </div>
                <div class="kpi-compact-bar bg-warning"></div>
            </div>
        </div>

        {{-- KPI 5: Parque TI (Computadoras + Térmicas) --}}
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white kpi-compact-card p-2 position-relative">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-bold text-uppercase d-block" style="font-size: 0.68rem; letter-spacing: 0.5px;">Parque TI</span>
                        <h4 class="mb-0 fw-bold text-dark font-outfit counter-val" data-target="{{ $totalEquipos }}">{{ $totalEquipos }}</h4>
                        <span class="text-muted" style="font-size: 0.7rem;">+{{ $totalImpresoras + $totalTermicas }} periféricos</span>
                    </div>
                    <div class="rounded-2 d-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info" style="width: 36px; height: 36px;">
                        <i class="bi bi-pc-display fs-5"></i>
                    </div>
                </div>
                <div class="kpi-compact-bar bg-info"></div>
            </div>
        </div>

        {{-- KPI 6: Mantenimientos TI --}}
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white kpi-compact-card p-2 position-relative">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-bold text-uppercase d-block" style="font-size: 0.68rem; letter-spacing: 0.5px;">Mantenimientos</span>
                        <h4 class="mb-0 fw-bold text-purple font-outfit counter-val" data-target="{{ $totalMantenimientos }}" style="color: #6f42c1;">{{ $totalMantenimientos }}</h4>
                        <span class="text-muted" style="font-size: 0.7rem;">Preventivos</span>
                    </div>
                    <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background-color: rgba(111, 66, 193, 0.1); color: #6f42c1;">
                        <i class="bi bi-tools fs-5"></i>
                    </div>
                </div>
                <div class="kpi-compact-bar" style="background-color: #6f42c1;"></div>
            </div>
        </div>

    </div>

    {{-- 3. Centro de Gráficos con Tabs Dinámicas + Panel de Atención Inmediata (Mediano: Altura ~310px) --}}
    <div class="row g-2.5 mb-2.5">
        
        {{-- Columna Izquierda (col-lg-7): Card de Gráficos con Tabs Interactivas --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                <div class="card-header bg-white border-0 py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <ul class="nav nav-pills nav-pills-custom gap-1" id="pills-tab-charts" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active py-1 px-2.5 rounded-2 small fw-semibold" id="tab-tendencia" data-bs-toggle="pill" data-bs-target="#pane-tendencia" type="button" role="tab">
                                <i class="bi bi-graph-up me-1"></i> Tendencia
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-1 px-2.5 rounded-2 small fw-semibold" id="tab-tipos" data-bs-toggle="pill" data-bs-target="#pane-tipos" type="button" role="tab">
                                <i class="bi bi-pie-chart me-1"></i> Por Tipo
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-1 px-2.5 rounded-2 small fw-semibold" id="tab-modulos" data-bs-toggle="pill" data-bs-target="#pane-modulos" type="button" role="tab">
                                <i class="bi bi-layers me-1"></i> Módulos
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-1 px-2.5 rounded-2 small fw-semibold" id="tab-equipos" data-bs-toggle="pill" data-bs-target="#pane-equipos" type="button" role="tab">
                                <i class="bi bi-laptop me-1"></i> Equipos
                            </button>
                        </li>
                    </ul>
                    <span class="badge bg-light text-muted border small px-2 py-1" style="font-size: 0.7rem;">
                        <i class="bi bi-bar-chart-line me-1"></i> Métricas TI
                    </span>
                </div>

                <div class="card-body p-2.5 pt-0">
                    <div class="tab-content" id="pills-tabContentCharts">
                        
                        {{-- Pane 1: Tendencia Semestral --}}
                        <div class="tab-pane fade show active" id="pane-tendencia" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-1 text-muted" style="font-size: 0.75rem;">
                                <span>Evolución mensual de solicitudes de asistencia técnica</span>
                                <span class="fw-bold text-dark">{{ array_sum($tendenciaTotales) }} tickets registrados</span>
                            </div>
                            <div style="height: 220px; position: relative;">
                                <canvas id="chartTendenciaCompact"></canvas>
                            </div>
                        </div>

                        {{-- Pane 2: Incidencias por Tipo (Software, Hardware, Redes) --}}
                        <div class="tab-pane fade" id="pane-tipos" role="tabpanel">
                            <div class="row align-items-center g-2 pt-1">
                                <div class="col-sm-6 text-center">
                                    <div style="height: 215px; position: relative;">
                                        <canvas id="chartTiposCompact"></canvas>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-2.5 rounded-3 bg-light border small">
                                        <h6 class="fw-bold mb-2 font-outfit" style="font-size: 0.85rem;">Distribución por Falla:</h6>
                                        <div class="d-flex justify-content-between align-items-center mb-1.5">
                                            <span><i class="bi bi-circle-fill text-primary me-1" style="font-size: 8px;"></i> Software / Core</span>
                                            <strong class="text-dark">{{ $incidenciasPorTipo['software'] ?? 0 }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-1.5">
                                            <span><i class="bi bi-circle-fill text-warning me-1" style="font-size: 8px;"></i> Hardware Físico</span>
                                            <strong class="text-dark">{{ $incidenciasPorTipo['hardware'] ?? 0 }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span><i class="bi bi-circle-fill text-success me-1" style="font-size: 8px;"></i> Conectividad / Red</span>
                                            <strong class="text-dark">{{ $incidenciasPorTipo['conectividad'] ?? 0 }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Pane 3: Top Módulos --}}
                        <div class="tab-pane fade" id="pane-modulos" role="tabpanel">
                            <div class="pt-1">
                                <div class="d-flex flex-column gap-2" style="max-height: 220px; overflow-y: auto;">
                                    @php
                                        $maxMod = !empty($topModulos) ? max($topModulos) : 1;
                                        $barColors = ['#0d6efd', '#2a9d8f', '#f4a261', '#6f42c1', '#6c757d'];
                                    @endphp
                                    @foreach($topModulos as $mod => $tot)
                                        @php
                                            $porc = round(($tot / $maxMod) * 100);
                                            $col = $barColors[$loop->index % count($barColors)];
                                        @endphp
                                        <div class="p-1.5 rounded-2 bg-light border">
                                            <div class="d-flex justify-content-between small mb-1">
                                                <span class="fw-semibold text-dark text-capitalize"><i class="bi bi-folder2-open me-1 text-muted"></i>{{ $mod }}</span>
                                                <span class="text-muted"><strong>{{ $tot }}</strong> tickets ({{ round(($tot / ($totalIncidencias ?: 1)) * 100, 1) }}%)</span>
                                            </div>
                                            <div class="progress rounded-pill" style="height: 6px; background-color: #e9ecef;">
                                                <div class="progress-bar rounded-pill" style="width: {{ $porc }}%; background-color: {{ $col }};"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Pane 4: Salud de Equipos --}}
                        <div class="tab-pane fade" id="pane-equipos" role="tabpanel">
                            <div class="row align-items-center g-2 pt-1">
                                <div class="col-sm-6 text-center">
                                    <div style="height: 215px; position: relative;">
                                        <canvas id="chartEquiposCompact"></canvas>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-2.5 rounded-3 bg-light border small">
                                        <h6 class="fw-bold mb-2 font-outfit" style="font-size: 0.85rem;">Resumen de Equipos:</h6>
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Operativos</span>
                                            <strong>{{ $equiposOperativos }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="text-warning"><i class="bi bi-exclamation-circle-fill me-1"></i> Con Observación</span>
                                            <strong>{{ $equiposObservacion }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-danger"><i class="bi bi-x-circle-fill me-1"></i> De Baja / Fuera</span>
                                            <strong>{{ $equiposBaja }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Columna Derecha (col-lg-5): Panel de Atención Urgente / Pendientes --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                <div class="card-header bg-white border-0 py-2 px-3 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fw-bold text-dark font-outfit small d-block">
                            <i class="bi bi-bell-fill text-danger me-1"></i> Atención Requerida
                        </span>
                        <small class="text-muted" style="font-size: 0.72rem;">Tickets pendientes y derivados</small>
                    </div>
                    <span class="badge {{ $incidenciasPendientes > 0 ? 'bg-danger' : 'bg-success' }} rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">
                        {{ $incidenciasPendientes + $incidenciasDerivadas }} abiertos
                    </span>
                </div>

                <div class="card-body p-2 pt-0">
                    <div class="d-flex flex-column gap-1.5" style="max-height: 250px; overflow-y: auto;">
                        @forelse($incidenciasAtencion as $inc)
                            @php
                                $badgeClase = $inc->estado === 'Pendiente' ? 'bg-danger text-white' : 'bg-warning text-dark';
                                $prioBadge = match($inc->prioridad) {
                                    'Alta' => 'text-danger fw-bold',
                                    'Media' => 'text-warning fw-semibold',
                                    default => 'text-muted'
                                };
                            @endphp
                            <div class="p-2 rounded-2 border bg-light bg-opacity-75 transition-all hover-highlight">
                                <div class="d-flex justify-content-between align-items-center mb-0.5">
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="badge {{ $badgeClase }} rounded-pill px-1.5 py-0.5" style="font-size: 0.65rem;">
                                            {{ $inc->estado }}
                                        </span>
                                        <span class="{{ $prioBadge }}" style="font-size: 0.7rem;">
                                            • {{ $inc->prioridad }}
                                        </span>
                                        <span class="fw-bold text-dark" style="font-size: 0.75rem;">#{{ $inc->id }}</span>
                                    </div>
                                    <span class="text-muted" style="font-size: 0.7rem;">
                                        <i class="bi bi-clock"></i> {{ $inc->created_at->diffForHumans(null, true) }}
                                    </span>
                                </div>
                                <div class="fw-semibold text-dark text-truncate mb-0.5" style="font-size: 0.8rem;" title="{{ $inc->problema }}">
                                    {{ $inc->problema }}
                                </div>
                                <div class="d-flex justify-content-between align-items-center text-muted" style="font-size: 0.72rem;">
                                    <span class="text-truncate" style="max-width: 70%;"><i class="bi bi-person"></i> {{ $inc->usuario_afectado }} &bull; {{ Str::limit($inc->agencia, 18) }}</span>
                                    <a href="{{ route('admin.incidencias.listado', ['search' => $inc->id]) }}" class="btn btn-xs btn-outline-primary py-0 px-1.5 rounded-1" style="font-size: 0.7rem;">
                                        Atender <i class="bi bi-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-shield-check text-success fs-3 d-block mb-1"></i>
                                <span class="small fw-semibold text-dark d-block">Sin incidencias pendientes</span>
                                <small style="font-size: 0.72rem;">Todos los tickets han sido atendidos.</small>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- 4. Sección Inferior: Bitácora Interactiva con Filtros Dinámicos en Vivo (Altura Compacta ~270px) --}}
    <div class="card border-0 shadow-sm rounded-3 bg-white mb-3">
        <div class="card-header bg-white border-0 py-2 px-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold text-dark font-outfit small">
                        <i class="bi bi-activity text-primary me-1"></i> Asistencias Técnicas Recientes
                    </span>
                    
                    {{-- Filtros Dinámicos Rápidos (Pills) --}}
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-xs btn-outline-secondary active py-0.5 px-2 filter-btn" onclick="filtrarTablaIncidencias('todos', this)">Todos</button>
                        <button type="button" class="btn btn-xs btn-outline-primary py-0.5 px-2 filter-btn" onclick="filtrarTablaIncidencias('software', this)">Software</button>
                        <button type="button" class="btn btn-xs btn-outline-warning py-0.5 px-2 filter-btn" onclick="filtrarTablaIncidencias('hardware', this)">Hardware</button>
                        <button type="button" class="btn btn-xs btn-outline-success py-0.5 px-2 filter-btn" onclick="filtrarTablaIncidencias('conectividad', this)">Redes</button>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <div class="input-group input-group-sm" style="width: 200px;">
                        <span class="input-group-text bg-light border-end-0 py-0 px-2"><i class="bi bi-search text-muted small"></i></span>
                        <input type="text" id="liveSearchIncidencias" class="form-control form-control-sm border-start-0 py-0" 
                               placeholder="Buscar en vivo..." style="font-size: 0.78rem;" onkeyup="buscarEnTablaEnVivo()">
                    </div>
                    <a href="{{ route('admin.incidencias.listado') }}" class="btn btn-xs btn-light border py-1 px-2 rounded-2" style="font-size: 0.75rem;" title="Ver tabla completa con paginación">
                        Ver Bitácora <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 240px; overflow-y: auto;">
                <table class="table table-sm table-hover align-middle mb-0" id="tablaDashboardIncidencias" style="font-size: 0.8rem;">
                    <thead class="bg-light text-muted sticky-top" style="font-size: 0.72rem; text-transform: uppercase;">
                        <tr>
                            <th class="ps-3 py-1.5">ID / Fecha</th>
                            <th class="py-1.5">Usuario & Sede</th>
                            <th class="py-1.5">Categoría & Módulo</th>
                            <th class="py-1.5">Problema</th>
                            <th class="py-1.5">Estado</th>
                            <th class="py-1.5">Técnico TI</th>
                            <th class="text-end pe-3 py-1.5">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($incidenciasRecientes as $inc)
                            @php
                                $estadoClase = match($inc->estado) {
                                    'Atendido' => 'bg-success text-white',
                                    'Pendiente' => 'bg-danger text-white',
                                    'Derivado' => 'bg-warning text-dark',
                                    default => 'bg-secondary text-white'
                                };
                                $tipoClase = match(strtolower($inc->tipo)) {
                                    'software' => 'text-primary',
                                    'hardware' => 'text-warning',
                                    'conectividad' => 'text-success',
                                    default => 'text-muted'
                                };
                            @endphp
                            <tr class="fila-incidencia" 
                                data-tipo="{{ strtolower($inc->tipo) }}" 
                                data-estado="{{ $inc->estado }}">
                                <td class="ps-3 py-1.5">
                                    <span class="fw-bold text-dark">#{{ $inc->id }}</span>
                                    <small class="text-muted d-block" style="font-size: 0.68rem;">{{ $inc->created_at->diffForHumans(null, true) }}</small>
                                </td>
                                <td class="py-1.5">
                                    <span class="fw-semibold text-dark d-block">{{ $inc->usuario_afectado }}</span>
                                    <small class="text-muted" style="font-size: 0.7rem;">{{ Str::limit($inc->agencia, 22) }}</small>
                                </td>
                                <td class="py-1.5">
                                    <span class="fw-semibold {{ $tipoClase }} text-capitalize d-block">{{ $inc->tipo }}</span>
                                    <small class="text-muted text-capitalize" style="font-size: 0.7rem;">{{ $inc->modulo }}</small>
                                </td>
                                <td class="py-1.5">
                                    <span class="text-dark d-block text-truncate" style="max-width: 280px;" title="{{ $inc->problema }}">
                                        {{ $inc->problema }}
                                    </span>
                                </td>
                                <td class="py-1.5">
                                    <span class="badge {{ $estadoClase }} rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">
                                        {{ $inc->estado }}
                                    </span>
                                </td>
                                <td class="py-1.5">
                                    <small class="text-dark fw-medium">{{ $inc->atendidoPor->name ?? 'Técnico TI' }}</small>
                                </td>
                                <td class="text-end pe-3 py-1.5">
                                    <a href="{{ route('admin.incidencias.listado', ['search' => $inc->id]) }}" class="btn btn-xs btn-light border py-0.5 px-2 rounded-1" title="Ver en bitácora">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3 text-muted">No hay registros recientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Estilos Compactos & Dinámicos --}}
<style>
    .font-outfit {
        font-family: 'Outfit', sans-serif;
    }
    .kpi-compact-card {
        transition: transform 0.18s ease, box-shadow 0.18s ease;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    .kpi-compact-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.07) !important;
    }
    .kpi-compact-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2.5px;
    }
    .cursor-pointer {
        cursor: pointer;
    }
    .nav-pills-custom .nav-link {
        color: #6c757d;
        background-color: transparent;
        transition: all 0.2s;
    }
    .nav-pills-custom .nav-link.active {
        color: #ffffff;
        background-color: #0d6efd;
    }
    .hover-highlight:hover {
        background-color: #ffffff !important;
        border-color: #0d6efd !important;
    }
    .btn-xs {
        padding: 0.2rem 0.45rem;
        font-size: 0.75rem;
    }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    const palette = {
        primary: '#0d6efd',
        teal: '#2a9d8f',
        gold: '#f4a261',
        danger: '#dc3545',
        warning: '#ffc107',
        purple: '#6f42c1',
        dark: '#1d2d44'
    };

    // 1️⃣ Animación de Contadores Numéricos Suaves
    document.querySelectorAll('.counter-val').forEach(counter => {
        const target = +counter.getAttribute('data-target');
        if (!target) return;
        const duration = 650;
        const stepTime = 20;
        const steps = duration / stepTime;
        const inc = target / steps;
        let val = 0;
        const t = setInterval(() => {
            val += inc;
            if (val >= target) {
                counter.innerText = Number(target).toLocaleString('es-PE');
                clearInterval(t);
            } else {
                counter.innerText = Math.floor(val).toLocaleString('es-PE');
            }
        }, stepTime);
    });

    // 2️⃣ Gráfico 1: Tendencia Semestral (Compact Line Spline)
    const ctxTendencia = document.getElementById('chartTendenciaCompact');
    if (ctxTendencia) {
        const grad = ctxTendencia.getContext('2d').createLinearGradient(0, 0, 0, 200);
        grad.addColorStop(0, 'rgba(13, 110, 253, 0.25)');
        grad.addColorStop(1, 'rgba(13, 110, 253, 0.00)');

        new Chart(ctxTendencia, {
            type: 'line',
            data: {
                labels: @json($tendenciaLabels ?? []),
                datasets: [{
                    label: 'Incidencias',
                    data: @json($tendenciaTotales ?? []),
                    borderColor: palette.primary,
                    borderWidth: 2.5,
                    fill: true,
                    backgroundColor: grad,
                    tension: 0.35,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: palette.primary,
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: palette.dark,
                        padding: 8,
                        cornerRadius: 6,
                        callbacks: {
                            label: ctx => ` ${ctx.parsed.y} asistencias`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)', borderDash: [2, 2] },
                        ticks: { font: { size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    }

    // 3️⃣ Gráfico 2: Tipos de Incidencias (Compact Doughnut)
    let chartTiposInstance = null;
    document.getElementById('tab-tipos')?.addEventListener('shown.bs.tab', function () {
        if (chartTiposInstance) return;
        const ctx = document.getElementById('chartTiposCompact');
        if (!ctx) return;

        const tiposData = @json($incidenciasPorTipo ?? []);
        chartTiposInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Software', 'Hardware', 'Conectividad'],
                datasets: [{
                    data: [
                        tiposData['software'] ?? 0, 
                        tiposData['hardware'] ?? 0, 
                        tiposData['conectividad'] ?? 0
                    ],
                    backgroundColor: [palette.primary, palette.gold, palette.teal],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: palette.dark,
                        callbacks: {
                            label: ctx => ` ${ctx.label}: ${ctx.raw} tickets`
                        }
                    }
                }
            }
        });
    });

    // 4️⃣ Gráfico 3: Equipos Estado (Compact Doughnut)
    let chartEquiposInstance = null;
    document.getElementById('tab-equipos')?.addEventListener('shown.bs.tab', function () {
        if (chartEquiposInstance) return;
        const ctx = document.getElementById('chartEquiposCompact');
        if (!ctx) return;

        const eqData = @json($equiposPorEstado ?? []);
        chartEquiposInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(eqData),
                datasets: [{
                    data: Object.values(eqData),
                    backgroundColor: [palette.teal, palette.warning, palette.danger, '#6c757d'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });

});

// ⚡ Funciones Dinámicas en Vivo (Filtro rápido sin recargar página)
function filtrarTablaIncidencias(filtro, btnEl) {
    if (btnEl) {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btnEl.classList.add('active');
    }

    const filas = document.querySelectorAll('#tablaDashboardIncidencias tbody .fila-incidencia');
    filtro = filtro.toLowerCase();

    filas.forEach(fila => {
        const tipo = (fila.getAttribute('data-tipo') || '').toLowerCase();
        const estado = (fila.getAttribute('data-estado') || '').toLowerCase();

        if (filtro === 'todos' || tipo === filtro || estado === filtro) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}

function buscarEnTablaEnVivo() {
    const input = document.getElementById('liveSearchIncidencias');
    const term = input.value.toLowerCase();
    const filas = document.querySelectorAll('#tablaDashboardIncidencias tbody .fila-incidencia');

    filas.forEach(fila => {
        const texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(term) ? '' : 'none';
    });
}
</script>
@endpush
