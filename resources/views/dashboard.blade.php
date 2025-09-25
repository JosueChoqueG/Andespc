@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <h2>Panel de Administración</h2>
        <p>Bienvenido al sistema de gestión de equipos de Andes PC.</p>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-pc-display"></i> Equipos</h5>
                <p class="card-text">{{ \App\Models\Equipo::count() }} dispositivos registrados</p>
                <a href="{{ route('equipos.index') }}" class="btn btn-light btn-sm">Ver todos</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-building"></i> Oficinas</h5>
                <p class="card-text">{{ \App\Models\Oficina::count() }} oficinas activas</p>
                <a href="{{ route('oficinas.index') }}" class="btn btn-light btn-sm">Gestionar</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-people"></i> Responsables</h5>
                <p class="card-text">{{ \App\Models\Responsable::count() }} responsables</p>
                <a href="{{ route('responsables.index') }}" class="btn btn-light btn-sm">Ver lista</a>
            </div>
        </div>
    </div>
</div>
@endsection
