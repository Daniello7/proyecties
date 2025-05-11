<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActiveEntriesPDF;
use App\Models\Person;
use App\Models\PersonEntry;
use Barryvdh\DomPDF\Facade\PDF;

class PDFController extends Controller
{

    public function cleaningRules($person_id)
    {
        $person = Person::findOrFail($person_id);

        $pdf = PDF::loadView('pdf.cleaningRules', ['person' => $person]);

        return $pdf->stream('cleaningRules.pdf');
    }

    public function visitorRules($person_id)
    {
        $person = Person::findOrFail($person_id);

        $pdf = PDF::loadView('pdf.visitorRules', ['person' => $person]);

        return $pdf->stream('visitorRules.pdf');
    }

    public function driverRules($person_id)
    {
        $person = Person::findOrFail($person_id);

        $pdf = PDF::loadView('pdf.driverRules', ['person' => $person]);

        return $pdf->stream('driverRules.pdf');
    }

    public function activeEntriesPdf(ActiveEntriesPDF $request)
    {
        $request->validated();

        $columns = json_decode($request->columns, true);
        $entries_id = json_decode($request->entries, true);

        array_pop($columns);
        array_splice($columns, 3, 0, ['Reason', 'Date entry']);

        $entries = PersonEntry::with([
            'person:id,name,last_name,company',
            'internalPerson:id,person_id',
            'internalPerson.person:id,name,last_name',
        ])
            ->whereIn('id', $entries_id)
            ->get();

        $pdf = PDF::loadView('pdf.activeEntries', [
            'columns' => $columns,
            'entries' => $entries
        ]);

        return $pdf->stream('activeEntries.pdf');
    }
}
