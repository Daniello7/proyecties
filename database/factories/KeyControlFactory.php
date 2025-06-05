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
        $receiver = random_int(0, 30) == 0 ? null : User::inRandomOrder()->first()?->id ?? User::factory()->create()->id;

        return [
            'key_id' => Key::inRandomOrder()->first()?->id ?? Key::factory()->create()->id,
            'person_id' => Person::inRandomOrder()->first()?->id ?? Person::factory()->create()->id,
            'deliver_user_id' => User::inRandomOrder()->first()?->id ?? User::factory()->create()->id,
            'receiver_user_id' => $receiver,
            'comment' => $this->faker->randomElement([null, $this->faker->realTextBetween(20, 40)]),
            'exit_time' => $this->faker->dateTime(),
            'entry_time' => $receiver == null ? null : $this->faker->dateTime(),
        ];
    }

}
