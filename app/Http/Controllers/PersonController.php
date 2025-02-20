<?php

namespace App\Http\Controllers;

use App\Http\Requests\Person\StorePersonRequest;
use App\Http\Requests\Person\UpdatePersonRequest;
use App\Models\Person;

class PersonController extends Controller
{
    public function index()
    {
        return view('person.index');
    }

    public function create()
    {
        return view('person.create');
    }

    public function store(StorePersonRequest $request)
    {
        Person::create($request->validated());

        return to_route('person-entries')->with('status', 'Person created successfully');
    }

    public function show($id)
    {
        $person = Person::findOrFail($id);

        return view('person.show', compact('person'));
    }

    public function edit($id)
    {
        $person = Person::findOrFail($id);

        return view('person.edit', compact('person'));
    }

    public function update(UpdatePersonRequest $request, $id)
    {
        $person = Person::findOrFail($id);

        $person->update($request->validated());

        return to_route('person-entries')->with('status', 'Person updated successfully');
    }
}
