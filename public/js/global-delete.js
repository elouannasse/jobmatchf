/**
 * Script global pour résoudre les problèmes de suppression dans l'application
 * Ce script intercepte tous les boutons de suppression et modaux qui ne fonctionnent pas
 */
document.addEventListener('DOMContentLoaded', function() {
    // 1. Fixer les modaux Bootstrap qui seraient bloqués ou inaccessibles
    fixBlockedModals();

    // 2. Ajouter une fonction de suppression directe à tous les boutons de suppression
    addDirectDeleteHandlers();

    // 3. Surveiller les clics sur l'overlay modal (pour fermer les modaux bloqués)
    watchModalOverlays();

    // 4. Ajouter un gestionnaire global pour les boutons de suppression qui apparaîtraient dynamiquement
    document.body.addEventListener('click', function(e) {
        if (e.target && (
            e.target.classList.contains('btn-danger') || 
            e.target.closest('.btn-danger') ||
            e.target.classList.contains('action-btn-delete') ||
            e.target.closest('.action-btn-delete')
        )) {
            const button = e.target.closest('.btn-danger') || e.target.closest('.action-btn-delete') || e.target;
            
            // Ne pas interferer avec les boutons qui ont déjà des gestionnaires
            if (button.hasAttribute('data-has-handler')) {
                return;
            }

            // Vérifier si c'est un bouton de suppression
            if (button.textContent.includes('Supprimer') || 
                button.getAttribute('title')?.includes('Supprimer') ||
                button.innerHTML.includes('fa-trash')) {
                
                e.preventDefault();
                e.stopPropagation();
                
                let offreId = button.getAttribute('data-offre-id') || 
                              button.getAttribute('data-id') || 
                              extractIdFromUrl(window.location.href);
                
                if (offreId) {
                    if (confirm('Êtes-vous sûr de vouloir supprimer cette offre? Cette action est irréversible.')) {
                        submitDeleteForm(offreId);
                    }
                }
                
                button.setAttribute('data-has-handler', 'true');
            }
        }
    });

    // Vérifier si un modal de suppression est déjà ouvert au chargement
    setTimeout(checkForOpenModals, 500);
});

/**
 * Répare les modaux Bootstrap bloqués
 */
function fixBlockedModals() {
    const modals = document.querySelectorAll('.modal');
    
    modals.forEach(modal => {
        // Remplacer les gestionnaires d'événements sur les boutons du modal
        const deleteButtons = modal.querySelectorAll('.btn-danger, button[type="submit"]');
        
        deleteButtons.forEach(button => {
            // Supprimer tous les gestionnaires existants
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);
            
            // Ajouter un nouveau gestionnaire
            newButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                let form = newButton.closest('form');
                let offreId = extractIdFromUrl(form ? form.action : window.location.href);
                
                if (offreId) {
                    submitDeleteForm(offreId);
                }
                
                // Fermer le modal
                const bootstrapModal = bootstrap.Modal.getInstance(modal);
                if (bootstrapModal) {
                    bootstrapModal.hide();
                } else {
                    modal.style.display = 'none';
                    modal.classList.remove('show');
                    document.body.classList.remove('modal-open');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) backdrop.remove();
                }
            });
        });
    });
}

/**
 * Ajoute des gestionnaires de suppression directe aux boutons de suppression
 */
function addDirectDeleteHandlers() {
    // Boutons de suppression standard
    const deleteButtons = document.querySelectorAll('.btn-danger, .action-btn-delete');
    
    deleteButtons.forEach(button => {
        // Vérifier si c'est un bouton de suppression
        if (button.textContent.includes('Supprimer') || 
            button.getAttribute('title')?.includes('Supprimer') ||
            button.innerHTML.includes('fa-trash')) {
            
            // Remplacer les gestionnaires d'événements
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);
            
            // Ajouter un nouveau gestionnaire
            newButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                let offreId = newButton.getAttribute('data-offre-id') || 
                              newButton.getAttribute('data-id') || 
                              extractIdFromUrl(window.location.href);
                
                if (offreId) {
                    if (confirm('Êtes-vous sûr de vouloir supprimer cette offre? Cette action est irréversible.')) {
                        submitDeleteForm(offreId);
                    }
                }
            });
            
            newButton.setAttribute('data-has-handler', 'true');
        }
    });
}

/**
 * Surveille les clics sur les overlays de modal pour fermer les modaux bloqués
 */
function watchModalOverlays() {
    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal') && e.target.classList.contains('show')) {
            // Clic sur l'overlay du modal - fermer le modal
            const modal = e.target;
            
            const bootstrapModal = bootstrap.Modal.getInstance(modal);
            if (bootstrapModal) {
                bootstrapModal.hide();
            } else {
                modal.style.display = 'none';
                modal.classList.remove('show');
                document.body.classList.remove('modal-open');
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.remove();
            }
        }
    });
}

/**
 * Vérifie la présence de modaux ouverts au chargement
 */
function checkForOpenModals() {
    const openModals = document.querySelectorAll('.modal.show');
    
    if (openModals.length > 0) {
        openModals.forEach(modal => {
            // Fixer les boutons du modal
            const deleteButtons = modal.querySelectorAll('.btn-danger, button[type="submit"]');
            
            deleteButtons.forEach(button => {
                // Supprimer tous les gestionnaires existants
                const newButton = button.cloneNode(true);
                button.parentNode.replaceChild(newButton, button);
                
                // Ajouter un nouveau gestionnaire
                newButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    let form = newButton.closest('form');
                    let offreId = extractIdFromUrl(form ? form.action : window.location.href);
                    
                    if (offreId) {
                        submitDeleteForm(offreId);
                    }
                    
                    // Fermer le modal
                    modal.style.display = 'none';
                    modal.classList.remove('show');
                    document.body.classList.remove('modal-open');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) backdrop.remove();
                });
            });
        });
    }
}

/**
 * Extrait l'ID de l'offre à partir d'une URL
 */
function extractIdFromUrl(url) {
    // Essayer de trouver l'ID à la fin de l'URL, comme dans /offres/123
    const matches = url.match(/\/offres\/(\d+)/);
    if (matches && matches[1]) {
        return matches[1];
    }
    
    return null;
}

/**
 * Soumet un formulaire de suppression pour une offre
 */
function submitDeleteForm(offreId) {
    // Vérifier si un formulaire existe déjà
    let form = document.getElementById('direct-delete-form-' + offreId);
    
    if (!form) {
        // Créer un nouveau formulaire
        form = document.createElement('form');
        form.method = 'POST';
        form.action = '/offres/' + offreId;
        form.id = 'direct-delete-form-' + offreId;
        form.style.display = 'none';
        
        // Ajouter le token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        
        // Ajouter la méthode DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        // Ajouter le formulaire au document
        document.body.appendChild(form);
    }
    
    // Soumettre le formulaire
    form.submit();
}
