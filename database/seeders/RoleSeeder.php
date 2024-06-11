<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lista de roles a crear
        $roles = ['administrador', 'club', 'user'];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
