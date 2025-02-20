<?php

namespace Database\Factories;

use App\Models\Key;
use App\Models\Person;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KeyControl>
 */
class KeyControlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key_id' => Key::inRandomOrder()->firstOrFail()->id,
            'person_id' => Person::inRandomOrder()->firstOrFail()->id,
            'deliver_user_id' => User::inRandomOrder()->firstOrFail()->id,
            'receiver_user_id' => User::inRandomOrder()->firstOrFail()->id,
            'comment' => $this->faker->randomElement([null, $this->faker->realTextBetween(20, 40)]),
            'exit_time' => $this->randomDateTime(),
            'entry_time' => $this->faker->randomElement(array_merge([null],
                array_fill(0, 20, $this->randomDateTime())))
        ];
    }

    public function randomDateTime()
    {
        $date = $this->faker->dateTimeBetween('-15 years', 'now', 'UTC');
        $exit_time = Carbon::instance($date);

        return $exit_time->format('Y-m-d H:i:s');
    }
}
