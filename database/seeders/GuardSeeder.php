<?php

namespace Database\Seeders;

use App\Models\Guard;
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
        Guard::factory(59)->create();
    }
}
