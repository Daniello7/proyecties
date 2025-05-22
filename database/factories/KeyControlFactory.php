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
        $receiver = random_int(0, 30) == 0 ? null : User::inRandomOrder()->firstOrFail()->id;

        return [
            'key_id' => Key::inRandomOrder()->firstOrFail()->id,
            'person_id' => Person::inRandomOrder()->firstOrFail()->id,
            'deliver_user_id' => User::inRandomOrder()->firstOrFail()->id,
            'receiver_user_id' => $receiver,
            'comment' => $this->faker->randomElement([null, $this->faker->realTextBetween(20, 40)]),
            'exit_time' => $this->faker->dateTime(),
            'entry_time' => $receiver == null ? null : $this->faker->dateTime(),
        ];
    }

}
