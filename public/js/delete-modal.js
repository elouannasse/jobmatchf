/**
 * Delete confirmation modal handling
 */
document.addEventListener("DOMContentLoaded", function() {
    // Get modal elements
    const modal = document.getElementById('deleteModal');
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const cancelBtn = document.getElementById('cancelDelete');
    const confirmBtn = document.getElementById('confirmDelete');
    const modalBackdrop = document.querySelector('.modal-backdrop');
    let deleteForm = null;

    // Function to open modal with correct form action
    function openModal(event) {
        event.preventDefault();
        
        // Get the form associated with the clicked delete button
        deleteForm = this.closest('form');
        
        // Get user information for confirmation message if available
        const userName = deleteForm.getAttribute('data-user-name') || 'cet utilisateur';
        const confirmationMessage = document.getElementById('confirmationMessage');
        if (confirmationMessage) {
            confirmationMessage.textContent = `Êtes-vous sûr de vouloir supprimer ${userName}?`;
        }
        
        // Show modal
        if (modal) {
            modal.classList.add('show');
            modal.style.display = 'block';
            
            // Create backdrop if it doesn't exist
            if (!modalBackdrop) {
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                document.body.appendChild(backdrop);
            } else {
                modalBackdrop.classList.add('show');
                modalBackdrop.style.display = 'block';
            }
            
            // Prevent body scrolling
            document.body.classList.add('modal-open');
            document.body.style.overflow = 'hidden';
            document.body.style.paddingRight = '15px';
        }
    }

    // Function to close modal
    function closeModal() {
        if (modal) {
            modal.classList.remove('show');
            modal.style.display = 'none';
            
            // Remove backdrop
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.classList.remove('show');
                document.body.removeChild(backdrop);
            }
            
            // Restore body scrolling
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }
    }

    // Function to submit delete form
    function confirmDelete() {
        if (deleteForm) {
            deleteForm.submit();
        }
        closeModal();
    }

    // Attach event listeners
    if (deleteButtons) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', openModal);
        });
    }

    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeModal);
    }

    if (confirmBtn) {
        confirmBtn.addEventListener('click', confirmDelete);
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    // Close modal with escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
});
