// Direct delete script - handles delete actions without relying on modals
document.addEventListener('DOMContentLoaded', function() {
    // Get all delete buttons in the action column
    const deleteButtons = document.querySelectorAll('.action-btn-delete');
    
    // For each delete button, attach a click handler
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Get the offre ID from the data attribute
            const offreId = this.getAttribute('data-offre-id');
            
            // Confirm deletion
            if (confirm('Êtes-vous sûr de vouloir supprimer cette offre? Cette action est irréversible.')) {
                // Find the associated form
                const form = document.getElementById('delete-form-' + offreId);
                if (form) {
                    form.submit();
                } else {
                    // Fallback method in case the form isn't found
                    // Create a form element
                    const newForm = document.createElement('form');
                    newForm.method = 'POST';
                    newForm.action = '/offres/' + offreId;
                    newForm.style.display = 'none';
                    
                    // Add CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    
                    // Add method field
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    
                    // Add inputs to form
                    newForm.appendChild(csrfInput);
                    newForm.appendChild(methodInput);
                    
                    // Add form to document, submit it, and remove it
                    document.body.appendChild(newForm);
                    newForm.submit();
                    document.body.removeChild(newForm);
                }
            }
        });
    });
});
