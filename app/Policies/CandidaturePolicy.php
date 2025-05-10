<?php

namespace App\Policies;

use App\Models\Candidature;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CandidaturePolicy
{
    
    public function viewAny(User $user): bool
    {
        return false;
    }

    
    public function view(User $user, Candidature $candidature): bool
    {
        return false;
    }

    
    public function create(User $user): bool
    {
        return false;
    }

    
    public function update(User $user, Candidature $candidature)
    {
               
        return $user->id === $candidature->offre->user_id;
    }

    
    public function delete(User $user, Candidature $candidature): bool
    {
        return false;
    }

    
    public function restore(User $user, Candidature $candidature): bool
    {
        return false;
    }

    
    public function forceDelete(User $user, Candidature $candidature): bool
    {
        return false;
    }
}
