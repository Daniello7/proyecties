<?php

namespace App\Http\Controllers;

class ControlAccessController extends Controller
{
    public function __invoke()
    {
        return view('porter.home');
    }
}
