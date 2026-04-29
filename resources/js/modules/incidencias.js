document.addEventListener("DOMContentLoaded", () => {

    const $ = (selector) => document.querySelector(selector);

    const tipoSelect = $("select[name='tipo']");
    const problemaSelect = $("select[name='problema']");
    const agenciaSelect = $("#agencia");
    const subAgenciaSelect = $("#sub_agencia");

    const problemas = { 
        software: [
            "USUARIO SFI BLOQUEADO",
            "USUARIO SE OLVIDO LA CONTRASEÑA FINANCIAL",
            "DESCONFIGURACION FINANCIAL",
            "ERROR DE INCIO SESION FINANCIAL",
            "ERROR DE FUNCIONALIDAD FINANCIAL",
            "PARAMETROS DE SEGURIDAD FINANCIAL",
            "USUARIO FINANCIAL",
            "CAIDA DEL SERVICIO FINANCIAL",
            "NO ACTUALIZA FINANCIAL",
            "USUARIO SIEPA BLOQUEADO",
            "USUARIO SE OLVIDO LA CONTRASEÑA SIEPA",
            "ERROR DE INICIO SESION SIEPA",
            "ERROR DE FUNCIONALIDAD SIEPA",
            "CAIDA DEL SERVICIO SIEPA", 
            "REPORTEADOR",
            "USUARIO BLOQUEADO SENTINEL",
            "USUARIO SE OLVIDO CONTRASEÑA SENTINEL",
            "ERROR DE INICIO SESION CARPETA COMPARTIDA",
            "ERROR AL CARGAR BAUCHER DIGITALIZADO",
            "USUARIO CORREO BLOQUEADO",
            "USUARIO SE OLVIDO CONTRASEÑA CORREO",
            "FIRMA DE USUARIO SIN CONFIGURAR EN CORREO",
            "ALMACENAMIENTO DE CORREO",
            "ERROR DE FUNCIONALIDAD CORREO",       
            "ERROR DE INICIO SESION CORREO",
            "ERROR DE ACTIVCACION ANTIVIRUS ESET",
            "USUARIO CLOUD BLOQUEADO",
            "USUARIO SE OLVIDO CONTRASEÑA CLOUD",
            "ERROR DE FUNCIONALIDAD CLOUD",
            "ERROR DE INICIO SESION CLOUD",
            "NAVEGADOR DE INTERNET",
            "ANYDESK",
            "WINDOWS",
            "ANDROID"
        ],
        hardware: [
            "TECLADO",
            "IMPRESORA",
            "MINI IMPRESORA",
            "ERROR DE ESCANEO IMPRESORA",
            "ERROR DE COMPONENTE EQUIPO COMPUTO",
            "ERROR DE CONFIGURACION BIOMETRICO",
            "ERROR DE CONFIGURACION TOTEM",
            "IMPRESORA TERMICA",
            "ESCANEADOR DE VOUCHERS",
            "ALMACENAMIENTO LLENO",
            "PUERTO USB BLOQUEADO",
            "LECTOR DE HUELLAS",
            "CELULAR RPC"
        ],
        conectividad: [
            "DESCONFIGURACION DE TOTEM",
            "CERTIFICADO VPN",
            "CONECTIVIDAD RED",
            "CONFIGURACION DE VPN",
            "CONECTIVIDAD GENERAL",
            "ENERGÍA ELÉCTRICA",
            "ACCESO A INTERNET",
            "INTALACION/ERROR DE ANTIVIRUS",
            "CARPETAS COMPARTIDAS",
            "CLOUD LA NUBE"
        ] };
    const subAgencias = {
        "Empresas de coopac": ['Ayni Andes', 'Incoop','Servisur Andino' ],
        "99 Soporte Administrativo": ['TI', 'Operaciones', 'Contabilidad', 'Logística', 'Comunicaciones','Asesoría Legal','Riesgos','Procesos','Planificación','Tesorería','Subgerencia', 'Servicios Financieros', 'Talento Humano','Servicios Complementarios','Auditoría Interna','Oficial de Cumplimiento'],
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
        "113 Secclla": ['Secclla','Congalla','Lircay','Acobamba','Paucará'],
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
        "125 Nueva Esperanza": ['Nueva Esperanza','Cascabamba','Chicmo'],
        "126 Ocampo": ['Ocampo','Lambrama','Paccaypata','Tamburco'],
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
