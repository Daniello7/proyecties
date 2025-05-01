<?php

namespace Database\Seeders;

use App\Models\Api\Guard;
use App\Models\User;
use Illuminate\Database\Seeder;

class GuardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Guard::create([
            'user_id' => 1,
            'name' => 'Daniel López',
            'dni' => '12345678A',
        ]);

        $users = User::whereDoesntHave('assignedGuard')->inRandomOrder()->take(14)->get();

        $users->each(fn($user) => Guard::factory()->create(['user_id' => $user->id]));
    }
}
