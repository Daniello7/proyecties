<?php

namespace Database\Factories;

use App\Models\Api\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

class ZoneFactory extends Factory
{
    protected $model = Zone::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'location' => $this->faker->address(),
        ];
    }
}
