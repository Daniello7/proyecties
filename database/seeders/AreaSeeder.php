<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            'Office',
            'Entrance',
            'Factory',
            'Parking',
            'Control Access'
        ];

        foreach ($zones as $zone) {
            Area::factory()->create([
                'name' => $zone
            ]);
        }
    }
}
