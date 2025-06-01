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
            'person_id' => Person::inRandomOrder()->first() ?? Person::factory()->create()->id,
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'contract_type' => $this->faker->randomElement(['Temporal', 'Indefinido']),
            'hired_at' => $this->faker->date(),
            'address' => $this->faker->address(),
            'country' => $this->faker->country(),
            'city' => $this->faker->city(),
            'province' => $this->faker->state(),
            'zip_code' => $this->faker->postcode(),
        ];
    }
}
