<?php

namespace Database\Seeders;

use App\Models\Api\Zone;
use Illuminate\Database\Seeder;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Zone::factory(15)->create();
    }
}
