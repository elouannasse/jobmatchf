<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface EloquentRepositoryInterface
{
    
    public function all(array $columns = ['*'], array $relations = []): Collection;
    
   
    public function paginate(int $perPage = 10, array $columns = ['*'], array $relations = []);

   
    public function allTrashed(): Collection;

   
    public function findById(
        int $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): ?Model;

    
    public function findTrashedById(int $modelId): ?Model;

    public function findOnlyTrashedById(int $modelId): ?Model;

   
    public function create(array $payload): ?Model;

 
    public function update(int $modelId, array $payload): bool;

   
    public function deleteById(int $modelId): bool;

   
    public function restoreById(int $modelId): bool;

   
    public function permanentlyDeleteById(int $modelId): bool;
}
