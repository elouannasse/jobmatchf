/**
 * Delete confirmation modal handling
 */
document.addEventListener("DOMContentLoaded", function() {
    // Get modal elements
    const deleteButtons = document.querySelectorAll('.delete-btn');
    let deleteForm = null;
    
    // Simple direct approach without relying on Bootstrap JS
    function openModal(event) {
        event.preventDefault();
        
        // Get the form associated with the clicked delete button
        deleteForm = this.closest('form');
        
        // Get user information for confirmation message
        const userName = deleteForm.getAttribute('data-user-name') || 'cet utilisateur';
        
        // Update the modal content directly with jQuery
        $('#confirmationMessage').text(`Êtes-vous sûr de vouloir supprimer l'utilisateur ${userName} ?`);
        $('#deleteModalLabel').text('Confirmer la suppression');
        
        // Show the modal with jQuery
        $('#deleteModal').modal('show');
    }
    
    // Function to submit delete form
    function confirmDelete() {
        if (deleteForm) {
            deleteForm.submit();
        }
        $('#deleteModal').modal('hide');
    }
    
    // Attach event listeners to all delete buttons using jQuery for maximum compatibility
    $(document).on('click', '.delete-btn', openModal);
    
    // Attach event listener to cancel button
    $('#cancelDelete').on('click', function() {
        $('#deleteModal').modal('hide');
    });
    
    // Attach event listener to close button
    $('#closeModalX').on('click', function() {
        $('#deleteModal').modal('hide');
    });
    
    // Attach event listener to confirm button
    $('#confirmDelete').on('click', confirmDelete);
});
