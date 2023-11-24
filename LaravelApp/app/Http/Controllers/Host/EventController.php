<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    //
    public function create(Request $request) {
        return view("host.multi-step-form");
    }
    public function index() {
        $currentDateTime = Carbon::now();
        
        $events = auth()->user()->events()
        ->where('event_datetime', '>', $currentDateTime)
        ->orderBy('event_datetime', 'asc')
        ->get();

        return view ('host.manage-events.index', compact('events'));
    }
}
