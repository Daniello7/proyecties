<?php

namespace App\Http\Controllers;

class PublicController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }


}
