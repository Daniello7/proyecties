<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['tester', 'admin', 'client', 'porter', 'rrhh'];

        foreach ($roles as $role) {
            $user = User::create([
                'name' => ucfirst($role) . ' User',
                'email' => $role . '@mail.es',
                'password' => bcrypt($role . '123'),
            ]);

            $user->assignRole($role);
        }

        User::factory(20)->create();
    }
}
