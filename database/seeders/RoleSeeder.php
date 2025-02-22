<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {

        $roles = ['admin', 'porter', 'client', 'rrhh', 'tester'];

        foreach ($roles as $role) {
            Role::findOrCreate($role);
        }
    }
}
