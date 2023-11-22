<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    //
    public function create(Request $request) {
        return view("host.multi-step-form");
    }
    public function index(){
        return view("host.manage-events.index");
    }
}
