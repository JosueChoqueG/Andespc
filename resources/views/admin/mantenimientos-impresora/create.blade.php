@extends('layouts.admin')

@section('title', 'Registrar Mantenimiento')
@section('header', 'Nuevo Mantenimiento de Impresora')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tools"></i> Registrar Mantenimiento
                    </h3>
                    <div class="card-tools">
                        @if($impresoraId)
                            <a href="{{ route('admin.impresoras.show', $impresoraId) }}" class="btn btn-default btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver a Impresora
                            </a>
                        @else
                            <a href="{{ route('admin.mantenimientos-impresora.index') }}" class="btn btn-default btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        @endif
                    </div>
                </div>
                <form action="{{ route('admin.mantenimientos-impresora.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Impresora *</label>
                                    <select name="impresora_id" class="form-control @error('impresora_id') is-invalid @enderror" required>
                                        <option value="">Seleccione una impresora</option>
                                        @foreach($impresoras as $impresora)
                                            <option value="{{ $impresora->id }}" 
                                                {{ ($impresoraId ?? old('impresora_id')) == $impresora->id ? 'selected' : '' }}>
                                                {{ $impresora->marca_impresora }} {{ $impresora->modelo_impresora }}
                                                ({{ $impresora->serie_impresora }}) - {{ $impresora->estado_impresora }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('impresora_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha Mantenimiento *</label>
                                    <input type="date" name="fecha_mantenimiento" 
                                           class="form-control @error('fecha_mantenimiento') is-invalid @enderror" 
                                           value="{{ old('fecha_mantenimiento', date('Y-m-d')) }}" required>
                                    @error('fecha_mantenimiento')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tipo Mantenimiento *</label>
                                    <select name="tipo_mantenimiento" class="form-control @error('tipo_mantenimiento') is-invalid @enderror" required>
                                        <option value="Preventivo" {{ old('tipo_mantenimiento') == 'Preventivo' ? 'selected' : '' }}>Preventivo</option>
                                        <option value="Correctivo" {{ old('tipo_mantenimiento') == 'Correctivo' ? 'selected' : '' }}>Correctivo</option>
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
                                    <label>Fecha de Fallas (si aplica)</label>
                                    <input type="date" name="fecha_fallas" 
                                           class="form-control @error('fecha_fallas') is-invalid @enderror" 
                                           value="{{ old('fecha_fallas') }}">
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
                                      rows="4" required>{{ old('descripcion_mantenimiento') }}</textarea>
                            <small class="form-text text-muted">
                                Describa detalladamente los trabajos realizados, cada paso en una línea separada.
                            </small>
                            @error('descripcion_mantenimiento')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Observaciones del Mantenimiento</label>
                            <textarea name="observacion_mantenimiento" 
                                      class="form-control @error('observacion_mantenimiento') is-invalid @enderror" 
                                      rows="3">{{ old('observacion_mantenimiento') }}</textarea>
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
                                              rows="3">{{ old('fallas_detectadas') }}</textarea>
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
                                              rows="3">{{ old('fallas_solucion') }}</textarea>
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
                                      rows="3">{{ old('observacion_general') }}</textarea>
                            @error('observacion_general')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="reset" class="btn btn-default">Limpiar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Mantenimiento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection