<?php

namespace App\Http\Controllers;

use App\Http\Requests\Person\StorePersonRequest;
use App\Http\Requests\Person\UpdatePersonRequest;
use App\Models\Comment;
use App\Models\Person;
use Illuminate\Http\Request;

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
        $person = $request->validated();
        $person['comment_id'] = Comment::create(['user_id' => auth()->user()->id])->id;

        Person::create($person);

        return to_route('person-entries')->with('status', 'Person created successfully');
    }

    public function show($id)
    {
        $person = Person::findOrFail($id);

        return view('person.show', compact('person'));
    }

    public function edit($id)
    {
        $person = Person::with('comment')->findOrFail($id);

        return view('person.edit', compact('person'));
    }

    public function update(UpdatePersonRequest $request, $id)
    {
        $data = $request->validated();

        $person = Person::findOrFail($id);

        $comment = Comment::findOrFail($person->comment_id);
        $comment->update([
            'user_id' => auth()->user()->id,
            'content' => $request['comment']
        ]);


        $person->update($data);
        return to_route('person-entries')->with('status', 'Person updated successfully');
    }
}
