<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\AdminNotification;
use App\Repositories\Interfaces\OffreRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class OffreService
{
    /**
     * @var OffreRepositoryInterface
     */
    protected $offreRepository;

    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * OffreService constructor.
     *
     * @param OffreRepositoryInterface $offreRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        OffreRepositoryInterface $offreRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->offreRepository = $offreRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Create a new job offer
     *
     * @param array $data
     * @return \App\Models\Offre
     */
    public function createOffre(array $data)
    {
        $payload = $data;
        $payload['user_id'] = Auth::id();
        $payload['etat'] = false; // Initialement inactive
        $payload['approved'] = false; // En attente d'approbation
        $payload['title'] = $data['titre'];
        $payload['salaire_proposer'] = null;
        $payload['type_offre'] = null;
        $payload['date_debut'] = now()->format('Y-m-d'); // Format explicite pour la date
        $payload['date_fin'] = isset($data['date_expiration']) ? 
            $data['date_expiration'] : now()->addMonths(3)->format('Y-m-d');
        
        $offre = $this->offreRepository->create($payload);
        
        // Envoyer des notifications aux administrateurs
        $this->notifyAdmins($offre, false);
        
        return $offre;
    }

    /**
     * Update an existing job offer
     *
     * @param int $offreId
     * @param array $data
     * @return \App\Models\Offre
     */
    public function updateOffre(int $offreId, array $data)
    {
        $offre = $this->offreRepository->findById($offreId);
        
        $payload = $data;
        $payload['approved'] = false; // L'offre modifiée doit être à nouveau approuvée
        $payload['etat'] = false;
        $payload['title'] = $data['titre'];
        
        // S'assurer que date_debut reste inchangé ou est défini si null
        if (!isset($payload['date_debut']) || empty($payload['date_debut'])) {
            $payload['date_debut'] = $offre->date_debut ?? now()->format('Y-m-d');
        }
        
        // Gérer date_fin
        if (isset($data['date_expiration']) && !empty($data['date_expiration'])) {
            $payload['date_fin'] = $data['date_expiration'];
        } else if (empty($offre->date_fin)) {
            $payload['date_fin'] = now()->addMonths(3)->format('Y-m-d');
        }

        $this->offreRepository->update($offreId, $payload);
        
        // Récupérer l'offre mise à jour
        $updatedOffre = $this->offreRepository->findById($offreId);
        
        // Notifier les administrateurs
        $this->notifyAdmins($updatedOffre, true);
        
        return $updatedOffre;
    }

    /**
     * Delete a job offer
     *
     * @param int $offreId
     * @return bool
     */
    public function deleteOffre(int $offreId)
    {
        return $this->offreRepository->deleteById($offreId);
    }

    /**
     * Toggle the status of a job offer (active/inactive)
     *
     * @param int $offreId
     * @return \App\Models\Offre
     */
    public function toggleOffreStatus(int $offreId)
    {
        $offre = $this->offreRepository->findById($offreId);
        $payload = ['etat' => !$offre->etat];
        
        $this->offreRepository->update($offreId, $payload);
        
        return $this->offreRepository->findById($offreId);
    }

    /**
     * Update approval status of a job offer
     *
     * @param int $offreId
     * @param bool $approved
     * @return \App\Models\Offre
     */
    public function updateApprovalStatus(int $offreId, bool $approved)
    {
        $this->offreRepository->updateApprovalStatus($offreId, $approved);
        return $this->offreRepository->findById($offreId);
    }

    /**
     * Notify administrators about new or updated job offers
     *
     * @param \App\Models\Offre $offre
     * @param bool $isUpdate
     * @return void
     */
    protected function notifyAdmins($offre, bool $isUpdate = false)
    {
        $admins = $this->userRepository->getUsersByRole(User::ROLE_ADMINISTRATEUR_ID);
        
        $notificationData = [
            'offer_id' => $offre->id,
            'offer_title' => $offre->titre,
            'recruteur_name' => Auth::user()->name,
            'created_at' => now()->toDateTimeString()
        ];
        
        if ($isUpdate) {
            $notificationData['is_update'] = true;
        }
        
        foreach ($admins as $admin) {
            $admin->notify(new AdminNotification('pending_offer', $notificationData));
        }
    }
}
