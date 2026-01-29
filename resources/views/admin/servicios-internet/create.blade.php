@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3>Nuevo Servicio de Internet</h3>

    <form method="POST" action="{{ route('admin.servicios-internet.store') }}">
        @include('admin.servicios-internet.form')
    </form>
</div>
@endsection
