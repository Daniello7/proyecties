<?php

namespace Database\Seeders;

use App\Models\Api\Alarm;
use Illuminate\Database\Seeder;

class AlarmSeeder extends Seeder
{
    public function run(): void
    {
        Alarm::factory(50)->create();
    }
}
