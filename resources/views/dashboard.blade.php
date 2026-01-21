@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-8">
        <h2 class="mb-0">Panel de Administración</h2>
        <p class="text-muted mb-0">
            Bienvenido al sistema de gestión de equipos tecnológicos de la Coopac Los Andes.
        </p>
    </div>
</div>

<div class="row">
    <div class="col-md-2 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-pc-display"></i> Equipos</h5>
                <p class="card-text">{{ \App\Models\Equipo::count() }} dispositivos registrados</p>
                <a href="{{ route('equipos.index') }}" class="btn btn-light btn-sm">Ver todos</a>
            </div>
        </div>
    </div>

    <div class="col-md-2 mb-3">
    <div class="card text-white bg-warning">
        <div class="card-body">
            <h5 class="card-title">
                <i class="bi bi-printer"></i> Impresoras
            </h5>
            <p class="card-text">
                {{ \App\Models\Impresora::count() }} impresoras registradas
            </p>
            <a href="{{ route('admin.impresoras.index') }}" class="btn btn-light btn-sm">
                Ver impresoras
            </a>
        </div>
    </div>
</div>
</div>
@endsection
