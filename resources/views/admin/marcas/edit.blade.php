@extends('layouts.app')

@section('title', 'Editar Marca')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5>Editar Marca</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('marcas.update', $marca) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nombre_marca" class="form-label">Nombre *</label>
                        <input type="text" name="nombre_marca" id="nombre_marca" class="form-control" required value="{{ old('nombre_marca', $marca->nombre_marca) }}">
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('marcas.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection