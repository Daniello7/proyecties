<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonEntryRequest;
use App\Models\Comment;
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
        $id = $request->input('entry_id');

        $personEntry = PersonEntry::with('person')->findOrFail($id);

        return view('person-entry.create', compact('personEntry'));
    }

    public function store(PersonEntryRequest $request)
    {

//        dd(auth()->user()->id);
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
}
