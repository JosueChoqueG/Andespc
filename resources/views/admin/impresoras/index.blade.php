@extends('layouts.app')

@section('content')
<a href="{{ route('admin.impresoras.create') }}" class="btn btn-primary mb-3">Nueva Impresora</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Agencia</th>
            <th>Oficina</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Serie</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($impresoras as $i)
        <tr>
            <td>{{ $i->oficina->agencia->nombre_agencia }}</td>
            <td>{{ $i->oficina->nombre_oficina }}</td>
            <td>{{ $i->marca }}</td>
            <td>{{ $i->modelo }}</td>
            <td>{{ $i->serie }}</td>
            <td>{{ $i->estado_equipo }}</td>
            <td>
                <a href="{{ route('admin.impresoras.show',$i) }}" class="btn btn-info btn-sm">Ver</a>
                <a href="{{ route('admin.impresoras.edit',$i) }}" class="btn btn-warning btn-sm">Editar</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $impresoras->links() }}
@endsection
