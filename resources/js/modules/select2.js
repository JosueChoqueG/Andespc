export function initSelect2() {
    if (window.$ && typeof $.fn.select2 === 'function') {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true,
            placeholder: 'Todas las oficinas'
        });
    } else {
        console.error('Select2 o jQuery no están cargados');
    }
}
