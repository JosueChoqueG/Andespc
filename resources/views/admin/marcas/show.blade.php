@extends('layouts.app')

@section('title', 'Ver Marca')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5>Detalles de la Marca</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>ID</th><td>{{ $marca->id_marca }}</td></tr>
                    <tr><th>Nombre</th><td>{{ $marca->nombre_marca }}</td></tr>
                </table>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('marcas.edit', $marca) }}" class="btn btn-warning me-2">Editar</a>
                    <a href="{{ route('marcas.index') }}" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection