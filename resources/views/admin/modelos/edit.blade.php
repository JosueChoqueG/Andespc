@extends('layouts.app')

@section('title', 'Editar Modelo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5>Editar Modelo</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('modelos.update', $modelo) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nombre_modelo" class="form-label">Nombre del Modelo *</label>
                        <input type="text" name="nombre_modelo" id="nombre_modelo"
                            class="form-control @error('nombre_modelo') is-invalid @enderror"
                            required
                            value="{{ old('nombre_modelo', $modelo->nombre_modelo) }}">
                        @error('nombre_modelo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="marca_id" class="form-label">Marca *</label>
                        <select name="marca_id" id="marca_id"
                            class="form-select @error('marca_id') is-invalid @enderror"
                            required>
                            <option value="">Seleccionar marca</option>
                            @foreach($marcas as $marca)
                                <option value="{{ $marca->id }}"
                                    {{ old('marca_id', $modelo->marca_id) == $marca->id ? 'selected' : '' }}>
                                    {{ $marca->nombre_marca }}
                                </option>
                            @endforeach
                        </select>
                        @error('marca_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('modelos.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
