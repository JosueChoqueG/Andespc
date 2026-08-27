<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe N° {{ str_pad($informe->id, 2, '0', STR_PAD_LEFT) }} - {{ $informe->impresora->oficina->nombre_oficina ?? 'IMPRESORA' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #e8e8e8;
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            padding: 20px;
            -webkit-print-color-adjust: exact;
        }

        .pagina {
            width: 210mm;
            min-height: 297mm;
            background: #fff;
            margin: 0 auto 20px auto;
            padding: 1.27cm 2.09cm 0.49cm 2.40cm; /* arriba, derecha, abajo, izquierda */
            box-shadow: 0 0 8px rgba(0,0,0,0.15);
            position: relative;
        }

        /* ENCABEZADO */
        .encabezado-cooperativa {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            text-align: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-container img {
            max-height: 60px;
            width: auto;
            object-fit: contain;
        }

        .anio-gobierno {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin: 8px 0;
        }

        .fecha-lugar {
            text-align: right;
            font-size: 12pt;
            margin-bottom: 18px;
        }

        .titulo-informe {
            font-size: 12pt;
            font-weight: bold;
            margin: 10px 0 18px 0;
            text-decoration: underline;
            text-align: center;
        }

        /* TABLA DE DESTINATARIOS */
        .tabla-meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            font-size: 12pt;
        }

        .tabla-meta td {
            border: none;
            padding: 3px 8px 3px 0;
            vertical-align: top;
        }

        .tabla-meta .etiqueta {
            width: 70px;
            font-weight: bold;
            white-space: nowrap;
            padding-right: 15px;
        }

        .tabla-meta .contenido .cargo {
            font-size: 12pt;
            color: #222;
            display: block;
        }

        .cuerpo {
            font-size: 12pt;
            text-align: justify;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .cuerpo.primer-parrafo {
            border-top: 1px solid #000;
            padding-top: 12px;
            margin-top: 15px;
        }

        .seccion {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 14px;
            margin-bottom: 8px;
        }

        .datos-equipo {
            font-size: 12pt;
            line-height: 1.8;
            margin-bottom: 12px;
        }

        .lista-proc {
            font-size: 12pt;
            margin-left: 25px;
            margin-bottom: 14px;
            line-height: 1.7;
        }

        .lista-proc li {
            margin-bottom: 3px;
        }

        .caja-conclusion {
            padding: 8px 0;
            margin-bottom: 14px;
            font-size: 12pt;
            line-height: 1.5;
            text-align: justify;
        }

        .caja-conclusion p {
            margin-bottom: 6px;
        }

        .recomendaciones, .caja-texto {
            padding: 8px 0;
            margin-bottom: 14px;
            font-size: 12pt;
            line-height: 1.5;
            text-align: justify;
        }

        .evidencia-titulo {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 16px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .img-evidence {
            width: 100%;
            max-width: 480px;
            max-height: 600px;
            display: block;
            margin: 0 auto;
            border: 1px solid #bbb;
        }

        .placeholder {
            width: 100%;
            max-width: 480px;
            height: 320px;
            background: repeating-linear-gradient(
                45deg,
                #f0f0f0,
                #f0f0f0 10px,
                #e6e6e6 10px,
                #e6e6e6 20px
            );
            border: 1px solid #bbb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #555;
            font-size: 12pt;
            margin-bottom: 6px;
        }

        .placeholder.grande {
            height: 580px;
            background: #fafafa;
        }

        .caption {
            font-size: 12pt;
            font-style: italic;
            margin-top: 10px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .salto-pagina {
            page-break-after: always;
        }

        /* Botones flotantes */
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
            font-family: sans-serif;
        }
        .btn-pdf {
            position: fixed;
            top: 10px;
            right: 130px;
            padding: 10px 20px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
            text-decoration: none;
            font-family: sans-serif;
        }
        .btn-back {
            position: fixed;
            top: 10px;
            left: 10px;
            padding: 10px 20px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
            text-decoration: none;
            font-family: sans-serif;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .pagina {
                box-shadow: none;
                margin: 0;
                width: 100%;
            }
            .salto-pagina {
                border: none;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <a href="{{ route('admin.informes-impresora.show', $informe->id) }}" class="btn-back no-print">⬅ Volver al Informe</a>
    <button class="btn-print no-print" onclick="window.print()">🖨️ Imprimir</button>
    <a href="{{ route('admin.informes-impresora.pdf', $informe->id) }}" class="btn-pdf no-print" target="_blank">📥 Descargar PDF</a>

    @php
        $meses = ['January' => 'enero', 'February' => 'febrero', 'March' => 'marzo',
                  'April' => 'abril', 'May' => 'mayo', 'June' => 'junio',
                  'July' => 'julio', 'August' => 'agosto', 'September' => 'septiembre',
                  'October' => 'octubre', 'November' => 'noviembre', 'December' => 'diciembre'];
        $dia  = $informe->fecha_informe->format('d');
        $mes  = $meses[$informe->fecha_informe->format('F')];
        $anio = $informe->fecha_informe->format('Y');
        $oficinaNombre = $informe->impresora->oficina->nombre_oficina ?? 'Abancay';

        // Procedimientos como array
        $procs = $informe->procedimientos ?? [];
    @endphp

    <!-- PÁGINA 1 -->
    <div class="pagina">
        <div class="encabezado-cooperativa">
            <div class="logo-container">
                @if(is_file(public_path('img/andes.png')))
                    <img src="{{ asset('img/andes.png') }}" alt="Logo Los Andes">
                @else
                    <strong style="font-size:16pt">[LOGO LOS ANDES]</strong>
                @endif
            </div>
        </div>

        <div class="anio-gobierno">"Año de la Esperanza y el Fortalecimiento de la Democracia"</div>
        <div class="fecha-lugar">{{ $oficinaNombre }}, {{ $dia }} de {{ $mes }} de {{ $anio }}</div>

        <div class="titulo-informe">
            INFORME N° {{ str_pad($informe->id, 2, '0', STR_PAD_LEFT) }}-{{ $anio }}-JCG/UII/TI/COOPAC LOS ANDES
        </div>

        <!-- TABLA DE DESTINATARIOS -->
        <table class="tabla-meta">
            <tr>
                <td class="etiqueta">A</td>
                <td class="contenido">
                    Zenon Serrano Quispe
                    <span class="cargo">Jefe de Logística</span>
                </td>
            </tr>
            <tr>
                <td class="etiqueta">CC</td>
                <td class="contenido">
                    Helard Niño de Guzman Bedoya
                    <span class="cargo">Responsable de la Unidad de Patrimonio y Almacén</span>
                </td>
            </tr>
            <tr>
                <td class="etiqueta">De</td>
                <td class="contenido">
                    {{ $informe->tecnico_nombre }}
                    <span class="cargo">Asistente de Infraestructura Informática</span>
                </td>
            </tr>
            <tr>
                <td class="etiqueta">Asunto</td>
                <td class="contenido">Incidencia {{ strtolower($informe->tipo_informe) }} en la oficina {{ $oficinaNombre }}</td>
            </tr>
            <tr>
                <td class="etiqueta">Fecha</td>
                <td class="contenido">{{ $informe->fecha_informe->format('d/m/Y') }}</td>
            </tr>
        </table>

        @if($informe->descripcion_problema)
        <div class="cuerpo primer-parrafo">
            {!! nl2br(e($informe->descripcion_problema)) !!}
        </div>
        @else
        <div class="cuerpo primer-parrafo">
            Es grato dirigirme a usted para saludarlo cordialmente e informarle sobre la impresora multifuncional {{ $informe->impresora->marca_impresora }} {{ $informe->impresora->modelo_impresora }}, con número de serie {{ $informe->impresora->serie_impresora }}. Dicho equipo fue asignado a la oficina {{ $oficinaNombre }}.
        </div>
        @endif

        <div class="datos-equipo">
            <strong>Modelo:</strong> {{ $informe->impresora->modelo_impresora }}<br>
            <strong>Número de Serie:</strong> {{ $informe->impresora->serie_impresora }}<br>
            <strong>Lugar:</strong> {{ $oficinaNombre }}
        </div>

        @if($informe->incidencias)
        <div class="seccion">[Incidencias]</div>
        <div class="datos-equipo">{{ $informe->incidencias }}</div>
        @endif

        @if(count($procs) > 0)
        <div class="seccion">[Procedimientos]</div>
        <ul class="lista-proc">
            @foreach($procs as $paso)
                <li>{{ $paso }}</li>
            @endforeach
        </ul>
        @endif
    </div>

    <div class="salto-pagina"></div>

    <!-- PÁGINA 2 -->
    <div class="pagina">
        <div class="encabezado-cooperativa">
            <div class="logo-container">
                @if(is_file(public_path('img/andes.png')))
                    <img src="{{ asset('img/andes.png') }}" alt="Logo Los Andes">
                @else
                    <strong style="font-size:16pt">[LOGO LOS ANDES]</strong>
                @endif
            </div>
        </div>

        <div class="seccion">[Conclusión]</div>
        <div class="caja-conclusion">
            @if($informe->observaciones)
                @foreach(explode("\n", trim($informe->observaciones)) as $linea)
                    @if(trim($linea))
                    <p>{{ $linea }}</p>
                    @endif
                @endforeach
            @endif
            @if($informe->contador_copias)
            <p>
                Como antecedentes sobre las impresoras {{ $informe->impresora->marca_impresora }} al pasar las 25000 copias/impresiones empiezan a presentar fallos y en este caso el equipo presentó fallas a las {{ number_format($informe->contador_copias) }} copias/impresiones.
            </p>
            @endif
        </div>

        <div class="seccion">[Recomendaciones]</div>
        <div class="recomendaciones">
            @if($informe->recomendaciones)
                {!! nl2br(e($informe->recomendaciones)) !!}
            @else
                Sin recomendaciones registradas.
            @endif
        </div>
    </div>

    <!-- PÁGINAS DE EVIDENCIAS -->
    @if($informe->imagen_01_path)
    <div class="salto-pagina"></div>
    <div class="pagina">
        <div class="encabezado-cooperativa">
            <div class="logo-container">
                @if(is_file(public_path('img/andes.png')))
                    <img src="{{ asset('img/andes.png') }}" alt="Logo Los Andes">
                @else
                    <strong style="font-size:16pt">[LOGO LOS ANDES]</strong>
                @endif
            </div>
        </div>
        <div class="evidencia-titulo">[EVIDENCIA ATASCO DE PAPEL]</div>
        @if(is_file(storage_path('app/public/' . $informe->imagen_01_path)))
            <img src="{{ asset('storage/' . $informe->imagen_01_path) }}" class="img-evidence" alt="Evidencia 01">
        @else
            <div class="placeholder">[Imagen 01 no disponible]</div>
        @endif
        @if($informe->imagen_01_caption)
            <div class="caption">{{ $informe->imagen_01_caption }}</div>
        @endif
    </div>
    @endif

    @if($informe->imagen_02_path)
    <div class="salto-pagina"></div>
    <div class="pagina">
        <div class="encabezado-cooperativa">
            <div class="logo-container">
                @if(is_file(public_path('img/andes.png')))
                    <img src="{{ asset('img/andes.png') }}" alt="Logo Los Andes">
                @else
                    <strong style="font-size:16pt">[LOGO LOS ANDES]</strong>
                @endif
            </div>
        </div>
        <div class="evidencia-titulo">[Página de estado de la impresora]</div>
        @if(is_file(storage_path('app/public/' . $informe->imagen_02_path)))
            <img src="{{ asset('storage/' . $informe->imagen_02_path) }}" class="img-evidence placeholder grande" alt="Evidencia 02">
        @else
            <div class="placeholder grande">[Imagen 02 no disponible]</div>
        @endif
        @if($informe->imagen_02_caption)
            <div class="caption">{{ $informe->imagen_02_caption }}</div>
        @endif
    </div>
    @endif

</body>
</html>
