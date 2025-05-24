<?php

namespace App\Http\Controllers;

class HumanResourcesController extends Controller
{
    public function __invoke()
    {
        return view('hr.index');
    }
}
