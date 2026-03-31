export function initSelect2() {
    $(function () {
        if ($.fn.select2) {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,
                placeholder: 'Todas las oficinas'
            });
        }
    });
}
