<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Dispositivo</th>
                <th>Serie</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Tipo</th>
                <!-- <th>IP</th> -->
                <th>Fecha Compra</th>
                <!-- <th>Fecha Mantenimiento</th> -->
                <th>Oficina</th>
                <th>Responsable</th>
                <th class="text-center">Estado</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($equipos as $equipo)
                    <tr>
                        <td>{{ $equipo->nombre_dispositivo }}</td>
                        <td>{{ $equipo->numero_serie ?? 'N/A' }}</td>
                        <td>{{ $equipo->modelo?->marca?->nombre_marca ?? 'N/A' }}</td>
                        <td>{{ $equipo->modelo?->nombre_modelo ?? 'N/A' }}</td>
                        <td>{{ $equipo->tipoequipo?->nombre_tipo ?? 'N/A' }}</td>
                        <td>{{ $equipo->fecha_adquisicion ? \Carbon\Carbon::parse($equipo->fecha_adquisicion)->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td>{{ $equipo->oficina?->nombre_oficina ?? 'N/A' }}</td>
                        <td>{{ $equipo->responsable?->nombre_responsable ?? 'Sin asignar' }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ 
                                            $equipo->estado_equipo == 'Activo' ? 'success' :
                ($equipo->estado_equipo == 'Inactivo' ? 'warning' : 'danger') 
                                                    }}">
                                {{ $equipo->estado_equipo }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('equipos.show', $equipo) }}" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('equipos.edit', $equipo) }}" class="btn btn-sm btn-warning" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('equipos.destroy', $equipo) }}" method="POST" class="d-inline-block"
                                onsubmit="return confirm('¿Está seguro de eliminar este equipo? Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-sm btn-danger" aria-label="Eliminar equipo">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-muted">
                        <i class="bi bi-exclamation-circle"></i> No hay equipos registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Paginación Bootstrap -->
<div class="d-flex justify-content-center mt-3">
    @if ($equipos->hasPages())
        <nav>
            <ul class="pagination">

                {{-- Flecha izquierda --}}
                @if ($equipos->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">&laquo;</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $equipos->previousPageUrl() }}" rel="prev">&laquo;</a>
                    </li>
                @endif

                {{-- Números --}}
                @foreach ($equipos->getUrlRange(1, $equipos->lastPage()) as $page => $url)
                    @if ($page == $equipos->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                {{-- Flecha derecha --}}
                @if ($equipos->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $equipos->nextPageUrl() }}" rel="next">&raquo;</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">&raquo;</span>
                    </li>
                @endif

            </ul>
        </nav>
    @endif
</div>