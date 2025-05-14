<?php

namespace App\Http\Controllers;

use App\Http\Requests\Person\StorePersonRequest;
use App\Http\Requests\Person\UpdatePersonRequest;
use App\Models\Person;

class PersonController extends Controller
{
    public function show($id)
    {
        $person = Person::findOrFail($id);

        return view('person.show', compact('person'));
    }
}
