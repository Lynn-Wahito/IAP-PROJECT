<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Seat;

class SeatingArrangement extends Component
{

    public $event;
    public $seats; 
    public $selectedSeats = [];
    public $cart = [];
    public $showBanner =false;
    public $bookingSummary = [];
    public $bookingConfirmed = false;
    public function mount($event) {
        $this->event = $event;
        $this->seats = Seat::where('event_id', $event->id)
        ->orderBy('row_number')
        ->orderBy('seat_number')
        ->get();

        $this->setSessionData();
    }
    public function render()
    {
        return view('livewire.seating-arrangement');
    }
}
