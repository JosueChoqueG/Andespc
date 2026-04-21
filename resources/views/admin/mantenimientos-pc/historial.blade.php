{{-- resources/views/admin/mantenimientos-pc/historial.blade.php --}}
@extends('layouts.app')

@section('title', 'Historial de Mantenimientos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> 
                        Historial de Mantenimientos: {{ $equipo->nombre_dispositivo }}
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('admin.mantenimientos-pc.create', $equipo) }}" 
                           class="btn btn-light btn-sm">
                            <i class="bi bi-plus-circle"></i> Nuevo
                        </a>
                        <a href="{{ route('admin.equipos.hoja-vida', $equipo) }}" 
                           class="btn btn-light btn-sm" target="_blank">
                            <i class="bi bi-file-pdf"></i> Hoja de Vida
                        </a>
                        <a href="{{ route('admin.equipos.hoja-vida.pdf', $equipo) }}" 
                           class="btn btn-light btn-sm">
                            <i class="bi bi-download"></i> PDF
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    {{-- Información del equipo --}}
                    <div class="alert alert-secondary">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Equipo:</strong> {{ $equipo->nombre_dispositivo }}
                            </div>
                            <div class="col-md-3">
                                <strong>Serie:</strong> {{ $equipo->numero_serie ?? 'N/A' }}
                            </div>
                            <div class="col-md-3">
                                <strong>Oficina:</strong> {{ $equipo->oficina->nombre_oficina ?? 'N/A' }}
                            </div>
                            <div class="col-md-3">
                                <strong>Estado:</strong> 
                                <span class="badge bg-{{ $equipo->estado_equipo == 'Activo' ? 'success' : 'warning' }}">
                                    {{ $equipo->estado_equipo }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Tabla de historial --}}
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th width="100">Fecha</th>
                                    <th width="100">Tipo</th>
                                    <th>Trabajos Realizados</th>
                                    <th width="150">Técnico</th>
                                    <th width="120">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mantenimientos as $mantenimiento)
                                <tr>
                                    <td>{{ $mantenimiento->fecha_mantenimiento->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $mantenimiento->tipo_mantenimiento == 'Preventivo' ? 'primary' : 'warning' }}">
                                            {{ $mantenimiento->tipo_mantenimiento }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>
                                            @foreach($mantenimiento->getDescripcionArrayAttribute() as $item)
                                                • {{ $item }}<br>
                                            @endforeach
                                        </small>
                                    </td>
                                    <td>{{ $mantenimiento->tecnico_nombre ?? 'Josue Choque Gomez' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.mantenimientos-pc.show', $mantenimiento) }}" 
                                               class="btn btn-info" title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.equipos.hoja-vida-mantenimiento', $mantenimiento) }}" 
                                               class="btn btn-primary" title="Hoja de Vida" target="_blank">
                                                <i class="bi bi-file-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        No hay mantenimientos registrados para este equipo
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $mantenimientos->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection