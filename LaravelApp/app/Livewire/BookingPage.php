<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;

class BookingPage extends Component
{

    public $event;
    public $cart  = [];

    public function mount($event_id)
    {
        $this->event = Event::find($event_id);
    }

    protected $listeners = ['cartUpdated' => 'render'];
    
    public function render()
    {
        return view('livewire.booking-page');
    }
}