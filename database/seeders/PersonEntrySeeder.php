<?php

namespace Database\Seeders;

use App\Models\PersonEntry;
use Illuminate\Database\Seeder;

class PersonEntrySeeder extends Seeder
{
    public function run(): void
    {
        PersonEntry::factory(500)->create();
    }
}
