@extends('layouts.app')

@section('title', 'Ver Responsable')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5>Detalles del Responsable</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>ID</th><td>{{ $responsable->id_responsable }}</td></tr>
                    <tr><th>Nombre</th><td>{{ $responsable->nombre_responsable }}</td></tr>
                </table>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('responsables.edit', $responsable) }}" class="btn btn-warning me-2">Editar</a>
                    <a href="{{ route('responsables.index') }}" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection