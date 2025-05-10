<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
   
    protected $userRepository;

    
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

   
    public function getAllUsers(int $perPage = 10)
    {
        return $this->userRepository->getAllUsersPaginated($perPage);
    }

    
    public function getUsersByRole(int $roleId, int $perPage = 10)
    {
        return $this->userRepository->getUsersByRolePaginated($roleId, $perPage);
    }

    
    public function createUser(array $data)
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
        ];

        if (isset($data['prenom'])) {
            $userData['prenom'] = $data['prenom'];
        }

        return $this->userRepository->create($userData);
    }

    
    public function createCandidate(array $data)
    {
        $candidatRole = Role::where('name', 'candidat')->first();
        
        if (!$candidatRole) {
            Log::error('Candidate role not found');
            return null;
        }

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $candidatRole->id,
        ];

        if (isset($data['prenom'])) {
            $userData['prenom'] = $data['prenom'];
        }

        return $this->userRepository->create($userData);
    }

    
    public function updateUser(int $userId, array $data)
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role_id' => $data['role_id'],
        ];

        if (isset($data['prenom'])) {
            $userData['prenom'] = $data['prenom'];
        }

        $result = $this->userRepository->update($userId, $userData);

        if ($result && isset($data['password']) && !empty($data['password'])) {
            $this->userRepository->update($userId, [
                'password' => Hash::make($data['password']),
            ]);
        }

        return $result;
    }

    
    public function deleteUser(int $userId)
    {
        return $this->userRepository->deleteUser($userId);
    }

    
    public function toggleUserStatus(int $userId)
    {
        $user = $this->userRepository->findById($userId);
        
        if (!$user) {
            return false;
        }

        $newStatus = !$user->is_active;
        
        return $this->userRepository->update($userId, [
            'is_active' => $newStatus
        ]);
    }
}
