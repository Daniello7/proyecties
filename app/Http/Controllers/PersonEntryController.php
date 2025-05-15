<?php

namespace App\Http\Controllers;

use App\Events\NotifyContactVisitorEvent;
use App\Http\Requests\PersonEntry\StorePersonEntryRequest;
use App\Http\Requests\PersonEntry\UpdatePersonEntryRequest;
use App\Models\Person;
use App\Models\PersonEntry;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class PersonEntryController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        return view('person-entry.index');
    }
}
