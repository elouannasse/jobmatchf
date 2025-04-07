<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            'Administrateur',
            'Recruteur',
            'Candidat',
        ])->each(function ($role) {
            Role::firstOrCreate(['name' => $role]);
        });
    }
}
