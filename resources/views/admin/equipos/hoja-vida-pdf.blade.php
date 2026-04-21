{{-- resources/views/admin/equipos/hoja-vida-pdf.blade.php --}}
{{-- Es exactamente igual que hoja-vida.blade.php pero sin los botones de imprimir/PDF --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoja de Vida - {{ $equipo->nombre_dispositivo }}</title>
    <style>
        @page { size: A4; margin: 10mm; }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
            background-color: white;
        }
        .page {
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 0 auto;
            padding: 10mm 15mm;
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
            background-color: #d9d9d9;
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
    </style>
</head>
<body>
    <div class="page">
        {{-- Encabezado --}}
        <table>
            <tr>
                <td style="width: 20%; text-align: center;">
                    <div style="font-weight:900; font-size:11px;">LOS ANDES</div>
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
                <th style="width: 15%;">Tipo de Equipo</th>
                <td style="width: 12%;" class="text-center">{{ $equipo->tipo_equipo_nombre }}</td>
                <th style="width: 10%;">Marca</th>
                <td style="width: 20%;" class="text-center">{{ $equipo->marca }}</td>
                <th style="width: 10%;">Modelo</th>
                <td style="width: 33%;" class="text-center">{{ $equipo->nombre_modelo }}</td>
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

        {{-- 2. CARACTERÍSTICAS TECNICAS --}}
        <div class="section-title">2. CARACTERÍSTICAS TECNICAS</div>
        <table>
            <tr>
                <th style="width: 15%;">Nombre de Host</th>
                <td style="width: 20%;" class="text-center">{{ $equipo->nombre_dispositivo }}</td>
                <th style="width: 15%;">Procesador</th>
                <td style="width: 20%;" class="text-center">{{ $equipo->hardware->procesador ?? 'N/A' }}</td>
                <th style="width: 7%;">Ram</th>
                <td style="width: 23%;" class="text-center">{{ $equipo->hardware->ram_gb ?? 'N/A' }} GB | DDR4</td>
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

        {{-- 5. RECOMENDACIONES --}}
        <div class="section-title">5. RECOMENDACIONES Y/O OBSERVACIONES</div>
        <div style="border: 1px solid #000; padding: 8px; min-height: 40px;">
            {{ $ultimoMantenimiento->observaciones ?? $mantenimiento->observaciones ?? 'No instalar aplicaciones de internet o sitios no confiables. No manipular la configuración de IP.' }}
        </div>

        {{-- 6. CONFORMIDAD --}}
        <div class="section-title">6. CONFORMIDAD DE TRABAJO</div>
        <div style="border: 1px solid #000; padding: 10px; margin-top: -1px;">
            <table style="border: none; margin-top: 20px;">
                <tr style="border: none;">
                    <td style="border: none; text-align: center;">
                        <div class="signature-line">{{ $tecnico }}<br>Ejecutivo TI</div>
                    </td>
                    <td style="border: none; text-align: center;">
                        <div class="signature-line">{{ $equipo->responsable->nombre_responsable ?? '_________________' }}<br>Usuario Asignado</div>
                    </td>
                    <td style="border: none; text-align: center; vertical-align: bottom;">
                        <strong>Fecha Conformidad:</strong> {{ isset($mantenimiento) ? $mantenimiento->fecha_mantenimiento->format('d/m/Y') : date('d/m/Y') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>