<?php

namespace Database\Seeders;

use App\Events\TokenGeneratedEvent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('log:clear');

        Storage::delete('tokens.md');

        $roles = ['porter', 'rrhh', 'admin', 'client'];

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
