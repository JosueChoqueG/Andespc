document.addEventListener("DOMContentLoaded", () => {

    const $ = (selector) => document.querySelector(selector);

    const tipoSelect = $("select[name='tipo']");
    const problemaSelect = $("select[name='problema']");
    const agenciaSelect = $("#agencia");
    const subAgenciaSelect = $("#sub_agencia");

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
        ] };
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

    tipoSelect?.addEventListener("change", () => {
        cargarOpciones(problemaSelect, problemas[tipoSelect.value]);
    });

    agenciaSelect?.addEventListener("change", () => {
        cargarOpciones(subAgenciaSelect, subAgencias[agenciaSelect.value]);
    });

    function cargarOpciones(select, opciones = []) {
        // Limpiar opciones
        select.innerHTML = "";

        opciones.forEach((op, index) => {
            const option = document.createElement("option");
            option.value = op;
            option.textContent = op; // textContent evita XSS

            // Seleccionar el primer valor
            if (index === 0) option.selected = true;

            select.appendChild(option);
        });
    }
});
