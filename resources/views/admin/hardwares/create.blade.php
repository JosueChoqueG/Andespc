@extends('layouts.app')

@section('title', 'Crear Configuración de Hardware')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5><i class="bi bi-cpu"></i> Nueva Configuración de Hardware</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('hardwares.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="procesador" class="form-label">Procesador</label>
                            <input type="text" name="procesador" id="procesador" class="form-control" placeholder="Intel Core i7-10700K, AMD Ryzen 5 5600X..." value="{{ old('procesador') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="ram_gb" class="form-label">RAM (GB) *</label>
                            <input type="number" name="ram_gb" id="ram_gb" class="form-control @error('ram_gb') is-invalid @enderror" required value="{{ old('ram_gb') }}">
                            @error('ram_gb')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="almacenamiento_gb" class="form-label">Almacenamiento (GB) *</label>
                            <input type="number" name="almacenamiento_gb" id="almacenamiento_gb" class="form-control @error('almacenamiento_gb') is-invalid @enderror" required value="{{ old('almacenamiento_gb') }}">
                            @error('almacenamiento_gb')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tipo_almacenamiento" class="form-label">Tipo de Almacenamiento *</label>
                            <select name="tipo_almacenamiento" id="tipo_almacenamiento" class="form-select @error('tipo_almacenamiento') is-invalid @enderror" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="HDD" {{ old('tipo_almacenamiento') == 'HDD' ? 'selected' : '' }}>HDD</option>
                                <option value="SSD" {{ old('tipo_almacenamiento') == 'SSD' ? 'selected' : '' }}>SSD</option>
                                <option value="NVMe" {{ old('tipo_almacenamiento') == 'NVMe' ? 'selected' : '' }}>NVMe</option>
                            </select>
                            @error('tipo_almacenamiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('hardwares.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-success">Guardar Hardware</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection