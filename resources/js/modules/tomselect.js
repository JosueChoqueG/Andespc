import TomSelect from "tom-select";

export function initTomSelect() {
    document.querySelectorAll('.tomselect').forEach(el => {
        if (!el.tomselect) {
            new TomSelect(el, {
                placeholder: el.dataset.placeholder || 'Seleccione una opción',
                allowEmptyOption: true,
                plugins: ['clear_button'],
                create: false
            });
        }
    });
}
