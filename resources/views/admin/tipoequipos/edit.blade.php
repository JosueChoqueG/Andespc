@extends('layouts.app')

@section('title', 'Editar Tipo de Equipo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5>Editar Tipo de Equipo</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('tipoequipos.update', $tipoequipo) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nombre_tipo" class="form-label">Nombre *</label>
                        <input type="text" name="nombre_tipo" id="nombre_tipo" class="form-control @error('nombre_tipo') is-invalid @enderror"
                               required value="{{ old('nombre_tipo', $tipoequipo->nombre_tipo) }}">
                        @error('nombre_tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('tipoequipos.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection