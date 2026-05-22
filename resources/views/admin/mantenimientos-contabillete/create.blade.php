@extends('layouts.app')

@section('title', 'Registrar Mantenimiento de Contadora')
@section('header', 'Nuevo Mantenimiento de Contadora de Billetes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-tools"></i> 
                        Mantenimiento de Contadora de Billetes: {{ $contabillete->serie_contabilletes }}
                    </h5>
                </div>
                
                <div class="card-body">
                    {{-- Datos de la contadora --}}
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle"></i> Información de la Contadora de Billetes</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Marca/Modelo:</strong> {{ $contabillete->marca_contabilletes }} {{ $contabillete->modelo_contabilletes }}
                            </div>
                            <div class="col-md-3">
                                <strong>Serie:</strong> {{ $contabillete->serie_contabilletes }}
                            </div>
                            <div class="col-md-3">
                                <strong>Oficina:</strong> {{ $contabillete->oficina->nombre_oficina ?? 'N/A' }}
                            </div>
                            <div class="col-md-3">
                                <strong>Estado Actual:</strong> {{ $contabillete->estado_contabilletes }}
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.mantenimientos-contabillete.store', $contabillete) }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Mantenimiento <span class="text-danger">*</span></label>
                                    <select name="tipo_mantenimiento" class="form-select @error('tipo_mantenimiento') is-invalid @enderror" required>
                                        <option value="Preventivo" {{ old('tipo_mantenimiento') == 'Preventivo' ? 'selected' : '' }}>Preventivo</option>
                                        <option value="Correctivo" {{ old('tipo_mantenimiento') == 'Correctivo' ? 'selected' : '' }}>Correctivo</option>
                                    </select>
                                    @error('tipo_mantenimiento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Fecha del Mantenimiento <span class="text-danger">*</span></label>
                                    <input type="date" name="fecha_mantenimiento" class="form-control @error('fecha_mantenimiento') is-invalid @enderror" 
                                           value="{{ old('fecha_mantenimiento', date('Y-m-d')) }}" required>
                                    @error('fecha_mantenimiento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trabajos Realizados <span class="text-danger">*</span></label>
                            <textarea name="descripcion_mantenimiento" class="form-control @error('descripcion_mantenimiento') is-invalid @enderror" rows="4" required 
                                      placeholder="Ingrese cada trabajo realizado en una línea nueva...">{{ old('descripcion_mantenimiento') }}</textarea>
                            <small class="text-muted"><i class="bi bi-info-circle"></i> Presione Enter para agregar múltiples trabajos</small>
                            @error('descripcion_mantenimiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observaciones Específicas del Mantenimiento</label>
                            <textarea name="observacion_mantenimiento" class="form-control @error('observacion_mantenimiento') is-invalid @enderror" rows="2" 
                                      placeholder="Observaciones de las tareas realizadas en este equipo...">{{ old('observacion_mantenimiento') }}</textarea>
                            @error('observacion_mantenimiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Fecha de Fallas (Opcional)</label>
                                    <input type="date" name="fecha_fallas" class="form-control @error('fecha_fallas') is-invalid @enderror" 
                                           value="{{ old('fecha_fallas') }}">
                                    @error('fecha_fallas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Fallas Detectadas</label>
                                    <textarea name="fallas_detectadas" class="form-control @error('fallas_detectadas') is-invalid @enderror" rows="2" 
                                              placeholder="Describa las fallas encontradas...">{{ old('fallas_detectadas') }}</textarea>
                                    @error('fallas_detectadas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Solución Aplicada</label>
                                    <textarea name="fallas_solucion" class="form-control @error('fallas_solucion') is-invalid @enderror" rows="2" 
                                              placeholder="Describa las soluciones aplicadas...">{{ old('fallas_solucion') }}</textarea>
                                    @error('fallas_solucion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observación General y Recomendaciones</label>
                            <textarea name="observacion_general" class="form-control @error('observacion_general') is-invalid @enderror" rows="2" 
                                      placeholder="Recomendaciones para el usuario u otras notas de carácter general...">{{ old('observacion_general', $ultimoMantenimiento->observacion_general ?? '') }}</textarea>
                            @error('observacion_general')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <a href="{{ route('admin.contabilletes.show', $contabillete->id) }}" class="btn btn-secondary">
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
