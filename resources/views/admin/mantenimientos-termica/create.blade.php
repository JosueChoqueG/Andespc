@extends('layouts.app')

@section('title', 'Registrar Mantenimiento Térmico')
@section('header', 'Nuevo Mantenimiento de Impresora Térmica')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-tools"></i> 
                        Mantenimiento de Impresora Térmica: {{ $termica->serie_termica }}
                    </h5>
                </div>
                
                <div class="card-body">
                    {{-- Datos de la impresora térmica --}}
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle"></i> Información de la Impresora Térmica</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Marca/Modelo:</strong> {{ $termica->marca_termica }} {{ $termica->modelo_termica }}
                            </div>
                            <div class="col-md-3">
                                <strong>Serie:</strong> {{ $termica->serie_termica ?? 'N/A' }}
                            </div>
                            <div class="col-md-3">
                                <strong>IP:</strong> {{ $termica->direccion_ip ?? 'N/A' }}
                            </div>
                            <div class="col-md-3">
                                <strong>Oficina:</strong> {{ $termica->oficina->nombre_oficina ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.mantenimientos-termica.store', $termica) }}" method="POST">
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
                                    <label class="form-label">Fecha <span class="text-danger">*</span></label>
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
                                      placeholder="Ingrese cada trabajo en una línea nueva...">{{ old('descripcion_mantenimiento') }}</textarea>
                            <small class="text-muted"><i class="bi bi-info-circle"></i> Presione Enter para agregar múltiples trabajos</small>
                            @error('descripcion_mantenimiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Fallas Encontradas</label>
                                    <textarea name="fallas_detectadas" class="form-control @error('fallas_detectadas') is-invalid @enderror" rows="3" 
                                              placeholder="Describa las fallas encontradas...">{{ old('fallas_detectadas') }}</textarea>
                                    @error('fallas_detectadas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Soluciones Aplicadas</label>
                                    <textarea name="fallas_solucion" class="form-control @error('fallas_solucion') is-invalid @enderror" rows="3" 
                                              placeholder="Describa las soluciones aplicadas...">{{ old('fallas_solucion') }}</textarea>
                                    @error('fallas_solucion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observaciones y Recomendaciones</label>
                            <textarea name="observacion_general" class="form-control @error('observacion_general') is-invalid @enderror" rows="3" 
                                      placeholder="Observaciones y recomendaciones generales...">{{ old('observacion_general', $ultimoMantenimiento->observacion_general ?? '') }}</textarea>
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
                            <a href="{{ route('admin.termicas.show', $termica->id) }}" class="btn btn-secondary">
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
