@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Registrar Nueva Incidencia</h2>
        <div>
            <a href="{{ route('admin.incidencias.listado') }}" class="btn btn-secondary">Ver Incidencias</a>
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

            <form action="{{ route('admin.incidencias.guardar') }}"
                method="POST"
                class="row g-3 needs-validation"
                novalidate>
                @csrf

                <div class="col-md-4">
                    <label class="form-label">Tipo de Asistencia</label>
                    <select name="tipo" class="form-select" required>
                        <option value="">Seleccione</option>
                        <option value="software">Software</option>
                        <option value="hardware">Hardware</option>
                        <option value="conectividad">Conectividad</option>
                    </select>
                    <div class="invalid-feedback">
                        Seleccione un tipo de asistencia.
                    </div>
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
                    <div class="invalid-feedback">
                        Seleccione un módulo.
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tipo de Problema</label>
                    <select name="problema" class="form-select" required>
                        <option value="">Seleccione un tipo primero</option>
                    </select>
                    <div class="invalid-feedback">
                        Seleccione el tipo de problema.
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Descripción del problema</label>
                    <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                    <div class="invalid-feedback">
                        Ingrese la descripción del problema.
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Solución aplicada</label>
                    <textarea name="solucion" class="form-control" rows="3" required></textarea>
                    <div class="invalid-feedback">
                        Ingrese la solución aplicada.
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Usuario afectado</label>
                    <input type="text" name="usuario_afectado" class="form-control" required>
                    <div class="invalid-feedback">
                        Ingrese el usuario afectado.
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Agencia</label>
                    <select name="agencia" id="agencia" class="form-select" required>
                        <option value="">Seleccione</option>
                        <option value="99 Soporte Administrativo">99 Soporte Administrativo</option>
                        <option value="101 Chalhuanca">101 Chalhuanca</option>
                        <option value="102 Andahuaylas">102 Andahuaylas</option>
                        <option value="103 Uripa">103 Uripa</option>
                        <option value="104 Antabamba">104 Antabamba</option>
                        <option value="105 Tintay">105 Tintay</option>
                        <option value="106 Huaccana">106 Huaccana</option>
                        <option value="107 Lima">107 Lima</option>
                        <option value="108 Huancarama">108 Huancarama</option>
                        <option value="109 Abancay">109 Abancay</option>
                        <option value="110 Grau">110 Grau</option>
                        <option value="111 Cotabambas">111 Cotabambas</option>
                        <option value="112 Curahuasi">112 Curahuasi</option>
                        <option value="113 Secclla">113 Secclla</option>
                        <option value="114 Cusco">114 Cusco</option>
                        <option value="115 Santo Tomás">115 Santo Tomás</option>
                        <option value="116 Pampa Cangallo">116 Pampa Cangallo</option>
                        <option value="117 Huamanga">117 Huamanga</option>
                        <option value="118 Pampas Tayacaja">118 Pampas Tayacaja</option>
                        <option value="119 Huancayo">119 Huancayo</option>
                        <option value="120 Urubamba">120 Urubamba</option>
                        <option value="121 Combapata">121 Combapata</option>
                        <option value="122 La Merced">122 La Merced</option>
                        <option value="123 San Juan de Miraflores">123 S.J. Miraflores</option>
                        <option value="124 San Juan de Lurigancho">124 S.J. Lurigancho</option>
                        <option value="125 Nueva Esperanza">125 Nueva Esperanza</option>
                        <option value="126 Ocampo">126 Ocampo</option>
                        <option value="127 Cañete">127 Cañete</option>
                    </select>

                    <!-- CAMPO OCULTO PARA GUARDAR EL NOMBRE -->
                    <input type="hidden" name="agencia_nombre" id="agencia_nombre">

                    <div class="invalid-feedback">
                        Seleccione una agencia.
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sub Agencia</label>
                    <select name="sub_agencia" id="sub_agencia" class="form-select" required>
                        <option value="">Seleccione una agencia primero</option>
                    </select>
                    <div class="invalid-feedback">
                        Seleccione una sub agencia.
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Prioridad</label>
                    <select name="prioridad" class="form-select" required>
                        <option value="">Seleccione</option>
                        <option value="Alta" selected>Alta</option>
                        <option value="Media">Media</option>
                        <option value="Baja">Baja</option>
                    </select>
                    <div class="invalid-feedback">
                        Seleccione la prioridad.
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select" required>
                        <option value="">Seleccione</option>
                        <option value="Atendido" selected>Atendido</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Derivado">Derivado</option>
                    </select>
                    <div class="invalid-feedback">
                        Seleccione el estado.
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit"
                            class="btn btn-primary w-100"
                            data-swal
                            data-title="¿Guardar incidencia?"
                            data-text="Verifica que la información sea correcta"
                            data-confirm="Sí, guardar">
                        Guardar Incidencia
                    </button>
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
            "ERROR DE INICIO SESION CLOUD",
            "NAVEGADOR DE INTERNET"
        ],
        hardware: [
            "ATASCO PAPEL IMPRESORA",
            "PROBLEMA CONFIGURACION IMPRESORA",
            "ERROR DE ESCANEO IMPRESORA",
            "ERROR DE COMPONENTE EQUIPO COMPUTO",
            "ERROR DE CONFIGURACION BIOMETRICO",
            "ERROR DE CONFIGURACION TOTEM",
            "IMPRESORA TERMICA",
            "ALMACENAMIENTO LLENO",
            "PUERTO USB BLOQUEADO"
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
        "99 Soporte Administrativo": ['TI', 'Operaciones', 'Contabilidad', 'Logística', 'Comunicaciones','Asesría Legal','Riesgos','Procesos','Planificación','Tesorería','Subgerencia', 'Servicios Financieros', 'Talento Humano','Servicios Complementarios','Financial','Auditoría Interna'],
        "101 Chalhuanca": ['Chalhuanca', 'Huancapampa','Cotaruse', 'Chacapuente', 'Socco', 'Pachaconas', 'Tampumayu'],
        "102 Andahuaylas": ['Andahuaylas','Talavera','Champacocha','Pacucha', 'Andarapa','Kaquiabamba', 'Soras','Pampachiri','Jose María Arguedas','Turpo','Huancaray'],
        "103 Uripa": ['Uripa', 'Ahuayro', 'Cocharcas', 'Uranmarca', 'Chumbes' ,'Ranracancha'],
        "104 Antabamba": ['Antabamba', 'Mollebamba', 'Huaquirca'],
        "105 Tintay": ['Tintay', 'Casinchihua'],
        "106 Huaccana": ['Huaccana','Chungui','Rocchacc','Ocobamba'],
        "107 Lima": ['San Isidro'],
        "108 Huancarama": ['Huancarama','Pacobamba','Kishuara','Matapuquio','San Fernando','San Martin','Amaybamba','Pucyura'],
        "109 Abancay": ['Abancay','Huanipaca','Tacmara'],
        "110 Grau": ['Chuquibambilla','Vilcabamba','Totora Oropesa'],
        "111 Cotabambas": ['Challhuahuacho','Haquira','Mara','Tambobamba','Cotabambas','Coyllurqui','Progreso'],
        "112 Curahuasi": ['Curahuasi','Cachora','Limatambo','Mollepata'],
        "113 Secclla": ['Secclla','Congalla','Lircay','Acobamba','Antaparco'],
        "114 Cusco": ['Cusco','Ocongate','Pillcopata','Anta'],
        "115 Santo Tomás": ['Santo Tomás','Velille','Pulpera','Colquemarca','Quiñota'],
        "116 Pampa Cangallo": ['Pampa Cangallo','Vilcas Huamán','Chuschi','Huanca Sancos','Huancapi'],
        "117 Huamanga": ['Huamanga','Tambo La Mar','Tambillo','Acos Vinchos','Vinchos','Pampamarca','Ccayarpachi'],
        "118 Pampas Tayacaja": ['Pampas Tayacaja','Paucarbamba','Colcabamba','Inyac'],
        "119 Huancayo": ['Huancayo','Lampa','S.M. de Rocchacc','Pichus'],
        "120 Urubamba": ['Urubamba','Chinchero','Calca'],
        "121 Combapata": ['Combapata','Pomacanchi','Acomayo', 'Accha'],
        "122 La Merced": ['La Merced','Yurinaki'],
        "123 San Juan de Miraflores": ['S.J. Miraflores'],
        "124 San Juan de Lurigancho": ['S.J. de Lurigancho'],
        "125 Nueva Esperanza": ['Nueva Esperanza','Cascabamba'],
        "126 Ocampo": ['Ocampo','Lambrama','Paccaypata'],
        "127 Cañete": ['Cañete']
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
<script>
(() => {
  'use strict'

  const forms = document.querySelectorAll('.needs-validation')

  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})()
</script>

@endsection