<?php

namespace Database\Factories\Api;

use App\Models\Api\Alarm;
use App\Models\Api\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlarmFactory extends Factory
{
    protected $model = Alarm::class;

    public function definition(): array
    {
        return [
            'zone_id' => Zone::inRandomOrder()->firstOrFail(),
            'type' => $this->faker->randomElement([
                'intrusion',
                'fire',
                'panic',
                'glass_break',
                'motion',
                'door_forced',
                'temperature',
                'power_failure',
                'water_leak',
                'gas_leak',
            ]),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'description' => $this->faker->text(),
        ];
    }
}
