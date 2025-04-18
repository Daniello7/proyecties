<?php

namespace Database\Factories;

use App\Models\Api\Guard;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuardFactory extends Factory
{
    protected $model = Guard::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'dni' => $this->faker->unique()->numerify('########')
                . ucfirst($this->faker->randomLetter()),
        ];
    }
}
