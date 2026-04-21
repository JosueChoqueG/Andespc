{{-- resources/views/admin/mantenimientos-pc/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Nuevo Mantenimiento')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-tools"></i> 
                        Mantenimiento de Equipo: {{ $equipo->nombre_dispositivo }}
                    </h5>
                </div>
                
                <div class="card-body">
                    {{-- Datos del equipo (pre-llenados automáticamente) --}}
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle"></i> Información del Equipo</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Nombre:</strong> {{ $equipo->nombre_dispositivo }}
                            </div>
                            <div class="col-md-3">
                                <strong>Serie:</strong> {{ $equipo->numero_serie ?? 'N/A' }}
                            </div>
                            <div class="col-md-3">
                                <strong>IP:</strong> {{ $equipo->direccion_ip ?? 'N/A' }}
                            </div>
                            <div class="col-md-3">
                                <strong>Oficina:</strong> {{ $equipo->oficina->nombre_oficina ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong>Hardware:</strong> {{ $equipo->hardware_completo }}
                            </div>
                            <div class="col-md-6">
                                <strong>Sistema Operativo:</strong> {{ $equipo->sistema_operativo_completo }}
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.mantenimientos-pc.store', $equipo) }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Mantenimiento <span class="text-danger">*</span></label>
                                    <select name="tipo_mantenimiento" class="form-select" required>
                                        <option value="Preventivo">Preventivo</option>
                                        <option value="Correctivo">Correctivo</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Fecha <span class="text-danger">*</span></label>
                                    <input type="date" name="fecha_mantenimiento" class="form-control" 
                                           value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trabajos Realizados <span class="text-danger">*</span></label>
                            <textarea name="descripcion_mantenimiento" class="form-control" rows="4" required 
                                      placeholder="Ingrese cada trabajo en una línea nueva..."></textarea>
                            <small class="text-muted"><i class="bi bi-info-circle"></i> Presione Enter para agregar múltiples trabajos</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Fallas Encontradas</label>
                                    <textarea name="fallas_encontradas" class="form-control" rows="3" 
                                              placeholder="Describa las fallas encontradas..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Soluciones Aplicadas</label>
                                    <textarea name="soluciones_aplicadas" class="form-control" rows="3" 
                                              placeholder="Describa las soluciones aplicadas..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observaciones y Recomendaciones</label>
                            <textarea name="observaciones" class="form-control" rows="3" 
                                      placeholder="Observaciones y recomendaciones para el usuario...">{{ $ultimoMantenimiento->observaciones ?? '' }}</textarea>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="generar_hoja_vida" class="form-check-input" 
                                       id="generarHojaVida" checked>
                                <label class="form-check-label" for="generarHojaVida">
                                    <i class="bi bi-file-pdf"></i> Generar Hoja de Vida automáticamente
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('equipos.show', $equipo) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Mantenimiento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection