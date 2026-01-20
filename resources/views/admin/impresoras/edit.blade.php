@extends('layouts.app')

@section('content')
<h4>Editar Impresora</h4>

<form action="{{ route('admin.impresoras.update',$impresora) }}" method="POST">
    @csrf
    @method('PUT')
    @include('admin.impresoras.form')
    <button class="btn btn-warning">Actualizar</button>
    <a href="{{ route('admin.impresoras.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
