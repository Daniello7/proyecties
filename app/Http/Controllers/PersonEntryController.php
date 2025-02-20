<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonEntry\StorePersonEntryRequest;
use App\Http\Requests\PersonEntry\UpdatePersonEntryRequest;
use App\Models\Comment;
use App\Models\Person;
use App\Models\PersonEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PersonEntryController extends Controller
{
    public function index()
    {
        return view('person-entry.index');
    }

    public function create(Request $request)
    {
        $person_id = $request->input('person_id');

        $person = Person::with(['personEntries' => function ($query) {
            $query->orderBy('exit_time', 'desc')->first();
        }])->findOrFail($person_id);

        $lastEntry = $person->personEntries->first();

        return view('person-entry.create', ['person' => $person, 'lastEntry' => $lastEntry]);
    }

    public function store(StorePersonEntryRequest $request)
    {

        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;
        $data['comment_id'] = Comment::create([
            'user_id' => auth()->user()->id,
            'content' => $request->input('comment')
        ])->id;
        $data['arrival_time'] = Carbon::now();


        PersonEntry::create($data);

        return to_route('control-access')
            ->with('status', 'Entry created successfully');
    }

    public function edit($id)
    {
        $personEntry = PersonEntry::findOrFail($id);

        return view('person-entry.edit', compact('personEntry'));
    }

    public function update(UpdatePersonEntryRequest $request, $id)
    {
        $data = $request->validated();
        $personEntry = PersonEntry::findOrFail($id);
        $comment = Comment::findOrFail($personEntry->comment_id);
        $comment->update([
            'user_id' => auth()->user()->id,
            'content' => $request['comment']
        ]);
        $personEntry->update($data);

        return to_route('control-access')->with('status', 'Entry updated successfully');
    }

    public function destroy($id)
    {
        PersonEntry::destroy($id);

        return to_route('control-access')
            ->with('status', 'Post deleted successfully');
    }
}
