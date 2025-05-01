// Fix for modals that might be displayed but have unclickable buttons
document.addEventListener('DOMContentLoaded', function() {
    // Create a style element and append it to the head
    const styleElement = document.createElement('style');
    styleElement.textContent = `
        .modal-fix-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
        }
        
        .modal-fix-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 90%;
            width: 500px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .modal-fix-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .modal-fix-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
        }
        
        .modal-fix-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            color: #5a5c69;
        }
        
        .modal-fix-body {
            margin-bottom: 20px;
        }
        
        .modal-fix-warning {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        
        .modal-fix-warning i {
            color: #f6c23e;
            font-size: 1.5rem;
        }
        
        .modal-fix-warning-text {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .modal-fix-danger-text {
            color: #e74a3b;
            margin: 0;
        }
        
        .modal-fix-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .modal-fix-btn {
            padding: 8px 16px;
            border-radius: 5px;
            border: none;
            font-weight: 600;
            cursor: pointer;
        }
        
        .modal-fix-btn-cancel {
            background-color: #858796;
            color: white;
        }
        
        .modal-fix-btn-danger {
            background-color: #e74a3b;
            color: white;
        }
    `;
    document.head.appendChild(styleElement);
    
    // Create the markup for our custom modal
    const modalMarkup = `
        <div id="modalFixOverlay" class="modal-fix-overlay">
            <div class="modal-fix-content">
                <div class="modal-fix-header">
                    <h5 class="modal-fix-title">Confirmer la suppression</h5>
                    <button type="button" class="modal-fix-close" id="modalFixClose">&times;</button>
                </div>
                <div class="modal-fix-body">
                    <div class="modal-fix-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <p class="modal-fix-warning-text">Êtes-vous sûr de vouloir supprimer cette offre ?</p>
                            <p class="modal-fix-danger-text">Cette action est irréversible.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-fix-footer">
                    <button type="button" class="modal-fix-btn modal-fix-btn-cancel" id="modalFixCancel">Annuler</button>
                    <button type="button" class="modal-fix-btn modal-fix-btn-danger" id="modalFixDelete">Supprimer</button>
                </div>
            </div>
        </div>
    `;
    
    // Add the modal markup to the end of the body
    document.body.insertAdjacentHTML('beforeend', modalMarkup);
    
    // Get references to our custom modal elements
    const modalFixOverlay = document.getElementById('modalFixOverlay');
    const modalFixClose = document.getElementById('modalFixClose');
    const modalFixCancel = document.getElementById('modalFixCancel');
    const modalFixDelete = document.getElementById('modalFixDelete');
    
    let currentOffreId = null;
    
    // Fix modals that might be open but have non-clickable buttons
    const fixModals = () => {
        // Check if any Bootstrap modals are visible
        const visibleModals = document.querySelectorAll('.modal.show');
        if (visibleModals.length > 0) {
            // Remove all visible modals
            visibleModals.forEach(modal => {
                // Get backdrop
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
                
                // Hide and remove from DOM
                modal.classList.remove('show');
                modal.style.display = 'none';
                document.body.classList.remove('modal-open');
            });
            
            // Show our custom modal instead
            modalFixOverlay.style.display = 'flex';
        }
    };
    
    // Add event listeners for our custom modal
    modalFixClose.addEventListener('click', () => {
        modalFixOverlay.style.display = 'none';
        currentOffreId = null;
    });
    
    modalFixCancel.addEventListener('click', () => {
        modalFixOverlay.style.display = 'none';
        currentOffreId = null;
    });
    
    modalFixDelete.addEventListener('click', () => {
        if (currentOffreId) {
            // Create and submit a form to delete the offer
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/offres/${currentOffreId}`;
            form.style.display = 'none';
            
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
            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            
            // Add form to document, submit it, and remove it
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
        
        modalFixOverlay.style.display = 'none';
        currentOffreId = null;
    });
    
    // Add a click listener to all delete buttons that might trigger a modal
    document.querySelectorAll('button[data-bs-toggle="modal"]').forEach(button => {
        button.addEventListener('click', (e) => {
            // Extract the offre ID from the modal target
            const modalId = button.getAttribute('data-bs-target');
            if (modalId && modalId.includes('deleteModal')) {
                // Extract ID from deleteModal{id}
                const offreId = modalId.replace('#deleteModal', '');
                if (offreId) {
                    currentOffreId = offreId;
                    
                    // Delay to let Bootstrap modal show first
                    setTimeout(fixModals, 100);
                }
            }
        });
    });
    
    // Check for any visible modals on page load
    setTimeout(fixModals, 500);
});
