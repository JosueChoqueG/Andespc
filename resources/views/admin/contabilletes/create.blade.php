@extends('layouts.app')

@section('title', 'Nueva Contadora de Billetes')
@section('header', 'Registrar Contadora de Billetes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-plus-circle"></i> Datos de la Contadora de Billetes
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.contabilletes.index') }}" class="btn btn-default btn-sm">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.contabilletes.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <h5><i class="icon fas fa-ban"></i> ¡Error!</h5>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Oficina *</label>
                                    <select name="oficina_id" class="form-control select2 @error('oficina_id') is-invalid @enderror" required>
                                        <option value="">Seleccione una oficina</option>
                                        @foreach($oficinas as $oficina)
                                            <option value="{{ $oficina->id }}" {{ old('oficina_id') == $oficina->id ? 'selected' : '' }}>
                                                {{ $oficina->nombre_oficina }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('oficina_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Responsable</label>
                                    <select name="responsable_id" class="form-control select2 @error('responsable_id') is-invalid @enderror">
                                        <option value="">Seleccionar responsable</option>
                                        @foreach($responsables as $responsable)
                                            <option value="{{ $responsable->id }}" {{ old('responsable_id') == $responsable->id ? 'selected' : '' }}>
                                                {{ $responsable->nombre_responsable }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('responsable_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Tipo de Contadora *</label>
                                    <input type="text" name="tipo_contabilletes" class="form-control @error('tipo_contabilletes') is-invalid @enderror" 
                                        value="{{ old('tipo_contabilletes', 'Contadora de billetes') }}" placeholder="ej: Contadora de billetes" required>
                                    @error('tipo_contabilletes')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Marca *</label>
                                    <input type="text" name="marca_contabilletes" class="form-control @error('marca_contabilletes') is-invalid @enderror" 
                                        value="{{ old('marca_contabilletes', 'Epson') }}" placeholder="ej: Epson, AccuBanker, Billcon" required>
                                    @error('marca_contabilletes')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Modelo *</label>
                                    <input type="text" name="modelo_contabilletes" class="form-control @error('modelo_contabilletes') is-invalid @enderror" 
                                           value="{{ old('modelo_contabilletes') }}" placeholder="ej: AB4200, MC-100" required>
                                    @error('modelo_contabilletes')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Número de Serie *</label>
                                    <input type="text" name="serie_contabilletes" class="form-control @error('serie_contabilletes') is-invalid @enderror" 
                                           value="{{ old('serie_contabilletes') }}" placeholder="Número de serie único" required>
                                    @error('serie_contabilletes')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Velocidad de Conteo</label>
                                    <input type="text" name="velocidad_contabilletes" class="form-control @error('velocidad_contabilletes') is-invalid @enderror" 
                                           value="{{ old('velocidad_contabilletes') }}" placeholder="ej: 1000 billetes/min">
                                    @error('velocidad_contabilletes')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Tipo de Detección</label>
                                    <input type="text" name="tipo_deteccion" class="form-control @error('tipo_deteccion') is-invalid @enderror" 
                                           value="{{ old('tipo_deteccion') }}" placeholder="ej: UV, MG, IR, CIS">
                                    @error('tipo_deteccion')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Pantalla</label>
                                    <input type="text" name="pantalla_contabilletes" class="form-control @error('pantalla_contabilletes') is-invalid @enderror" 
                                           value="{{ old('pantalla_contabilletes') }}" placeholder="ej: LCD, LED, Externa">
                                    @error('pantalla_contabilletes')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Fecha de Adquisición</label>
                                    <input type="date" name="fecha_adquisicion" class="form-control @error('fecha_adquisicion') is-invalid @enderror" 
                                           value="{{ old('fecha_adquisicion') }}">
                                    @error('fecha_adquisicion')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Capacidad Tolva (Hoppers)</label>
                                    <input type="number" name="capacidad_tolva" class="form-control @error('capacidad_tolva') is-invalid @enderror" 
                                           value="{{ old('capacidad_tolva') }}" placeholder="ej: 300">
                                    @error('capacidad_tolva')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Capacidad Bandeja (Stacker)</label>
                                    <input type="number" name="capacidad_bandeja" class="form-control @error('capacidad_bandeja') is-invalid @enderror" 
                                           value="{{ old('capacidad_bandeja') }}" placeholder="ej: 200">
                                    @error('capacidad_bandeja')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Estado</label>
                                    <select name="estado_contabilletes" class="form-control @error('estado_contabilletes') is-invalid @enderror">
                                        <option value="OPTIMO" {{ old('estado_contabilletes') == 'OPTIMO' ? 'selected' : '' }}>Óptimo</option>
                                        <option value="BUENO" {{ old('estado_contabilletes') == 'BUENO' ? 'selected' : '' }}>Bueno</option>
                                        <option value="REGULAR" {{ old('estado_contabilletes') == 'REGULAR' ? 'selected' : '' }}>Regular</option>
                                        <option value="DEFICIENTE" {{ old('estado_contabilletes') == 'DEFICIENTE' ? 'selected' : '' }}>Deficiente</option>
                                        <option value="DE BAJA" {{ old('estado_contabilletes') == 'DE BAJA' ? 'selected' : '' }}>De Baja</option>
                                    </select>
                                    @error('estado_contabilletes')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="reset" class="btn btn-secondary">Limpiar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Contadora
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
@endsection
