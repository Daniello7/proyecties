<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'document_number' => $this->faker->randomNumber(8) . strtoupper($this->faker->randomLetter()),
            'company' => $this->faker->company(),
            'comment' => $this->faker->randomElement([null, null, $this->faker->realTextBetween(20, 40)]),
        ];
    }
}
