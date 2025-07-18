<?php

namespace Database\Factories;

use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactory extends Factory
{
    protected $model = Area::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->streetName(),
            'location' => $this->faker->address(),
            'description' => $this->faker->realText(),
        ];
    }
}
