<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Serie</th>
                <th>Marca / Modelo</th>
                <th>Oficina</th>
                <th>Conexión</th>
                <th>IP</th>
                <th>Estado</th>
                <th>Últ. Mant.</th>
                <th width="150">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($impresoras as $impresora)
            <tr>
                <td>{{ $impresora->id }}</td>
                <td><strong>{{ $impresora->serie_impresora }}</strong></td>
                <td>
                    <strong>{{ $impresora->marca_impresora }}</strong><br>
                    <small class="text-muted">{{ $impresora->modelo_impresora }}</small>
                </td>
                <td>{{ $impresora->oficina->nombre_oficina ?? 'N/A' }}</td>
                <td>
                    @php
                        $iconos = [
                            'USB' => 'fa-usb',
                            'WIFI' => 'fa-wifi',
                            'ETHERNET' => 'fa-network-wired',
                            'WIFI-DIRECT' => 'fa-wifi'
                        ];
                    @endphp
                    <i class="fas {{ $iconos[$impresora->tipo_conexion] ?? 'fa-plug' }}"></i>
                    {{ $impresora->tipo_conexion }}
                </td>
                <td>{{ $impresora->direccion_ip ?? 'N/A' }}</td>
                <td>
                    @php
                        $estado = strtoupper(trim($impresora->estado_impresora));
                        $badgeClass = [
                            'OPTIMO' => 'success',
                            'BUENO' => 'info',
                            'REGULAR' => 'warning',
                            'DEFICIENTE' => 'danger',
                            'DE BAJA' => 'secondary'
                        ][$estado] ?? 'dark';
                    @endphp
                    
                    <span class="badge bg-{{ $badgeClass }}">
                        {{ $impresora->estado_impresora }}
                    </span>
                </td>
                <td>
                    @if($impresora->ultimoMantenimiento)
                        {{ date('d/m/Y', strtotime($impresora->ultimoMantenimiento->fecha_mantenimiento)) }}
                    @else
                        <span class="text-muted">Sin registro</span>
                    @endif
                </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.impresoras.show', $impresora->id) }}" 
                           class="btn btn-info" title="Ver">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.impresoras.edit', $impresora->id) }}" 
                           class="btn btn-warning" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="{{ route('admin.mantenimientos-impresora.create', $impresora->id) }}" 
                           class="btn btn-primary" title="Registrar Mantenimiento">
                            <i class="bi bi-tools"></i>
                        </a>
                        <a href="{{ route('admin.informes-impresora.create', $impresora->id) }}" 
                           class="btn btn-success" title="Nuevo Informe">
                            <i class="bi bi-file-earmark-text"></i> 
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-4">
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> No se encontraron impresoras registradas con los filtros aplicados.
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3 d-flex justify-content-between align-items-center flex-wrap">
    <div class="text-muted small mb-2 mb-md-0">
        Mostrando {{ $impresoras->count() }} de {{ $impresoras->total() }} registros
    </div>
    <div>
        {{ $impresoras->links() }}
    </div>
</div>
