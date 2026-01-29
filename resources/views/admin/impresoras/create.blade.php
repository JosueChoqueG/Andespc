@extends('layouts.app')

@section('content')
<h4>Nueva Impresora</h4>

<form action="{{ route('admin.impresoras.store') }}" method="POST">
    @csrf
    @include('admin.impresoras.form')
    <button class="btn btn-primary">Guardar</button>
    <a href="{{ route('admin.impresoras.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
