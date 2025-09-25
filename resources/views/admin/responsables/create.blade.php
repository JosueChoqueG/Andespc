@extends('layouts.app')

@section('title', 'Crear Responsable')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5>Nuevo Responsable</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('responsables.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nombre_responsable" class="form-label">Nombre *</label>
                        <input type="text" name="nombre_responsable" id="nombre_responsable" class="form-control" required value="{{ old('nombre_responsable') }}">
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('responsables.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection