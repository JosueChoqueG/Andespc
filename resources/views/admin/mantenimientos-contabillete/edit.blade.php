@extends('layouts.app')

@section('title', 'Editar Mantenimiento de Contadora')
@section('header', 'Editar Mantenimiento de Contadora de Billetes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil"></i> 
                        Editar Mantenimiento de Contadora: {{ $mantenimiento->contabillete->serie_contabilletes }}
                    </h5>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.mantenimientos-contabillete.update', $mantenimiento->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Contadora de Billetes *</label>
                                    <select name="contabillete_id" class="form-select select2 @error('contabillete_id') is-invalid @enderror" required>
                                        @foreach($contabilletes as $cont)
                                            <option value="{{ $cont->id }}" {{ old('contabillete_id', $mantenimiento->contabillete_id) == $cont->id ? 'selected' : '' }}>
                                                {{ $cont->marca_contabilletes }} {{ $cont->modelo_contabilletes }} (Serie: {{ $cont->serie_contabilletes }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('contabillete_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Mantenimiento *</label>
                                    <select name="tipo_mantenimiento" class="form-select @error('tipo_mantenimiento') is-invalid @enderror" required>
                                        <option value="Preventivo" {{ old('tipo_mantenimiento', $mantenimiento->tipo_mantenimiento) == 'Preventivo' ? 'selected' : '' }}>Preventivo</option>
                                        <option value="Correctivo" {{ old('tipo_mantenimiento', $mantenimiento->tipo_mantenimiento) == 'Correctivo' ? 'selected' : '' }}>Correctivo</option>
                                    </select>
                                    @error('tipo_mantenimiento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Fecha del Mantenimiento *</label>
                                    <input type="date" name="fecha_mantenimiento" class="form-control @error('fecha_mantenimiento') is-invalid @enderror" 
                                           value="{{ old('fecha_mantenimiento', $mantenimiento->fecha_mantenimiento ? $mantenimiento->fecha_mantenimiento->format('Y-m-d') : date('Y-m-d')) }}" required>
                                    @error('fecha_mantenimiento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trabajos Realizados *</label>
                            <textarea name="descripcion_mantenimiento" class="form-control @error('descripcion_mantenimiento') is-invalid @enderror" rows="4" required>{{ old('descripcion_mantenimiento', $mantenimiento->descripcion_mantenimiento) }}</textarea>
                            <small class="text-muted"><i class="bi bi-info-circle"></i> Presione Enter para agregar múltiples trabajos</small>
                            @error('descripcion_mantenimiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observaciones Específicas del Mantenimiento</label>
                            <textarea name="observacion_mantenimiento" class="form-control @error('observacion_mantenimiento') is-invalid @enderror" rows="2">{{ old('observacion_mantenimiento', $mantenimiento->observacion_mantenimiento) }}</textarea>
                            @error('observacion_mantenimiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Fecha de Fallas (Opcional)</label>
                                    <input type="date" name="fecha_fallas" class="form-control @error('fecha_fallas') is-invalid @enderror" 
                                           value="{{ old('fecha_fallas', $mantenimiento->fecha_fallas ? $mantenimiento->fecha_fallas->format('Y-m-d') : '') }}">
                                    @error('fecha_fallas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Fallas Detectadas</label>
                                    <textarea name="fallas_detectadas" class="form-control @error('fallas_detectadas') is-invalid @enderror" rows="2">{{ old('fallas_detectadas', $mantenimiento->fallas_detectadas) }}</textarea>
                                    @error('fallas_detectadas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Solución Aplicada</label>
                                    <textarea name="fallas_solucion" class="form-control @error('fallas_solucion') is-invalid @enderror" rows="2">{{ old('fallas_solucion', $mantenimiento->fallas_solucion) }}</textarea>
                                    @error('fallas_solucion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observación General y Recomendaciones</label>
                            <textarea name="observacion_general" class="form-control @error('observacion_general') is-invalid @enderror" rows="2">{{ old('observacion_general', $mantenimiento->observacion_general) }}</textarea>
                            @error('observacion_general')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.contabilletes.show', $mantenimiento->contabillete_id) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.jQuery && $.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
    }
});
</script>
@endpush
