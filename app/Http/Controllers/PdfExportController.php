<?php

namespace App\Http\Controllers;

class PdfExportController extends Controller
{
    public function index()
    {
        return view('pdf_exports.index');
    }
}
