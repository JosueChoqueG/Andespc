@extends('layouts.app')

@section('title', 'Ver Modelo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5>Detalles del Modelo</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>Modelo</th><td>{{ $modelo->nombre_modelo }}</td></tr>
                    <tr><th>Marca</th><td>{{ $modelo->marca->nombre_marca ?? 'N/A' }}</td></tr>
                </table>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('modelos.edit', $modelo) }}" class="btn btn-warning me-2">Editar</a>
                    <a href="{{ route('modelos.index') }}" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection