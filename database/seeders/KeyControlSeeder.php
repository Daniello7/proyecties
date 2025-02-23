<?php

namespace Database\Seeders;

use App\Models\KeyControl;
use Illuminate\Database\Seeder;

class KeyControlSeeder extends Seeder
{
    public function run(): void
    {
        KeyControl::factory(400)->create();
    }
}
