<?php

namespace App\Http\Controllers;

use App\Models\InternalPerson;

class InternalPersonController extends Controller
{
    public function index()
    {
        return view('internal-person.index');
    }

    public function show(int $id)
    {
        $internalPerson = InternalPerson::findOrFail($id);

        return view('internal-person.show', compact('internalPerson'));
    }
}
