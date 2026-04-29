@extends('layouts.admin')

@section('title', 'Detalle de Mantenimiento')
@section('header', 'Detalle de Mantenimiento')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tools"></i> Mantenimiento #{{ $mantenimiento->id }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.impresoras.show', $mantenimiento->impresora_id) }}" class="btn btn-default btn-sm">
                            <i class="fas fa-print"></i> Ver Impresora
                        </a>
                        <a href="{{ route('admin.mantenimientos-impresora.edit', $mantenimiento->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted">Impresora</span>
                                    <span class="info-box-number">
                                        {{ $mantenimiento->impresora->marca_impresora }} 
                                        {{ $mantenimiento->impresora->modelo_impresora }}
                                    </span>
                                    <small>Serie: {{ $mantenimiento->impresora->serie_impresora }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted">Tipo de Mantenimiento</span>
                                    <span class="info-box-number">
                                        <span class="badge badge-{{ $mantenimiento->tipo_mantenimiento == 'Preventivo' ? 'success' : 'danger' }} badge-lg">
                                            {{ $mantenimiento->tipo_mantenimiento }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted">Fecha Mantenimiento</span>
                                    <span class="info-box-number">
                                        {{ date('d/m/Y', strtotime($mantenimiento->fecha_mantenimiento)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted">Fecha de Fallas</span>
                                    <span class="info-box-number">
                                        @if($mantenimiento->fecha_fallas)
                                            {{ date('d/m/Y', strtotime($mantenimiento->fecha_fallas)) }}
                                        @else
                                            <span class="text-muted">No registrada</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header bg-info">
                            <h5 class="card-title mb-0">Descripción del Mantenimiento</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text" style="white-space: pre-line;">
                                {{ $mantenimiento->descripcion_mantenimiento }}
                            </p>
                        </div>
                    </div>

                    @if($mantenimiento->observacion_mantenimiento)
                    <div class="card mt-3">
                        <div class="card-header bg-secondary">
                            <h5 class="card-title mb-0">Observaciones del Mantenimiento</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text" style="white-space: pre-line;">
                                {{ $mantenimiento->observacion_mantenimiento }}
                            </p>
                        </div>
                    </div>
                    @endif

                    @if($mantenimiento->fallas_detectadas)
                    <div class="card mt-3 border-danger">
                        <div class="card-header bg-danger text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-exclamation-triangle"></i> Fallas Detectadas
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text" style="white-space: pre-line;">
                                {{ $mantenimiento->fallas_detectadas }}
                            </p>
                        </div>
                    </div>
                    @endif

                    @if($mantenimiento->fallas_solucion)
                    <div class="card mt-3 border-success">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-check-circle"></i> Solución Aplicada
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text" style="white-space: pre-line;">
                                {{ $mantenimiento->fallas_solucion }}
                            </p>
                        </div>
                    </div>
                    @endif

                    @if($mantenimiento->observacion_general)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Observaciones Generales</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text" style="white-space: pre-line;">
                                {{ $mantenimiento->observacion_general }}
                            </p>
                        </div>
                    </div>
                    @endif

                    <div class="mt-3 text-muted text-center">
                        <small>
                            Creado: {{ $mantenimiento->created_at ? date('d/m/Y H:i:s', strtotime($mantenimiento->created_at)) : 'N/A' }} |
                            Actualizado: {{ $mantenimiento->updated_at ? date('d/m/Y H:i:s', strtotime($mantenimiento->updated_at)) : 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection