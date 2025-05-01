<?php

namespace Database\Seeders;

use App\Models\Api\Alarm;
use App\Models\Api\Guard;
use Illuminate\Database\Seeder;

class AlarmGuardSeeder extends Seeder
{
    public function run(): void
    {
        $guards = Guard::all();

        foreach ($guards as $guard) {
            $guard->alarms()->attach(Alarm::inRandomOrder()->firstOrFail()->id,
                ['date' => now()],
                ['is_false_alarm' => random_int(0, 1) == 1 ? true : false]
            );
        }
    }
}
