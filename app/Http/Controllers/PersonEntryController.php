<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonEntryRequest;
use App\Models\Comment;
use App\Models\PersonEntry;
use Illuminate\Http\Request;

class PersonEntryController extends Controller
{
    public function index()
    {
        return view('person-entry.index');
    }

    public function create(Request $request)
    {
        $id = $request->input('entry_id');

        $personEntry = PersonEntry::with('person')->findOrFail($id);

        return view('person-entry.create', compact('personEntry'));
    }

    public function store(PersonEntryRequest $request)
    {

        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;
        $data['comment_id'] = Comment::create([
            'user_id' => auth()->user()->id,
            'content' => $request->input('comment')
        ])->id;

        PersonEntry::create($data->validated());

        return to_route('control-access.index')
            ->with('status', 'Entry created successfully');
    }
}
