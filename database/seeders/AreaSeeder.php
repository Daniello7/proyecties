<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            'Entrance',
            'Control Access',
            'Office',
            'Workshop',
            'Factory',
            'Parking'
        ];

        foreach ($zones as $zone) {
            Area::factory()->create([
                'name' => $zone
            ]);
        }
    }
}
