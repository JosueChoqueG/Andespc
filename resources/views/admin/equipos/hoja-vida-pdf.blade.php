<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hoja de Vida - {{ $equipo->nombre_dispositivo }}</title>
    <style>
        @page {
            margin: 10mm 8mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11.5px;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        th, td {
            border: 1px solid #000000ff;
            padding: 4px 5px;
            vertical-align: middle;
            font-size: 11.5px;
        }
        th {
            font-weight: bold;
            text-align: center;
            background-color: #f0f0f0;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .section-title {
            background-color: #d9d9d9;
            font-weight: bold;
            padding: 4px 5px;
            border: 1px solid #000000ff;
            margin-top: 8px;
            margin-bottom: 0;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
        }
        ul {
            margin: 0;
            padding-left: 14px;
        }
        ul li {
            margin-bottom: 2px;
        }
        /* Indicadores de estado visual (reemplazan radio buttons) */
        .estado-row {
            padding: 2px 0;
        }
        .estado-label {
            display: block;
            padding: 2px 0;
        }
        .radio-checked {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            border-radius: 50%;
            position: relative;
            vertical-align: middle;
        }
        .radio-checked::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 4px;
            height: 4px;
            background-color: #000;
            border-radius: 50%;
        }
        .radio-unchecked {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            border-radius: 50%;
            vertical-align: middle;
        }
        /* Fila con altura mínima */
        .firma-row td {
            height: 35px;
        }
    </style>
</head>
<body>

    {{-- ========== ENCABEZADO ========== --}}
    <table>
        <tr>
            <td style="width: 18%; padding: 2px;">
                @php
                    $logoBase64 = null;
                    $logoPath = public_path('logo.jpeg');
                    if (file_exists($logoPath)) {
                        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                        $data = file_get_contents($logoPath);
                        $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    }
                @endphp
                
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="LOS ANDES" style="width: 100%; max-width: 120px; height: auto;">
                @else
                    <div style="font-weight: bold; font-size: 10px; text-align: center; padding: 10px;">
                        LOS ANDES
                    </div>
                @endif
            </td>
            <td style="width: 52%; text-align: center; font-size: 12px; font-weight: bold;">
                HOJA DE VIDA DE EQUIPOS INFORMÁTICOS
                <br>
                <span style="font-size: 11px; font-weight: normal;">
                    Oficina: {{ $equipo->oficina->nombre_oficina ?? 'Abancay' }}
                </span>
            </td>
            <td style="width: 8%; font-weight: bold;">Código</td>
            <td style="width: 22%; font-weight: bold;">{{ $equipo->numero_serie ?? $equipo->id }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <th style="width: 16%;">Realizado por</th>
            <td style="width: 20%; text-align: center;">{{ $tecnico }}</td>
            <th style="width: 16%;">Departamento</th>
            <td style="width: 16%; text-align: center;">TI</td>
            <td style="width: 32%; text-align: center;">Versión: 1.0</td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width: 40%; text-align: center;">Uso: Interno - Confidencial</td>
            <td style="width: 60%; text-align: center;">UNIDAD DE INFRAESTRUCTURA COMUNICACIÓN Y SOPORTE</td>
        </tr>
    </table>

    {{-- ========== 1. DATOS GENERALES ========== --}}
    <div class="section-title">1. DATOS GENERALES DEL EQUIPO</div>
    <table>
        <tr>
            <th style="width: 15%;">Tipo Equipo</th>
            <td style="width: 12%; text-align: center;">{{ $equipo->tipo_equipo_nombre }}</td>
            <th style="width: 10%;">Marca</th>
            <td style="width: 20%; text-align: center;">{{ $equipo->marca }}</td>
            <th style="width: 10%;">Modelo</th>
            <td style="text-align: center;">{{ $equipo->nombre_modelo }}</td>
        </tr>
        <tr>
            <th style="width: 15%;">Fecha Adquisición</th>
            <td style="width: 12%; text-align: center;">{{ $equipo->fecha_adquisicion ? date('d/m/Y', strtotime($equipo->fecha_adquisicion)) : 'N/A' }}</td>
            <th style="width: 10%;">Proveedor</th>
            <td style="width: 20%; text-align: center;">J&C CORP E.I.R.L</td>
            <th style="width: 10%;">Garantía</th>
            <td style="text-align: center;">Con Garantía</td>
        </tr>
        <tr>
            <th style="width: 15%;">Número Serie</th>
            <td style="width: 12%; text-align: center;">{{ $equipo->numero_serie ?? 'N/A' }}</td>
            <th style="width: 10%;">Ubicación</th>
            <td style="width: 20%; text-align: center;">{{ $equipo->oficina->nombre_oficina ?? 'Abancay' }}</td>
            <th ></th>
            <td ></td>
        </tr>
    </table>
    @php
    // Normalizamos a mayúsculas para búsqueda insensible
    $host = strtoupper($equipo->nombre_dispositivo ?? '');

    // Evaluamos las palabras clave en el orden que indicaste
    if (strpos($host, 'ADMIN') !== false) {
        $area = 'ADMINISTRACIÓN';
    } elseif (strpos($host, 'CAJA') !== false) {
        $area = 'CAJA';
    } elseif (strpos($host, 'PLAT') !== false) {
        $area = 'PLATAFORMA';
    } elseif (strpos($host, 'CRED') !== false) {
        $area = 'CRÉDITOS';
    } else {
        // Si no coincide ninguna, usa el nombre de oficina o 'N/A'
        $area = $equipo->oficina->nombre_oficina ?? 'N/A';
    }
    @endphp

    <table>
        <tr>
            <td style="width: 15%;">Responsable / Área</td>
            <td style="width: 40%;" class="text-center">
                {{ $equipo->responsable->nombre_responsable ?? 'N/A' }} - {{ $area }}
            </td>
        </tr>
    </table>

    {{-- ========== 2. CARACTERÍSTICAS TÉCNICAS ========== --}}
    <div class="section-title">2. CARACTERÍSTICAS TÉCNICAS</div>
    <table>
        <tr>
            <th style="width: 15%;">Nombre de Host</th>
            <td style="width: 20%; text-align: center;">{{ $equipo->nombre_dispositivo }}</td>
            <th style="width: 15%;">Procesador</th>
            <td style="width: 20%; text-align: center;">{{ $equipo->hardware->procesador ?? 'N/A' }}</td>
            <th style="width: 7%;">RAM</th>
            <td style="text-align: center;">{{ $equipo->hardware->ram_gb ?? 'N/A' }} GB | DDR4</td>
        </tr>
        <tr>
            <th style="width: 15%;">Disco Principal</th>
            <td style="width: 20%; text-align: center;">{{ $equipo->hardware->tipo_almacenamiento ?? 'SSD' }} {{ $equipo->hardware->almacenamiento_gb ?? 'N/A' }} GB</td>
            <th style="width: 15%;">Sistema Operativo</th>
            <td style="width: 20%; text-align: center;">{{ $equipo->sistema_operativo_completo ?? 'N/A' }}</td>
            <th style="width: 7%;">CPU</th>
            <td style="text-align: center;">{{ $equipo->hardware->procesador ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th style="width: 15%;">Disco Secundario</th>
            <td style="width: 20%; text-align: center;">{{ $equipo->hardware->tipo_almacenamiento_sec ?? 'SSD' }} {{ $equipo->hardware->almacenamiento_sec_gb ?? 'N/A' }} GB</td>
            <th style="width: 15%;">Dirección IP</th>
            <td style="width: 20%; text-align: center;">{{ $equipo->direccion_ip ?? 'N/A' }}</td>
            <th style="width: 7%;">MAC</th>
            <td style="text-align: center;">{{ $equipo->direccion_mac ?? 'N/A' }}</td>
        </tr>
    </table>

    {{-- ========== 3. HISTORIAL DE MANTENIMIENTO ========== --}}
    <div class="section-title">3. HISTORIAL DE MANTENIMIENTO</div>
    <table>
        <thead>
            <tr>
                <th style="width: 14%;">Fecha</th>
                <th style="width: 14%;">Tipo</th>
                <th>Descripción del Trabajo Realizado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($historialMantenimientos as $item)
            <tr>
                <td style="text-align: center;">{{ $item->fecha_mantenimiento->format('d/m/Y') }}</td>
                <td style="text-align: center;">{{ $item->tipo_mantenimiento }}</td>
                <td>
                    <ul>
                        @foreach($item->descripcion_array as $trabajo)
                            <li>{{ $trabajo }}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            @empty
            <tr>
                <td style="text-align: center;" colspan="3">No hay mantenimientos registrados</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ========== 4. REGISTRO DE FALLAS ========== --}}
    <div class="section-title">4. REGISTRO DE FALLAS E INCIDENCIAS</div>
    <table>
        <thead>
            <tr>
                <th style="width: 14%;">Fecha</th>
                <th style="width: 36%;">Descripción de la Falla</th>
                <th>Solución Aplicada</th>
            </tr>
        </thead>
        <tbody>
            @php $tieneFallas = false; @endphp
            @forelse($fallasHistorial as $falla)
                @if($falla->fallas_encontradas)
                    <tr>
                        <td style="text-align: center;">{{ $falla->fecha_mantenimiento->format('d/m/Y') }}</td>
                        <td>{{ $falla->fallas_encontradas }}</td>
                        <td>{{ $falla->soluciones_aplicadas ?? 'No especificada' }}</td>
                    </tr>
                    @php $tieneFallas = true; @endphp
                @endif
            @empty
            @endforelse
            @if(!$tieneFallas)
            <tr>
                <td style="text-align: center;" colspan="3">No hay fallas registradas</td>
            </tr>
            @endif
        </tbody>
    </table>

    {{-- ========== 5. ESTADO ACTUAL DEL EQUIPO ========== --}}
    <div class="section-title">5. ESTADO ACTUAL DEL EQUIPO</div>
    <table>
        <thead>
            <tr>
                <th style="width: 25%;">Descripción</th>
                <th style="width: 10%;">Estado</th>
                <th style="width: 65%;">Observaciones Generales</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    @php
                        $estados = [
                            'Operativo',
                            'Operativo con observaciones',
                            'En mantenimiento',
                            'Fuera de servicio',
                            'De baja'
                        ];
                        $estadoActual = $equipo->estado_equipo ?? 'Operativo';
                    @endphp
                    @foreach($estados as $estado)
                        <span class="estado-label">{{ $estado }}</span>
                    @endforeach
                </td>
                <td style="text-align: center;">
                    @foreach($estados as $estado)
                        <span class="estado-label">
                            @if($estado == $estadoActual)
                                <span class="radio-checked"></span>
                            @else
                                <span class="radio-unchecked"></span>
                            @endif
                        </span>
                    @endforeach
                </td>
                <td style="font-size: 11px;">
                    {{ $ultimoMantenimiento->observaciones ?? ($mantenimiento->observaciones ?? 'No instalar aplicaciones de internet o sitios no confiables. No manipular la configuración de IP.') }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- ========== 6. RESPONSABLES ========== --}}
    <div class="section-title">6. RESPONSABLES</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 15%;">ROL</th>
                <th style="width: 30%;">NOMBRE Y APELLIDO</th>
                <th style="width: 25%;">CARGO</th>
                <th style="width: 25%;">FIRMA</th>
            </tr>
        </thead>
        <tbody>
            <tr class="firma-row">
                <td style="text-align: center;">6.1</td>
                <td>Ejecutivo TI</td>
                <td>{{ $tecnico }}</td>
                <td>Asistente de Infraestructura Informática</td>
                <td></td>
            </tr>
            <tr class="firma-row">
                <td style="text-align: center;">6.2</td>
                <td>Usuario Asignado</td>
                <td></td>
                @php
                    $host = strtoupper($equipo->nombre_dispositivo ?? '');
                    
                    if (strpos($host, 'ADMIN') !== false) {
                        $cargo = 'Administrador';
                    } elseif (strpos($host, 'CAJA') !== false) {
                        $cargo = 'Asistente de Operaciones';
                    } elseif (strpos($host, 'PLAT') !== false) {
                        $cargo = 'Asistente Admisión y Plataforma';
                    } elseif (strpos($host, 'CRED') !== false) {
                        $cargo = 'Asesor de Créditos';
                    } else {
                        $cargo = $equipo->oficina->nombre_oficina ?? 'N/A';
                    }
                @endphp

                <td style="width: 40%; text-align: center;">
                    {{ $cargo }}
                </td>
                <td></td>
            </tr>
            <tr class="firma-row">
                <td style="text-align: center;">6.3</td>
                <td>Ejecutivo TI</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="firma-row">
                <td style="text-align: center;">6.4</td>
                <td>Usuario Asignado</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

</body>
</html>