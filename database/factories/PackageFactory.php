<?php

namespace Database\Factories;

use App\Models\InternalPerson;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $deliver = random_int(0, 30) == 0 ? null : User::inRandomOrder()->firstOrFail()->id;

        return [
            'type' => $this->faker->randomElement(['entry', 'exit']),
            'agency' => $this->faker->randomElement(Package::AGENCIES),
            'package_count' => $this->faker->numberBetween(1, 5),
            'external_entity' => $this->faker->company(),
            'receiver_user_id' => User::inRandomOrder()->firstOrFail()->id,
            'deliver_user_id' => $deliver,
            'internal_person_id' => InternalPerson::inRandomOrder()->firstOrFail()->id,
            'entry_time' => $this->randomDateTime(),
            'exit_time' => $deliver == null ? null : $this->randomDateTime(),
        ];
    }

    public function randomDateTime()
    {
        $date = $this->faker->dateTimeBetween('-15 years', 'now', 'UTC');
        $exit_time = Carbon::instance($date);

        return $exit_time->format('Y-m-d H:i:s');
    }
}
