@extends('layouts.app')

@section('title', 'Editar Agencia')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white"><h5>Editar Agencia</h5></div>
            <div class="card-body">
                <form action="{{ route('agencias.update', $agencia) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label for="codigo_agencia" class="form-label">Código *</label>
                        <input type="text" name="codigo_agencia" id="codigo_agencia" class="form-control" required value="{{ old('codigo_agencia', $agencia->codigo_agencia) }}">
                    </div>
                    <div class="mb-3">
                        <label for="nombre_agencia" class="form-label">Nombre *</label>
                        <input type="text" name="nombre_agencia" id="nombre_agencia" class="form-control" required value="{{ old('nombre_agencia', $agencia->nombre_agencia) }}">
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('agencias.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection