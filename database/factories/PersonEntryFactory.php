<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\InternalPerson;
use App\Models\Person;
use App\Models\PersonEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PersonEntry>
 */
class PersonEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->firstOrFail()->id,
            'person_id' => Person::inRandomOrder()->firstOrFail()->id,
            'internal_person_id' => InternalPerson::inRandomOrder()->firstOrFail()->id,
            'comment_id' => Comment::factory()->create(),
            'reason' => $this->faker->randomElement(PersonEntry::REASONS),
            'arrival_time' => $this->faker->dateTime(),
            'entry_time' => $this->faker->randomElement([$this->faker->dateTime(), $this->faker->dateTime(), $this->faker->dateTime(), null]),
            'exit_time' => $this->faker->randomElement([$this->faker->dateTime(), $this->faker->dateTime(), $this->faker->dateTime(), $this->faker->dateTime(),$this->faker->dateTime(), $this->faker->dateTime(), $this->faker->dateTime(), $this->faker->dateTime(),$this->faker->dateTime(), $this->faker->dateTime(), $this->faker->dateTime(), $this->faker->dateTime(), null]),
        ];
    }
}
