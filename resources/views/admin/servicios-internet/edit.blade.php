@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Cabecera --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.servicios-internet.index') }}"
           class="btn btn-outline-secondary btn-sm rounded-circle"
           title="Volver">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-bold">
                <i class="bi bi-pencil-square text-warning me-2"></i>Editar Servicio de Internet
            </h4>
            <small class="text-muted">
                Oficina: <strong>{{ $servicio->oficina->nombre_oficina ?? '—' }}</strong>
                &bull; Proveedor: <strong>{{ $servicio->nombre_proveedor }}</strong>
            </small>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Revise los siguientes campos:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.servicios-internet.update', $servicio) }}">
        @method('PUT')
        @php $cancelUrl = route('admin.servicios-internet.index'); @endphp
        @include('admin.servicios-internet.form')
    </form>

</div>
@endsection
