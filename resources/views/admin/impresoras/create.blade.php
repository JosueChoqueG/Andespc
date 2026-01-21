@extends('layouts.app')

@section('content')
<h4>Nueva Impresora</h4>

<form action="{{ route('admin.impresoras.store') }}" method="POST">
    @csrf
    @include('admin.impresoras.form')
    <button class="btn btn-primary">Guardar</button>
    <a href="{{ route('admin.impresoras.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
<script>
    $(document).ready(function() {
        // Para el select de oficinas
        $('#oficina_id').select2({
            theme: "bootstrap-5",
            placeholder: "Seleccionar oficina",
            width: '100%'
        });
    });
</script>
@endsection
