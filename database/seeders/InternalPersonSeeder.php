<?php

namespace Database\Seeders;

use App\Models\InternalPerson;
use App\Models\Person;
use Illuminate\Database\Seeder;

class InternalPersonSeeder extends Seeder
{
    public function run(): void
    {
        $daniel = Person::factory()->create(['name' => 'Daniel', 'last_name' => 'López Olmos']);
        InternalPerson::factory()->create(['person_id' => $daniel->id, 'email' => 'daniellopezolmos7@gmail.com']);

        $internalPerson = Person::inRandomOrder()->take(50)->get();
        foreach ($internalPerson as $person) {
            InternalPerson::factory()->create(['person_id' => $person->id]);
        }

    }
}
