@extends('layouts.admin')

@section('title', 'Editar Mantenimiento')
@section('header', 'Editar Mantenimiento de Impresora')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> Editar Mantenimiento #{{ $mantenimiento->id }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.impresoras.show', $mantenimiento->impresora_id) }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver a Impresora
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.mantenimientos-impresora.update', $mantenimiento->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            Impresora: <strong>{{ $mantenimiento->impresora->marca_impresora }} {{ $mantenimiento->impresora->modelo_impresora }}</strong>
                            (Serie: {{ $mantenimiento->impresora->serie_impresora }})
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha Mantenimiento *</label>
                                    <input type="date" name="fecha_mantenimiento" 
                                           class="form-control @error('fecha_mantenimiento') is-invalid @enderror" 
                                           value="{{ old('fecha_mantenimiento', $mantenimiento->fecha_mantenimiento) }}" required>
                                    @error('fecha_mantenimiento')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tipo Mantenimiento *</label>
                                    <select name="tipo_mantenimiento" class="form-control @error('tipo_mantenimiento') is-invalid @enderror" required>
                                        <option value="Preventivo" {{ old('tipo_mantenimiento', $mantenimiento->tipo_mantenimiento) == 'Preventivo' ? 'selected' : '' }}>Preventivo</option>
                                        <option value="Correctivo" {{ old('tipo_mantenimiento', $mantenimiento->tipo_mantenimiento) == 'Correctivo' ? 'selected' : '' }}>Correctivo</option>
                                    </select>
                                    @error('tipo_mantenimiento')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de Fallas</label>
                                    <input type="date" name="fecha_fallas" 
                                           class="form-control @error('fecha_fallas') is-invalid @enderror" 
                                           value="{{ old('fecha_fallas', $mantenimiento->fecha_fallas) }}">
                                    @error('fecha_fallas')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Descripción del Mantenimiento *</label>
                            <textarea name="descripcion_mantenimiento" 
                                      class="form-control @error('descripcion_mantenimiento') is-invalid @enderror" 
                                      rows="4" required>{{ old('descripcion_mantenimiento', $mantenimiento->descripcion_mantenimiento) }}</textarea>
                            @error('descripcion_mantenimiento')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Observaciones del Mantenimiento</label>
                            <textarea name="observacion_mantenimiento" 
                                      class="form-control @error('observacion_mantenimiento') is-invalid @enderror" 
                                      rows="3">{{ old('observacion_mantenimiento', $mantenimiento->observacion_mantenimiento) }}</textarea>
                            @error('observacion_mantenimiento')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fallas Detectadas</label>
                                    <textarea name="fallas_detectadas" 
                                              class="form-control @error('fallas_detectadas') is-invalid @enderror" 
                                              rows="3">{{ old('fallas_detectadas', $mantenimiento->fallas_detectadas) }}</textarea>
                                    @error('fallas_detectadas')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Solución Aplicada</label>
                                    <textarea name="fallas_solucion" 
                                              class="form-control @error('fallas_solucion') is-invalid @enderror" 
                                              rows="3">{{ old('fallas_solucion', $mantenimiento->fallas_solucion) }}</textarea>
                                    @error('fallas_solucion')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Observaciones Generales</label>
                            <textarea name="observacion_general" 
                                      class="form-control @error('observacion_general') is-invalid @enderror" 
                                      rows="3">{{ old('observacion_general', $mantenimiento->observacion_general) }}</textarea>
                            @error('observacion_general')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection