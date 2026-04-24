{{-- resources/views/admin/equipos/hoja-vida.blade.php --}}
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
            font-size: 11px;
            margin: 0;
            padding: 0;
            background-color: #272525ff;
            -webkit-print-color-adjust: exact;
        }
        .page {
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 0 auto;
            padding: 10mm 15mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: middle;
            word-wrap: break-word;
        }
        th {
            font-weight: bold;
            text-align: center;
        }
        .section-title {
            background-color: #d9d9d9;
            font-weight: bold;
            padding: 5px;
            border: 1px solid #000;
            margin-top: 10px;
            margin-bottom: -1px;
            text-transform: uppercase;
            font-size: 10px;
        }
        .text-center { text-align: center; }
        ul { margin: 0; padding-left: 15px; }
        .signature-line {
            border-top: 1px solid #000;
            width: 80%;
            margin: 40px auto 5px auto;
            text-align: center;
        }
        .btn-print {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
        }
        .btn-pdf {
            position: fixed;
            top: 10px;
            right: 150px;
            padding: 10px 20px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
            text-decoration: none;
        }
        @media print {
            .btn-print, .btn-pdf { display: none; }
            body { background: white; }
            .page { box-shadow: none; padding: 10mm; }
        }
    </style>
</head>
<body>
    <button class="btn-print no-print" onclick="window.print()">🖨️ Imprimir</button>
    <a href="{{ isset($mantenimiento) ? route('admin.equipos.hoja-vida-mantenimiento.pdf', $mantenimiento) : route('admin.equipos.hoja-vida.pdf', $equipo) }}" 
       class="btn-pdf">📥 Descargar PDF
    </a>

    <div class="page">
        {{-- Encabezado --}}
        <table>
            <tr>
                <td style="width: 20%; padding: 0;">
                    <img src="{{ asset('logo.jpeg') }}" alt="LOS ANDES" style="width: 100%; height: 100%; object-fit: cover;">
                </td>
                <td style="width: 55%; text-align: center; font-size: 13px; font-weight: bold;">
                    HOJA DE VIDA DE EQUIPOS INFORMÁTICOS
                    <br><span style="font-size: 15px; font-weight: normal;">
                        Oficina: {{ $equipo->oficina->nombre_oficina ?? 'Abancay' }}
                    </span>
                </td>
                <td style="width: 7%;"><strong>Código</strong></td>
                <td style="width: 18%;"><strong>{{ $equipo->numero_serie ?? $equipo->id }}</strong></td>
            </tr>
        </table>
        
        <table>
            <tr>
                <th style="width: 15%;">Realizado por</th>
                <td style="width: 20%;" class="text-center">{{ $tecnico }}</td>
                <th style="width: 15%;">Departamento</th>
                <td style="width: 15%;" class="text-center">TI</td>
                <td style="width: 35%;" class="text-center">Versión: 1.0</td>
            </tr>
        </table>
        
        <table>
            <tr>
                <td style="width: 40%;" class="text-center">Uso: Interno - Confidencial</td>
                <td style="width: 60%;" class="text-center">UNIDAD DE INFRAESTRUCTURA COMUNICACIÓN Y SOPORTE</td>
            </tr>
        </table>
             
        {{-- 1. DATOS GENERALES DEL EQUIPO --}}
        <div class="section-title">1. DATOS GENERALES DEL EQUIPO</div>
        <table>
            <tr>
                <th style="width: 15%;">Tipo Equipo</th>
                <td style="width: 12%;" class="text-center">{{ $equipo->tipo_equipo_nombre }}</td>
                <th style="width: 10%;">Marca</th>
                <td style="width: 20%;" class="text-center">{{ $equipo->marca }}</td>
                <th style="width: 10%;">Modelo</th>
                <td class="text-center" >{{ $equipo->nombre_modelo }}</td>
            </tr>
            <tr>
                <th style="width: 15%;">Fecha Adquisición</th>
                <td style="width: 12%;" class="text-center">{{ $equipo->fecha_adquisicion ? date('d/m/Y', strtotime($equipo->fecha_adquisicion)) : 'N/A' }}</td>
                <th style="width: 10%;">Proveedor</th>
                <td style="width: 20%;" class="text-center">J&C CORP E.I.R.L</td>
                <th style="width: 10%;">Garantía</th>
                <td class="text-center">Con Garantía</td>
            </tr>
            <tr>
                <th style="width: 15%;">Número Serie</th>
                <td style="width: 12%;" class="text-center">{{ $equipo->numero_serie ?? 'N/A' }}</td>
                <th style="width: 10%;">Ubicación</th>
                <td style="width: 20%;" class="text-center">{{ $equipo->oficina->nombre_oficina ?? 'Abancay' }}</td>
            </tr>
        </table>
        <table>
            <tr>
                <td style="width: 15%;" >Responsable / Area</td>
                <td style="width: 40%;" class="text-center">{{ $equipo->responsable->nombre_responsable }} - {{ $equipo->oficina->nombre_oficina }}</td>
            </tr>
        </table>

        {{-- 2. CARACTERÍSTICAS TECNICAS --}}
        <div class="section-title">2. CARACTERÍSTICAS TECNICAS</div>
        <table>
            <tr>
                <th style="width: 15%;">Nombre de Host</th>
                <td style="width: 20%;" class="text-center">{{ $equipo->nombre_dispositivo }}</td>
                <th style="width: 15%;">Procesador</th>
                <td style="width: 20%;" class="text-center">{{ $equipo->hardware->procesador ?? 'N/A' }}</td>
                <th style="width: 7%;">Ram</th>
                <td class="text-center">{{ $equipo->hardware->ram_gb ?? 'N/A' }} GB | DDR4</td>
            </tr>
            <tr>
                <th width="15%">Disco Principal</th>
                <td width="20%" class="text-center">{{ $equipo->hardware->tipo_almacenamiento ?? 'SSD' }} {{ $equipo->hardware->almacenamiento_gb ?? 'N/A' }} GB</td>
                <th width="15%">Sistema Operativo</th>
                <td width="20%" class="text-center">{{ $equipo->sistema_operativo_completo }}</td>
                <th width="7%">CPU</th>
                <td class="text-center">{{ $equipo->hardware->procesador ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th width="15%">Disco Secundario</th>
                <td width="20%" class="text-center">{{ $equipo->hardware->tipo_almacenamiento ?? 'SSD' }} {{ $equipo->hardware->almacenamiento_gb ?? 'N/A' }} GB</td>
                <th width="15%">Dirección IP</th>
                <td width="20%" class="text-center">{{ $equipo->direccion_ip ?? 'N/A' }}</td>
                <th width="7%">MAC</th>
                <td width="20%" class="text-center">{{ $equipo->mac_address ?? 'N/A' }}</td>
            </tr>
        </table>
        
        {{-- 3. HISTORIAL DE MANTENIMIENTO --}}
        <div class="section-title">3. HISTORIAL DE MANTENIMIENTO</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Fecha</th>
                    <th style="width: 15%;">Tipo</th>
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
                    <th style="width: 15%;">Fecha</th>
                    <th style="width: 35%;">Descripción de la falla</th>
                    <th>Solución Aplicada</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fallasHistorial as $falla)
                    @if($falla->fallas_encontradas)
                        <tr>
                            <td class="text-center">{{ $falla->fecha_mantenimiento->format('d/m/Y') }}</td>
                            <td class="text-center">{{ $falla->fallas_encontradas }}</td>
                            <td>{{ $falla->soluciones_aplicadas ?? 'No especificada' }}</td>
                        </tr>
                    @endif
                @empty
                <tr>
                    <td class="text-center" colspan="3">No hay fallas registradas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{-- 5. ESTADO ACTUAL DE EQUIPO --}}
        <div class="section-title">5. ESTADO ACTUAL DE EQUIPO</div>
        <table>
            <thead>
                <tr>
                    <th width="25%">Descripción</th>
                    <th width="10%" class="text-center">Estado</th>
                    <th width="65%">Observaciones Generales</th>
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
                    <!-- Descripción -->
                    <td>
                        @foreach($estados as $estado)
                            <label style="display:block; margin-bottom: 8px;">
                                {{ $estado }}
                            </label>
                        @endforeach
                    </td>

                    <!-- Radio buttons -->
                    <td class="text-center">
                        @foreach($estados as $estado)
                            <label style="display:block; ">
                                <input type="radio" name="estado_equipo" value="{{ $estado }}"
                                    {{ $equipo->estado_equipo == $estado ? 'checked' : '' }}>
                            </label>
                        @endforeach
                    </td>

                    <!-- Observaciones -->
                    <td>
                        {{ $ultimoMantenimiento->observaciones 
                            ?? $mantenimiento->observaciones 
                            ?? 'No instalar aplicaciones de internet o sitios no confiables. No manipular la configuración de IP.' }}
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- 6. RESPONSABLES --}}
        <div class="section-title">6. RESPONSABLES</div>
        <table>
            <thead>
                <tr>
                    <th width="20%"></th>
                    <th width="30%">NOMBRE Y APELLIDO</th>
                    <th>CARGO</th>
                    <th>FIRMA</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="height: 30px;">
                        6.1 Ejecutivo TI
                    </td>
                    <td> {{ $tecnico }}</td>
                    <td> Asistente de Infraestructura Informática</td>
                    <td> </td>
                </tr>
                <tr>
                    <td style="height: 30px;">
                        6.2 Usuario Asignado
                    </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                </tr>
                <tr>
                    <td style="height: 30px;">
                        6.3 Ejecutivo TI
                    </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                </tr>
                <tr>
                    <td style="height: 30px;">
                        6.4 Usuario Asignado
                    </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>