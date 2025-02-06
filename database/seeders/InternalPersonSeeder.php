<?php

namespace Database\Seeders;

use App\Models\InternalPerson;
use App\Models\Person;
use Illuminate\Database\Seeder;

class InternalPersonSeeder extends Seeder
{
    public function run(): void
    {
        $internalPerson = Person::inRandomOrder()->take(100)->get();

        foreach ($internalPerson as $person) {
            InternalPerson::factory()->create(['person_id' => $person->id]);
        }
    }
}
