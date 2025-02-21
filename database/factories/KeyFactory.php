<?php

namespace Database\Factories;

use App\Models\Key;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Key>
 */
class KeyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'zone' => $this->faker->randomElement(Key::ZONES),
            'name' => $this->faker->words(random_int(2, 3), true),
        ];
    }
}
