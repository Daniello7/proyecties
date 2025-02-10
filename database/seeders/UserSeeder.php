<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@mail.es',
            'password' => bcrypt('123'),
        ]);

        User::factory(50)->create();
    }
}
