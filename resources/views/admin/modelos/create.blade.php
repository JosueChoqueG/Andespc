@extends('layouts.app')

@section('title', 'Crear Modelo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5>Nuevo Modelo</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('modelos.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nombre_modelo" class="form-label">Nombre del Modelo *</label>
                        <input type="text" name="nombre_modelo" id="nombre_modelo" class="form-control" required value="{{ old('nombre_modelo') }}">
                    </div>
                    <div class="mb-3">
                        <label for="id_marca" class="form-label">Marca *</label>
                        <select name="id_marca" id="id_marca" class="form-select" required>
                            <option value="">Seleccionar marca</option>
                            @foreach($marcas as $marca)
                                <option value="{{ $marca->id_marca }}" {{ old('id_marca') == $marca->id_marca ? 'selected' : '' }}>
                                    {{ $marca->nombre_marca }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('modelos.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection