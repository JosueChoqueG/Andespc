<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Nombre</th>
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
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('equipos.show', $equipo) }}" class="btn btn-sm btn-info" title="Ver detalles">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('equipos.edit', $equipo) }}" class="btn btn-sm btn-warning" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="{{ route('admin.mantenimientos-pc.create', $equipo) }}" 
                        class="btn btn-sm btn-success" title="Nuevo Mantenimiento">
                            <i class="bi bi-tools"></i>
                        </a>
                        <a href="{{ route('admin.mantenimientos-pc.historial', $equipo) }}" 
                        class="btn btn-sm btn-secondary" title="Historial">
                            <i class="bi bi-clock-history"></i>
                        </a>
                        <a href="{{ route('admin.equipos.hoja-vida', $equipo) }}" 
                        class="btn btn-sm btn-primary" title="Hoja de Vida" target="_blank">
                            <i class="bi bi-file-pdf"></i>
                        </a>
                    </div>
                </td>
             <!--  <td class="text-center">
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
                </td> -->
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
    {{ $equipos->links('pagination::bootstrap-5') }}
</div>