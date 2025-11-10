@extends('layouts.app')

@section('title', 'Editar Configuración de Hardware')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5><i class="bi bi-pencil-square"></i> Editar Configuración de Hardware</h5>
            </div>
            <div class="card-body">

                {{-- Mostrar errores --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('hardwares.update', $hardware) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="procesador" class="form-label">Procesador</label>
                            <input type="text" name="procesador" id="procesador" class="form-control"
                                value="{{ old('procesador', $hardware->procesador) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="ram_gb" class="form-label">RAM (GB) *</label>
                            <input type="number" name="ram_gb" id="ram_gb" class="form-control" required
                                value="{{ old('ram_gb', $hardware->ram_gb) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="almacenamiento_gb" class="form-label">Almacenamiento (GB) *</label>
                            <input type="number" name="almacenamiento_gb" id="almacenamiento_gb" class="form-control" required
                                value="{{ old('almacenamiento_gb', $hardware->almacenamiento_gb) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="tipo_almacenamiento" class="form-label">Tipo de Almacenamiento *</label>
                            <select name="tipo_almacenamiento" id="tipo_almacenamiento" class="form-select" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="HDD" {{ old('tipo_almacenamiento', $hardware->tipo_almacenamiento) == 'HDD' ? 'selected' : '' }}>HDD</option>
                                <option value="SSD" {{ old('tipo_almacenamiento', $hardware->tipo_almacenamiento) == 'SSD' ? 'selected' : '' }}>SSD</option>
                                <option value="NVMe" {{ old('tipo_almacenamiento', $hardware->tipo_almacenamiento) == 'NVMe' ? 'selected' : '' }}>NVMe</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('hardwares.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-warning">Actualizar Hardware</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
