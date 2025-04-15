<?php

namespace Database\Seeders;

use App\Events\TokenGeneratedEvent;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Mail;

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

            $token = $user->createToken($role . '_token', [$role])->plainTextToken;

            event(new TokenGeneratedEvent($user, $token));
        }

        User::factory(20)->create();
    }
}
