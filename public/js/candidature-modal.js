/**
 * Candidature details modal handling
 */
document.addEventListener("DOMContentLoaded", function() {
    // Get modal elements
    const modal = document.getElementById('candidatureDetailsModal');
    const viewButtons = document.querySelectorAll('.view-candidature-btn');
    const modalContent = document.getElementById('candidatureDetailsContent');
    const closeBtn = document.getElementById('closeCandidatureModal');
    const modalBackdrop = document.querySelector('.modal-backdrop');
    
    // CSRF token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Function to load candidature details
    function loadCandidatureDetails(candidatureId) {
        if (modalContent) {
            // Show loading spinner
            modalContent.innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement des détails...</p>
                </div>
            `;
            
            // Make AJAX request to get candidature details
            fetch(`/api/candidatures/${candidatureId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Format the data and update modal content
                if (data.candidature) {
                    const c = data.candidature;
                    let statusClass = '';
                    let statusText = '';
                    
                    switch(c.statut) {
                        case 'en_attente':
                            statusClass = 'warning';
                            statusText = 'En attente';
                            break;
                        case 'acceptee':
                            statusClass = 'success';
                            statusText = 'Acceptée';
                            break;
                        case 'refusee':
                            statusClass = 'danger';
                            statusText = 'Refusée';
                            break;
                        default:
                            statusClass = 'secondary';
                            statusText = c.statut;
                    }
                    
                    // Update modal content with candidature details
                    modalContent.innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Informations du candidat</h5>
                                <div class="mb-3">
                                    <strong>Nom:</strong> ${c.user.name || ''}
                                </div>
                                <div class="mb-3">
                                    <strong>Prénom:</strong> ${c.user.prenom || ''}
                                </div>
                                <div class="mb-3">
                                    <strong>Email:</strong> ${c.user.email || ''}
                                </div>
                                <div class="mb-3">
                                    <strong>Date de candidature:</strong> ${new Date(c.created_at).toLocaleDateString('fr-FR')}
                                </div>
                                <div class="mb-3">
                                    <strong>Statut:</strong> 
                                    <span class="badge bg-${statusClass}">${statusText}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Détails de l'offre</h5>
                                <div class="mb-3">
                                    <strong>Titre:</strong> ${c.offre.titre || c.offre.title || ''}
                                </div>
                                <div class="mb-3">
                                    <strong>Entreprise:</strong> ${c.offre.user ? c.offre.user.name : 'N/A'}
                                </div>
                                <div class="mb-3">
                                    <strong>Type de contrat:</strong> ${c.offre.type_contrat || ''}
                                </div>
                                <div class="mb-3">
                                    <strong>Lieu:</strong> ${c.offre.lieu || ''}
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row mt-3">
                            <div class="col-12">
                                <h5>Lettre de motivation</h5>
                                <div class="p-3 bg-light rounded">
                                    ${c.lettre_motivation || '<em>Aucune lettre de motivation fournie</em>'}
                                </div>
                            </div>
                        </div>
                        
                        ${c.cv_path ? `
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h5>CV</h5>
                                    <a href="/storage/${c.cv_path}" class="btn btn-outline-primary" target="_blank">
                                        <i class="fas fa-file-pdf me-2"></i>Télécharger le CV
                                    </a>
                                </div>
                            </div>
                        ` : ''}
                    `;
                } else {
                    modalContent.innerHTML = `
                        <div class="alert alert-danger">
                            Impossible de charger les détails de cette candidature.
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error fetching candidature details:', error);
                modalContent.innerHTML = `
                    <div class="alert alert-danger">
                        Une erreur est survenue lors du chargement des détails.
                        <br>
                        ${error.message}
                    </div>
                `;
            });
        }
    }

    // Function to open modal
    function openModal(event) {
        event.preventDefault();
        
        // Get candidature ID from button
        const candidatureId = this.getAttribute('data-candidature-id');
        
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
            
            // Load candidature details
            loadCandidatureDetails(candidatureId);
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

    // Attach event listeners
    if (viewButtons) {
        viewButtons.forEach(button => {
            button.addEventListener('click', openModal);
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
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
