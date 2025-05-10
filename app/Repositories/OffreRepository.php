<?php

namespace App\Repositories;

use App\Models\Offre;
use App\Repositories\Interfaces\OffreRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class OffreRepository extends BaseRepository implements OffreRepositoryInterface
{
    
    public function __construct(Offre $model)
    {
        parent::__construct($model);
    }

    
    public function getAllActive(): Collection
    {
        return $this->model
            ->where('approved', true)
            ->where('etat', true)
            ->whereDate('date_fin', '>=', now())
            ->get();
    }
    
    
    public function getAllActivePaginated(int $perPage = 12)
    {
        return $this->model
            ->where('approved', true)
            ->where('etat', true)
            ->whereDate('date_fin', '>=', now())
            ->with('user')
            ->latest()
            ->paginate($perPage);
    }

   
    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->get();
    }
   
    public function getByUserPaginated(int $userId, int $perPage = 10)
    {
        return $this->model->where('user_id', $userId)->latest()->paginate($perPage);
    }

    
    public function getPendingApproval(): Collection
    {
        return $this->model->where('approved', false)->get();
    }
    
   
    public function getPendingApprovalPaginated(int $perPage = 10)
    {
        return $this->model
            ->where('approved', false)
            ->with('user')
            ->latest()
            ->paginate($perPage);
    }
    
    
    public function getAdminOffresPaginated(array $filters = [], int $perPage = 10)
    {
        $query = $this->model->with(['user', 'candidatures']);
        
        
        if (isset($filters['etat'])) {
            $query->where('etat', $filters['etat']);
        }
        
        if (isset($filters['approved'])) {
            $query->where('approved', $filters['approved']);
        }
        
        if (isset($filters['pending']) && $filters['pending']) {
            $query->whereNull('approved');
        }
        
        return $query->latest()->paginate($perPage);
    }

    public function updateApprovalStatus(int $offreId, bool $approved): bool
    {
        $offre = $this->findById($offreId);
        return $offre->update(['approved' => $approved]);
    }
}
