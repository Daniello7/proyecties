<?php

namespace Database\Factories;

use App\Models\Area;
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
            'area_id' => Area::inRandomOrder()->first() ?? Area::factory()->create()->id,
            'name' => $this->faker->streetName(),
        ];
    }
}
