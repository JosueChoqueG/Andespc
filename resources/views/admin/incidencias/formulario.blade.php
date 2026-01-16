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
                        <select name="agencia" id="agencia" class="form-select" required>
                            <option value="">Seleccione</option>
                            <option value="99">99 Soporte Administrativo</option>
                            <option value="101">101 Chalhuanca</option>
                            <option value="102">102 Andahuaylas</option>
                            <option value="103">103 Uripa</option>
                            <option value="104">104 Antabamba</option>
                            <option value="105">105 Tintay</option>
                            <option value="106">106 Huaccana</option>
                            <option value="107">107 Lima</option>
                            <option value="108">108 Huancarama</option>
                            <option value="109">109 Abancay</option>
                            <option value="110">110 Grau</option>
                            <option value="111">111 Cotabambas</option>
                            <option value="112">112 Curahuasi</option>
                            <option value="113">113 Secclla</option>
                            <option value="114">114 Cusco</option>
                            <option value="115">115 SantoTomas</option>
                            <option value="116">116 Pampa Cangallo</option>
                            <option value="117">117 Huamanga</option>
                            <option value="118">118 Pampas Tayacaja</option>
                            <option value="119">119 Huancayo</option>
                            <option value="120">120 Urubamba</option>
                            <option value="121">121 Combapata</option>
                            <option value="122">122 La Merced</option>
                            <option value="123">123 SJMiraflores</option>
                            <option value="124">124 SJLurigancho</option>
                            <option value="125">125 Nueva Esperanza</option>
                            <option value="126">126 Ocampo</option>
                            <option value="127">127 Cañete</option>
                        </select>
                    </div>

                    <!-- <div class="col-md-4">
                        <label class="form-label">Sub Agencia</label>
                        <input type="text" name="sub_agencia" class="form-control">
                    </div> -->
                    <div class="col-md-4">
                        <label class="form-label">Sub Agencia</label>
                        <select name="sub_agencia" id="sub_agencia" class="form-select" required>
                            <option value="">Seleccione una agencia primero</option>
                        </select>
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
document.addEventListener("DOMContentLoaded", () => {

    /* =========================
       PROBLEMAS POR TIPO
    ==========================*/
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
            "ERROR DE CONECTIVIDAD RED",
            "ACTIVACION O CONFIGURACION DE VPN",
            "CONECTIVIDAD GENERAL",
            "SIN ENERGÍA ELÉCTRICA",
            "SIN ACCESO A INTERNET",
            "INTALACION/ERROR DE ANTIVIRUS",
            "CARPETAS COMPARTIDAS",
            "CLOUD LA NUBE"
        ]
    };

    const tipoSelect = document.querySelector("select[name='tipo']");
    const problemaSelect = document.querySelector("select[name='problema']");

    if (tipoSelect && problemaSelect) {
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
    }

    /* =========================
       AGENCIA / SUB AGENCIA
    ==========================*/
    const subAgencias = {
        99: ['TI', 'Operaciones', 'Contabilidad', 'logística', 'Comunicaciones'],
        101: ['Chalhuanca', 'Cotaruse', 'Chacapuente', 'Socco', 'Pachaconas', 'Tampumayu'],
        102: ['Talavera', 'Andarapa','Kaquiabamba', 'Soras', 'Pampachiri'],
        103: ['Uripa', 'Ahuayro', 'Cocharcas', 'Uranmarca', 'Chumbes' ,'ranracancha'],
        104: ['Yanaca', 'Ayrihuanca'],
    };

    const agenciaSelect = document.getElementById('agencia');
    const subAgenciaSelect = document.getElementById('sub_agencia');

    if (agenciaSelect && subAgenciaSelect) {
        agenciaSelect.addEventListener('change', () => {
            const agenciaId = agenciaSelect.value;
            subAgenciaSelect.innerHTML = '<option value="">Seleccione</option>';

            if (subAgencias[agenciaId]) {
                subAgencias[agenciaId].forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub;
                    option.textContent = sub;
                    subAgenciaSelect.appendChild(option);
                });
            }
        });
    }

});
</script>

@endsection