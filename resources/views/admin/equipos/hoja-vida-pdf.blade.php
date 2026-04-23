{{-- resources/views/admin/equipos/hoja-vida-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoja de Vida - {{ $equipo->nombre_dispositivo }}</title>
    <style>
        @page { size: A4; margin: 8mm; }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            -webkit-print-color-adjust: exact;
        }
        .page {
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 0 auto;
            padding: 8mm 12mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #000;
            padding: 3px 2px;
            vertical-align: middle;
            word-wrap: break-word;
        }
        th {
            background-color: #d9d9d9;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }
        td {
            font-size: 9px;
        }
        .section-title {
            background-color: #d9d9d9;
            font-weight: bold;
            padding: 3px 5px;
            border: 1px solid #000;
            margin-top: 8px;
            margin-bottom: -1px;
            text-transform: uppercase;
            font-size: 9px;
        }
        .text-center { text-align: center; }
        ul { 
            margin: 2px 0; 
            padding-left: 12px; 
        }
        li {
            font-size: 9px;
        }
        .btn-print, .btn-pdf {
            position: fixed;
            top: 10px;
            padding: 8px 15px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
            text-decoration: none;
            font-size: 12px;
        }
        .btn-print { 
            right: 10px; 
            background: #007bff; 
        }
        .btn-pdf { 
            right: 120px; 
            background: #dc3545; 
        }
        @media print {
            .btn-print, .btn-pdf { display: none; }
            body { background: white; }
            .page { box-shadow: none; padding: 8mm; }
        }
        .radio-group {
            display: block;
            margin-bottom: 5px;
        }
        .logo-img {
            max-height: 40px;
            width: auto;
        }
    </style>
</head>
<body>
    <div class="page">
        {{-- Encabezado --}}
        <table>
            <tr>
                <td style="width: 18%; text-align: center; padding: 2px;">
                    <img src="{{ asset('assets/images/logo.jpeg') }}" alt="LOS ANDES" style="max-height: 35px; width: auto;">
                </td>
                <td style="width: 57%; text-align: center; font-size: 12px; font-weight: bold;">
                    HOJA DE VIDA DE EQUIPOS INFORMÁTICOS
                    <br><span style="font-size: 13px; font-weight: normal;">
                        Oficina: {{ $equipo->oficina->nombre_oficina ?? 'Abancay' }}
                    </span>
                </td>
                <td style="width: 8%; background-color: #d9d9d9; text-align: center;"><strong>Código</strong></td>
                <td style="width: 17%; text-align: center;"><strong>{{ $equipo->numero_serie ?? $equipo->id }}</strong></td>
            </tr>
        </table>
        
        <table>
            <tr>
                <th style="width: 12%;">Realizado por</th>
                <td style="width: 20%;" class="text-center">{{ $tecnico }}</td>
                <th style="width: 12%;">Departamento</th>
                <td style="width: 15%;" class="text-center">TI</td>
                <td style="width: 41%;" class="text-center">Versión: 1.0</td>
            </tr>
        </table>
        
        <table>
            <tr>
                <td style="width: 35%;" class="text-center">Uso: Interno - Confidencial</td>
                <td style="width: 65%;" class="text-center">UNIDAD DE INFRAESTRUCTURA COMUNICACIÓN Y SOPORTE</td>
            </tr>
        </table>
             
        {{-- 1. DATOS GENERALES DEL EQUIPO --}}
        <div class="section-title">1. DATOS GENERALES DEL EQUIPO</div>
        <table>
            <tr>
                <th style="width: 12%;">Tipo de Equipo</th>
                <td style="width: 13%;" class="text-center">{{ $equipo->tipo_equipo_nombre }}</td>
                <th style="width: 8%;">Marca</th>
                <td style="width: 15%;" class="text-center">{{ $equipo->marca }}</td>
                <th style="width: 10%;">Modelo</th>
                <td style="width: 42%;" class="text-center">{{ $equipo->nombre_modelo }}</td>
            </tr>
            <tr>
                <th>Fecha Adquisición</th>
                <td class="text-center">{{ $equipo->fecha_adquisicion ? date('d/m/Y', strtotime($equipo->fecha_adquisicion)) : 'N/A' }}</td>
                <th>Proveedor</th>
                <td class="text-center">J&C CORP E.I.R.L</td>
                <th>Garantía</th>
                <td class="text-center">Con Garantía</td>
            </tr>
            <tr>
                <th>Número Serie</th>
                <td class="text-center">{{ $equipo->numero_serie ?? 'N/A' }}</td>
                <th>Ubicación</th>
                <td class="text-center">{{ $equipo->oficina->nombre_oficina ?? 'Abancay' }}</td>
                <th>Estado</th>
                <td class="text-center">{{ $equipo->estado_equipo }}</td>
            </tr>
        </table>
        
        <table>
            <tr>
                <th style="width: 20%;">Responsable / Área</th>
                <td style="width: 80%;" class="text-center">
                    {{ $equipo->responsable->nombre_responsable ?? 'N/A' }} - {{ $equipo->oficina->nombre_oficina ?? 'N/A' }}
                </td>
            </tr>
        </table>

        {{-- 2. CARACTERÍSTICAS TÉCNICAS --}}
        <div class="section-title">2. CARACTERÍSTICAS TÉCNICAS</div>
        <table>
            <tr>
                <th style="width: 12%;">Nombre de Host</th>
                <td style="width: 20%;" class="text-center">{{ $equipo->nombre_dispositivo }}</td>
                <th style="width: 12%;">Procesador</th>
                <td style="width: 23%;" class="text-center">{{ $equipo->hardware->procesador ?? 'N/A' }}</td>
                <th style="width: 8%;">Ram</th>
                <td style="width: 25%;" class="text-center">{{ $equipo->hardware->ram_gb ?? 'N/A' }} GB | DDR4</td>
            </tr>
            <tr>
                <th>Disco Principal</th>
                <td class="text-center">{{ $equipo->hardware->tipo_almacenamiento ?? 'SSD' }} {{ $equipo->hardware->almacenamiento_gb ?? 'N/A' }} GB</td>
                <th>Sistema Operativo</th>
                <td class="text-center">{{ $equipo->sistema_operativo_completo }}</td>
                <th>CPU</th>
                <td class="text-center">{{ $equipo->hardware->procesador ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Disco Secundario</th>
                <td class="text-center">N/A</td>
                <th>Dirección IP</th>
                <td class="text-center">{{ $equipo->direccion_ip ?? 'N/A' }}</td>
                <th>MAC</th>
                <td class="text-center">N/A</td>
            </tr>
        </table>
        
        {{-- 3. HISTORIAL DE MANTENIMIENTO --}}
        <div class="section-title">3. HISTORIAL DE MANTENIMIENTO</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Fecha</th>
                    <th style="width: 12%;">Tipo</th>
                    <th>Descripción del Trabajo realizado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($historialMantenimientos as $item)
                <tr>
                    <td class="text-center">{{ $item->fecha_mantenimiento->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $item->tipo_mantenimiento }}</td>
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
                    <td class="text-center" colspan="3">No hay mantenimientos registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- 4. REGISTRO DE FALLAS E INCIDENCIAS --}}
        <div class="section-title">4. REGISTRO DE FALLAS E INCIDENCIAS</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Fecha</th>
                    <th style="width: 38%;">Descripción de la falla</th>
                    <th>Solución Aplicada</th>
                </tr>
            </thead>
            <tbody>
                @php $hasFallas = false; @endphp
                @foreach($fallasHistorial as $falla)
                    @if($falla->fallas_encontradas)
                        @php $hasFallas = true; @endphp
                        <tr>
                            <td class="text-center">{{ $falla->fecha_mantenimiento->format('d/m/Y') }}</td>
                            <td>{{ $falla->fallas_encontradas }}</td>
                            <td>{{ $falla->soluciones_aplicadas ?? 'No especificada' }}</td>
                        </tr>
                    @endif
                @endforeach
                @if(!$hasFallas)
                <tr>
                    <td class="text-center" colspan="3">No hay fallas registradas</td>
                </tr>
                @endif
            </tbody>
        </table>

        {{-- 5. ESTADO ACTUAL DE EQUIPO --}}
        <div class="section-title">5. ESTADO ACTUAL DE EQUIPO</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 30%;">Descripción</th>
                    <th style="width: 10%;" class="text-center">Estado</th>
                    <th style="width: 60%;">Observaciones Generales</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $estados = [
                        'Operativo',
                        'Operativo con observaciones',
                        'En mantenimiento',
                        'Fuera de servicio',
                        'De baja'
                    ];
                @endphp

                <tr>
                    <td style="padding: 3px;">
                        @foreach($estados as $estado)
                            <div style="margin-bottom: 5px;">☐ {{ $estado }}</div>
                        @endforeach
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        @foreach($estados as $estado)
                            <div style="margin-bottom: 5px; height: 18px;">
                                @if($equipo->estado_equipo == $estado)
                                    <strong>✗</strong>
                                @else
                                    &nbsp;
                                @endif
                            </div>
                        @endforeach
                    </td>
                    <td style="vertical-align: top; padding: 5px;">
                        @if(isset($mantenimiento) && $mantenimiento->observaciones)
                            {{ $mantenimiento->observaciones }}
                        @elseif(isset($ultimoMantenimiento) && $ultimoMantenimiento->observaciones)
                            {{ $ultimoMantenimiento->observaciones }}
                        @else
                            No instalar aplicaciones de internet o sitios no confiables. No manipular la configuración de IP.
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- 6. CONFORMIDAD DE TRABAJO --}}
        <div class="section-title">6. CONFORMIDAD DE TRABAJO</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 25%;"></th>
                    <th style="width: 30%;">NOMBRE Y APELLIDO</th>
                    <th style="width: 25%;">CARGO</th>
                    <th style="width: 20%;">FIRMA</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="height: 35px; padding-left: 5px;">6.1 Ejecutivo TI</td>
                    <td class="text-center">{{ $tecnico }}</td>
                    <td class="text-center">Ejecutivo TI</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 35px; padding-left: 5px;">6.2 Usuario Asignado</td>
                    <td class="text-center">{{ $equipo->responsable->nombre_responsable ?? '_________________' }}</td>
                    <td class="text-center">Usuario</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 35px; padding-left: 5px;">6.3 Jefe de Infraestructura</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 35px; padding-left: 5px;">6.4 VoBo Gerencia</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        
        {{-- Fecha de Conformidad --}}
        <div style="margin-top: 10px; text-align: right; font-size: 9px;">
            <strong>Fecha de Conformidad:</strong> {{ isset($mantenimiento) ? $mantenimiento->fecha_mantenimiento->format('d/m/Y') : date('d/m/Y') }}
        </div>
    </div>
</body>
</html>