import Swal from 'sweetalert2';

export function confirmDelete(form, message) {
    Swal.fire({
        title: 'Confirma a exclusão?',
        html: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Excluir',
        cancelButtonText: 'Cancelar',
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-danger me-2',
            cancelButton: 'btn btn-secondary'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}