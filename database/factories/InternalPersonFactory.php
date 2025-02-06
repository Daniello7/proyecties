<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InternalPerson>
 */
class InternalPersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'person_id' => Person::inRandomOrder()->firstOrFail()->id,
            'email' => $this->faker->unique()->safeEmail(),
            'contract_type' => $this->faker->randomElement(['Temporal', 'Indefinido']),
            'hired_at' => $this->faker->date(),
        ];
    }
}
