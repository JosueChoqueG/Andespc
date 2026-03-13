@extends('layouts.app')

@section('title', 'Hardware Registrado')

@section('content')

<div>
    <h2>Características de Hardware</h2>
    <a href="{{ route('hardwares.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-circle"></i> Nuevo Hardware
    </a>
</div>

<div class="card shadow-sm d-inline-block">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Procesador</th>
                        <th>RAM</th>
                        <th>Almacenamiento</th>
                        <th>Tipo</th>
                        <th class="text-center text-nowrap">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($hardwares as $hw)
                    <tr>
                        <td>{{ $hw->procesador ?? 'N/A' }}</td>

                        <td>{{ $hw->ram_gb }} GB</td>

                        <td>{{ $hw->almacenamiento_gb }} GB</td>

                        <td>
                            <span class="badge bg-{{ $hw->tipo_almacenamiento == 'SSD' ? 'success' : ($hw->tipo_almacenamiento == 'NVMe' ? 'primary' : 'secondary') }}">
                                {{ $hw->tipo_almacenamiento }}
                            </span>
                        </td>

                        <td class="text-center text-nowrap">

                            <a href="{{ route('hardwares.show', $hw) }}"
                               class="btn btn-sm btn-info"
                               title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('hardwares.edit', $hw) }}"
                               class="btn btn-sm btn-warning"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('hardwares.destroy', $hw) }}"
                                  method="POST"
                                  class="d-inline-block"
                                  onsubmit="return confirm('¿Eliminar este hardware? Esta acción no se puede deshacer.')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-sm btn-danger"
                                        title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </form>

                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            No hay configuraciones de hardware registradas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection