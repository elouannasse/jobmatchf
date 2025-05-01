<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $roles = [
            [
                'id' => User::ROLE_ADMINISTRATEUR_ID,
                'name' => User::ROLE_ADMINISTRATEUR,
            ],
            [
                'id' => User::ROLE_RECRUTEUR_ID,
                'name' => User::ROLE_RECRUTEUR,
            ],
            [
                'id' => User::ROLE_CANDIDAT_ID,
                'name' => User::ROLE_CANDIDAT,
            ],
        ];

        // Insert roles if they don't exist
        foreach ($roles as $role) {
            Role::updateOrCreate(['id' => $role['id']], $role);
        }
    }
}