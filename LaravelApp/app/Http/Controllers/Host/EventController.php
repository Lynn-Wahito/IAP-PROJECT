<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Seat;
use Illuminate\Support\Facades\Storage;

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

    public function edit(Request $request, Event $event) {
        Event::find($event->id);
       
        
    
        return view('host.manage-events.edit', compact('event'));
    }

    public function destroy(Event $event)
    {
        if (!$event) {
            abort(404, 'Event not found');
        }
    
        // Delete associated seats
        Seat::where('event_id', $event->id)->delete();
    
        // Delete template path
        if ($event->template_path) {
            Storage::disk('public')->delete($event->template_path);
        }
    
        // Delete the event
        $event->delete();
    
        return redirect()->route('host.manage-events.index')->with('message', 'Event deleted successfully');
    }
}
