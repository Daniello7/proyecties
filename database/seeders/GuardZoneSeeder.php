<?php

namespace Database\Seeders;

use App\Models\Guard;
use App\Models\Zone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            foreach ($zones as $zone) {
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
