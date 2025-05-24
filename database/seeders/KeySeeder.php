<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Key;
use Illuminate\Database\Seeder;

class KeySeeder extends Seeder
{
    public function run(): void
    {
        Area::all()->each(function ($area) {
           $area->keys()->saveMany(Key::factory(10)->make());
        });
    }
}
