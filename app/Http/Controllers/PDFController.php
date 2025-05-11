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
}
