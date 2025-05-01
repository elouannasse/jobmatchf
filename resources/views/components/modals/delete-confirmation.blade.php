<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModalX">&times;</button>
            </div>
            <div class="modal-body">
                <p id="confirmationMessage">Êtes-vous sûr de vouloir supprimer cet utilisateur?</p>
                <p class="text-danger">
                    Cette action est irréversible et entraînera la suppression de toutes les données associées.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelDelete">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Supprimer définitivement</button>
            </div>
        </div>
    </div>
</div>
