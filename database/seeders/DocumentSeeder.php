<?php

namespace Database\Seeders;

use Artisan;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('files:clear --f');
    }
}
