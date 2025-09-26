@extends('layouts.app')

@section('title', 'Crear Sistema Operativo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5>Nuevo Sistema Operativo</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('sistemas.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nombre_so" class="form-label">Nombre *</label>
                        <input type="text" name="nombre_so" id="nombre_so" class="form-control" required value="{{ old('nombre_so') }}">
                    </div>
                    <div class="mb-3">
                        <label for="edicion" class="form-label">Edición</label>
                        <input type="text" name="edicion" id="edicion" class="form-control" value="{{ old('edicion') }}">
                    </div>
                    <div class="mb-3">
                        <label for="version" class="form-label">Versión</label>
                        <input type="text" name="version" id="version" class="form-control" value="{{ old('version') }}">
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('sistemas.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection