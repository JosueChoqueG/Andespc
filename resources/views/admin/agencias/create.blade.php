@extends('layouts.app')

@section('title', 'Crear Agencia')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white"><h5>Crear Agencia</h5></div>
            <div class="card-body">
                <form action="{{ route('agencias.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="codigo_agencia" class="form-label">Código *</label>
                        <input type="text" name="codigo_agencia" id="codigo_agencia" class="form-control" required value="{{ old('codigo_agencia') }}">
                    </div>
                    <div class="mb-3">
                        <label for="nombre_agencia" class="form-label">Nombre *</label>
                        <input type="text" name="nombre_agencia" id="nombre_agencia" class="form-control" required value="{{ old('nombre_agencia') }}">
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('agencias.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection