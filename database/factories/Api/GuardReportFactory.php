<?php

namespace Database\Factories\Api;

use App\Models\Api\Guard;
use App\Models\Api\GuardReport;
use App\Models\Api\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuardReportFactory extends Factory
{
    protected $model = GuardReport::class;

    public function definition(): array
    {
        return [
            'entry_time' => $this->faker->dateTime(),
            'exit_time' => $this->faker->dateTime(),
            'incident' => $this->faker->realText(),
            'guard_id' => Guard::inRandomOrder()->first()?->id ?? Guard::factory()->create()->id,
            'zone_id' => Zone::inRandomOrder()->first()?->id ?? Zone::factory()->create()->id,
        ];
    }
}
