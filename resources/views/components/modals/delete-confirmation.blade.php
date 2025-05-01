<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" data-bs-backdrop="static" style="z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="closeModalX"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                    <h4 id="confirmationMessage" class="mb-3">Êtes-vous sûr de vouloir supprimer cet utilisateur?</h4>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i> Cette action est irréversible et entraînera la suppression de toutes les données associées.
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-secondary px-4" id="cancelDelete">Annuler</button>
                <button type="button" class="btn btn-danger px-4" id="confirmDelete">Supprimer définitivement</button>
            </div>
        </div>
    </div>
</div>

<style>
    #deleteModal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
    }
    #deleteModal .modal-content {
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    #deleteModal .modal-header {
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
    #deleteModal .btn {
        border-radius: 4px;
        padding: 8px 16px;
        font-weight: 500;
    }
    #deleteModal .btn-danger {
        background-color: #dc3545;
    }
    #deleteModal .btn-danger:hover {
        background-color: #c82333;
    }
</style>
