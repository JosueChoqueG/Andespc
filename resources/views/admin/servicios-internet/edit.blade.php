@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3>Editar Servicio de Internet</h3>

    <form method="POST"
        action="{{ route('admin.servicios-internet.update', $servicio) }}">
        @method('PUT')
        @include('admin.servicios-internet.form')
    </form>
</div>
@endsection
