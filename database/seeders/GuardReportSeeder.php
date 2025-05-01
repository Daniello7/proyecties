<?php

namespace Database\Seeders;

use App\Models\Api\GuardReport;
use Illuminate\Database\Seeder;

class GuardReportSeeder extends Seeder
{
    public function run(): void
    {
        GuardReport::factory(100)->create();
    }
}
