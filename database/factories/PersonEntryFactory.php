<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\InternalPerson;
use App\Models\Person;
use App\Models\PersonEntry;
use App\Models\User;
use Carbon\Carbon;
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
            'arrival_time' => $this->randomDateTime(),
            'entry_time' => $this->faker->randomElement([$this->randomDateTime(), $this->randomDateTime(), $this->randomDateTime(), null]),
            'exit_time' => $this->faker->randomElement([$this->randomDateTime(), $this->randomDateTime(), $this->randomDateTime(), $this->randomDateTime(), $this->randomDateTime(), $this->randomDateTime(), $this->randomDateTime(), $this->randomDateTime(), $this->randomDateTime(), $this->randomDateTime(), $this->randomDateTime(), null]),
        ];
    }

    public function randomDateTime()
    {
        $date = $this->faker->dateTimeInInterval('-30 years', '+0 days', 'UTC');
        $exit_time = Carbon::instance($date);

        return $exit_time->format('Y-m-d H:i:s');
    }
}
