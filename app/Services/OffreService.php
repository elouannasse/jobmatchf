<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\AdminNotification;
use App\Repositories\Interfaces\OffreRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class OffreService
{
   
    protected $offreRepository;

        protected $userRepository;

   
    public function __construct(
        OffreRepositoryInterface $offreRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->offreRepository = $offreRepository;
        $this->userRepository = $userRepository;
    }

   
    public function createOffre(array $data)
    {
        $payload = $data;
        $payload['user_id'] = Auth::id();
        $payload['etat'] = false; 
        $payload['approved'] = false; 
        $payload['title'] = $data['titre'];
        $payload['salaire_proposer'] = null;
        $payload['type_offre'] = null;
        $payload['date_debut'] = now()->format('Y-m-d'); 
        $payload['date_fin'] = isset($data['date_expiration']) ? 
            $data['date_expiration'] : now()->addMonths(3)->format('Y-m-d');
        
        $offre = $this->offreRepository->create($payload);
        
        
        $this->notifyAdmins($offre, false);
        
        return $offre;
    }

   
    public function updateOffre(int $offreId, array $data)
    {
        $offre = $this->offreRepository->findById($offreId);
        
        $payload = $data;
        $payload['approved'] = false; 
        $payload['etat'] = false;
        $payload['title'] = $data['titre'];
        
        if (!isset($payload['date_debut']) || empty($payload['date_debut'])) {
            $payload['date_debut'] = $offre->date_debut ?? now()->format('Y-m-d');
        }
        
        if (isset($data['date_expiration']) && !empty($data['date_expiration'])) {
            $payload['date_fin'] = $data['date_expiration'];
        } else if (empty($offre->date_fin)) {
            $payload['date_fin'] = now()->addMonths(3)->format('Y-m-d');
        }

        $this->offreRepository->update($offreId, $payload);
        
        $updatedOffre = $this->offreRepository->findById($offreId);
        
        $this->notifyAdmins($updatedOffre, true);
        
        return $updatedOffre;
    }

   
    public function deleteOffre(int $offreId)
    {
        return $this->offreRepository->deleteById($offreId);
    }

   
    public function toggleOffreStatus(int $offreId)
    {
        $offre = $this->offreRepository->findById($offreId);
        $payload = ['etat' => !$offre->etat];
        
        $this->offreRepository->update($offreId, $payload);
        
        return $this->offreRepository->findById($offreId);
    }

   
    public function updateApprovalStatus(int $offreId, bool $approved)
    {
        $this->offreRepository->updateApprovalStatus($offreId, $approved);
        return $this->offreRepository->findById($offreId);
    }

   
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
