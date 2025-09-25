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
                <form action="{{ route('tipos.update', $tipo) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nombre_tipo" class="form-label">Nombre *</label>
                        <input type="text" name="nombre_tipo" id="nombre_tipo" class="form-control" required value="{{ old('nombre_tipo', $tipo->nombre_tipo) }}">
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('tipos.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection