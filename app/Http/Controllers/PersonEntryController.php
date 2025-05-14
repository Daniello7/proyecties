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

    public function destroy($id)
    {
        $this->authorize('delete', PersonEntry::class);

        PersonEntry::destroy($id);

        return to_route('control-access')
            ->with('status', __('messages.person-entry_deleted'));
    }
}
