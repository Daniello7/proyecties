<?php

namespace App\Http\Controllers;

use App\Http\Requests\KeyControl\StoreKeyControlRequest;
use App\Http\Requests\KeyControl\UpdateKeyControlRequest;
use App\Models\KeyControl;

class KeyControlController extends Controller
{
    public function index()
    {
        return view('key-control.index');
    }
}
