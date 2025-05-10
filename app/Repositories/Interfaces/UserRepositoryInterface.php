<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends EloquentRepositoryInterface
{
   
    public function getUsersByRole(int $roleId): Collection;
    
    
    public function getUsersByRolePaginated(int $roleId, int $perPage = 10);
    
  
    public function findByEmail(string $email): ?User;
    
    
    public function getAllUsersPaginated(int $perPage = 10);
    
    public function deleteUser(int $userId): bool;
}
