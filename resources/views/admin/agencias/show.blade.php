@extends('layouts.app')

@section('title', 'Ver Agencia')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white"><h5>Detalles de la Agencia</h5></div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>Código</th><td>{{ $agencia->codigo_agencia }}</td></tr>
                    <tr><th>Nombre</th><td>{{ $agencia->nombre_agencia }}</td></tr>
                </table>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('agencias.edit', $agencias) }}" class="btn btn-warning me-2">Editar</a>
                    <a href="{{ route('agencias.index') }}" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection