<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\PersonDocument;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PersonDocumentFactory extends Factory
{
    protected $model = PersonDocument::class;

    public function definition(): array
    {
        return [
            'filename' => $this->faker->word(),
            'original_name' => $this->faker->name(),
            'type' => $this->faker->word(),
            'size' => $this->faker->randomNumber(),
            'path' => $this->faker->word(),
            'person_id' => Person::factory(),
        ];
    }
}
