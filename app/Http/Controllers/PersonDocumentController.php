<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class PersonDocumentController extends Controller
{
    public function store(Request $request, Person $person)
    {
        abort_if(!$request->hasFile('pdf'), 400, 'Error: no se ha enviado ningún archivo');

        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:10240'
        ]);

        $pdf = $request->file('pdf');

        $document = $person->documents()->create([
            'filename' => $pdf->hashName(),
            'original_name' => $pdf->getClientOriginalName(),
            'type' => $pdf->getClientMimeType(),
            'size' => $pdf->getSize(),
            'path' => $pdf->store('person-documents', 'public')
        ]);


        session()->flash('document-status', 'Documento subido correctamente');

        return response()->json([
            'success' => true,
            'message' => 'Documento subido correctamente',
            'document' => $document
        ]);
    }
}
