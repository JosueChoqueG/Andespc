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
                        <input type="text" name="nombre_modelo" id="nombre_modelo" class="form-control" required value="{{ old('nombre_modelo', $modelo->nombre_modelo) }}">
                    </div>
                    <div class="mb-3">
                        <label for="id_marca" class="form-label">Marca *</label>
                        <select name="id_marca" id="id_marca" class="form-select" required>
                            <option value="">Seleccionar marca</option>
                            @foreach($marcas as $marca)
                                <option value="{{ $marca->id_marca }}" {{ old('id_marca', $modelo->id_marca) == $marca->id_marca ? 'selected' : '' }}>
                                    {{ $marca->nombre_marca }}
                                </option>
                            @endforeach
                        </select>
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