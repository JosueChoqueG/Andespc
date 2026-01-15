@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Registrar Nueva Incidencia</h2>
        <div>
            <a href="{{ route('admin.incidencias.listado') }}" class="btn btn-secondary">Ver Incidencias</a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-danger">Salir</button>
            </form>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.incidencias.guardar') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tipo de Asistencia</label>
                        <select name="tipo" class="form-select" required>
                            <option value="">Seleccione</option>
                            <option value="software">Software</option>
                            <option value="hardware">Hardware</option>
                            <option value="conectividad">Conectividad</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Módulo</label>
                        <select name="modulo" class="form-select" required>
                            <option value="">Seleccione</option>
                            <option value="caja">Caja</option>
                            <option value="creditos">Créditos</option>
                            <option value="administracion">Administración</option>
                            <option value="soporte administrativo">Soporte Administrativo</option>
                            <option value="OTROS">OTROS</option>
                            <option value="plataforma">Plataforma</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tipo de Problema</label>
                        <select name="problema" class="form-select" required>
                            <option value="">Seleccione un tipo primero</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Descripción del problema</label>
                        <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Solución aplicada</label>
                        <textarea name="solucion" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Usuario afectado</label>
                        <input type="text" name="usuario_afectado" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Agencia</label>
                        <select name="agencia" class="form-select" required>
                            <option value="">Seleccione</option>
                            <option>99 Soporte Administrativo</option>
                            <option>101 Chalhuanca</option>
                            <option>102 Andahuaylas</option>
                            <option>103 Uripa</option>
                            <option>104 Antabamba</option>
                            <option>105 Tintay</option>
                            <option>106 Huaccana</option>
                            <option>107 Lima</option>
                            <option>108 Huancarama</option>
                            <option>109 Abancay</option>
                            <option>110 Grau</option>
                            <option>111 Cotabambas</option>
                            <option>112 Curahuasi</option>
                            <option>113 Secclla</option>
                            <option>114 Cusco</option>
                            <option>115 SantoTomas</option>
                            <option>116 Pampa Cangallo</option>
                            <option>117 Huamanga</option>
                            <option>118 Pampas Tayacaja</option>
                            <option>119 Huancayo</option>
                            <option>120 Urubamba</option>
                            <option>121 Combapata</option>
                            <option>122 La Merced</option>
                            <option>123 SJMiraflores</option>
                            <option>124 SJLurigancho</option>
                            <option>125 Nueva Esperanza</option>
                            <option>126 Ocampo</option>
                            <option>127 Cañete</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Sub Agencia</label>
                        <input type="text" name="sub_agencia" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Prioridad</label>
                        <select name="prioridad" class="form-select" required>
                            <option>Alta</option>
                            <option>Media</option>
                            <option>Baja</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select" required>
                            <option>Pendiente</option>
                            <option>Derivado</option>
                            <option>Atendido</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">Guardar Incidencia</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const problemas = {
        software: [
            "USUARIO SFI BLOQUEADO",
            "USUARIO SE OLVIDO LA CONTRASEÑA SFI",
            "DESCONFIGURACION SFI",
            "USUARIO SFI LOGGEADO",
            "ERROR DE INCIO SESION SFI",
            "ERROR DE FUNCIONALIDAD SFI",
            "CAIDA DEL SERVICIO SFI",
            "FINANCIAL",
            "DESCONFIGURACIÓN FINANCIAL",
            "USUARIO SIEPA BLOQUEADO",
            "USUARIO SE OLVIDO LA CONTRASEÑA SIEPA",
            "ERROR DE INICIO SESION SIEPA",
            "ERROR DE FUNCIONALIDAD SIEPA",
            "CAIDA DEL SERVICIO SIEPA",  
            "USUARIO BLOQUEADO SENTINEL",
            "USUARIO SE OLVIDO CONTRASEÑA SENTINEL",
            "ERROR DE FUNCIONALIDAD SENTINEL",
            "ERROR DE INICIO SESION SENTINEL",
            "ERROR DE INICIO SESION CARPETA COMPARTIDA",
            "ERROR AL CARGAR BAUCHER DIGITALIZADO",
            "USUARIO CORREO BLOQUEADO",
            "USUARIO SE OLVIDO CONTRASEÑA CORREO",
            "ERROR DE FUNCIONALIDAD CORREO",       
            "ERROR DE INICIO SESION CORREO",
            "ERROR DE ACTIVCACION ANTIVIRUS ESET",
            "USUARIO CLOUD BLOQUEADO",
            "USUARIO SE OLVIDO CONTRASEÑA CLOUD",
            "ERROR DE FUNCIONALIDAD CLOUD",
            "ERROR DE INICIO SESION CLOUD"
        ],
        hardware: [
            "ATASCO PAPEL IMPRESORA",
            "PROBLEMA CONFIGURACION IMPRESORA",
            "ERROR DE ESCANEO IMPRESORA",
            "ERROR DE COMPONENTE EQUIPO COMPUTO",
            "ERROR DE CONFIGURACION BIOMETRICO",
            "ERROR DE CONFIGURACION TOTEM",
            "IMPRESORA TERMICA",
            "ALMACENAMIENTO LLENO"
        ],
        conectividad: [
            "DESCONFIGURACION DE TOTEM",
            "ERROR DE CONEXION CERTIFICADO VPN",
            "ERORO DE CONECTIVIDAD RED",
            "ACTIVACION O CONFIGURACION DE VPN",
            "CONECTIVIDAD GENERAL",
            "SIN ENERGÍA ELÉCTRICA",
            "SIN ACCESO A INTERNET",
            "INTALACION/ERROR DE ANTIVIRUS",
            "CARPETAS COMPARTIDAS",
            "CLOUD LA NUBE"
        ]
    };

    document.addEventListener("DOMContentLoaded", () => {
        const tipoSelect = document.querySelector("select[name='tipo']");
        const problemaSelect = document.querySelector("select[name='problema']");

        tipoSelect.addEventListener("change", () => {
            const tipo = tipoSelect.value;
            problemaSelect.innerHTML = '<option value="">Seleccione</option>';

            if (problemas[tipo]) {
                problemas[tipo].forEach(p => {
                    const option = document.createElement("option");
                    option.value = p;
                    option.textContent = p;
                    problemaSelect.appendChild(option);
                });
            }
        });
    });
</script>
@endsection