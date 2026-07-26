@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-8">
        <h2 class="mb-0">Panel de Administración</h2>
        <p class="text-muted mb-0">
            Bienvenido al sistema de gestión de equipos tecnológicos TI.
        </p>
    </div>
</div>

<div class="row">
    <div class="col-md-2 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-pc-display"></i> Equipos</h5>
                <p class="card-text">{{ \App\Models\Equipo::count() }} dispositivos registrados</p>
                <a href="{{ route('equipos.index') }}" class="btn btn-light btn-sm">Ver Computadoras</a>
            </div>
        </div>
    </div>

    <div class="col-md-2 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-printer"></i> Impresoras
                </h5>
                <p class="card-text">
                    {{ \App\Models\Impresora::count() }} impresoras registradas
                </p>
                <a href="{{ route('admin.impresoras.index') }}" class="btn btn-light btn-sm">
                    Ver impresoras
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div>
                    <h5 class="card-title">
                        <i class="bi bi-journal-text me-1"></i> Bitácora
                    </h5>
                    <p class="card-text">
                        {{ \App\Models\Incidencia::count() }} Incidencias registradas
                    </p>
                    <a href="{{ route('admin.incidencias.listado') }}"class="btn btn-light btn-sm">
                        Ver bitácora
                    </a>
                </div>
                
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-diagram-3"></i> Servicio Internet
                </h5>
                <p class="card-text">
                    {{ \App\Models\ServicioInternet::count() }}
                </p>
                <a href="{{ route('admin.servicios-internet.index') }}" class="btn btn-light btn-sm">
                    Ver Servicio Internet
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Chart: Equipos por Estado -->
    <div class="col-xl-4 col-lg-6 mb-4">
        <div class="card shadow-sm h-100 border-0 rounded-4">
            <div class="card-body">
                <h5 class="card-title mb-4 fw-bold text-secondary">Estado de Equipos</h5>
                <div class="text-center">
                    <canvas id="chartEquipos" height="260"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart: Incidencias -->
    <div class="col-xl-4 col-lg-6 mb-4">
        <div class="card shadow-sm h-100 border-0 rounded-4">
            <div class="card-body">
                <h5 class="card-title mb-4 fw-bold text-secondary">Estado de Incidencias</h5>
                <div class="text-center">
                    <canvas id="chartIncidencias" height="260"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Actividad Reciente -->
    <div class="col-xl-4 mb-4">
        <div class="card shadow-sm h-100 border-0 rounded-4">
            <div class="card-body">
                <h5 class="card-title mb-4 fw-bold text-secondary">Actividad Reciente</h5>
                
                @if($incidenciasRecientes && $incidenciasRecientes->count() > 0)
                    <ul class="activity-feed mb-0 ps-2" style="list-style: none; padding-left: 0;">
                        @foreach($incidenciasRecientes as $incidencia)
                            @php
                                $statusColor = match(strtolower($incidencia->estado)) {
                                    'pendiente' => 'warning',
                                    'resuelto', 'atendido' => 'success',
                                    'en proceso' => 'info',
                                    default => 'danger'
                                };
                            @endphp
                            <li class="feed-item {{ $statusColor }}" style="position: relative; padding-bottom: 24px; padding-left: 20px; border-left: 2px solid #f3f3f9;">
                                <div class="feed-item-list">
                                    <p class="text-muted mb-1" style="font-size: 12px;"><i class="bi bi-clock"></i> {{ $incidencia->created_at->diffForHumans() }}</p>
                                    <p class="mb-0" style="font-size: 14px;">
                                        <span class="text-primary fw-medium">{{ $incidencia->modulo }}</span> - {{ Str::limit($incidencia->problema, 35) }}
                                    </p>
                                    @if($incidencia->atendido_por)
                                        <small class="text-muted d-block mt-1">Atendido por: {{ $incidencia->atendidoPor->name }}</small>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-1"></i>
                        <p class="mt-2">No hay actividad reciente</p>
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Datos de Equipos
        const equiposData = @json($equiposPorEstado ?? []);
        const equiposLabels = Object.keys(equiposData).length > 0 ? Object.keys(equiposData) : ['Sin datos'];
        const equiposValues = Object.values(equiposData).length > 0 ? Object.values(equiposData) : [1];
        
        const ctxEquipos = document.getElementById('chartEquipos');
        if(ctxEquipos) {
            new Chart(ctxEquipos.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: equiposLabels,
                    datasets: [{
                        data: equiposValues,
                        backgroundColor: ['#5156be', '#2ab57d', '#ffbf53', '#fd625e'],
                        hoverBackgroundColor: ['#5156be', '#2ab57d', '#ffbf53', '#fd625e'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }

        // Datos de Incidencias
        const incidenciasData = @json($incidenciasPorEstado ?? []);
        const incidenciasLabels = Object.keys(incidenciasData).length > 0 ? Object.keys(incidenciasData) : ['Sin datos'];
        const incidenciasValues = Object.values(incidenciasData).length > 0 ? Object.values(incidenciasData) : [1];

        const ctxIncidencias = document.getElementById('chartIncidencias');
        if(ctxIncidencias) {
            new Chart(ctxIncidencias.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: incidenciasLabels,
                    datasets: [{
                        label: 'Cantidad',
                        data: incidenciasValues,
                        backgroundColor: '#4ba6ef',
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [2, 2] } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    });
</script>
@endpush
