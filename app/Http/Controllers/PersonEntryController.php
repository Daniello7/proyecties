<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonEntry\StorePersonEntryRequest;
use App\Http\Requests\PersonEntry\UpdatePersonEntryRequest;
use App\Models\Person;
use App\Models\PersonEntry;
use Barryvdh\DomPDF\Facade\Pdf;
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

        Gate::authorize('create', PersonEntry::class);

        $person_id = $request->input('person_id');

        $person = Person::with(['personEntries' => function ($query) {
            $query->orderBy('exit_time', 'desc')->first();
        }])->findOrFail($person_id);

        $lastEntry = $person->personEntries->first();

        return view('person-entry.create', ['person' => $person, 'lastEntry' => $lastEntry]);
    }

    public function store(StorePersonEntryRequest $request)
    {
        Gate::authorize('create', PersonEntry::class);

        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;
        $data['arrival_time'] = Carbon::now();

        $personEntry = PersonEntry::create($data);

        $reason = $request->input('reason');

        if ($reason == 'Charge' || $reason == 'Discharge') {
            $pdfUrl = route('driver-rules', ['person' => $personEntry->person]);
        } elseif ($reason == 'Cleaning') {
            $pdfUrl = route('cleaning-rules', ['person' => $personEntry->person]);
        } else {
            $pdfUrl = route('visitor-rules', ['person' => $personEntry->person]);
        }

        return response()->json([
            'message' => __('messages.person-entry_created'),
            'pdfUrl' => $pdfUrl,
        ]);
    }

    public function edit($id)
    {
        Gate::authorize('update', PersonEntry::class);

        $personEntry = PersonEntry::with('internalPerson')->findOrFail($id);

        return view('person-entry.edit', compact('personEntry'));
    }

    public function update(UpdatePersonEntryRequest $request, $id)
    {
        Gate::authorize('update', PersonEntry::class);

        $personEntry = PersonEntry::findOrFail($id);
        $personEntry->update($request->validated());

        return to_route('control-access')->with('status', __('messages.person-entry_updated'));
    }

    public function destroy($id)
    {
        Gate::authorize('delete', PersonEntry::class);

        PersonEntry::destroy($id);

        return to_route('control-access')
            ->with('status', __('messages.person-entry_deleted'));
    }
}
