<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KeyControlController extends Controller
{
    public function index()
    {
        return view('key-control.index');
    }

    public function create()
    {
        return view('key-control.create');
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
