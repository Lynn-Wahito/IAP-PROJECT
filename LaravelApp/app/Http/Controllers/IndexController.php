<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Event;

class IndexController extends Controller
{
    public function index() {
        return view("host.index");
    }
    public function customerIndex() {
        $currentDateTime = Carbon::now();
        
        $upcomingEvents = Event::where('event_datetime', '>', $currentDateTime)
            ->orderBy('event_datetime', 'asc')
            ->get();
        return view("customer.customerIndex", compact('upcomingEvents'));
    }
}

