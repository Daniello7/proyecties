<?php

namespace Database\Seeders;

use App\Models\Api\Guard;
use App\Models\Api\Zone;
use Illuminate\Database\Seeder;

class GuardZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guards = Guard::all();
        $zones = Zone::all();

        foreach ($guards as $guard) {
            $randomZones = $zones->random(3);
            foreach ($randomZones as $zone) {
                $guard->zones()->attach($zone->id,
                    [
                        'schedule' => rand(0, 1) == 0
                            ? '07:00 - 19:00'
                            : '19:00 - 7:00'
                    ]);
            }
        }
    }
}
