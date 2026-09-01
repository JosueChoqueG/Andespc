<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Serie</th>
                <th>Marca / Modelo</th>
                <th>Oficina</th>
                <th>Velocidad</th>
                <th>Detección</th>
                <th>Pantalla</th>
                <th>Estado</th>
                <th>Últ. Mant.</th>
                <th width="150">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contabilletes as $contabillete)
            <tr>
                <td>{{ $contabillete->id }}</td>
                <td>{{ $contabillete->serie_contabilletes }}</td>
                <td>
                    <strong>{{ $contabillete->marca_contabilletes }}</strong><br>
                    <small>{{ $contabillete->modelo_contabilletes }}</small>
                </td>
                <td>{{ $contabillete->oficina->nombre_oficina ?? 'N/A' }}</td>
                <td>{{ $contabillete->velocidad_contabilletes ?? 'N/A' }}</td>
                <td>{{ $contabillete->tipo_deteccion ?? 'N/A' }}</td>
                <td>{{ $contabillete->pantalla_contabilletes ?? 'N/A' }}</td>
                <td>
                    @php
                        $estado = strtoupper(trim($contabillete->estado_contabilletes));
                        $badgeClass = [
                            'OPTIMO' => 'success',
                            'BUENO' => 'info',
                            'REGULAR' => 'warning',
                            'DEFICIENTE' => 'danger',
                            'DE BAJA' => 'secondary'
                        ][$estado] ?? 'dark';
                    @endphp
                    
                    <span class="badge bg-{{ $badgeClass }}">
                        {{ $contabillete->estado_contabilletes }}
                    </span>
                </td>
                <td>
                    @if($contabillete->ultimoMantenimiento)
                        {{ date('d/m/Y', strtotime($contabillete->ultimoMantenimiento->fecha_mantenimiento)) }}
                    @else
                        <span class="text-muted">Sin registro</span>
                    @endif
                </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.contabilletes.show', $contabillete->id) }}" 
                           class="btn btn-info" title="Ver">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.contabilletes.edit', $contabillete->id) }}" 
                           class="btn btn-warning" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="{{ route('admin.mantenimientos-contabillete.create', $contabillete->id) }}" 
                           class="btn btn-primary" title="Registrar Mantenimiento">
                            <i class="bi bi-tools"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> No hay contadoras de billetes registradas o que coincidan con la búsqueda
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $contabilletes->appends(request()->query())->links() }}
</div>
