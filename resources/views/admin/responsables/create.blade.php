@extends('layouts.app')

@section('title', 'Nuevo Responsable')

@section('content')
<div class="container-fluid py-2">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1 text-dark fw-bold">
                <i class="bi bi-person-plus-fill text-primary me-2"></i>Registrar Responsable
            </h4>
            <span class="text-muted" style="font-size: 0.9rem;">
                Añadir nuevo colaborador para asignación de equipos, impresoras y periféricos
            </span>
        </div>
        <div>
            <a href="{{ route('responsables.index') }}" class="btn btn-outline-secondary shadow-sm px-3">
                <i class="bi bi-arrow-left me-1"></i> Volver al listado
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <strong>Revise los siguientes campos:</strong>
            <ul class="mb-0 ps-3 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-light border-bottom py-2.5 px-3">
                    <span class="fw-bold text-dark"><i class="bi bi-card-heading me-1 text-primary"></i> Información del Responsable</span>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('responsables.store') }}" method="POST">
                        @csrf

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label for="dni" class="form-label fw-semibold text-muted">DNI</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-card-heading"></i></span>
                                    <input type="text" name="dni" id="dni" class="form-control font-monospace" 
                                           placeholder="Ej: 47070551" maxlength="20" value="{{ old('dni') }}">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label for="nombre_responsable" class="form-label fw-semibold text-muted">Nombre y Apellidos <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                    <input type="text" name="nombre_responsable" id="nombre_responsable" class="form-control" 
                                           placeholder="Ej: JUAN CARLOS QUISPE FLORES" required value="{{ old('nombre_responsable') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="cargo" class="form-label fw-semibold text-muted">Cargo / Puesto</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-briefcase"></i></span>
                                    <input type="text" name="cargo" id="cargo" class="form-control" list="cargosList"
                                           placeholder="Ej: Administrador, Asesor financiero..." value="{{ old('cargo') }}">
                                    <datalist id="cargosList">
                                        @foreach($cargosComunes as $c)
                                            <option value="{{ $c }}">
                                        @endforeach
                                    </datalist>
                                </div>
                                <span class="text-muted small">Puede seleccionar de la lista o escribir un cargo nuevo.</span>
                            </div>

                            <div class="col-md-6">
                                <label for="oficina_id" class="form-label fw-semibold text-muted">Oficina Asignada</label>
                                <select name="oficina_id" id="oficina_id" class="form-select select2">
                                    <option value="">— Seleccionar Oficina —</option>
                                    @foreach($oficinas as $ofi)
                                        <option value="{{ $ofi->id }}" {{ old('oficina_id') == $ofi->id ? 'selected' : '' }}>
                                            {{ $ofi->nombre_oficina }} ({{ $ofi->agencia->nombre_agencia ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4 pt-2 border-top">
                            <a href="{{ route('responsables.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary px-4 fw-medium shadow-sm">
                                <i class="bi bi-floppy2-fill me-1"></i> Guardar Responsable
                            </button>
                        </div>
                    </form>
                </div>
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
            width: '100%',
            allowClear: true
        });
    }
});
</script>
@endpush
@endsection
