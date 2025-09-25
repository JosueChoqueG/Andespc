@extends('layouts.app')

@section('title', 'Crear Oficina')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5><i class="bi bi-building"></i> Nueva Oficina</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('oficinas.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nombre_oficina" class="form-label">Nombre de la Oficina *</label>
                        <input type="text" name="nombre_oficina" id="nombre_oficina" class="form-control @error('nombre_oficina') is-invalid @enderror" required value="{{ old('nombre_oficina') }}">
                        @error('nombre_oficina')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="id_agencia" class="form-label">Agencia *</label>
                        <select name="id_agencia" id="id_agencia" class="form-select @error('id_agencia') is-invalid @enderror" required>
                            <option value="">Seleccionar agencia</option>
                            @foreach($agencias as $agencia)
                                <option value="{{ $agencia->id_agencia }}" {{ old('id_agencia') == $agencia->id_agencia ? 'selected' : '' }}>
                                    {{ $agencia->nombre_agencia }} ({{ $agencia->codigo_agencia }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_agencia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="ubicacion_equipo" class="form-label">Ubicación del Equipo</label>
                        <textarea name="ubicacion_equipo" id="ubicacion_equipo" class="form-control" rows="2">{{ old('ubicacion_equipo') }}</textarea>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('oficinas.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-success">Guardar Oficina</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection