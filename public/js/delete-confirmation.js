// Delete confirmation script
document.addEventListener('DOMContentLoaded', function() {
    // Get the delete button in the "danger zone" card
    const deleteBtn = document.querySelector('.btn-danger[data-bs-toggle="modal"]');
    
    // Get the delete form in the modal
    const deleteForm = document.querySelector('#deleteModal form');
    
    // Get the submit button in the modal
    const confirmDeleteBtn = document.querySelector('#deleteModal .btn-danger');
    
    // Add event listener to the submit button
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (deleteForm) {
                deleteForm.submit();
            }
        });
    }
    
    // For direct delete button outside the modal
    const directDeleteBtn = document.querySelector('.delete-offer-btn');
    if (directDeleteBtn) {
        directDeleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Êtes-vous sûr de vouloir supprimer cette offre? Cette action est irréversible et entraînera la suppression de toutes les candidatures associées.')) {
                const form = document.querySelector('#delete-offer-form');
                if (form) {
                    form.submit();
                }
            }
        });
    }
});