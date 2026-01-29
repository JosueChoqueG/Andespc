import Swal from 'sweetalert2';

export function confirmDelete(form) {
    Swal.fire({
        title: '¿Eliminar?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}