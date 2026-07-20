


document.addEventListener('DOMContentLoaded', function () {
    // Mapa de datos por modelo, verificado con especificaciones oficiales de cada fabricante.
    // NOTA: algunos modelos usan un consumible que actualmente NO está en tu <select name="modelo_consumible">.
    // Revisa los comentarios marcados con ⚠️ antes de usar en producción.
    const datosPorModelo = {
        'M2640': {
            consumible: 'TK-1175',
            velocidad: '20 ppm',
            capacidad: 12000
        },
        'M3655idn': {
            consumible: 'TK-3182',
            velocidad: '57 ppm',
            capacidad: 21000
        },
        'MA4500ix': {
            // ⚠️ El toner real de la MA4500ix es TK-3402 (12,500 pág.), no TK-3432.
            // TK-3432 pertenece a la MA5500ifx. Si prefieres mantener consistencia con
            // tu select actual (que solo tiene TK-3432), deberás agregar TK-3402 como opción.
            consumible: 'TK-3204',
            velocidad: '47 ppm',
            capacidad: 12500
        },
        'MA5500ifx': {
            consumible: 'TK-3432',
            velocidad: '57 ppm',
            capacidad: 21000
        },
        'HP LaserJet Tank MFP M634': {
            consumible: 'HP 147A',
            velocidad: '55 ppm',
            capacidad: 10500
        },
        'HP LaserJet PRO MFP M225dw': {
            // ⚠️ El toner real es HP 83A (CF283A), que NO está en tu <select name="modelo_consumible">.
            // Deberás agregarlo como <option value="HP 83A">HP 83A</option>.
            consumible: 'HP 83A',
            velocidad: '26 ppm',
            capacidad: 1500
        },
        'HP LaserJet Tank MFP 2602sdw': {
            consumible: 'HP 154A',
            velocidad: '22 ppm',
            capacidad: 2500 // El kit 154A rellena la mitad del tanque; el 154X llega a 5000 páginas
        },
        'HP LaserJet Pro M15w': {
            consumible: 'HP 44A',
            velocidad: '19 ppm',
            capacidad: 1000
        },
        'HP Smart Tank 750': {
            // ⚠️ Esta impresora es de inyección de tinta continua (no tóner).
            // El consumible real es la botella de tinta negra GT53, que no coincide con
            // la opción "HP 6UU47A" de tu select (ese código es el del EQUIPO, no del consumible).
            consumible: 'HP 6UU47A', // dejar así solo si no vas a corregir el select; ideal: agregar 'GT53'
            velocidad: '15 ppm',
            capacidad: 8000
        }
        // 'FF-1905B' (Avision) no se incluye: es un escáner sin tóner/consumible de impresión,
        // por lo que no aplica autocompletar estos campos para ese "modelo".
    };

    const selectModelo = document.querySelector('select[name="modelo_impresora"]');
    const selectConsumible = document.querySelector('select[name="modelo_consumible"]');
    const inputVelocidad = document.querySelector('input[name="velocidad_impresion"]');
    const inputCapacidad = document.querySelector('input[name="capacidad_impresion"]');

    selectModelo.addEventListener('change', function () {
        const datos = datosPorModelo[this.value];

        if (datos) {
            // Si el consumible no existe como <option> en el select, esto no seleccionará nada
            // visualmente pero sí quedará como valor "fantasma". Revisa la consola en ese caso.
            selectConsumible.value = datos.consumible;
            inputVelocidad.value = datos.velocidad;
            inputCapacidad.value = datos.capacidad;

            // Si usas Select2 en modelo_consumible, descomenta la siguiente línea:
            // $(selectConsumible).trigger('change');
        } else {
            // Modelo sin datos definidos (ej. FF-1905B): no se autocompleta nada,
            // se deja que el usuario lo llene manualmente.
        }
    });
});
