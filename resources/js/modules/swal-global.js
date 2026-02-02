document.addEventListener('DOMContentLoaded', () => {

    document.addEventListener('click', function (e) {

        const btn = e.target.closest('[data-swal]');
        if (!btn) return;

        e.preventDefault();

        const form = btn.closest('form');

        const title = btn.dataset.title || '¿Estás seguro?';
        const text = btn.dataset.text || 'Confirma la acción';
        const icon = btn.dataset.icon || 'question';
        const confirmText = btn.dataset.confirm || 'Aceptar';
        const cancelText = btn.dataset.cancel || 'Cancelar';

        Swal.fire({
            title,
            text,
            icon,
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            customClass: {
                confirmButton: 'btn btn-primary me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {

            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Procesando...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            if (form) {
                form.submit();
            }
        });
    });

});
