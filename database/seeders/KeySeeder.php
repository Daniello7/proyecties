<?php

namespace Database\Seeders;

use App\Models\Key;
use Illuminate\Database\Seeder;

class KeySeeder extends Seeder
{
    public function run(): void
    {
        Key::factory(50)->create();
    }
}
