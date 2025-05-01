<?php

namespace Database\Seeders;

use App\Models\Api\Alarm;
use App\Models\Api\Guard;
use Illuminate\Database\Seeder;

class AlarmGuardSeeder extends Seeder
{
    public function run(): void
    {
        $alarms = Alarm::all();

        foreach ($alarms as $alarm) {
            for ($i = 0; $i < mt_rand(1, 5); $i++) {
                $alarm->assignedGuards()->attach(Guard::inRandomOrder()->firstOrFail()->id,
                    [
                        'date' => now(),
                        'is_false_alarm' => mt_rand(0, 3) == 0 ? false : true,
                    ]
                );
            }
        }
    }
}
