<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonEntry\StorePersonEntryRequest;
use App\Http\Requests\PersonEntry\UpdatePersonEntryRequest;
use App\Models\Person;
use App\Models\PersonEntry;
use App\Policies\PersonEntryPolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PersonEntryController extends Controller
{
    public function index()
    {
        return view('person-entry.index');
    }

    public function create(Request $request)
    {
        Gate::authorize('create', PersonEntryPolicy::class);

        $person_id = $request->input('person_id');

        $person = Person::with(['personEntries' => function ($query) {
            $query->orderBy('exit_time', 'desc')->first();
        }])->findOrFail($person_id);

        $lastEntry = $person->personEntries->first();

        return view('person-entry.create', ['person' => $person, 'lastEntry' => $lastEntry]);
    }

    public function store(StorePersonEntryRequest $request)
    {
        Gate::authorize('create', PersonEntryPolicy::class);

        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;
        $data['arrival_time'] = Carbon::now();

        PersonEntry::create($data);

        return to_route('control-access')
            ->with('success', __('messages.person-entry_created'));
    }

    public function edit($id)
    {
        Gate::authorize('update', PersonEntryPolicy::class);

        $personEntry = PersonEntry::with('internalPerson')->findOrFail($id);

        return view('person-entry.edit', compact('personEntry'));
    }

    public function update(UpdatePersonEntryRequest $request, $id)
    {
        Gate::authorize('update', PersonEntryPolicy::class);

        $personEntry = PersonEntry::findOrFail($id);
        $personEntry->update($request->validated());

        return to_route('control-access')->with('status', __('messages.person-entry_updated'));
    }

    public function destroy($id)
    {
        Gate::authorize('delete', PersonEntryPolicy::class);

        PersonEntry::destroy($id);

        return to_route('control-access')
            ->with('status', __('messages.person-entry_deleted'));
    }
}
