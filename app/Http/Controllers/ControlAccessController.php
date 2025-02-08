<?php

namespace App\Http\Controllers;

class ControlAccessController extends Controller
{
    public function __invoke()
    {
        return view('control-access.index');
    }
}
