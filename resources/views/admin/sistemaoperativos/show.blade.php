@extends('layouts.app')

@section('title', 'Ver Sistema Operativo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5>Detalles del Sistema Operativo</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>Nombre</th><td>{{ $sistemaoperativos->nombre_so }}</td></tr>
                    <tr><th>Edición</th><td>{{ $sistemaoperativos->edicion ?? 'N/A' }}</td></tr>
                    <tr><th>Versión</th><td>{{ $sistemaoperativos->version ?? 'N/A' }}</td></tr>
                </table>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('sistemaoperativos.edit', $sistema) }}" class="btn btn-warning me-2">Editar</a>
                    <a href="{{ route('sistemaoperativos.index') }}" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection