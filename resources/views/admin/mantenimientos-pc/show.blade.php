{{-- resources/views/admin/mantenimientos-pc/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detalle de Mantenimiento')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-eye"></i> 
                        Detalle de Mantenimiento #{{ $mantenimiento->id }}
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('admin.equipos.hoja-vida-mantenimiento', $mantenimiento) }}" 
                           class="btn btn-light btn-sm" target="_blank">
                            <i class="bi bi-file-pdf"></i> Hoja de Vida
                        </a>
                        <a href="{{ route('admin.equipos.hoja-vida-mantenimiento.pdf', $mantenimiento) }}" 
                           class="btn btn-light btn-sm">
                            <i class="bi bi-download"></i> Descargar PDF
                        </a>
                        <a href="{{ route('admin.mantenimientos-pc.historial', $mantenimiento->equipo) }}" 
                           class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Equipo</th>
                                    <td>{{ $equipo->nombre_dispositivo }}</td>
                                </tr>
                                <tr>
                                    <th>Número de Serie</th>
                                    <td>{{ $equipo->numero_serie ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Oficina</th>
                                    <td>{{ $equipo->oficina->nombre_oficina ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Técnico Responsable</th>
                                    <td>{{ $mantenimiento->tecnico_nombre ?? 'Josue Choque Gomez' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Tipo de Mantenimiento</th>
                                    <td>
                                        <span class="badge bg-{{ $mantenimiento->tipo_mantenimiento == 'Preventivo' ? 'primary' : 'warning' }}">
                                            {{ $mantenimiento->tipo_mantenimiento }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fecha</th>
                                    <td>{{ $mantenimiento->fecha_mantenimiento->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha de Registro</th>
                                    <td>{{ $mantenimiento->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0">Trabajos Realizados</h6>
                                </div>
                                <div class="card-body">
                                    <ul>
                                        @foreach($mantenimiento->getDescripcionArrayAttribute() as $trabajo)
                                            <li>{{ $trabajo }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($mantenimiento->fallas_encontradas)
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0">Fallas Encontradas</h6>
                                </div>
                                <div class="card-body">
                                    <p>{{ $mantenimiento->fallas_encontradas }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">Soluciones Aplicadas</h6>
                                </div>
                                <div class="card-body">
                                    <p>{{ $mantenimiento->soluciones_aplicadas ?? 'No especificada' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($mantenimiento->observaciones)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">Observaciones y Recomendaciones</h6>
                                </div>
                                <div class="card-body">
                                    <p>{{ $mantenimiento->observaciones }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection