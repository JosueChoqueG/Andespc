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
            @forelse($termicas as $termica)
            <tr>
                <td>{{ $termica->id }}</td>
                <td><strong>{{ $termica->serie_termica }}</strong></td>
                <td>
                    <strong>{{ $termica->marca_termica }}</strong><br>
                    <small class="text-muted">{{ $termica->modelo_termica }}</small>
                </td>
                <td>{{ $termica->oficina->nombre_oficina ?? 'N/A' }}</td>
                <td>
                    @php
                        $iconos = [
                            'USB' => 'fab fa-usb',
                            'WI-FI' => 'fas fa-wifi',
                            'ETHERNET' => 'fas fa-network-wired',
                            'SERIAL' => 'fas fa-plug',
                            'BLUETOOTH' => 'fab fa-bluetooth'
                        ];
                    @endphp
                    <i class="{{ $iconos[$termica->tipo_conexion] ?? 'fas fa-plug' }}"></i>
                    {{ $termica->tipo_conexion }}
                </td>
                <td>{{ $termica->direccion_ip ?? 'N/A' }}</td>
                <td>
                    @php
                        $estado = strtoupper(trim($termica->estado_termica));
                        $badgeClass = [
                            'OPTIMO' => 'success',
                            'BUENO' => 'info',
                            'REGULAR' => 'warning',
                            'DEFICIENTE' => 'danger',
                            'DE BAJA' => 'secondary'
                        ][$estado] ?? 'dark';
                    @endphp
                    
                    <span class="badge bg-{{ $badgeClass }}">
                        {{ $termica->estado_termica }}
                    </span>
                </td>
                <td>
                    @if($termica->ultimoMantenimiento)
                        {{ date('d/m/Y', strtotime($termica->ultimoMantenimiento->fecha_mantenimiento)) }}
                    @else
                        <span class="text-muted">Sin registro</span>
                    @endif
                </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.termicas.show', $termica->id) }}" 
                           class="btn btn-info" title="Ver">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.termicas.edit', $termica->id) }}" 
                           class="btn btn-warning" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="{{ route('admin.mantenimientos-termica.create', $termica->id) }}" 
                           class="btn btn-primary" title="Registrar Mantenimiento">
                            <i class="bi bi-tools"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-4">
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> No se encontraron impresoras térmicas registradas con los filtros aplicados.
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3 d-flex justify-content-between align-items-center flex-wrap">
    <div class="text-muted small mb-2 mb-md-0">
        Mostrando {{ $termicas->count() }} de {{ $termicas->total() }} registros
    </div>
    <div>
        {{ $termicas->links() }}
    </div>
</div>
