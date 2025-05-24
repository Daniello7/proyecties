<?php

namespace Database\Factories;

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
            'person_id' => Person::doesntHave('internalPerson')->inRandomOrder()->firstOrFail()->id,
            'internal_person_id' => InternalPerson::inRandomOrder()->firstOrFail()->id,
            'comment' => $this->faker->randomElement([null, null, $this->faker->realTextBetween(20, 40)]),
            'reason' => $this->faker->randomElement(PersonEntry::REASONS),
            'arrival_time' => $this->faker->dateTime(),
            'entry_time' => $this->faker->randomElement(array_merge([null],
                array_fill(0, 10, $this->faker->dateTime()))),
            'exit_time' => $this->faker->randomElement(array_merge([null],
                array_fill(0, 10, $this->faker->dateTime())))
        ];
    }

}
