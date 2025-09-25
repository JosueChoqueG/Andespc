@extends('layouts.app')

@section('title', 'Ver Tipo de Equipo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5>Detalles del Tipo de Equipo</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>ID</th><td>{{ $tipo->id_tipo }}</td></tr>
                    <tr><th>Nombre</th><td>{{ $tipo->nombre_tipo }}</td></tr>
                </table>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('tipos.edit', $tipo) }}" class="btn btn-warning me-2">Editar</a>
                    <a href="{{ route('tipos.index') }}" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection